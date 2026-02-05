<?php
/**
 * 统计分析 API
 *
 * GET /api/v1/stats/overview - 总览统计
 * GET /api/v1/stats/bets - 投注统计
 * GET /api/v1/stats/revenue - 营收统计
 * GET /api/v1/stats/users - 用户统计
 */

require_once(__DIR__ . '/BaseController.php');

class StatsController extends BaseController {

    public function handle() {
        $this->requireMethod('GET');

        $requestUri = $_SERVER['REQUEST_URI'];

        if (preg_match('#/api/v1/stats/overview#', $requestUri)) {
            $this->overview();
        }
        elseif (preg_match('#/api/v1/stats/bets#', $requestUri)) {
            $this->betStats();
        }
        elseif (preg_match('#/api/v1/stats/revenue#', $requestUri)) {
            $this->revenueStats();
        }
        elseif (preg_match('#/api/v1/stats/users#', $requestUri)) {
            $this->userStats();
        }
        // 回退：通过 action 参数路由（兼容直接文件访问模式）
        elseif (preg_match('#/api/v1/stats(\?.*)?$#', $requestUri)) {
            $action = $_GET['action'] ?? 'overview';
            switch ($action) {
                case 'overview': $this->overview(); break;
                case 'bets': $this->betStats(); break;
                case 'revenue': $this->revenueStats(); break;
                case 'users': $this->userStats(); break;
                default: $this->respondError('Invalid stats endpoint', ErrorCode::PARAM_INVALID, 400);
            }
        }
        else {
            $this->respondError('Invalid stats endpoint', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 总览统计
     */
    private function overview() {
        try {
            global $tb_user, $tb_bet, $tb_money;
            $agentId = $this->agentId;

            // 用户总数
            $stmt = $this->mysqli->prepare("SELECT COUNT(*) as cnt FROM `$tb_user` WHERE fid = ?");
            $stmt->bind_param('i', $agentId);
            $stmt->execute();
            $totalUsers = $stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();

            // 今日活跃用户
            $stmt = $this->mysqli->prepare("SELECT COUNT(DISTINCT b.userid) as cnt FROM `$tb_bet` b INNER JOIN `$tb_user` u ON b.userid = u.userid WHERE u.fid = ? AND DATE(b.time) = CURDATE()");
            $stmt->bind_param('i', $agentId);
            $stmt->execute();
            $todayActive = $stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();

            // 今日投注总额
            $stmt = $this->mysqli->prepare("SELECT COALESCE(SUM(b.je), 0) as total FROM `$tb_bet` b INNER JOIN `$tb_user` u ON b.userid = u.userid WHERE u.fid = ? AND DATE(b.time) = CURDATE()");
            $stmt->bind_param('i', $agentId);
            $stmt->execute();
            $todayBetAmount = $stmt->get_result()->fetch_assoc()['total'];
            $stmt->close();

            // 今日投注笔数
            $stmt = $this->mysqli->prepare("SELECT COUNT(*) as cnt FROM `$tb_bet` b INNER JOIN `$tb_user` u ON b.userid = u.userid WHERE u.fid = ? AND DATE(b.time) = CURDATE()");
            $stmt->bind_param('i', $agentId);
            $stmt->execute();
            $todayBetCount = $stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();

            // 今日充值总额
            $stmt = $this->mysqli->prepare("SELECT COALESCE(SUM(m.money), 0) as total FROM `$tb_money` m INNER JOIN `$tb_user` u ON m.userid = u.userid WHERE u.fid = ? AND m.mtype = 1 AND m.status = 1 AND DATE(m.tjtime) = CURDATE()");
            $stmt->bind_param('i', $agentId);
            $stmt->execute();
            $todayDeposit = $stmt->get_result()->fetch_assoc()['total'];
            $stmt->close();

            // 今日提现总额
            $stmt = $this->mysqli->prepare("SELECT COALESCE(SUM(m.money), 0) as total FROM `$tb_money` m INNER JOIN `$tb_user` u ON m.userid = u.userid WHERE u.fid = ? AND m.mtype = 2 AND m.status = 1 AND DATE(m.tjtime) = CURDATE()");
            $stmt->bind_param('i', $agentId);
            $stmt->execute();
            $todayWithdrawal = $stmt->get_result()->fetch_assoc()['total'];
            $stmt->close();

            $this->respondSuccess([
                'total_users' => (int)$totalUsers,
                'today' => [
                    'active_users' => (int)$todayActive,
                    'bet_count' => (int)$todayBetCount,
                    'bet_amount' => (float)$todayBetAmount,
                    'deposit_amount' => (float)$todayDeposit,
                    'withdrawal_amount' => (float)$todayWithdrawal,
                    'net_income' => (float)($todayDeposit - $todayWithdrawal),
                ]
            ]);

        } catch (Exception $e) {
            error_log("Stats overview error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 投注统计（按天/按游戏）
     */
    private function betStats() {
        $this->requireScope('bets.read');

        try {
            global $tb_bet, $tb_user;
            $agentId = $this->agentId;

            $groupBy = $this->getOptionalParam('group_by', 'string', 'day', 'GET');
            $days = $this->getOptionalParam('days', 'int', 7, 'GET');
            $days = min(max($days, 1), 90);

            if ($groupBy === 'game') {
                // 按游戏分组
                $stmt = $this->mysqli->prepare("
                    SELECT b.gid as game_id,
                           COUNT(*) as bet_count,
                           SUM(b.je) as bet_amount,
                           SUM(b.prize) as win_amount,
                           COUNT(DISTINCT b.userid) as unique_users
                    FROM `$tb_bet` b
                    INNER JOIN `$tb_user` u ON b.userid = u.userid
                    WHERE u.fid = ? AND b.time >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                    GROUP BY b.gid
                    ORDER BY bet_amount DESC
                ");
                $stmt->bind_param('ii', $agentId, $days);
            } else {
                // 按天分组
                $stmt = $this->mysqli->prepare("
                    SELECT DATE(b.time) as date,
                           COUNT(*) as bet_count,
                           SUM(b.je) as bet_amount,
                           SUM(b.prize) as win_amount,
                           COUNT(DISTINCT b.userid) as unique_users
                    FROM `$tb_bet` b
                    INNER JOIN `$tb_user` u ON b.userid = u.userid
                    WHERE u.fid = ? AND b.time >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                    GROUP BY DATE(b.time)
                    ORDER BY date DESC
                ");
                $stmt->bind_param('ii', $agentId, $days);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $stats = [];
            while ($row = $result->fetch_assoc()) {
                $item = [];
                foreach ($row as $key => $val) {
                    if (in_array($key, ['bet_count', 'unique_users', 'game_id'])) {
                        $item[$key] = (int)$val;
                    } elseif (in_array($key, ['bet_amount', 'win_amount'])) {
                        $item[$key] = (float)$val;
                    } else {
                        $item[$key] = $val;
                    }
                }
                $stats[] = $item;
            }
            $stmt->close();

            $this->respondSuccess([
                'group_by' => $groupBy,
                'days' => $days,
                'stats' => $stats
            ]);

        } catch (Exception $e) {
            error_log("Bet stats error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 营收统计（充值、提现、净收入按天）
     */
    private function revenueStats() {
        try {
            global $tb_money, $tb_user;
            $agentId = $this->agentId;

            $days = $this->getOptionalParam('days', 'int', 7, 'GET');
            $days = min(max($days, 1), 90);

            $stmt = $this->mysqli->prepare("
                SELECT DATE(m.tjtime) as date,
                       SUM(CASE WHEN m.mtype = 1 THEN m.money ELSE 0 END) as deposit_amount,
                       SUM(CASE WHEN m.mtype = 2 THEN m.money ELSE 0 END) as withdrawal_amount,
                       SUM(CASE WHEN m.mtype = 1 THEN 1 ELSE 0 END) as deposit_count,
                       SUM(CASE WHEN m.mtype = 2 THEN 1 ELSE 0 END) as withdrawal_count
                FROM `$tb_money` m
                INNER JOIN `$tb_user` u ON m.userid = u.userid
                WHERE u.fid = ? AND m.status = 1 AND m.tjtime >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY DATE(m.tjtime)
                ORDER BY date DESC
            ");
            $stmt->bind_param('ii', $agentId, $days);
            $stmt->execute();
            $result = $stmt->get_result();

            $stats = [];
            while ($row = $result->fetch_assoc()) {
                $stats[] = [
                    'date' => $row['date'],
                    'deposit_amount' => (float)$row['deposit_amount'],
                    'withdrawal_amount' => (float)$row['withdrawal_amount'],
                    'net_income' => (float)($row['deposit_amount'] - $row['withdrawal_amount']),
                    'deposit_count' => (int)$row['deposit_count'],
                    'withdrawal_count' => (int)$row['withdrawal_count']
                ];
            }
            $stmt->close();

            $this->respondSuccess(['days' => $days, 'stats' => $stats]);

        } catch (Exception $e) {
            error_log("Revenue stats error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 用户统计（注册趋势、活跃度）
     */
    private function userStats() {
        $this->requireScope('users.read');

        try {
            global $tb_user, $tb_bet;
            $agentId = $this->agentId;

            $days = $this->getOptionalParam('days', 'int', 7, 'GET');
            $days = min(max($days, 1), 90);

            // 新注册用户趋势
            $stmt = $this->mysqli->prepare("
                SELECT DATE(regtime) as date, COUNT(*) as new_users
                FROM `$tb_user`
                WHERE fid = ? AND regtime >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY DATE(regtime)
                ORDER BY date DESC
            ");
            $stmt->bind_param('ii', $agentId, $days);
            $stmt->execute();
            $result = $stmt->get_result();

            $registrations = [];
            while ($row = $result->fetch_assoc()) {
                $registrations[] = [
                    'date' => $row['date'],
                    'new_users' => (int)$row['new_users']
                ];
            }
            $stmt->close();

            // 活跃用户趋势（按天）
            $stmt = $this->mysqli->prepare("
                SELECT DATE(b.time) as date, COUNT(DISTINCT b.userid) as active_users
                FROM `$tb_bet` b
                INNER JOIN `$tb_user` u ON b.userid = u.userid
                WHERE u.fid = ? AND b.time >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY DATE(b.time)
                ORDER BY date DESC
            ");
            $stmt->bind_param('ii', $agentId, $days);
            $stmt->execute();
            $result = $stmt->get_result();

            $activity = [];
            while ($row = $result->fetch_assoc()) {
                $activity[] = [
                    'date' => $row['date'],
                    'active_users' => (int)$row['active_users']
                ];
            }
            $stmt->close();

            $this->respondSuccess([
                'days' => $days,
                'registrations' => $registrations,
                'activity' => $activity
            ]);

        } catch (Exception $e) {
            error_log("User stats error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }
}

$controller = new StatsController();
$controller->handle();
