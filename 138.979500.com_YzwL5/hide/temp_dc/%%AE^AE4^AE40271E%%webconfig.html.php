<?php /* Smarty version 2.6.18, created on 2024-12-23 15:36:53
         compiled from webconfig.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style>
td,th{height:30px;line-height:30px}
.edittb{position:absolute;width:750px;display:none;background:#fff}
.edittb th{width:150px;text-align:right;padding-right:5px}
.edittb td{text-align:left}
.h{display:none}
.edittb .txt1{width:300px}
.edittb .txt2{width:80px}
.infotb th{text-align:right;padding-right:5px;width:160px;line-height:150%;height:30px}
.infotb td{text-align:left;padding-left:5px;line-height:150%;height:30px}
.infotb td.mid{text-align:center}
label{color:#D50000}
</style>
</head><body>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='webconfig';</script>
<div class="main" >
	<div class="top_info">
		<span class="title">网站配置</span><span class="right"></span>
	</div>
 <input type="hidden" name="xtype" value="setsys" />
 <table class="data_table list infotb">
  <tr>
    <th>服务器1</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['s1']; ?>
' class="s1" size=12 /></td>
    <th>服务器2</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['s2']; ?>
' class="s2" size=12 /></td>
    <th>服务器3</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['s3']; ?>
' class="s3" size=12 />%</td>
  </tr>
  <tr>
    <th>服务器4</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['s4']; ?>
' class="s4" size=12 /></td>
    <th>幸运飞艇限制倍数</th><td>1/<input type="text" value='<?php echo $this->_tpl_vars['config']['s5']; ?>
' class="s5" size=12 /></td>
    <th>幸运飞艇限额限制开关</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['s6']; ?>
' class="s6" size=12 /></td>
  </tr>
  <tr>
     <th>自动删除登录日志</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['autodellogin'] == 1): ?>checked<?php endif; ?> class="autodellogin" /><input type="text" value='<?php echo $this->_tpl_vars['config']['autodellogintime']; ?>
' class="autodellogintime txt1" />天前</td>
     <th>自动删除修改日志</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['autodeledit'] == 1): ?>checked<?php endif; ?> class="autodeledit" /><input type="text" value='<?php echo $this->_tpl_vars['config']['autodeledittime']; ?>
' class="autodeledittime txt1" />天前</td>
     <th>自动删除赔率日志</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['autodelpl'] == 1): ?>checked<?php endif; ?> class="autodelpl" /><input type="text" value='<?php echo $this->_tpl_vars['config']['autodelpltime']; ?>
' class="autodelpltime txt1" />天前</td>
  </tr>
 <tr> 
    <th>开奖网址</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['kjip']; ?>
' class="kjip" size=16  /></td>
    <th>startid</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['startid']; ?>
' class="startid" size=11 /></td> 
    <th>启用右键</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['rkey'] == 1): ?>checked<?php endif; ?> class="rkey" /></td>
 </tr>
 <tr>
   <th>加密码</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['allpass']; ?>
' class="allpass txt1"  /></td>
   <th>注单加密</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['libkey'] == 1): ?>checked<?php endif; ?> class="libkey" /></td>
   <th>限制人数</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['maxrenflag'] == 1): ?>checked<?php endif; ?> class="maxrenflag" /></td>
 </tr>

  <tr>
    <th>每页记录数1</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['psize']; ?>
' class="psize txt1"  /></td>
    <th>每页记录数2</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['psize1']; ?>
' class="psize1 txt1"  /></td>
    <th>每页记录数3</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['psize2']; ?>
' class="psize2 txt1"  /></td>
    
  </tr>
  <tr>
    <th>每页记录数4</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['psize3']; ?>
' class="psize3 txt1"  /></td>
    <th>每页记录数5</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['psize5']; ?>
' class="psize5 txt1"  /></td>
    <th>使用验证码登录</th><td><input type="checkbox" <?php if ($this->_tpl_vars['config']['logincode'] == 1): ?>checked<?php endif; ?> class="logincode" /></td>
  </tr>
  <tr>
    <th>登录方式</th><td><select class="loginfs"><option value="url" <?php if ($this->_tpl_vars['config']['loginfs'] == 'url'): ?>selected<?php endif; ?>>网址</option><OPTION value="dk" <?php if ($this->_tpl_vars['config']['loginfs'] == 'dk'): ?>selected<?php endif; ?>>端口</OPTION><OPTION value="code" <?php if ($this->_tpl_vars['config']['loginfs'] == 'code'): ?>selected<?php endif; ?>>验证码</OPTION></select></td>
    <th>系统彩计算次数</th><td><input type="text" value='<?php echo $this->_tpl_vars['config']['trys']; ?>
' class="psize3 trys"  /></td>
    <td colspan="w" class="mid"><input type="button" value="修改" class="editall btn1 btnf" /></td>
  </tr>
     <tr>   
   <th>客服网址</th><td colspan="5"><input type='text' class="txt1 kfurl"  value="<?php echo $this->_tpl_vars['config']['kfurl']; ?>
" style="width:600px;" /></td>
   </tr>
 </table>
 <BR />
 <table class="data_table data_list list ztb">
 <thead>
  <tr>
   <th>网站ID</th>
   <th>名称</th>
   <th>手机网址</th>
   <th>会员网址</th>
   <th>代理网址</th>
   <th>管理网址</th>
   <th>手机端口</th>
   <th>会员端口</th>
   <th>代理端口</th>
   <th>管理端口</th>
   <th>赔率模式</th>
   <th>最大级数</th>
   <th>操作
    <input type="button" value="添加" class="add btn1 btnf" /></th>
  </tr></thead>
  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['web']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
  <tr>
   <td class="wid"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['wid']; ?>
</td>
   <td class="webname"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['webname']; ?>
</td>
   <td class="murl"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['murl']; ?>
</td>
   <td class="uurl"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['uurl']; ?>
</td>
   <td class="aurl" ><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['aurl']; ?>
</td>
   <td class="hurl"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['hurl']; ?>
</td>
   <td class="mpo"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['mpo']; ?>
</td>
   <td class="upo"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['upo']; ?>
</td>
   <td class="apo"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['apo']; ?>
</td>
   <td class="hpo"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['hpo']; ?>
</td>
   <td class="patt"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['patt']; ?>
</td>
   <td class="maxlayer"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['maxlayer']; ?>
</td>
   <td><input type="button" value="修改" class="edit btn1 btnf" />
    <input type="button" value="删除" class="dels btn1 btnf" /></td>
   <td class="h moneytype"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['moneytype']; ?>
</td> 
   <td class="h slowtype"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['slowtype']; ?>
</td> 
   <td class="h fasttype"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['fasttype']; ?>
</td> 
   <td class="h zcagent"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['zcagent']; ?>
</td> 
   <td class="h guser"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['guser']; ?>
</td>
   <td class="h uskin"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['uskin']; ?>
</td>
   <td class="h skins"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['skins']; ?>
</td>
   <td class="h namehead"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['namehead']; ?>
</td>
   <td class="h layer"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['layer']; ?>
</td>
   <td class="h mdi"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['mdi']; ?>
</td>
   <td class="h udi"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['udi']; ?>
</td>
   <td class="h adi"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['adi']; ?>
</td>
   <td class="h mdi"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['mdi']; ?>
</td>
   <td class="h hdi"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['hdi']; ?>
</td>
   <td class="h mpo"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['mpo']; ?>
</td>
   <td class="h upo"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['upo']; ?>
</td>
   <td class="h apo"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['apo']; ?>
</td>
   <td class="h hpo"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['hpo']; ?>
</td>
   <td class="h mcode"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['mcode']; ?>
</td>
   <td class="h ucode"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['ucode']; ?>
</td>
   <td class="h acode"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['acode']; ?>
</td>
   <td class="h hcode"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['hcode']; ?>
</td>
   <td class="h webclose"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['webclose']; ?>
</td>
   <td class="h fastinput"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['fastinput']; ?>
</td>
   <td class="h uimg"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['uimg']; ?>
</td>
   <td class="h aimg"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['aimg']; ?>
</td>
   <td class="h mimg"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['mimg']; ?>
</td>
   <td class="h himg"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['himg']; ?>
</td>
   <td class="h times"><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['times']; ?>
</td>
  </tr>
  <?php endfor; endif; ?>
  </tr>
  
 </table>
</div>
<table class="data_table edittb">
 <tr>
  <th colspan="2" style="text-align:center"><input type="button" value="提交" class="editnext btn1 btnf" />
   &nbsp;&nbsp;&nbsp;
   <input type="button" value="关闭" class="close btn1 btnf" /><input type="hidden" class="action" value="" /></th>
 </tr>
 <tr>
  <th>网站ID</th>
  <td><label class="wid"></label></td>
 </tr>
 <tr>
  <th>网站名称</th>
  <td><input type="text" class="webname txt1" /></td>
 </tr>
 <tr>
  <th>用户名头【共九级】</th>
  <td><input type="text" class="namehead txt1" /></td>
 </tr>
 <tr>
  <th>层级名称【共九级】</th>
  <td><input type="text" class="layer txt1" /></td>
 </tr>
 <tr>
  <th>赔率点水模式【限HK彩】</th>
  <td><input type="text" class="patt txt1" />[1-5]</td>
 </tr>
 <tr>
  <th>最大级数</th>
  <td><input type="text" class="maxlayer txt1" /></td>
 </tr>
 <tr>
  <th>现金/信用</th>
  <td><input type="text" class="moneytype txt1" />0-信用,1-现金</td>
 </tr>
 <tr>
  <th>低频彩种</th>
  <td><input type="text" class="slowtype txt1" />(0关闭/1开放)</td>
 </tr>
 <tr>
  <th>快开彩种</th>
  <td><input type="text" class="fasttype txt1" />(0关闭/1开放)</td>
 </tr>
 <tr>
  <th>自助注册代理</th>
  <td><input type="text" class="zcagent txt1" /></td>
 </tr>
  <tr>
  <th>试用会员</th>
  <td><input type="text" class="guser txt1" />多个以逗号(,)分开</td>
 </tr>
   <tr>
  <th>会员skin</th>
  <td><input type="text" class="uskin txt1" /></td>
 </tr>
 <tr>
  <th>SKINS</th>
  <td><input type="text" class="skins txt1" />【default】</td>
 </tr>
 <tr>
  <th>会员提示</th>
  <td><textarea class="umess" cols="30" rows="5" ></textarea></td>
 </tr>
 <tr>
  <th>代理提示</th>
  <td><textarea class="amess"  cols="30" rows="5"></textarea></td>
 </tr>
 <tr>
  <th>手机目录</th>
  <td><input type="text" class="mdi txt1" /></td>
 </tr>
 <tr>
  <th>会员目录</th>
  <td><input type="text" class="udi txt1" /></td>
 </tr>
 <tr>
  <th>代理目录</th>
  <td><input type="text" class="adi txt1" />[agent]</td>
 </tr>
 <tr>
  <th>管理目录</th>
  <td><input type="text" class="hdi txt1" /></td>
 </tr>
 <tr>
  <th>手机端口</th>
  <td><input type="text" class="mpo txt1" /></td>
 </tr>
 <tr>
  <th>会员端口</th>
  <td><input type="text" class="upo txt1" /></td>
 </tr>
 <tr>
  <th>代理端口</th>
  <td><input type="text" class="apo txt1" /></td>
 </tr>

 <tr>
  <th>管理端口</th>
  <td><input type="text" class="hpo txt1" /></td>
 </tr>
 <tr>
  <th>手机网址</th>
  <td><input type="text" class="murl txt1" /></td>
 </tr>
 <tr>
  <th>会员网址</th>
  <td><input type="text" class="uurl txt1" /></td>
 </tr>
 <tr>
  <th>代理网址</th>
  <td><input type="text" class="aurl txt1" /></td>
 </tr>

 <tr>
  <th>管理网址</th>
  <td><input type="text" class="hurl txt1" /></td>
 </tr>
 <tr>
  <th>手机背景图</th>
  <td><input type="text" class="mimg txt1" /></td>
 </tr>
 <tr>
  <th>会员背景图</th>
  <td><input type="text" class="uimg txt1" /></td>
 </tr>
 <tr>
  <th>代理背景图</th>
  <td><input type="text" class="aimg txt1" /></td>
 </tr>
 </td>
 </tr>
 <tr>
  <th>管理背景图</th>
  <td><input type="text" class="himg txt1" /></td>
 </tr>
 </td>
 </tr>
 
  <tr>
  <th>手机验证码</th>
  <td><input type="text" class="mcode txt1" /></td>
 </tr>
 </td>
 </tr>
 <tr>
  <th>会员验证码</th>
  <td><input type="text" class="ucode txt1" /></td>
 </tr>
 </td>
 </tr>
 

 
 <tr>
  <th>代理验证码</th>
  <td><input type="text" class="acode txt1" /></td>
 </tr>
 </td>
 </tr>
 
 <tr>
  <th>管理验证码</th>
  <td><input type="text" class="hcode txt1" /></td>
 </tr>
 </td>
 </tr>
  <tr>
  <th>网站开放</th>
  <td><input type="text" class="webclose txt1" />(0关闭/1开放)</td>
 </tr>
 </td>
 </tr>
  <tr>
  <th>会员快捷输入</th>
   <td><input type="text" class="fastinput txt1" />(0关闭/1开放)</td>
 </tr>
 </td>
 </tr>

 
 <tr>
  <th>开关盘参数</th>
  <td>
  
   <TABLE  class="timestb tinfo wd100">
    <tr><th>彩种</th><th>开盘</th><th>开盘时间推后[秒]</th><th>关盘时间提前[秒]</th></tr>
   <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['game']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
  <tr class='timescs g<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
' gid=<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
><td><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</td>
  <td><input type="checkbox" value=1 <?php if ($this->_tpl_vars['game'][$this->_sections['i']['index']]['io'] == 1): ?>checked<?php endif; ?> /></td>
  <td><input type="text" class="txt2 o" value="" />
  </td><td><input type="text" class="txt2 c" value="" /></td></tr>
   <?php endfor; endif; ?>
   
   
   </TABLE>
   
  
  </td>
 </tr>
 
 
</table>
<div id='test'></div>
</body>
</html>