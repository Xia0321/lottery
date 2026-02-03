<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../data/config.inc.php';
include '../data/db.php';
include '../global/db.inc.php';
include '../func/func.php';
include '../func/csfunc.php';
include '../func/adminfunc.php';
include '../func/js.php';
include "../func/self.php";

$gid = 177;
$qishu = '20251213271'; // Use the qishu from the previous output

echo "Testing calc for gid=$gid, qishu=$qishu\n";

// Fetch game config
$tsql->query("select * from `{$tb_game}` where gid='$gid'");
$tsql->next_record();
$fenlei = $tsql->f('fenlei');
$mnum = $tsql->f('mnum');
$cs = json_decode($tsql->f('cs'), true);
$mtype = json_decode($tsql->f('mtype'), true);
$ztype = json_decode($tsql->f('ztype'), true);

echo "Fenlei: $fenlei, Mnum: $mnum\n";

// Reset JS status to 0 for testing
$tsql->query("update `{$tb_kj}` set js=0 where gid='$gid' and qishu='$qishu'");
echo "Reset JS status to 0.\n";

// Fetch drawn numbers to verify
$tsql->query("select * from `{$tb_kj}` where gid='$gid' and qishu='$qishu'");
if ($tsql->next_record()) {
    echo "Drawn numbers: " . $tsql->f('m1') . "," . $tsql->f('m2') . "...\n";
    echo "Current JS status: " . $tsql->f('js') . "\n";
    echo "KJ Time: " . $tsql->f('kjtime') . "\n";
} else {
    die("No kj record found!\n");
}

// Call calc
// echo "Calling calc...\n";
// function calc($fenlei, $gid, $cs, $qishu, $mnum, $ztype, $mtype,$qz=false)
// $ms = calc($fenlei, $gid, $cs, $qishu, $mnum, $ztype, $mtype);

// Check JS status after run
$tsql->query("select js from `{$tb_kj}` where gid='$gid' and qishu='$qishu'");
$tsql->next_record();
echo "JS status after calc: " . $tsql->f('js') . "\n";

// Replicate autokjs.php query logic
$msql->query("select editstart from `{$tb_config}` ");
$msql->next_record();
if (date("His") < str_replace(':', '', $msql->f('editstart'))) {
    $dates = date("Y-m-d", time() - 86400);
} else {
    $dates = date("Y-m-d");
}
$timekj = date("Y-m-d H:i:s");
echo "Query params: dates=$dates, timekj=$timekj, gid=$gid, mnum=$mnum\n";

$sql = "select qishu from `{$tb_kj}` where gid='{$gid}' and dates='{$dates}' and kjtime<='{$timekj}' and m" . $mnum . "!='' order by qishu desc limit 3";
echo "SQL: $sql\n";
$rs1 = $psql->arr($sql, 1);
echo "Records found: " . count($rs1) . "\n";
foreach ($rs1 as $r) {
    echo "Found qishu: " . $r['qishu'] . "\n";
}

echo "Done.\n";
?>
