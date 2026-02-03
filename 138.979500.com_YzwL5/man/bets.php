<?php
require("./check.php");

        $tpage = r1p($_REQUEST['page']);
        $psize = 500;
       
        $sqls = " and z=9 and bs=1 "; 

		$rs= $msql->arr("select count(id) from `$tb_lib` where userid='$userid' $sqls",0);
        $rcount = $rs[0][0];       
		
        $sql = "select qishu,je,peilv1,peilv2,points,content,gid,bid,sid,cid,pid,time,abcd,tid from `$tb_lib` where userid='$userid' $sqls order by time desc,id desc limit ";
        $sql .= ($tpage - 1) * $psize . ',' . $psize;
		//echo $sql;
        $msql->query($sql);
        $lib = array();
        $i   = 0;
		$tmp = array();
		$zje=0;
		$ky=0;
        while ($msql->next_record()) {
            $lib[$i]['tid'] = $msql->f('tid');
            $lib[$i]['qishu']  = $msql->f('qishu');
            $lib[$i]['je']     = (float) $msql->f('je');
            $lib[$i]['peilv1'] = (float) ($msql->f('peilv1'));
            $lib[$i]['peilv2'] = (float) ($msql->f('peilv2'));
            $lib[$i]['points']  = (float) $msql->f('points');
            $lib[$i]['content'] = $msql->f('content');
			if($tmp['g'.$msql->f('gid')]==''){
                $fsql->query("select gname,fenlei from `$tb_game` where gid='".$msql->f('gid')."'");
                $fsql->next_record();
                $tmp['g' . $msql->f('gid')] = $fsql->f('gname');
                $tmp['f' . $msql->f('gid')] = $fsql->f('fenlei');
			}		
			if($tmp['b'.$msql->f('gid').$msql->f('bid')]==''){
				$tmp['b'.$msql->f('gid').$msql->f('bid')] = transb8('name', $msql->f('bid'),$msql->f('gid'));
			}
			if($tmp['s'.$msql->f('gid').$msql->f('sid')]==''){
				$tmp['s'.$msql->f('gid').$msql->f('sid')] = transs8('name', $msql->f('sid'),$msql->f('gid'));
			}
			if($tmp['c'.$msql->f('gid').$msql->f('cid')]==''){
				$tmp['c'.$msql->f('gid').$msql->f('cid')] = transc8('name', $msql->f('cid'),$msql->f('gid'));
			}
			if($tmp['p'.$msql->f('gid').$msql->f('pid')]==''){
				$tmp['p'.$msql->f('gid').$msql->f('pid')] = transp8('name', $msql->f('pid'),$msql->f('gid'));
			}
			$lib[$i]['gid'] = $tmp['g'.$msql->f('gid')];
			$wf = wfuser($tmp['f' . $msql->f('gid')],$tmp['b' . $msql->f('gid') . $msql->f('bid')],$tmp['s' . $msql->f('gid') . $msql->f('sid')],$tmp['c' . $msql->f('gid') . $msql->f('cid')],$tmp['p' . $msql->f('gid') . $msql->f('pid')]);
			$lib[$i]['wf'] = daxue($wf);
			$lib[$i]['abcd'] = $msql->f('abcd');
            $lib[$i]['time'] = str_replace(' ', '<br />', $msql->f('time'));
			$lib[$i]['ky'] = (float)(($msql->f('peilv1')-1)*$msql->f('je'));
			$zje += $lib[$i]['je'];
			$ky += $lib[$i]['ky'];
            $i++;
        }
        $pcount = $rcount%$psize==0 ? $rcount/$psize : ($rcount-$rcount%$psize)/$psize+1;
		$tpl->assign("lib",$lib);
        $tpl->assign("zje",$zje);
		$tpl->assign("ky",$ky);
		$tpl->assign("rcount",$rcount);
		$tpl->assign("pcount",$pcount);
		$tpl->assign("psize",$psize);
		$tpl->assign("tpage",$tpage);

$tpl->display("member_bets.html");

function daxue($v){
	$v = str_replace('第1球', '第一球', $v);
	$v = str_replace('第2球', '第二球', $v);
	$v = str_replace('第3球', '第三球', $v);
	$v = str_replace('第4球', '第四球', $v);
	$v = str_replace('第5球', '第五球', $v);
	$v = str_replace('第6球', '第六球', $v);
	$v = str_replace('第7球', '第七球', $v);
	$v = str_replace('第8球', '第八球', $v);
	$v = str_replace('第3名', '第三名', $v);
	$v = str_replace('第4名', '第四名', $v);
	$v = str_replace('第5名', '第五名', $v);
	$v = str_replace('第6名', '第六名', $v);
	$v = str_replace('第7名', '第七名', $v);
	$v = str_replace('第8名', '第八名', $v);
	$v = str_replace('第9名', '第九名', $v);
	$v = str_replace('第10名', '第十名', $v);
	return $v;
}

