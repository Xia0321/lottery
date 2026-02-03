<?php
/**
 * SQL 安全辅助函数
 *
 * 提供安全的批量操作方法
 * 2026-02-03 安全加固
 */

/**
 * 解析用户 ID 列表并验证
 *
 * @param string $idList 逗号分隔的 ID 列表，如 "1,2,3,4,5"
 * @return array 验证后的整数数组
 */
function parseUserIds($idList) {
    if (empty($idList)) {
        return [];
    }

    // 移除首尾可能的分隔符
    $idList = trim($idList, ',| ');

    // 分割字符串
    $ids = preg_split('/[,|]+/', $idList);

    // 过滤和验证
    $validIds = [];
    foreach ($ids as $id) {
        $id = trim($id);
        if (is_numeric($id) && $id > 0) {
            $validIds[] = intval($id);
        }
    }

    // 去重
    return array_unique($validIds);
}

/**
 * 生成 IN 查询的占位符和参数
 *
 * @param array $ids ID 数组
 * @return array ['placeholders' => '?,?,?', 'types' => 'iii', 'values' => [1,2,3]]
 */
function prepareInClause($ids) {
    if (empty($ids)) {
        return [
            'placeholders' => '',
            'types' => '',
            'values' => []
        ];
    }

    $count = count($ids);
    $placeholders = implode(',', array_fill(0, $count, '?'));
    $types = str_repeat('i', $count);

    return [
        'placeholders' => $placeholders,
        'types' => $types,
        'values' => $ids
    ];
}

/**
 * 安全的批量更新状态
 *
 * @param mysqli $mysqli 数据库连接
 * @param string $table 表名
 * @param string $idList ID 列表
 * @param int $status 状态值
 * @return int 影响行数
 */
function batchUpdateStatus($mysqli, $table, $idList, $status) {
    $ids = parseUserIds($idList);
    if (empty($ids)) {
        return 0;
    }

    $inClause = prepareInClause($ids);
    $sql = "UPDATE `$table` SET status = ? WHERE userid IN ({$inClause['placeholders']})";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        return 0;
    }

    // 绑定参数：status + 所有 IDs
    $types = 'i' . $inClause['types'];
    $params = array_merge([$status], $inClause['values']);
    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    return $affected;
}

/**
 * 安全的批量删除
 *
 * @param mysqli $mysqli 数据库连接
 * @param string $table 表名
 * @param string $idList ID 列表
 * @param string $idField ID 字段名（默认 'userid'）
 * @param array $excludeIds 排除的 ID（如 99999999）
 * @return int 影响行数
 */
function batchDelete($mysqli, $table, $idList, $idField = 'userid', $excludeIds = []) {
    $ids = parseUserIds($idList);
    if (empty($ids)) {
        return 0;
    }

    // 移除排除的 ID
    if (!empty($excludeIds)) {
        $ids = array_diff($ids, $excludeIds);
    }

    if (empty($ids)) {
        return 0;
    }

    $inClause = prepareInClause($ids);
    $sql = "DELETE FROM `$table` WHERE `$idField` IN ({$inClause['placeholders']})";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        return 0;
    }

    $stmt->bind_param($inClause['types'], ...$inClause['values']);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    return $affected;
}

/**
 * 安全的批量查询
 *
 * @param mysqli $mysqli 数据库连接
 * @param string $sql SQL 查询（包含 IN 占位符）
 * @param string $idList ID 列表
 * @return mysqli_result|false
 */
function batchQuery($mysqli, $sql, $idList) {
    $ids = parseUserIds($idList);
    if (empty($ids)) {
        return false;
    }

    $inClause = prepareInClause($ids);
    // 替换 SQL 中的占位符
    $sql = str_replace(':placeholders', $inClause['placeholders'], $sql);

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        return false;
    }

    $stmt->bind_param($inClause['types'], ...$inClause['values']);
    $stmt->execute();

    return $stmt->get_result();
}
