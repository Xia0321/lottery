<?php
$_SESSION["wid"] = 100;

$gid=107;
switch ($_POST["lottery"]) {
	case 'BJPK10':
		$gid=107;
		break;
	case 'CQHLSX':
	case 'CQSSC':
		$gid=101;
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
}
$_SESSION["gid"] = $gid;