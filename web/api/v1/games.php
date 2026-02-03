<?php
/**
 * 游戏列表 API
 *
 * GET /api/v1/games.php - 查询游戏列表
 * GET /api/v1/games.php/{id} - 查询单个游戏详情
 */

require_once(__DIR__ . '/BaseController.php');

class GamesController extends BaseController {

    public function handle() {
        // 要求 GET 方法
        $this->requireMethod('GET');

        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];

        // GET /api/v1/games/{id}
        if (preg_match('#/api/v1/games/(\d+)$#', $requestUri, $matches)) {
            $this->getGameById($matches[1]);
        }
        // GET /api/v1/games
        elseif (preg_match('#/api/v1/games(\?.*)?$#', $requestUri)) {
            $this->getGamesList();
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 查询游戏列表
     */
    private function getGamesList() {
        try {
            // 获取表名
            global $tb_game;

            // 获取查询参数
            $status = $this->getOptionalParam('status', 'int', null, 'GET');
            $type = $this->getOptionalParam('type', 'string', null, 'GET');

            // 分页参数
            list($page, $pageSize) = $this->getPagination();
            $offset = ($page - 1) * $pageSize;

            // 构建查询条件
            $where = ['1=1'];
            $params = [];
            $types = '';

            // 状态筛选
            if ($status !== null) {
                $where[] = "status = ?";
                $params[] = $status;
                $types .= 'i';
            }

            // 游戏类型筛选
            if ($type !== null) {
                $where[] = "gametype = ?";
                $params[] = $type;
                $types .= 's';
            }

            $whereClause = implode(' AND ', $where);

            // 查询总数
            $countSql = "
                SELECT COUNT(*) as total
                FROM `$tb_game`
                WHERE $whereClause
            ";
            $countStmt = $this->mysqli->prepare($countSql);

            if (!empty($params)) {
                $countStmt->bind_param($types, ...$params);
            }

            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $total = $countResult->fetch_assoc()['total'];
            $countStmt->close();

            // 查询列表数据
            $sql = "
                SELECT gameid, gamename, gametype, minmoney, maxmoney, gamepic,
                       status, sort, created_at
                FROM `$tb_game`
                WHERE $whereClause
                ORDER BY sort ASC, gameid ASC
                LIMIT ? OFFSET ?
            ";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            // 添加分页参数
            $params[] = $pageSize;
            $params[] = $offset;
            $types .= 'ii';

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $games = [];
            while ($row = $result->fetch_assoc()) {
                $games[] = [
                    'game_id' => (int)$row['gameid'],
                    'game_name' => $row['gamename'],
                    'game_type' => $row['gametype'],
                    'min_bet' => floatval($row['minmoney']),
                    'max_bet' => floatval($row['maxmoney']),
                    'game_icon' => $row['gamepic'],
                    'status' => (int)$row['status'],
                    'sort_order' => (int)$row['sort'],
                    'created_at' => $row['created_at']
                ];
            }
            $stmt->close();

            // 构建响应
            $data = [
                'list' => $games,
                'pagination' => [
                    'page' => $page,
                    'page_size' => $pageSize,
                    'total' => (int)$total,
                    'total_pages' => (int)ceil($total / $pageSize)
                ]
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get games list error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询单个游戏详情
     *
     * @param int $gameId 游戏 ID
     */
    private function getGameById($gameId) {
        try {
            // 获取表名
            global $tb_game;

            // 查询游戏
            $sql = "
                SELECT gameid, gamename, gametype, minmoney, maxmoney, gamepic,
                       gamedesc, status, sort, created_at
                FROM `$tb_game`
                WHERE gameid = ?
            ";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            $stmt->bind_param('i', $gameId);
            $stmt->execute();
            $result = $stmt->get_result();
            $game = $result->fetch_assoc();
            $stmt->close();

            if (!$game) {
                $this->respondError('Game not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            // 格式化响应数据
            $data = [
                'game_id' => (int)$game['gameid'],
                'game_name' => $game['gamename'],
                'game_type' => $game['gametype'],
                'min_bet' => floatval($game['minmoney']),
                'max_bet' => floatval($game['maxmoney']),
                'game_icon' => $game['gamepic'],
                'description' => $game['gamedesc'] ?? '',
                'status' => (int)$game['status'],
                'sort_order' => (int)$game['sort'],
                'created_at' => $game['created_at']
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get game error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }
}

// 执行控制器
$controller = new GamesController();
$controller->handle();
