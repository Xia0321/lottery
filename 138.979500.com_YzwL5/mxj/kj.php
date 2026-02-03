<?php
include('../data/comm.inc.php');
include('../data/myadminvar.php');
include('../func/func.php');
include("../func/csfunc.php");
include('../func/adminfunc.php');
include('../include.php');
include './checklogin.php';
include('../data/myadminvar.php');

$input = $_POST;
$otherclosetime = $config['otherclosetime'];
function ds($expect)
{

    $returnSet = [];
    foreach ($expect as $k=>$v){
        if ($v % 2 == 0)
            $returnSet[] = 1;
        else {
            $returnSet[] =  0;
        }
    }
    return $returnSet;
}

function dx($gid, $expect)
{    
    $array = [];

    foreach ($expect as $v) {
      
        if ($gid==101) {
            if ($v <= 4)
                $array[] = 1;
            else
                $array[] = 0;
        } else if ($gid==121) {
            if ($v < 6)
                $array[] = 1;
            else if ($v < 10)
                $array[] = 0;
        } else if ($gid==103) {
            if ($v < 11)
                $array[] = 1;
            else
                $array[] = 0;
        } else if ($gid==151) {
            if ($v <= 3)
                $array[] = 1;
            else
                $array[] = 0;
        } else if ($gid==161) {
            if ($v < 41)
                $array[] = 1;
            else
                $array[] = 0;
        } else if ($gid == 107) {
            if ($v <= 5)
                $array[] = 1;
            else
                $array[] = 0;
        } else if ($gid == 100) {
            if ($v < 25)
                $array[] = 1;
            else if ($v <= 49)
                $array[] = 0;
        }
    }
    return $array;
}


function wdx($expect)
{
    $array = [];
    foreach ($expect as $v) {
        $v = $v % 10;
        if ($v <= 4)
            $array[] = 1;
        else
            $array[] = 0;
    }
    return $array;
}

function zonghe($expect)
{
    $a = 0;
    $b = 1;
    $c = 0;
    $d = 1;
    foreach ($expect as $v) {
        $a += $v;
    }
    if ($a % 2 == 0) {
        $b = 0;
    }
    return [$a,$b,$c,$d,1,1,1];
}

function lh($expect) {
    $expect2 = array_reverse($expect);
    $array = [];
    foreach ($expect as $k=>$v) {
        if ($expect[$k] > $expect2[$k]) {
            $array[] = 1;
        } else {
            $array[] = 0;
        }
    }
    return array_slice($array,5);
}

function hs($expect)
{
    $array = [];
    foreach ($expect as $v) {
     $ge = $v % 10;
        $array[] = ($v - $ge) / 10 + $ge;
    }
    return $array;
}

function shengxiaos($ma, $bml)
{
    $jiazhi = array('甲子', '乙丑', '丙寅', '丁卯', '戊辰', '己巳', '庚午', '辛未', '壬申', '癸酉', '甲戌', '乙亥', '丙子', '丁丑', '戊寅', '己卯', '庚辰', '辛巳', '壬午', '癸未', '甲申', '乙酉', '丙戌', '丁亥', '戊子', '己丑', '庚寅', '辛卯', '壬辰', '癸巳', '甲午', '乙未', '丙申', '丁酉', '戊戌', '己亥', '庚子', '辛丑', '壬寅', '癸卯', '甲辰', '乙巳', '丙午', '丁未', '戊申', '己酉', '庚戌', '辛亥', '壬子', '癸丑', '甲寅', '乙卯', '丙辰', '丁巳', '戊午', '己未', '庚申', '辛酉', '壬戌', '癸亥');
    $index = 0;
    foreach ($jiazhi as $key => $val) {
        if ($val == $bml) {
            $index = $key;
            break;
        }
    }
    $index = $index % 12 +2;
    $ma = $ma % 12;
    $arr = array('鼠', '牛', '虎', '兔', '龍', '蛇', '馬', '羊', '猴', '雞', '狗', '豬');
    $in= 0 ;
    if ($index >= $ma) {
      $in = $index - $ma;
    } else {
       $in =  $index - $ma + 12;
    }
    if($in>=12) $in -=12;
    return $arr[$in];
}

    $msql->query("select cs,mtype,ztype,fenlei from `$tb_game` where gid='$gid'");
    $msql->next_record();
    $fenlei = $msql->f("fenlei");
        
    $where = '1 = 1';
    $where .= " AND gid = {$input['gid']}";
    $where .= " AND kjtime like '%{$input['dates']}%'";
    $where .= ' AND js = 1';
    $array = [];
    $res = $msql->query("select * from `x_kj` where $where  order by id desc limit 1500");
    while ($msql->next_record()) {
        $expect = [];
        $sx=[];
        for ($j = 1; $j <= $config['mnum']; $j++) {
            $expect[] = $msql->f('m' . $j);
            $fenlei ==100 && $sx[] = shengxiaos( $msql->f('m' . $j) ,$msql->f("bml"));
        }
    //   var_dump(dx($msql->f('gid'),$expect));die;
        $array[] = [
            'gname' => 'g'.$msql->f('gid'),
            'gid' => $msql->f('gid'),
            'bml' => $msql->f('bml'),
            'oy' => substr($msql->f('opentime'), 0, 4),
            'cy' => substr($msql->f('closetime'), 0, 4),
            'ky' => substr($msql->f('kjtime'), 0, 4),
            'opentime' => date("m-d H:i:s", strtotime($msql->f('opentime'))),
            'closetime' => date("m-d H:i:s", strtotime($msql->f('closetime'))),
            'kjtime' => "&nbsp;&nbsp;".date("H:i:s", strtotime($msql->f('kjtime')))."&nbsp;",
            'otherclosetime' => date("m-d H:i:s", strtotime($msql->f('closetime')) - $otherclosetime),
            'baostatus' => $msql->f('baostatus'),
            'js' => $msql->f('js'),
            'qishu' => $msql->f('qishu'),
            'num' => $expect,
            'zonghe' => zonghe($expect),
            'ds' => ds($expect),
            'dx' => dx($fenlei,$expect),
            'wdx' => wdx($expect),
            'lh' => lh($expect),
            'hs' => hs($expect),
            'kj_num'=>  join($expect,','),
            'sx' => $sx
            ];
            
    }
        echo json_encode(array(
            "kj" => $array,
            'fenlei' => $fenlei,
            'rcount' => 1,
            'mnum' => $config['mnum']
        ));
        