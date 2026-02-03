<?php /* Smarty version 2.6.18, created on 2025-01-02 15:13:26
         compiled from top.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Welcome</title>
<link href="/css/default/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="/css/default/master.css" rel="stylesheet" type="text/css" />
<link href="/css/default/layout.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/jquery-1.8.3.min.js"></script>
   
<script language="javascript">
function hideinfo(){ if(event.srcElement.tagName=="A"){
   window.status=event.srcElement.innerText}
}
document.onmouseover=hideinfo; 
document.onmousemove=hideinfo;
var globalpath = "<?php echo $this->_tpl_vars['globalpath']; ?>
";
if(<?php echo $this->_tpl_vars['cnews']; ?>
>0){
    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['news']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    alert("<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['content']; ?>
");
    <?php endfor; endif; ?>
}

</script>
<style type="text/css">
.menus{width:160px;background:url(/css/default/img/menu.png);text-align:center;cursor:pointer;float:left;}
.status{float:left;}
.games{display:none;position:absolute;
	height: 28px;
	background:#FFF;z-index:99;
	width:100%
}
.games li{height:100%}

.games  a {
	display: block;
	height: 28px;
	color: #fff;
	line-height: 28px;
	background: url(/css/default/img/nav_bu.png) no-repeat left -67px;
	text-shadow: 1px 1px 0 #426b9e;
	text-align: center;
	margin: 0 2px 0 0;
	float: left;
	font-size: 14px;
	padding-left:5px;
	padding-right:5px;
}

.games a:hover{	color: #1554BE;
	background: url(/css/default/img/nav_bu.png) no-repeat left -32px;
	text-shadow: 1px 1px 0 #f8fafd;
	font-weight: bold;
	font-size: 14px;
}
#online{cursor:pointer}
.qzclose{padding:2px;}
.upkj{float:left;font-weight:bold;color:#000;}
#lotterys a{min-width:60px;}

</style>
<link href="/css/default/ball.css" rel="stylesheet" type="text/css" />
</head>
<body id="topbody">

<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='top';var ustatus = <?php echo $this->_tpl_vars['status']; ?>
;</script>
<ul class="games" id='nav'><li >
  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['gamecs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
      <a gid=<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
  gname=<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
 fenlei=<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['fenlei']; ?>
  <?php if ($this->_tpl_vars['gid'] == $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']): ?>class='xz'<?php endif; ?> href='javascript:void(0)'><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['sgname']; ?>
</a>
  <?php endfor; endif; ?></li>
</ul>
	<div class="header">
	<div class="top" style="position:relative">
		<div class="logo"><span><?php echo $this->_tpl_vars['webname']; ?>
</span></div>
        <div class="menu">
        <div class="expire_info">
             <div class="menus" style="display: none;">
</div>
<div class="status"  style="display: none;">
	<span style="margin-left:5px;"><label class="qishu"><?php echo $this->_tpl_vars['qishu']; ?>
</label>期</span><span class="panstatus" s='<?php echo $this->_tpl_vars['panstatus']; ?>
' style="margin-left:5px;"><span><?php if ($this->_tpl_vars['panstatus'] == 1): ?>距封盘:<?php else: ?>距开盘:<?php endif; ?></span><label class="time0"><?php echo $this->_tpl_vars['pantime']; ?>
</label></span><?php if ($this->_tpl_vars['gid'] == 100): ?><span s='<?php echo $this->_tpl_vars['otherstatus']; ?>
' class="otherstatus hide" style="margin-left:5px;"><span><?php if ($this->_tpl_vars['otherstatus'] == 1): ?>距正码关盘:<?php else: ?>距正码开盘:<?php endif; ?></span><label class="time1"><?php echo $this->_tpl_vars['othertime']; ?>
</label></span><?php endif; ?>&nbsp;<input type="button" value="关盘" class="s1 qzclose" style="display:none;"/>
</div>
<div style="float:left;display: none;">
	<label class='upqishu chu blue' m='<?php echo $this->_tpl_vars['upkj']; ?>
'><?php echo $this->_tpl_vars['upqishu']; ?>
</label><span class="hei">期开奖:</span>
</div>
<div class="upkj"  style="display: none;">
</div>
        </div> 
    	<ul class="menu_title topmenu">
        	<li>
            	<?php if (( $this->_tpl_vars['slib'] == 1 && $this->_tpl_vars['status'] == 1 )): ?><a href="javascript:void(0);" class="lib control" i=0 x="slib">即时注单</a><?php endif; ?>
                <?php if (( $this->_tpl_vars['libset'] == 1 && $this->_tpl_vars['status'] == 1 )): ?><a href='javascript:void(0);' x="libset">自动补货</a><?php endif; ?>
                <?php if (( $this->_tpl_vars['suser'] == 1 && $this->_tpl_vars['status'] == 1 )): ?><a href="javascript:void(0);" x='suser'  i=1>用户管理</a><?php endif; ?>
                <?php if (( $this->_tpl_vars['credit'] == 1 && $this->_tpl_vars['status'] == 1 )): ?><a href="javascript:void(0);"  i=2 x='credit'>个人管理</a><?php endif; ?>
                <?php if ($this->_tpl_vars['baox'] == 1): ?><a href="javascript:void(0);" target="frame" x="baox">报表查询</a><?php endif; ?>
				<a href="javascript:void(0);" target="frame" x="longs">开奖结果</a>
                <?php if (( $this->_tpl_vars['ifexe'] == 1 & $this->_tpl_vars['cssz'] == 1 & $this->_tpl_vars['status'] == 1 )): ?> <a href="javascript:void(0);" target="frame"  i=4 x="pset">高级功能</a><?php endif; ?>
                <a href="javascript:void(0);" >退出</a>
            </li>
        </ul>
    </div>
        

        <ul class="tools">
            <!--  <li class="tools_skin"><a href="javascript:;"><span>皮&nbsp;肤</span></a></li>-->
            <li class="tools_user"><span class="ico"></span></li>
            <li class="tools_user" style="width:66%">在线会员： 网页端：<label class="online">0</label> + APP：0总在线：<label class="online">0</label></span><br><?php echo $this->_tpl_vars['layername']; ?>
：<?php echo $this->_tpl_vars['username']; ?>
</li>
        </ul>

    </div>
    
    <div class="lottery nav" id="lotterys" style="display: none;">
    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['gamecs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <a gid='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
'  fenlei='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['fenlei']; ?>
'  class='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['class']; ?>
 g<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
' ><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
</a>
     <?php endfor; endif; ?>
        </div>
    
    
     <?php if ($this->_tpl_vars['slib'] == 1): ?><ul class="menu_sub"><li> </li></ul><?php endif; ?>
      <?php if ($this->_tpl_vars['libset'] == 1): ?><ul class="menu_sub">         
            <li class="menu_sub_title">当前选中：<span>注单设置</span></li>
            <li class="menu_sub_link">            
			<a href="javascript:void(0)" target="frame" u='libset' type='show'>自动补货设定</a> | 
      <a href="javascript:void(0)" target="frame" u='libset' type='flyrecord'>自动补货变更记录</a> | 
            <a href="javascript:void(0)" target="frame" u='libset' style="display: none;" type='warn'>警示金额</a>
            <?php if ($this->_tpl_vars['ifexe'] == 1): ?> | 
            <a href="javascript:void(0)" target="frame" u='libset' type='auto'>自动降倍</a><?php endif; ?>  
        </li></ul>
       <?php endif; ?> 
     <?php if ($this->_tpl_vars['suser'] == 1): ?><ul class="menu_sub">
          <li class="menu_sub_title">当前选中：<span>用户管理</span></li>
            <li class="menu_sub_link">
      <a href="javascript:void(0)" class="usermenu selected userzsdl">直属代理</a> | 
      <a href="javascript:void(0)" class="usermenu userzshy">直属会员</a> | 
      <a href="javascript:void(0)" class="usermenu userqbdl">全部代理</a> | 
      <a href="javascript:void(0)" class="usermenu userqbhy">全部会员</a> 
      | <a href="javascript:void(0)" class="usermenu userzzh">直属子账号</a>
      </li>
        </ul><?php endif; ?>
     <?php if ($this->_tpl_vars['credit'] == 1): ?>
        <ul class="menu_sub">
            <li class="menu_sub_title">当前选中：<span>个人管理</span></li>
            <li class="menu_sub_link">
			 <a href='javascript:void(0);' u='credit' type='show'>信用资料</a> | 
			 <a href='javascript:void(0);' u='credit' type='logininfo'>登录日志</a> | 
            <a href='javascript:void(0);' u='changepass2' type='show'>变更密码</a> | 
            <a href='javascript:void(0);' u='suser' type='cmoneypasse'>变更转账密码</a> | 
             <a href='javascript:void(0);' u='twoyan' type='show'>二次验证</a>
			 
			</li>            
        </ul>
      <?php endif; ?>  
      <?php if ($this->_tpl_vars['baox'] == 1): ?><ul class="menu_sub"><li></li></ul><?php endif; ?>
       <ul class="menu_sub"><li></li></ul>
       <ul class="menu_sub"><li></li></ul>

       <?php if (( $this->_tpl_vars['ifexe'] == 1 & $this->_tpl_vars['cssz'] == 1 )): ?>
        <ul class="menu_sub">
        	<li class="menu_sub_title">当前选中：<span>高级功能</span></li>
            <li class="menu_sub_link">
              <a href='javascript:void(0);' u="pset" type='show'>操盘记录</a> |
               <a href='javascript:void(0);' u="cssz" type='show'>赔率参数</a> |  
               <a href='javascript:void(0);' u="cssz" type='times'>开关盘时间</a>
               
			</li>
        </ul>
       <?php endif; ?>  



    

   </div> 
    <div id="contents" >
		<iframe id="frame" name="frame" frameborder="0" src="<?php if (( $this->_tpl_vars['status'] != 1 )): ?>/agent/baox.php?xtype=show<?php else: ?>/agent/news.php<?php endif; ?>"></iframe>
	</div>
    <div id="footer" class="footer"><div class="notice"><marquee scrolldelay="90" scrollamount="4"><a id="notices"  href="javascript:void(0);" target="frame"></a></marquee></div><a href="javascript:void(0)" target="frame" class="more">更多</a></div>
<script language="javascript" id='zhishu'>
var ngid=<?php echo $this->_tpl_vars['gid']; ?>
;
var fenlei = <?php echo $this->_tpl_vars['fenlei']; ?>
;
var layer=<?php echo $this->_tpl_vars['layer']; ?>
;
ma = [];
     ma['紅'] = new Array(01,02,07,08,12,13,18,19,23,24,29,30,34,35,40,45,46); 
     ma['藍'] = new Array(03,04,09,10,14,15,20,25,26,31,36,37,41,42,47,48); 
     ma['綠'] = new Array(05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49); 

</script>
</body>
</html>