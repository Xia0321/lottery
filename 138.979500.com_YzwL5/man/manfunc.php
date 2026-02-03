<?php
function getgidman($v)
{
    switch ($v) {
        case 'BJPK10':
            $gid = 107;
            break;
        case 'CQHLSX':
        case 'CQSSC':
            $gid = 101;
            break;
        case "PK10JSC":
            $gid = 172;
            break;
        case "XYFT":
            $gid = 171;
            break;
        case "LUCKYSB":
            $gid = 170;
            break;
        case "SGFT":
            $gid = 177;
            break;
        case "SSCJSC":
            $gid = 108;
            break;
        case "AULUCKY10":
            $gid = 175;
            break;
        case "AULUCKY5":
            $gid = 109;
            break;
        case "GDKLSF":
            $gid = 103;
            break;
        case "AULUCKY8":
            $gid = 131;
            break;
        case "BJKL8":
            $gid = 161;
            break;
        case "KL8JSC":
            $gid = 162;
            break;
        case "XYNC":
        case "CQXYNC":
            $gid=135;
        break; 
    }
    return $gid;
}
function getfenleiman($v)
{
    switch ($v) {
        case 'BJPK10':
        case "PK10JSC":
        case "XYFT":
        case "LUCKYSB":
        case "SGFT":
        case "AULUCKY10":
            $gid = 107;
            break;
        case 'CQSSC':
        case "SSCJSC":
        case "AULUCKY5":
        case "CQHLSX":
            $gid = 101;
            break;
        case "GDKLSF":
        case "AULUCKY8":
        case "CQXYNC":
        case "XYNC":
            $gid = 103;
            break;
        case "BJKL8":
        case "KL8JSC":
            $gid = 161;
            break;
    }
    return $gid;
}

function getgametype($gid)
{
    $type = "";
    switch ($gid) {
        case 107:
            $type = "BJPK10";
            break;
        case 101:
            $type = "CQSSC";
            break;
        case 172:
            $type = "PK10JSC";
            break;
        case 171:
            $type = "XYFT";
            break;
        case 170:
            $type = "LUCKYSB";
            break;
        case 177:
            $type = "SGFT";
            break;
        case 108:
            $type = "SSCJSC";
            break;
        case 175:
            $type = "AULUCKY10";
        break;
        case 109:
            $type = "AULUCKY5";
        break;
        case 103:
            $type ="GDKLSF";
        break;    
        case 131:
            $type = "AULUCKY8";
         break;   
        case 161:
            $type = "BJKL8";
         break;  
        case 162:
            $type = "KL8JSC";
         break; 
    }
    return $type;
}