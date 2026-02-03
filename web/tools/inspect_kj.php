<?php
include '../data/config.inc.php';
include '../data/db.php';
include '../global/db.inc.php';

$gid = 177;
$sql = "select * from `{$tb_kj}` where gid='{$gid}' order by qishu desc limit 5";
$query = $msql->query($sql);
while($msql->next_record()){
    echo "Qishu: " . $msql->f('qishu') . "\n";
    echo "Dates: " . $msql->f('dates') . "\n";
    echo "Kjtime: " . $msql->f('kjtime') . "\n";
    echo "M1-M10: " . $msql->f('m1') . "," . $msql->f('m2') . "..." . $msql->f('m10') . "\n";
    echo "JS: " . $msql->f('js') . "\n";
    echo "-------------------\n";
}
?>