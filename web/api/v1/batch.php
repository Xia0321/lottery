<?php
/**
 * 批量查询 API
 *
 * POST /api/v1/batch/users - 批量查询用户信息
 * POST /api/v1/batch/balances - 批量查询用户余额
 * POST /api/v1/batch/bets - 批量查询投注状态
 */

require_once(__DIR__ . '/BaseController.php');

class BatchController extends BaseController {

    public function handle() {
        $this->requireMethod('POST');

        $requestUri = $_SERVER['REQUEST_URI'];

        if (preg_match('#/api/v1/batch/users#', $requestUri)) {
            $this->batchUsers();
        }
        elseif (preg_match('#/api/v1/batch/balances#', $requestUri)) {
            $this->batchBalances();
        }
        elseif (preg_match('#/api/v1/batch/bets#', $requestUri)) {
            $this->batchBets();
        }
        else {
            $this->respondError('Invalid batch endpoint', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 批量查询用户信息
     * POST body: { "user_ids": [1, 2, 3] }
     */
    private function batchUsers() {
        $this->requireScope('users.read');

        try {
            global $tb_user;
            $input = json_decode(file_get_contents('php://input'), true);
            $userIds = isset($input['user_ids']) ? $input['user_ids'] : [];

            if (empty($userIds) || !is_array($userIds)) {
                $this->respondError('user_ids is required and must be an array', ErrorCode::PARAM_MISSING, 400);
            }

            // 限制批量数量
            if (count($userIds) > 100) {
                $this->respondError('Maximum 100 user_ids per request', ErrorCode::PARAM_OUT_OF_RANGE, 400);
            }

            // 过滤为整数
            $userIds = array_map('intval', $userIds);
            $placeholders = implode(',', array_fill(0, count($userIds), '?'));
            $types = str_repeat('i', count($userIds));

            $sql = "SELECT userid, username, usermoney, status, fid, regtime
                    FROM `$tb_user`
                    WHERE userid IN ($placeholders) AND fid = ?";

            $types .= 'i';
            $userIds[] = $this->agentId;

            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param($types, ...$userIds);
            $stmt->execute();
            $result = $stmt->get_result();

            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = [
                    'user_id' => (int)$row['userid'],
                    'username' => $row['username'],
                    'balance' => (float)$row['usermoney'],
                    'status' => (int)$row['status'],
                    'created_at' => $row['regtime']
                ];
            }
            $stmt->close();

            $this->respondSuccess([
                'users' => $users,
                'found' => count($users),
                'requested' => count($userIds) - 1 // 减去 agentId
            ]);

        } catch (Exception $e) {
            error_log("Batch users error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 批量查询余额
     * POST body: { "user_ids": [1, 2, 3] }
     */
    private function batchBalances() {
        $this->requireScope('users.read');

        try {
            global $tb_user;
            $input = json_decode(file_get_contents('php://input'), true);
            $userIds = isset($input['user_ids']) ? $input['user_ids'] : [];

            if (empty($userIds) || !is_array($userIds)) {
                $this->respondError('user_ids is required', ErrorCode::PARAM_MISSING, 400);
            }

            if (count($userIds) > 200) {
                $this->respondError('Maximum 200 user_ids per request', ErrorCode::PARAM_OUT_OF_RANGE, 400);
            }

            $userIds = array_map('intval', $userIds);
            $placeholders = implode(',', array_fill(0, count($userIds), '?'));
            $types = str_repeat('i', count($userIds));

            $sql = "SELECT userid, usermoney, maxmoney
                    FROM `$tb_user`
                    WHERE userid IN ($placeholders) AND fid = ?";

            $types .= 'i';
            $userIds[] = $this->agentId;

            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param($types, ...$userIds);
            $stmt->execute();
            $result = $stmt->get_result();

            $balances = [];
            while ($row = $result->fetch_assoc()) {
                $balances[] = [
                    'user_id' => (int)$row['userid'],
                    'balance' => (float)$row['usermoney'],
                    'credit_limit' => (float)$row['maxmoney']
                ];
            }
            $stmt->close();

            $this->respondSuccess(['balances' => $balances, 'found' => count($balances)]);

        } catch (Exception $e) {
            error_log("Batch balances error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 批量查询投注状态
     * POST body: { "bet_ids": [1, 2, 3] }
     */
    private function batchBets() {
        $this->requireScope('bets.read');

        try {
            global $tb_bet, $tb_user;
            $input = json_decode(file_get_contents('php://input'), true);
            $betIds = isset($input['bet_ids']) ? $input['bet_ids'] : [];

            if (empty($betIds) || !is_array($betIds)) {
                $this->respondError('bet_ids is required', ErrorCode::PARAM_MISSING, 400);
            }

            if (count($betIds) > 200) {
                $this->respondError('Maximum 200 bet_ids per request', ErrorCode::PARAM_OUT_OF_RANGE, 400);
            }

            $betIds = array_map('intval', $betIds);
            $placeholders = implode(',', array_fill(0, count($betIds), '?'));
            $types = str_repeat('i', count($betIds));

            $sql = "SELECT b.id, b.userid, b.gameid, b.qishu, b.status, b.money, b.win
                    FROM `$tb_bet` b
                    INNER JOIN `$tb_user` u ON b.userid = u.userid
                    WHERE b.id IN ($placeholders) AND u.fid = ?";

            $types .= 'i';
            $betIds[] = $this->agentId;

            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param($types, ...$betIds);
            $stmt->execute();
            $result = $stmt->get_result();

            $bets = [];
            while ($row = $result->fetch_assoc()) {
                $bets[] = [
                    'bet_id' => (int)$row['id'],
                    'user_id' => (int)$row['userid'],
                    'game_id' => (int)$row['gameid'],
                    'period' => $row['qishu'],
                    'status' => (int)$row['status'],
                    'bet_amount' => (float)$row['money'],
                    'win_amount' => (float)$row['win']
                ];
            }
            $stmt->close();

            $this->respondSuccess(['bets' => $bets, 'found' => count($bets)]);

        } catch (Exception $e) {
            error_log("Batch bets error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }
}

$controller = new BatchController();
$controller->handle();
