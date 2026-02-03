<?php /* Smarty version 2.6.18, created on 2024-12-22 01:47:20
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
<script language="javascript" src="/js/ui/jquery-ui.js"></script>
<script language="javascript">
function hideinfo(){ if(event.srcElement.tagName=="A"){
   window.status=event.srcElement.innerText}
}
document.onmouseover=hideinfo; 
document.onmousemove=hideinfo;
var globalpath = "<?php echo $this->_tpl_vars['globalpath']; ?>
";

</script>
<style type="text/css">
.menus{width:160px;background:url(/css/default/img/menu.png);text-align:center;cursor:pointer;float:left;}
.status{float:left;}
.games{display:none;position:absolute;
    min-height:400px;
    background:#FFF;z-index:99;
    width: 323px;
 
}
.games li{
    list-style: none;
    float: left;
    height:100%;
    width: 160px;
    border-bottom: solid 1px #fff;
}
.games li:nth-child(even){
    border-left: solid 1px #fff;
}

.games  a {
    float: left; 
    width: 160px;
    display: block;
    height: 30px;
    color: #fff;
    line-height: 30px;
    background: #1554BE;
    text-shadow: 1px 1px 0 #426b9e;
    text-align: center;
    float: left;
    font-size: 14px;
    padding-left:5px;
}

.games a:hover{ 
    background: #629EDA;
}
#online{cursor:pointer}
.qzclose{padding:2px;}
.upkj{float:left;font-weight:bold;color:#000;}


</style>
<link href="/css/default/ball.css" rel="stylesheet" type="text/css" />
</head>
<body id="topbody">
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='top';</script>
<ul class="games" id='nav'>
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
     <li > <a gid=<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
  gname=<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
  fenlei=<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['fenlei']; ?>
 <?php if ($this->_tpl_vars['gid'] == $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']): ?>class='xz'<?php endif; ?> href='javascript:void(0)'><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
</a></li>
  <?php endfor; endif; ?>
</ul>
    <div class="header">
    <div class="top" style="position:relative">
        <div class="logo"><span><?php echo $this->_tpl_vars['webname']; ?>
</span></div>
        <div class="menu">
        <div class="expire_info">
              <div class="menus"></div><div class="status"><span style="margin-left:5px;"><label class=qishu><?php echo $this->_tpl_vars['qishu']; ?>
</label>期</span>  <span class="panstatus" s='<?php echo $this->_tpl_vars['panstatus']; ?>
' style="margin-left:5px;"><span><?php if ($this->_tpl_vars['panstatus'] == 1): ?>距关盘:<?php else: ?>距开盘:<?php endif; ?></span><label class="time0"><?php echo $this->_tpl_vars['pantime']; ?>
</label></span><?php if ($this->_tpl_vars['gid'] == 100): ?><span s='<?php echo $this->_tpl_vars['otherstatus']; ?>
' class="otherstatus hide" style="margin-left:5px;"><span><?php if ($this->_tpl_vars['otherstatus'] == 1): ?>距正码关盘:<?php else: ?>距正码开盘:<?php endif; ?></span><label class="time1"><?php echo $this->_tpl_vars['othertime']; ?>
</label></span><?php endif; ?>&nbsp;<input type="button" value="关盘" class="s1 qzclose"  />      </div><Div style="float:left;">
<label class='upqishu chu blue' m='<?php echo $this->_tpl_vars['upkj']; ?>
'><?php echo $this->_tpl_vars['upqishu']; ?>
</label><span class="hei">期开奖:</span></Div><div class="upkj"></div>
        </div>
        <ul class="menu_title topmenu">
            <li>
                <?php if ($this->_tpl_vars['slib'] == 1): ?><a href="javascript:void(0);" class="lib control" i=0 x="slib">即时注单</a><?php endif; ?>
                <?php if ($this->_tpl_vars['suser'] == 1): ?><a href="javascript:void(0);" x='suser'  i=1>用户管理</a><?php endif; ?>
                <?php if ($this->_tpl_vars['baox'] == 1): ?><a href="javascript:void(0);" target="frame" x="baox">报表查询</a><?php endif; ?>
                <a href="javascript:void(0);" target="frame" x="longs">开奖结果</a>
                <a href="javascript:void(0);" target="frame"  i=3 x="caopan">系统功能</a>
                <a href="javascript:void(0);" target="frame" i=4 class='xjgl'  x="money" <?php if ($this->_tpl_vars['money'] != 1): ?>style='display:none;'<?php endif; ?>>现金管理</a>
                <?php if ($this->_tpl_vars['hide'] == 1): ?> <a href="javascript:void(0);" target="frame"  i=5 x="check">高级功能</a><?php endif; ?>                
                <a href="javascript:void(0);" target="frame" x="changepass2">密码修改</a>
                <a href="javascript:void(0);">退出</a>
            </li>
        </ul>
    </div>
        
        

        <ul class="tools">
            <!--  <li class="tools_skin"><a href="javascript:;"><span>皮&nbsp;肤</span></a></li>-->
            <li class="tools_user"><span class="ico"></span></li>
            <li class="tools_user">在线会员：<span class="online"><?php echo $this->_tpl_vars['onlinenum']; ?>
</span><br/>管理员：<?php echo $this->_tpl_vars['name']; ?>
</li>
        </ul>

    </div>
    
    <div class="lottery nav" id="lotterys">
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
 class='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['class']; ?>
<?php if ($this->_tpl_vars['gid'] == $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']): ?> s<?php endif; ?>' ><?php if ($this->_tpl_vars['gid'] == $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']): ?><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
<?php endif; ?></a>
     <?php endfor; endif; ?>
        </div>
    
    
        <?php if ($this->_tpl_vars['libset'] == 1): ?><ul class="menu_sub"><li> </li></ul><?php endif; ?>
        <?php if ($this->_tpl_vars['suser'] == 1): ?><ul class="menu_sub"><li> </li></ul><?php endif; ?>
        <?php if ($this->_tpl_vars['baox'] == 1): ?><ul class="menu_sub"><li> </li></ul><?php endif; ?>
        <ul class="menu_sub">
            <li class="menu_sub_title">当前选中：<span>系统功能</span></li>
            <li class="menu_sub_link">
            <?php if ($this->_tpl_vars['kj'] == 1): ?>
             <a href="javascript:void(0);" target="frame" u="kj" type='show'>开奖管理</a> | 
            <?php endif; ?> 
            <?php if ($this->_tpl_vars['buhuo'] == 1): ?>
            <a href="javascript:void(0)" target="frame" u='libset' type='show'>补货设置</a> |            
            <a href="javascript:void(0);" target="frame" u="fly" type='show'>飞单设置</a> |
            <a href="javascript:void(0);" target="frame" u="fly" type='flylist'>飞单记录</a> |          
            <?php endif; ?>
            <?php if ($this->_tpl_vars['liushui'] == 1): ?>
            <a href="javascript:void(0);" target="frame" u="fly" type='shui'>赚分设置</a> |                  
            <?php endif; ?>
            <?php if ($this->_tpl_vars['libset'] == 1): ?>         
            <a href="javascript:void(0)" target="frame" u='libset' type='warn'>警示金额</a> | 
            <a href="javascript:void(0)" target="frame" u='libset' type='auto'>自动降倍</a> | 
            <?php endif; ?>
            <?php if ($this->_tpl_vars['now'] == 1): ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['zshui'] == 1): ?>
            <a href="javascript:void(0)" target="frame" u='zshui' type='ma'>号码属性</a> | 
            <a href="javascript:void(0)" target="frame" u='zshui' type='ptype'>默认赔率</a> | 
            <a href="javascript:void(0)" target="frame" u='zshui' type='show'>默认退水</a> | 
            <a href="javascript:void(0)" target="frame" u='zshui' type='setattshow'>赔率参数</a> |  
            <?php endif; ?>
            <?php if ($this->_tpl_vars['news'] == 1): ?>
            <a href="javascript:void(0)" target="frame" u='news' type='show'>消息</a> |         
            <?php endif; ?>
            <?php if (( $this->_tpl_vars['hide'] == 1 || ( $this->_tpl_vars['caopan'] == 1 && $this->_tpl_vars['xxtz2'] == 1 ) )): ?>
            <a href="javascript:void(0)" target="frame" u='xxtz2' type='show'>注单删改</a> | 
            <?php endif; ?>
            <?php if (( $this->_tpl_vars['hide'] == 1 | $this->_tpl_vars['caopan'] == 1 )): ?>
            <a href="javascript:void(0)" target="frame" u='caopan' type='show'>操盘员</a> | 
            <?php if ($this->_tpl_vars['err'] == 1): ?><a href="javascript:void(0)" target="frame" u='err' type='show'>异常注单</a> | <?php endif; ?>
            <a href="javascript:void(0)" target="frame" u='history' type='show'>记录管理</a> | 
            <a href="javascript:void(0)" target="frame" u='sysconfig' type='show'>参数</a> |
            <a href="javascript:void(0)" target="frame" u='online' type='show'>在线</a>
            <?php endif; ?>
            
           
            </li>
        </ul>
    
        <ul class="menu_sub" <?php if ($this->_tpl_vars['money'] != 1): ?>style='display:none;'<?php endif; ?>>           
            <li class="menu_sub_title">当前选中：<span>现金管理</span></li>
            <li class="menu_sub_link">
            <a href="javascript:void(0)" target="frame" u='money' type='chongzhi'>充值管理</a> | 
            <a href="javascript:void(0)" target="frame" u='money' type='tikuan'>提现管理</a> | 
            <a href="javascript:void(0)" target="frame" u='money' type='moneyuser'>现金会员</a> | 
            <a href="javascript:void(0)" target="frame" u='money' type='bank'>银行</a> | 
            <a href="javascript:void(0)" target="frame" u='money' type='chongzhifs'>充值方式</a> | 
            <a href="javascript:void(0)" target="frame" u='money' type='banknum'>银行账号</a> | 
            <a href="javascript:void(0)" target="frame" u='money' type='notices'>消息管理</a> |
                <a href='javascript:void(0);' u="now" type='show'>注单管理</a>
            </li>           
        </ul>


       <?php if ($this->_tpl_vars['hide'] == 1): ?>
        <ul class="menu_sub">             
            <li class="menu_sub_title">当前选中：<span>高级功能</span></li>
            <li class="menu_sub_link">    
            <a href="javascript:void(0);" target="frame" u="baox" type='oldshow'>报表查询</a> | 
            <a href="javascript:void(0)" target="frame" u='xxtz' type='show'>注单明细</a> |   
            <a href="javascript:void(0)" target="frame" u='now' type='show'>注单管理</a> |   
            <a href="javascript:void(0)" target="frame" u='webconfig' type='show'>网站配置</a> |             
            
            <a href="javascript:void(0)" target="frame" u='zshui' type='gameset'>彩种开放</a> | 
                  
            <a href="javascript:void(0)" target="frame" u='class' type='classpan'>玩法归类</a> | 
            <a href="javascript:void(0)" target="frame" u='class' type='bigclass'>大分类</a> | 
            <a href="javascript:void(0)" target="frame" u='class' type='sclass'>小分类</a> | 
            <a href="javascript:void(0)" target="frame" u='class' type='class'>玩法分类</a> | 
            <a href="javascript:void(0)" target="frame" u='play' type='show'>玩法列表</a> | 
            <a href="javascript:void(0)" target="frame" u='err' type='show'>异常注单</a> | 
                  
            <?php if ($this->_tpl_vars['hides'] == 1): ?> 
             
            <a href="javascript:void(0)" target="frame" u='check' type='show'>检测</a> |    
            <a href="javascript:void(0)" target="frame" u='message' type='show'>会员反馈</a> | 
            <a href="javascript:void(0)" target="frame" u='play' type='downlist'>下载记录</a> | 
            <a href="javascript:void(0)" target="frame" u='loglist' type='loglist'>注单记录</a> 
            <?php endif; ?>
            </li>
        </ul>  
        <?php endif; ?>

    

   </div> 
    <div id="contents" >
        <iframe id="frame" name="frame" src='/hide/new.php' frameborder="0"></iframe>
    </div>
    <div id="footer" class="footer"><div class="notice"><marquee scrolldelay="90" scrollamount="4"><a id="notices"  href="javascript:void(0);" target="frame"></a></marquee></div><a href="javascript:void(0)" target="frame" class="more">更多</a></div>
    
<div id="dialog" title="您有新的交易请求" style="display:none;">
  <p style="text-align:center"><button class="clqq s1">前往处理</button></p>
</div>
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