<?php
include('../data/comm.inc.php');
include('../data/myadminvar.php');
include('../func/func.php');
include('../func/adminfunc.php');
include('../global/page.class.php');
include('../include.php');
include('./checklogin.php');
require_once('../global/sql_helper.php'); // 安全修复：引入 SQL 辅助函数 (2026-02-03)

switch ($_REQUEST['xtype']) {
    case "show":
	    $psize = $config['psize'];
        $msql->query("select count(id) from `$tb_message`");
        $msql->next_record();
         $rcount = $msql->f(0);       
        $thispage = r1($_REQUEST['PB_page']);
        $page     = new page(array(
            'total' => $rcount,
            'perpage' => $psize,
			'nowindex' => $thispage
        ));
        $msql->query("select * from `$tb_message` order by time desc limit " . (($thispage - 1) * $psize) . ",$psize");
        $m = array();
        $i = 0;
        while ($msql->next_record()) {
            $m[$i]['content']  = $msql->f('content');
            $m[$i]['response'] = $msql->f('response');
            $m[$i]['id']       = $msql->f('id');
            $m[$i]['time']     = $msql->f('time');
            $m[$i]['user']     = transu($msql->f('userid'));
            $i++;
        }
        $tpl->assign("m", $m);
        $tpl->assign('page', $page->show());
        $tpl->display("message.html");
        break;
    case "mdel":
        // 安全修复：使用批量删除函数 (2026-02-03)
        $id  = $_POST['id'];
        $affected = batchDelete($msql->mysqli, $tb_message, $id, 'id', []);
        if ($affected > 0) {
            echo 1;
        }
        break;

    case "response":
        // 安全修复：使用 prepared statement (2026-02-03)
        $id      = intval($_POST['id']);
        $message = $_POST['message'];

        $stmt = $msql->mysqli->prepare("UPDATE `$tb_message` SET response = ? WHERE id = ?");
        $stmt->bind_param("si", $message, $id);
        if ($stmt->execute()) {
            echo 1;
        }
        $stmt->close();
        break;

   case "nss":
        // 安全修复：使用 prepared statement (2026-02-03)
        $uid = intval($_REQUEST['uid']);
        $news = $_REQUEST['news'];

        $stmt = $msql->mysqli->prepare("INSERT INTO `$tb_message` (content, userid, time) VALUES (?, ?, NOW())");
        $stmt->bind_param("si", $news, $uid);
        if ($stmt->execute()) {
            echo 1;
        }
        $stmt->close();
   break;	
}
?>