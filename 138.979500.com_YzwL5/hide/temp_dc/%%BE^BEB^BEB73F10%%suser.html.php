<?php /* Smarty version 2.6.18, created on 2025-01-02 15:05:08
         compiled from suser.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'headers.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script language="javascript" src="/js/jquery-ui.js"></script>
<link href="/css/default/user.css" rel="stylesheet" type="text/css">
<style type="text/css">
.user_tb .account{text-align:left;padding-left:5px;}
.query_panel input.s1{padding:2px;}
.tinfo{display:none;position:absolute}
input.tytxt{width:60px !important;}
#usernameMsg {
    margin-left: 5px;
}
em.success {
    background: url(../css/images/checked.gif) no-repeat 0px 0px;
    margin-left: 3px;
    padding-left: 16px;
    color: blue;
}

em {
    font-style: italic;
}

.error {
    color: #f97c00;
}

em.error {
    background: url(../css/images/unchecked.gif) no-repeat 0px 0px;
    margin-left: 3px;
    padding-left: 16px;
    color: red;
}
</style>
</head>
<body style="overflow:hidden">
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='suser';</script>
	<div class="main">
		<div class="top_info">
			<span class="title"><label>集团</label> -&gt; <label>直属代理</label></span>
			<div class="center">
				<div class="query_panel">
					<span class="input_panel">
					<label>模式：<select id="" name="resetType" class="fudong">
        <option value="all" selected="selected">全部</option>
        <option value="0">信用</option>
        <option value="1">现金</option>
</select>
</label>
					<label>状态：<select id="" name="status" class="status">
        <option value="all"  selected="selected">全部</option>
        <option value="1">启用</option>
        <option value="2">冻结</option>
        <option value="0">停用</option>
</select>
</label> <label>搜索：</label> 账号或名称：<input name="name" class="input" id="usernames">
					</span> <input type="button" value="查找" class="query searchbtn">
     <input type="hidden" name="fid"  id="fid" value="<?php echo $this->_tpl_vars['fid']; ?>
" username='<?php echo $this->_tpl_vars['username']; ?>
' />
     <input type="hidden" name='layer' id='layer'  toplayer = '<?php echo $this->_tpl_vars['flayer']; ?>
' value='<?php $this->assign('flayers', ($this->_tpl_vars['flayer']+1)); ?><?php echo $this->_tpl_vars['flayers']; ?>
' />
     <input type="hidden" id='saveuserid' />
     <input type="hidden" id='topid' value="<?php echo $this->_tpl_vars['fid']; ?>
" />  
     选定:
     <input id='openselect' value='启用'  type="button" class="s1" />
     <input id='pauseselect' value='冻结'  type="button" class="s1" />
     <input id='closeselect' value='停用' type="button" class="s1" />
     <input  id='delselect' value='删除'  type="button" class="s1" />
     <input  id='jiaozheng' value='额度较正'  type="button" class="s1" />
     <input  id='resetmoney' value='低频信用恢复'  type="button" class="s1" />
     <input  id='resetkmoney' value='快开信用恢复'  type="button" class="s1" />
				</div>
				 <a class="addtop add" href="javascript:void(0);" type='ag'>新增一级代理</a>  <a class="add" style='display:none;' href="javascript:void(0);" type='us'>新增会员</a> 
   
			</div>
			<div class="right">
				<a class="back" href='javascript:void(0)'>返回</a>
			</div>
		</div>
		<div class="contents">
			<ul class="left_panel">
				<li class="title">[<label>集团</label>]下线</li>

			</ul>
			<div class="user_list">
				<table class="data_table list user_tb">
					<thead>
						<tr>
                            <th><input type="checkbox" value="all" class="selectall" /></th>
							<th class="online">在线</th>
							<th class="parent">上级账号</th>
							<th class="type">用户类型</th>
							<th class="username">账号</th> 
                            <?php if ($this->_tpl_vars['config']['fasttype'] == 1): ?>
							<th class="account">快开彩额度</th> 
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['config']['slowtype'] == 1): ?>
							<th class="account" style="display: none;">低频彩额度</th> 
                            <?php endif; ?>
							<th class="share">占成</th>  
							<!--<th class="branch ag">下线</th>-->
							<th class="branch ag">代理</th>
							<th class="branch ag">会员</th>
                            <th class="ag">新增</th>
                            <th class="us">盘口</th>
							
							<th class="created">新增日期</th>
							<th class="status">状态</th>
							<th class="op">功能</th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
                
				<div class="page"><div class="page_info">
<span class="record">共 <span>5</span> 条记录</span>
<span class="page_count">共 <span>1</span> 页</span>
<span class="page_control">

</span>
</div>
</div>
			</div>
		</div>
	</div>
 
 
<div class="utb"></div>
 

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable zcmxtb" tabindex="-1" role="dialog" aria-describedby="shares" aria-labelledby="ui-id-1" style="position: absolute; height: auto; width: 900px; display: none;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">pei099# 占成明细</span><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close"><span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">Close</span></button></div><div id="shares" class="popdiv ui-dialog-content ui-widget-content" style="display: block; width: auto; min-height: 96px; max-height: 346px; height: auto;"><table id="liml" class="data_table info_table zctb">
</table>
</div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>
 

 
<div id="statusPanel" class="popdiv" style="position:absolute; display: none;"><div class="title">修改帐户状态<i></i></div><div class="statuslist"><label><input name="ustatus" type="radio" value="1">启用</label><label><input name="ustatus" type="radio" value="2">冻结</label><label><input name="ustatus" type="radio" value="0">停用</label></div></div>

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable xxdiv" tabindex="-1" role="dialog" aria-describedby="shares" aria-labelledby="ui-id-1" style="position: absolute; height: auto; width: 900px; display: none;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">pei099# 占成明细</span><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close"><span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">Close</span></button></div><div id="shares" class="popdiv ui-dialog-content ui-widget-content" style="display: block; width: auto; min-height: 96px; max-height: 346px; height: auto;"> <table class="data_table info_table xxtb">
 <thead><tr class="bt">
 <th>彩种</th>
  <th>期數</th>  
  <th>類別</th>
  <th>金額</th>
  <th>赔率</th>
  <th>退水</th>
  <th>會員</th>
  <th>時間</th>
 </tr></thead><tbody></tbody>
</table></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>
<input type="hidden" class='sort' orderby='time' sorttype='DESC' page='1' xtype='2' js='0' gid='99' con='' />
<table class="recordtb info_table hide">
<tr><td class="e" valign="top"></td><td class="l" valign="top"></td></tr>

</table>
<table class="moneypasstb" style="width: 400px;display: none;">
	<tbody>
		<tr>
			<th>账号</th>
			<td></td>
		</tr>
		<tr>
			<th>转账密码</th>
			<td><input id="transferPassword"  type="password" class="input"></td>
		</tr>
		<tr>
			<th>确认转账密码</th>
			<td><input id="ckTransferPassword" type="password" class="input"></td>
		</tr>
		<tr>
			<th></th>
			<td><input class="moneypasssend" type="button" value="确定"></td>
		</tr>
	</tbody>
</table>


<table class="tinfo info_table copytb">
<tr><th>新账号</th><td><input type="text" class="copyusername input" uid='0' /></td><td rowspan="2"><input type="button" value="复制" class="copysend button" /></td></tr>
<tr><th>名称</th><td><input type="text" class="copyname input" /></td></tr>
</table>
<table class="tinfo cpasstb">
<tr><th>账号</th><td><label class="cpassusername"></label></td><td rowspan="2"><input type="button" value="修改密码" class="cpasssend button" /></td></tr>
<tr><th>新密码</th><td><input type="text" class="cpass1 input" /></td></tr>
<tr><th>确认密码</th><td><input type="text" class="cpass2 input" /></td></tr>
</table>
<table class="tinfo moneytb">
<tr><th>低频彩额度</th><td><input type="text" class="maxmoney input" uid='0' /><input type="text" class="money input" uid='0' /></td><td rowspan="2"><input type="button" value="提交" class="moneysend button" /></td></tr>
<tr><th>快开彩额度</th><td><input type="text" class="kmaxmoney input" /><input type="text" class="kmoney input" /></td></tr>
</table>
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable edudiv" tabindex="-1" role="dialog" aria-describedby="shares" aria-labelledby="ui-id-1" style="position: absolute; height: auto; width: 900px; display: none;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">pei099# 占成明细</span><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close"><span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">Close</span></button></div><div id="shares" class="popdiv ui-dialog-content ui-widget-content" style="display: block; width: auto; min-height: 96px; max-height: 346px; height: auto;"> 
<table class="data_table info_table edutb">
	<tbody>
		<tr>
			<th>账号</th>
			<td></td>
		</tr>
		<tr>
			<th>快开彩额度</th>
			<td><span></span>&nbsp;<input type="button" class="s1 tiquall" value="提取全部额度" ></td>
		</tr>
		<tr><th>当前余额</th><td>0</td></tr>
		<tr><th>上级可用额度</th><td>0</td></tr>
		<tr>
			<th>类型</th>
			<td><label><input type="radio" name="types" value="0">存款</label> <label><input type="radio" name="types" value="1">提款</label></td>
		</tr>
		<tr>
			<th>金额</th>
			<td><input name="balance" class="input"> <span id="popDx" style="color:red" class="dx"></span></td>
		</tr>
		<tr>
			<th></th>
			<td><input id="btnOK" class="s1 setmoney" type="button" value="确定" ></td>
		</tr>
	</tbody>
</table></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>


<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable zztb" tabindex="-1" role="dialog" aria-describedby="transferPasswordDialogue" aria-labelledby="ui-id-2" style="position: absolute; height: 142.28px; width: 550.28px; top: 230px; left: 400px; display: block; z-index: 101; right: auto; bottom: auto;display: none;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-2" class="ui-dialog-title">请输入转账密码</span><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close closezz" role="button" title="Close"><span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">Close</span></button></div><div id="transferPasswordDialogue" class="popdiv ui-dialog-content ui-widget-content" style="display: block; width: auto; min-height: 95px; max-height: none; height: auto;"><table class="list data_table info_table"><tbody><tr><th>转账密码</th><td><input id="inputTransferPassword" type="password" class="input"></td></tr></tbody></table><div style="position:absolute;right:170px;bottom:20px;"><input id="transferOk" type="button" value="确定"></div></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>

<table class="tinfo fedittb">
<tr><th></th><td><input type="text" class="feditmoney input" uid='0' /></td></tr><tr><td colspan=2 ><input type="button" value="提交" class="feditsend button" /><input type="button" value="关闭" class="feditclose button" /></td></tr>

</table>
<?php if ($this->_tpl_vars['hides'] == 1): ?>


<table class="list nss">
<tr><td><input type="text" size="20" class="mess" /><input type="button" class="close input" value="关闭" /><input type="button" class="sendmess input" value="发送" /></td></tr>
</table>
<script language="javascript" >
$(function(){
	$(".nss .close").click(function(){
	   $(".nss").hide();
	});
	$(".nss .sendmess").click(function(){
		var news = $(".nss .mess").val();
		var uid  = $(".nss").attr('uid');
					$.ajax({
					type: 'POST',
					url: 'message.php',
					cache: false,
					data: 'xtype=nss&uid=' + uid+"&news="+news,
					success: function(m) {
					
						if (Number(m) == 1) {
							alert('ok')
						}
					}
				})
	});
});

</script>
<STYLE type="text/css">
.nss{width:300px;position:absolute;background:#fff;display:none}
</STYLE>
<?php endif; ?>
<div id='test'></div>
<input type='text'  id='test2' class="hide" />
<input type="text" id='tests' class="hide"   /> 
<input type="text" id='testss' class="hide"   /> 
<script language="javascript">
layernames= new Array();
layername = new Array();
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['layer']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
layernames[<?php echo $this->_sections['i']['index']; ?>
] = new Array();
layernames[<?php echo $this->_sections['i']['index']; ?>
]['wid'] = <?php echo $this->_tpl_vars['layer'][$this->_sections['i']['index']]['wid']; ?>
;
layernames[<?php echo $this->_sections['i']['index']; ?>
]['layer'] = new Array();
<?php $_from = $this->_tpl_vars['layer'][$this->_sections['i']['index']]['layer']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['j']):
?>
layernames[<?php echo $this->_sections['i']['index']; ?>
]['layer'][<?php echo $this->_tpl_vars['key']; ?>
] = '<?php echo $this->_tpl_vars['j']; ?>
';
<?php endforeach; endif; unset($_from); ?>
layernames[<?php echo $this->_sections['i']['index']; ?>
]['namehead'] = '<?php echo $this->_tpl_vars['layer'][$this->_sections['i']['index']]['namehead']; ?>
';
<?php endfor; endif; ?>
<?php $_from = $this->_tpl_vars['layer'][0]['layer']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['j']):
?>
layername[<?php echo $this->_tpl_vars['key']; ?>
] = '<?php echo $this->_tpl_vars['j']; ?>
';
<?php endforeach; endif; unset($_from); ?>
var maxlayer = <?php echo $this->_tpl_vars['maxlayer']; ?>
;
var maxrenflag = <?php echo $this->_tpl_vars['maxrenflag']; ?>
;
var ustatus = 'all';
var wids=0;
var treeflag=false;
var fidarr=[];
var fidindex = -1;
var slowtype = <?php echo $this->_tpl_vars['config']['slowtype']; ?>
;
var fasttype = <?php echo $this->_tpl_vars['config']['fasttype']; ?>
;
var zcmode = <?php echo $this->_tpl_vars['config']['zcmode']; ?>
;
var moneypassflag = <?php echo $this->_tpl_vars['moneypassflag']; ?>
;
var transferok=false;
</script>
</body></html>