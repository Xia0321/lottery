<?php /* Smarty version 2.6.18, created on 2024-12-23 16:03:58
         compiled from top.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"   <?php if ($this->_tpl_vars['rkey'] == 0): ?>oncontextmenu="return false"<?php endif; ?>><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $this->_tpl_vars['title']; ?>
</title> 
    <link rel="stylesheet" type="text/css" href="/css/index/main.css"/>
    <link rel="stylesheet" type="text/css" href="/css/default/balls.css"/>
    <link rel="stylesheet" type="text/css" href="/css/index/notice_popup.css"/>
    <link rel="stylesheet" type="text/css" href="/css/index/loading.css"/>
    <link rel="stylesheet" type="text/css" href="/css/index/sweetalert.css"/>
<script language="javascript" src="/js/jquery-1.8.3.min.js"></script>
<script language="javascript" src="/js/public.js"></script>
<script language="javascript" src="/js/jquery-ui.min.js"></script>
<script language="javascript" src="/js/jquery.cookie.js"></script>
</head>
<body class='<?php echo $this->_tpl_vars['skin']; ?>
'>
<script type="text/javascript" id="myjs">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';
var js=1;
var sss="top";
</script>
<div id="header" class="header">
<div class="logo"><span><?php echo $this->_tpl_vars['webname']; ?>
</span></div>
<div class="top">
<div class="menu">
<div class="menu1"> 
        <div id="result_info" class="draw_number" v='611069'><div></div><div></div></div>
        <a id="result_balls" target="_blank" href="javascript:void(0)" class="T107"></a>
  </div>
  <div class="menu2">
    <span><a href="javascript:void(0);" class="lib">未结明细</a></span> |
    <span><a href="javascript:void(0);" class="baoday">今天已结</a></span> |
    <span><a href="javascript:void(0);" class="bao">两周报表</a></span> |
    <span><a href="javascript:void(0);" class="longs">开奖结果</a></span> <br/>
    <span><a href="javascript:void(0);" class="userinfo">个人资讯</a></span> |
    <span><a href="javascript:void(0);" class="changepass2">修改密码</a></span> |
    <span><a href="javascript:void(0);" class="rule">游戏规则</a></span> |
    <span id="skinPanel">
        <ul>
            <li class="red <?php if (( $this->_tpl_vars['skin'] == 'skin_red' )): ?>active<?php endif; ?>" skin="red"><i style="background:#dc2f39"><a href="#"></a></i></li>
            <li class="blue <?php if (( $this->_tpl_vars['skin'] == 'skin_blue' )): ?>active<?php endif; ?>" skin="blue"><i style="background:#5382bc"><a href="#"></a></i></li>
            <li class="orange <?php if (( $this->_tpl_vars['skin'] == 'skin_0ange' )): ?>active<?php endif; ?>" skin="0ange"><i style="background:#d45000"><a href="#"></a></i></li>
            <li class="green <?php if (( $this->_tpl_vars['skin'] == 'skin_green' )): ?>active<?php endif; ?>" skin="green"><i style="background:#61a000"><a href="#"></a></i></li>
        </ul>
    </span>
    <ul style="display: none;" ><li skin="red"><i style="background:#dc2f39"></i><span>&nbsp;&nbsp;红色</span></li><li skin="blue"><i style="background:#5382bc"></i><span>&nbsp;&nbsp;蓝色</span></li><li skin="gray" style="display: none;"><i style="background:#cdcdcd"></i><span>&nbsp;&nbsp;灰色</span></li></ul>
    </span> 
  </div>
  <div class="menu4" style='display:none;'><?php if ($this->_tpl_vars['moneytype'] == 1): ?><a target="_blank" href="<?php echo $this->_tpl_vars['kfurl']; ?>
" class="support"></a><?php endif; ?></div>
  <div class="menu3"><a href="<?php echo $this->_tpl_vars['mulu']; ?>
top.php?logout=yes" class="logout">退出</a></div>
  <div style="clear:both;"></div>
</div>
<div class="lotterys">
<div id="lotterys" >
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
<a href="javascript:void(0)" gid='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
' class='g<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
 g<?php if ($this->_tpl_vars['gid'] == $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']): ?> selected<?php endif; ?>' <?php if (( $this->_sections['i']['index'] > 7 || $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['ifok'] == 0 )): ?>style='display:none;'<?php endif; ?>><span><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
</span></a>
<?php endfor; endif; ?>

<a class="more_game" <?php if ($this->_tpl_vars['vgame'] < 8): ?>style="display: none;"<?php endif; ?>><span>更多游戏  ▼</span></a>
<?php if ($this->_tpl_vars['moneytype'] == 1): ?>
<?php endif; ?>

</div>
<div class="show"></div>
<!--<a class="more_game" style="display: none"><span>更多游戏 </span></a>-->
<a class="setting">设置</a>
</div>

<div class="sub">
<div>
</div></div>


</div>


</div>
<div id="main">
    <?php if ($this->_tpl_vars['cnews'] > 0): ?>
<div class="Notice">
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
    <div id="notice<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
" i="<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
" <?php if ($this->_tpl_vars['news'][$this->_sections['i']['index']]['id'] > 1): ?>style='display:none;'<?php endif; ?>>
        <div class="back_body">
        </div>
        <div class="back_body">
        </div>
        <div class="notice_div blue_back">
            <a href="#">
            <div id="notClose<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
" class="close_icon">
            </div>
            </a>
            <div class="notice_page">
                <a href="#" id="btnPrev<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
" class="notice_prev">&lt;&lt;</a><?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
/<?php echo $this->_tpl_vars['cnews']; ?>
<a href="#" class="notice_next" id="btnNext<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
">&gt;&gt;</a>
            </div>
            <div class="notice_icon">
                <div class="nicon_icon1">
                </div>
                <div class="nicon_button">
                    <a href="#" class="notice_white animate" style="display: none;">更多</a>
                </div>
            </div>
            <div class="notice_font">
                公告：<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['content']; ?>

            </div>
            <div id="notice_button<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
" class="notice_button">
                <a href="#" class="notice_yellow animate">知道</a>
            </div>
        </div>
    </div>
    <?php endfor; endif; ?>
</div>
<?php endif; ?>
<div class="side_left" id="side">
<div class="user_info">
<div class="title" sy="<?php echo $this->_tpl_vars['sy']; ?>
">账户信息</div>

<div class="zhanghu">
<div class="info"><label>账号：</label><span style='width:100px;'><?php echo $this->_tpl_vars['username']; ?>
</span></div>
<div class="info" <?php if (( $this->_tpl_vars['cpan'] == 1 )): ?>style='display:none;'<?php endif; ?>><label>盘口：</label><span>
    <select id = "abcd" class="abcd"><?php $_from = $this->_tpl_vars['pan']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?><OPTION value="<?php echo $this->_tpl_vars['i']; ?>
" <?php if (( $this->_tpl_vars['i'] == $this->_tpl_vars['defaultpan'] )): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['i']; ?>
盘</OPTION><?php endforeach; endif; unset($_from); ?></select></span></div>
<div id="account0" class="accounts kedu">
<div class="info" style="display:none;"><label>快开彩额度：</label><span class="kmaxmoney"><?php echo $this->_tpl_vars['kmaxmoney']; ?>
</span></div>
<div class="info"><label>快开彩额度：</label><span class="kmoney"><?php echo $this->_tpl_vars['kmoney']; ?>
</span></div>
<div class="info"><label>未结算金额：</label><span class="kmoneyuse"><?php echo $this->_tpl_vars['kmoneyuse']; ?>
</span></div>
</div>
<div id="account2" class="accounts dedu" >
<div class="info" ><label>低频彩额度：</label><span class="maxmoney"><?php echo $this->_tpl_vars['maxmoney']; ?>
</span></div>
<div class="info"><label>可用余额：</label><span class="money"><?php echo $this->_tpl_vars['money']; ?>
</span></div>
<div class="info" style="display:none;" ><label>未结算金额：</label><span class="moneyuse"><?php echo $this->_tpl_vars['moneyuse']; ?>
</span></div>
</div>

<div id="account2" class="accounts fedu" >
<div class="info" style="display:none;"><label>额度：</label><span class="fmaxmoney"><?php echo $this->_tpl_vars['kmaxmoney']; ?>
</span></div>
<div class="info"><label>快开彩额度：</label><span class="fmoney"><?php echo $this->_tpl_vars['kmoney']; ?>
</span></div>
<div class="info" ><label>未结算金额：</label><span class="fmoneyuse"><?php echo $this->_tpl_vars['kmoneyuse']; ?>
</span></div>
</div>

</div>
</div>
<?php if ($this->_tpl_vars['moneytype'] == 1): ?>
<div class="betdone" id="drawOfficial">
<?php if ($this->_tpl_vars['layer'] < $this->_tpl_vars['maxlayer']): ?>
<div class="title"><a href="member.php?xtype=myitem" target="frame">我的团队</a></div>
<!--<div class="title"><a href="member.php?xtype=ad" target="frame">推广链接</a></div>-->
<!--<div class="title"><a href="member.php?xtype=tuanbao" target="frame">团队报表</a></div>-->
<?php endif; ?>
<div class="title"><a href="member.php?xtype=notices" target="frame">个人中心</a></div>
<div class="title"><a href="member.php?xtype=zxcz" target="frame">在线充值</a></div>
<div class="title"><a href="member.php?xtype=zxtk" target="frame">在线提款</a></div>
</div>
<?php endif; ?>
<div class="betdone" id="drawOfficial">
<div class="title"><a id='kjurl' target='_blank' href="<?php echo $this->_tpl_vars['kfurl']; ?>
">全国开奖网</a></div>
</div>
<div class="betdone" id="lastBets">
<div class="title"><span>最新注单</span></div>
<ul class="bets last15"></ul>
</div>
<div style="display:none" id="betResultPanel">

<div class="control s0">
<a onclick="resetPanel()" href="javascript:void(0)">返 回</a>
</div>
<div id="betResultDrawNumber" class="Paneltitle"></div>
<div class="bresults">
<ul class="bets" id="betReulstList"></ul>
<table class="total s0">
<tbody>

</tbody></table>
</div>
</div>

</div>

<div class="frame"><iframe id="frame" name="frame" frameborder="0"></iframe></div>
</div>
<div id="footer"><div class="info"><marquee id="notices" scrollamount="2"></marquee></div><a href="javascript:void(0)" class="more">更多</a></div>


<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable sendtb" tabindex="-1"  style="display:none;position: relative; height: auto; width: 400px; top: 100px; left: 300px;  z-index: 101;"><div class="ui-dialog-titlebar  ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-2" class="ui-dialog-title">下注明细（请确认注单）</span><button type="button" class="ui-dialog-titlebar-close ui-button-icon-only close" role="button" title="Close"></button></div><div id="betsBox" class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 0px; max-height: none; height: auto;"><div class="betList"><table class="table"><thead><tr><th style="display: none;">序号</th><th>号码</th><th>赔率</th><th>金额</th><th>确认</th></tr></thead><tbody id="betlist"></tbody></table></div><div class="bottom"><span id="bcount"></span><span id="btotal"></span></div><div><label class='plts'><input style="display:none;" type="checkbox" id="ignoreOdds">如赔率变化，按最新赔率投注，成功后提示赔率变化</label><label style="display:none;" class='cgts red'>请[点击左上角]或[按回车键]关闭本窗口中,5秒后自动关闭本窗口!</label></div></div><div class="ui-dialog-buttonset"><button type="button" class="ui-button qr" ><span class="ui-button-text">确定</span></button><button type="button" class="ui-button close" role="button"><span class="ui-button-text">取消</span></button></div></div>

<div class="ui-widget-overlay ui-front ui-fronts" style="z-index: 100;display:none;"></div>

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-dialog-buttons ui-draggable news" tabindex="-1" style="position: relative; height: auto; width: 800px; top: 95px; left: 299px; display: block; z-index: 101; right: auto; bottom: auto;display:none;"><div class="ui-dialog-titlebar ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">历史公告</span><button type="button" class="ui-dialog-titlebar-close ui-button-icon-only close" role="button" title="Close"></button></div><div id="moreNotice" class="ui-dialog-content ui-widget-content" style="display: block; width: auto; min-height: 0px; max-height: none; height: 405px;"><table class="table notices"><thead><tr><th style="width:110px">时间</th><th>公告详情</th></tr></thead><tbody></tbody></table></div><div class="ui-dialog-buttonset"><button type="button" class="ui-button close" role="button"><span class="ui-button-text">关闭</span></button></div></div>

<div class="popPanel moregame" style="display: none;">
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
<?php if ($this->_sections['i']['index'] >= 8): ?>
<?php if (( $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['ifok'] == 1 )): ?>
<a href="javascript:void(0)" gid='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
'><span><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
</span></a>
<?php endif; ?>
<?php endif; ?> 
<?php endfor; endif; ?>
</div>



<?php if ($this->_tpl_vars['moneytype'] == 1): ?>
<?php endif; ?>

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable gameset"  style="position: relative; height: auto; width: 300px; top: 220px; left: 613px; display: none; z-index: 101;">

<div class="ui-dialog-titlebar  ui-corner-all ui-helper-clearfix ui-draggable-handle">
   <span id="ui-id-1" class="ui-dialog-title">选择显示彩种</span>
     <button type="button" class="ui-dialog-titlebar-close ui-button-icon-only close" role="button" title="Close"></button></div>
     
   <div id="lotteryChoose" class="ui-dialog-content ui-widget-content" style="width: auto; min-height: 55px; max-height: none; height: auto;">
   
   <ul class="ui-sortable">
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
  <li class="ui-sortable-handle"><input type="checkbox" <?php if (( $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['ifok'] == 1 )): ?>checked="checked"<?php endif; ?> value="<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
"><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
</li>
<?php endfor; endif; ?>
  </ul>
   <p>注：可拖动彩种位置来改变彩种排序。</p>
   </div>
   
   <div class="ui-dialog-buttonset"><button type="button" class="ui-button qr" ><span class="ui-button-text">确定</span></button><button type="button" class="ui-button close" role="button"><span class="ui-button-text">取消</span></button></div>
   
  </div>


<div class="gamecontainer moregames">
  <div style="height: 20px;"></div>
  <div class="gamebox clearfix" style="display:block">
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

<div class="itemmg"><a href="javascript:void(0)"  gid='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
'><span><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
</span></a>
    <?php if (in_array ( $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid'] , $this->_tpl_vars['garr'] )): ?>
    <div class="delbtn" style="display: block;"></div>
    <?php else: ?>
    <div class="addbtn" style="display: block;"></div>
    <?php endif; ?>
</div>
<?php endfor; endif; ?></div>

  <div class="gamesmltxt">
          
  </div>
  <div class="editon" style="display: block;">
    <button class="gamebtn2">关闭</button>
    <button class="gamebtn1">修改</button>
  </div>
  <div class="editoff" style="display: none;">
    <button class="gamebtn2">取消修改</button>
    <button class="gamebtn1">保存设置</button>
  </div>
</div>
<style type="text/css">
.skin_blue .gamecontainer,
.skin_blue .new-card-games-container {
    background-color: #e7e7e7;
    border: 1px solid #2161b3;
}
.skin_red .gamecontainer,
.skin_red .new-card-games-container {
    background-color: #e7e7e7;
    border: 1px solid #6a1f2d;
}
.skin_gray .gamecontainer,
.skin_gray .new-card-games-container {
    background-color: #e7e7e7;
    border: 1px solid #c58514;
}
.gamecontainer {
    display: none;
    position: absolute;
    top: 37px;
    left: 676px;
    width: 480px;
    height: auto;
    padding: 0 15px 10px 15px;
}


.skin_blue  .gamecontainer a {
    background: none;
    color: #fff;
    height: 100%;
    display: inline-block;
    height: 38px;
    line-height: 38px;
    font-size: 13px;
    text-align: center;
}

.skin_blue  .gamecontainer a {
    background-color: #2161b3;
    color: #fff;
    height: 100%;
    display: inline-block;
    height: 38px;
    line-height: 38px;
    font-size: 13px;
    text-align: center;
}

.skin_gray  .gamecontainer a {
    background: none;
}

.skin_gray  .gamecontainer a {
    background-color: #8c420b;
}

.skin_red  .gamecontainer a {
    background: none;
}

.skin_red  .gamecontainer a {
    background-color: #6a1f2d;
    color: #fff;
    height: 100%;
    display: inline-block;
    height: 38px;
    line-height: 38px;
    font-size: 13px;
    text-align: center;

}


.skin_blue  .gamecontainer a span {
    width: 130px;
    display: block;
}

.skin_blue  .gamecontainer a:hover {
    background-color: #2161b3;
    color: #fff;
}

.skin_red  .gamecontainer a span {
    width: 130px;
    display: block;
}

.skin_red  .gamecontainer a:hover {
    background-color: #6a1f2d;
    color: #fff;
}

.skin_gray  .gamecontainer a span {
    width: 130px;
    display: block;
}

.skin_gray  .gamecontainer a:hover {
    background-color: #8c420b;
    color: #fff;
}

.skin_gray .gamecontainer {
    background-color: #f8e8a2;
    border: 1px solid #c58514;
}

.skin_red .gamecontainer {
    background-color: #d67a84;
    border: 1px solid #6a1f2d;
}
.addbtn {
    position: absolute;
    top: 0;
    right: 0;
    width: 130px;
    height: 38px;
    background-image: url(../imgs/btnadd.png);
    background-repeat: no-repeat;
    background-position: 115px 13px;
    cursor: pointer;
    display: none;
}

.delbtn {
    position: absolute;
    top: 0;
    right: 0;
    width: 130px;
    height: 38px;
    background-image: url(../imgs/btnremove.png);
    background-repeat: no-repeat;
    background-position: 115px 13px;
    cursor: pointer;
    display: none;
}

.gamebox .row1 {
    -min-height: 18px;
    font-size: 13px;
    color: #000;
    margin-bottom: 3px;
}

.gamebox .row1:last-child {
    margin-bottom: 0;
}

.gamebox .col {
    width: 33.3%;
    float: left;
}

.gamebox .col input[type="checkbox" i] {
    margin-right: 5px;
}

.gamebox, .gamebox2 {
    padding: 25px 35px;
    background-color: #fff;
    border: 1px solid #ccc;
    font-size: 13px;
    display: none;
    height: auto;
    margin-top: 5px;
    border-top: none;
}

.gamebox .selected span {
    color: #fff;
}
.new-card-games-container .gamebox2 {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}

.item,
.itemmg {
    position: relative;
}

.itemmg {
    width: 130px;
    height: 38px;
    float: left;
    margin: 1px;
}
.itemmg a {
    width: 130px;
}

.gamebox .row1 {
    -min-height: 18px;
    font-size: 13px;
    color: #000;
    margin-bottom: 3px;
}

.gamebox .row1:last-child {
    margin-bottom: 0;
}

.gamebox .col {
    width: 33.3%;
    float: left;
}

.gamebox .col input[type="checkbox" i] {
    margin-right: 5px;
}

.gamesmltxt {
    margin-top: 20px;
    font-size: 12px;
    color: #333;
    float: left;
}

.skin_red .gamebtn1 {
    background-color: #ad394b;
    border: 1px solid #ad394b;
}

.skin_red .gamebtn2 {
    background-color: #cccccc;
    border: 1px solid #999;
}
.skin_blue .gamebtn1 {
    background-color: #2161b3;
    border: 1px solid #2161b3;
}

.skin_blue .gamebtn2 {
    background-color: #cccccc;
    border: 1px solid #999;
}

.gamebtn1,
.gamebtn2 {
    float: right;
    margin-right: 5px;
    padding: 8px;
    text-align: center;
    width: 90px;
    color: #fff;
    margin-top: 10px;
    cursor: pointer;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
}

.clearfix:before,
.clearfix:after {
    content: " ";
    display: table;
}

.clearfix:after {
    clear: both;
}

/*
* For IE 6/7 only
* Include this rule to trigger hasLayout and contain floats.
*/

.clearfix {
    *zoom: 1;
}


</style>
</body>
</html>