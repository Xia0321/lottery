<?php /* Smarty version 2.6.18, created on 2025-01-02 15:15:27
         compiled from suseredituser.html */ ?>
<div class="top_info">
    <span class="title"><?php echo $this->_tpl_vars['layername']; ?>
 <span><?php echo $this->_tpl_vars['username']; ?>
（<?php echo $this->_tpl_vars['name']; ?>
）</span> -&gt; 更改</span>
    <span class="right"><a class="back close">返回</a></span>
</div>
<ul class="tab">
    <li class="tab_title02">
        <a href="javascript:void(0);" class="selected">基本资料</a>
        <a href="javascript:void(0);" class="tuiset">退水设定</a>
    </li>
</ul>
<table class="data_table info_table user_panel edittb list">
    <thead style="display: none;"><tr><th colspan="2" class="actionname" style="text-align:center" layer='<?php echo $this->_tpl_vars['layer']; ?>
'>修改会员</th></tr></thead>
<tbody>

       <TR>
    <th><?php echo $this->_tpl_vars['layernamefu']; ?>
账号：</th>
    <TD><label><?php echo $this->_tpl_vars['fname']; ?>
</label></td>
     </TR>
   <tr>
    <th>会员帐号：</th>
    <TD><label id='username' uid='<?php echo $this->_tpl_vars['userid']; ?>
'><?php echo $this->_tpl_vars['username']; ?>
</label>
    &nbsp;&nbsp;<span class="statusControl">【    <label><input type="radio" name="status" value="1" <?php if ($this->_tpl_vars['status'] == 1): ?>checked="checked"<?php endif; ?>>启用</label>
    <label><input type="radio" name="status" value="2"  <?php if ($this->_tpl_vars['status'] == 2): ?>checked="checked"<?php endif; ?>>冻结</label>
    <label><input type="radio" name="status" value="0"  <?php if ($this->_tpl_vars['status'] == 0): ?>checked="checked"<?php endif; ?>>停用</label>
】</span>
    
    <input type="hidden" value="edituser" name='xtype' id='xtype' /><input type="hidden" value="<?php echo $this->_tpl_vars['action']; ?>
" name='action' id='action' /></td>

   </tr>
   <TR class='hides'>
    <th>帐户类型：</th>
    <TD><select name="ifagent" id=ifagent>

      <option value="0">会员</option>

     </select>&nbsp;&nbsp;(运营商/会员)</td>
    </TR>

 <TR style="display: none;">
    <th>限制赢利金额：</th>
    <TD><input type="text" name='yingdeny' id='yingdeny'  class="input" value='<?php echo $this->_tpl_vars['yingdeny']; ?>
'  />&nbsp;&nbsp;设0为不限制</td>
   </tr> 
   
  <?php if ($this->_tpl_vars['moneytype'] == 1): ?>
   <TR class='xj'>
    <th>真实性名：</th>
    <TD><input type="text" name='tname' id='tname'  class="input" value='<?php echo $this->_tpl_vars['tname']; ?>
'  /></td>
   </tr>  
   <TR class='xj'>
    <th>性别：</th>
    <TD><input type="text" name='sex' id='sex'  class="input" value='<?php echo $this->_tpl_vars['sex']; ?>
'  /></td>
   </tr>  
  
   <TR class='xj'>
    <th>生日：</th>
    <TD><input type="text" name='birthday' id='birthday'  class="input" value='<?php echo $this->_tpl_vars['birthday']; ?>
'  /></td>
   </tr>  
      
   <TR class='xj'>
    <th>电话：</th>
    <TD><input type="text" name='tel' id='tel'  class="input" value='<?php echo $this->_tpl_vars['tel']; ?>
'  /></td>
   </tr> 
   
   <TR class='xj'>
    <th>QQ：</th>
    <TD><input type="text" name='qq' id='qq'  class="input" value='<?php echo $this->_tpl_vars['qq']; ?>
'  /></td>
   </tr>  
   
   <TR class='xj'>
    <th>省市：</th>
    <TD><input type="text" name='shengshi' id='shengshi'  class="input" value='<?php echo $this->_tpl_vars['shengshi']; ?>
'  /></td>
   </tr> 

   <TR class='xj'>
    <th>详细地址：</th>
    <TD><input type="text" name='street' id='street'  class="input" value='<?php echo $this->_tpl_vars['street']; ?>
' style="width:300px;"  /></td>
   </tr> 
   
   <TR class='xj'>
    <th>收货人：</th>
    <TD><input type="text" name='shr' id='shr'  class="input" value='<?php echo $this->_tpl_vars['shr']; ?>
'  /></td>
   </tr> 
   
   <TR class='xj'>
    <th>备注：</th>
    <TD><input type="text" name='bz' id='bz'  class="input" value='<?php echo $this->_tpl_vars['bz']; ?>
'  /></td>
   </tr>  
    <?php endif; ?>
    
    
    <TR>
    <th>新密码：</th>
    <TD><input type="text" name=password id=password class="input" /><input type="hidden" name=userpass id=userpass class="input" /></td>
     </TR>

<tr>
  <th>
    错误登录次数
  </th>
  <td>
    <div>
      <span class="errortimesstatus"><?php echo $this->_tpl_vars['errortimes']; ?>
</span>&nbsp;&nbsp;<a href='javascript:void(0);' class="czpass">重置</a>
    </div>
  </td>
</tr>

   <TR>
    <th>会员名称：</th>
    <TD><input type="text" name='name' id='name'  class="input" value='<?php echo $this->_tpl_vars['name']; ?>
'  /></td>
   </tr>
  <tr style="display: none;">
 <th>额度模式：</th>
    <td > <label><input type="radio" name="fudong" value="0" class="fudong" <?php if ($this->_tpl_vars['fudong'] == 0): ?>checked="checked"<?php endif; ?> disabled />信用模式</label>
    <label><input type="radio" name="fudong" class="fudong" value="1" <?php if ($this->_tpl_vars['fudong'] == 1): ?>checked="checked"<?php endif; ?> disabled />现金模式</label></td>
</tr>
   <TR  class="modetr"  style="display: none;">
    <th>信用额度[低频]：</th>
    <TD ><input type="text" name="maxmoney" id='maxmoney' class="input hide" value="<?php echo $this->_tpl_vars['maxmoney']; ?>
" /><span><?php echo $this->_tpl_vars['money']; ?>
</span>&nbsp;&nbsp;<span id="dx" class="dx"></span><span class="hide">
     &nbsp;&nbsp;[限额
     <label><?php echo $this->_tpl_vars['fidmaxmoney']; ?>
</label>
     ]</span>&nbsp;&nbsp;【可用余额：
   <label id='money'><?php echo $this->_tpl_vars['money']; ?>
</label>】&nbsp;&nbsp;<a href='javascript:void(0);'>修改</a></td>
   </tr>
   <TR  class="kmodetr"  <?php if (( $this->_tpl_vars['fudong'] == 1 | $this->_tpl_vars['config']['fasttype'] == 0 )): ?>style='display:none;'<?php endif; ?>>
    <th>快开彩额度：</th>
    <TD ><input type="text" name="kmaxmoney" id='kmaxmoney' class="input hide" value="<?php echo $this->_tpl_vars['kmaxmoney']; ?>
" /><span><?php echo $this->_tpl_vars['kmoney']; ?>
</span>&nbsp;&nbsp;<span id="dxk" class="dx"></span><span class="hide">
     &nbsp;&nbsp;
     [限额
     <label><?php echo $this->_tpl_vars['fidkmaxmoney']; ?>
</label>
     ]</span>&nbsp;&nbsp;【可用余额：
   <label id='kmoney'><?php echo $this->_tpl_vars['kmoney']; ?>
</label>】&nbsp;&nbsp;<a href='javascript:void(0);'>修改</a></td>
   </tr>   
   
      <TR  class="fmodetr" <?php if ($this->_tpl_vars['fudong'] == 0): ?>style='display:none;'<?php endif; ?>>
    <th>快开彩额度：</th>
    <TD ><input type="text" name="fmaxmoney" id='fmaxmoney' class="input hide" value="<?php echo $this->_tpl_vars['kmaxmoney']; ?>
" /><span><?php echo $this->_tpl_vars['kmoney']; ?>
</span>&nbsp;&nbsp;<span id="dxf" class="dx"></span><span class="hide">
     &nbsp;&nbsp;
     [限额
     <label><?php echo $this->_tpl_vars['fidkmaxmoney']; ?>
</label>
     ]</span>&nbsp;&nbsp;【余额：
   <label id='kmoney'><?php echo $this->_tpl_vars['kmoney']; ?>
</label>】&nbsp;&nbsp;<a href='javascript:void(0);'>修改</a></td>
   </tr>   



  

   <?php if ($this->_tpl_vars['layer'] < $this->_tpl_vars['maxlayer']): ?>
     <?php if ($this->_tpl_vars['maxrenflag'] == 1): ?>
   <TR>
    <th>最多帐户数：</th>
    <TD><input type="text" class="input" name='maxren'  id='maxren' value="<?php echo $this->_tpl_vars['maxren']; ?>
" />
     [限额
     <label><?php echo $this->_tpl_vars['fidmaxren']; ?>
</label>
     ] </td>
    
   </tr>
   <?php endif; ?>
  <?php endif; ?>

<tr style="display: none;">
  <th>
    转账密码
  </th>
  <td>
    <div>
      <?php if (( $this->_tpl_vars['moneypassflag'] == 1 )): ?>已绑定<?php else: ?>未绑定<?php endif; ?> <?php if (( $this->_tpl_vars['moneypassflag'] == 1 )): ?><input type="button" class="czmoneypass" value="重置"><?php endif; ?>
    </div>
  </td>
</tr>
   <TR>
    <th>开放盘口：</th>
    <TD> <?php $_from = $this->_tpl_vars['fidpan']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
     <input  class="pantype" type="checkbox" value="<?php echo $this->_tpl_vars['i']; ?>
" <?php if (in_array ( $this->_tpl_vars['i'] , $this->_tpl_vars['pans'] )): ?>checked<?php endif; ?> <?php if ($this->_tpl_vars['dis'] == 0): ?>disabled<?php endif; ?>  />
     <?php echo $this->_tpl_vars['i']; ?>

     
     <?php endforeach; endif; unset($_from); ?> <select name="defaultpan" id='defaultpan' class="hide">
    <?php $_from = $this->_tpl_vars['pans']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
     <option value="<?php echo $this->_tpl_vars['i']; ?>
" <?php if ($this->_tpl_vars['defaultpan'] == $this->_tpl_vars['i']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['i']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
    </select> </td>
  
   </tr>
   <?php if ($this->_tpl_vars['layer'] == 1): ?>
   <TR>
    <th>网站名称：</th>
    <TD><select name='wid' id='wid'>
      
      
      
      
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
        
      
      
      
      <option value="<?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['wid']; ?>
" <?php if ($this->_tpl_vars['web'][$this->_sections['i']['index']]['wid'] == $this->_tpl_vars['wid']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['web'][$this->_sections['i']['index']]['webname']; ?>
</option>
      
      
      
      
      <?php endfor; endif; ?>
     
     
     
     
     </select></td>

   </tr>
  <TR>    <th>赔率调节：</th>
    <TD><select name='ifexe' id='ifexe'>
      <option value="0" <?php if ($this->_tpl_vars['ifexe'] == 0): ?>selected<?php endif; ?>>关</option>
      <option value="1" <?php if ($this->_tpl_vars['ifexe'] == 1): ?>selected<?php endif; ?>>开</option>
     </select></td>
     
     </TR>
     <tr>
    <th>赔率调节方式：</th>
    <TD><select name='pself' id='pself' style="width:210px;">
      <option value="0"   <?php if ($this->_tpl_vars['pself'] == 0): ?>selected<?php endif; ?>>使用上级赔率(上级基础上加减)</option>
      <option value="1"   <?php if ($this->_tpl_vars['pself'] == 1): ?>selected<?php endif; ?>>使用自设赔率</option>
     </select></td>

   </tr>
  <TR>    <th>参数设置：</th>
    <TD><select name='cssz' id='cssz'>
      <option value="0" <?php if ($this->_tpl_vars['cssz'] == 0): ?>selected<?php endif; ?>>关</option>
      <option value="1" <?php if ($this->_tpl_vars['cssz'] == 1): ?>selected<?php endif; ?>>开</option>
     </select>&nbsp;&nbsp;&nbsp;设置各盘赔率差</td>
     
     </TR>

   <?php endif; ?>
    <?php if ($this->_tpl_vars['fidplc'] == 1): ?>
   <TR>
    <th>赔率差调节</th>
       <TD>
<select name='plc' id='plc'>
      <option value="0"   <?php if ($this->_tpl_vars['plc'] == 0): ?>selected<?php endif; ?> >关</option>
      <option value="1"  <?php if ($this->_tpl_vars['plc'] == 1): ?>selected<?php endif; ?> >开</option>
     </select>&nbsp;&nbsp;&nbsp;设置此帐户能否赚下级赔率差，如果关闭,该帐户所有下级也不能赚赔率差。
    </td>

   </tr>
   <?php endif; ?>
   

   

 <TR style="display:none;">
    <th>默认彩种：</th>
    <TD><select name="mgid" id=mgid>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['fidgamecs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<?php if ($this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['ifok'] == 1): ?>
<option value="<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['gid']; ?>
" <?php if ($this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['gid'] == $this->_tpl_vars['mgid']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['gname']; ?>
</option>
<?php endif; ?>
<?php endfor; endif; ?>
     </select></td>
    </TR>
    
    <tr  class='hides'><Td></Td>
    <TD><input type="button" class="btn1 btnf"  id='edit' value="修改<?php echo $this->_tpl_vars['layername']; ?>
" style="margin-right:20px;" /><input type="button" class="btn1 btnf close"  value="关闭窗口" /></td>
   </tr>
   <TR class='hides'>
    <Td colspan="2" style="font-size:13px;color:#0063e3">备注：快开类型在【<?php echo $this->_tpl_vars['editstart']; ?>
--<?php echo $this->_tpl_vars['editend']; ?>
】修改才能生效，低频类型在开盘期间修改以下参数不会生效！管理员修改参数立即生效<br />
        <label>如果锁定占成，不管补货功能开放与否，该帐户将不能补货！</label>---会员略过!<BR />
    <label>本级吃补占成=吃补占成-直属下级吃补占成，如果是直属下级补货，本级吃补占成=吃补占成-0</label>---会员略过!<BR />
    <label>如果直属下级的【上级占成】设为0，本级将没有吃补占成</label>---会员略过!</Td></TR>

    </tbody>
  </table>

  
  
  
<?php if ($this->_tpl_vars['config']['zcmode'] == 1): ?> 
 <table class="data_table info_table share_panel input_panel addtb2 list">
 <thead>
  <th colspan=3>占成设置</th>

   <tr class="shead">
    <th rowspan="2">彩种</th>
    <th rowspan="2">开关</th>     
    <th rowspan="2"><?php echo $this->_tpl_vars['layernamefu']; ?>
实际占成</th>
    <th colspan="2" class="hides"><?php echo $this->_tpl_vars['layername']; ?>
占成</th>    
    <th rowspan="2" class="hides">补货功能</th>
    <th rowspan="2" class="hides">下线补货占成</th>
</tr>
<tr class="shead">
   <th class="hides">最低</th>
   <th class="hides">最高</th>
</tr>
<tr class="shead tongyi">
<th>统一设置</th>
<th><select id='ifok'>
    <option value="">请选择</option>
    <option value="0">关</option>
    <option value="1">开</option></select>
</th>
<th><input type="text" class="share" id='upzc' /></th>
<th class="hides"><input type="text" class="share" id='zcmin' /></th>
<th class="hides"><input type="text" class="share" id='zc' /></th>
<th class="hides"><select id='flytype'>
         <option value="">请选择</option>
         <option value="0">关闭</option>
         <option value="1">内补</option>
         <option value="2">外补</option>
         <option value="3">内外补</option>
         </select>
</th>
<th class="hides"><input type="text" class="share" id='flyzc' /></th>
</tr>
</thead>
<tbody>
   <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['fidgamecs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
   <tr <?php if ($this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['ifok'] == 0): ?>style="display:none"<?php endif; ?>>
    <th gid='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['gid']; ?>
'><?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['gname']; ?>
</th>
    <td><select class="ifok"  val='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uifok']; ?>
'>
    <?php if ($this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['ifok'] == 0): ?>
      <option value="0">关</option>
     <?php else: ?>
      <option value="0">关</option>
      <option value="1">开</option>
     <?php endif; ?>
     </select></td>
     
    <td>
        <input type="text" class="upzc share" maxzc='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uupzc']; ?>
' />(0% 至 <?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
%)    
    </td>
    <td  class="hides">
        <input type="text" class="zcmin share"  maxzc='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uzcmin']; ?>
'  />%
    </td> 
    <td  class="hides">
        <input type="text" class="zc share"  maxzc='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uzc']; ?>
' />(最大<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
%)
    </td> 
     <td  class="hides">
<select class="flytype"  val='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uflytype']; ?>
'>
     <?php if ($this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['flytype'] == 0): ?>
         <option value="0">关闭</option>
     <?php elseif ($this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['flytype'] == 1): ?>
        <option value="0">关闭</option>
        <option value="1">内补</option>
     <?php elseif ($this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['flytype'] == 2): ?>
         <option value="0">关闭</option>
         <option value="2">外补</option>
     <?php else: ?>
         <option value="0">关闭</option>
         <option value="1">内补</option>
         <option value="2">外补</option>
         <option value="3">内外补</option>
     <?php endif; ?>
     </select>
     </td>
    <td  class="hides">
        <input type="text" class="flyzc share" maxzc='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['flyzc']; ?>
'  value='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uflyzc']; ?>
' />(0% 至 <?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['flyzc']; ?>
%)
    </td>
   </tr>
   <?php endfor; endif; ?>
  
</tbody>
</table>
<?php else: ?>
 <table class="data_table info_table share_panel input_panel addtb2 list">
 <thead>
  <th colspan=2>占成设置</th>

   <tr class="shead">
    <th rowspan="2">类型</th>    
    <th rowspan="2"><?php echo $this->_tpl_vars['layernamefu']; ?>
实际占成</th>
    <th colspan="2"  class="hides"><?php echo $this->_tpl_vars['layername']; ?>
占成</th>    
    <th rowspan="2"  class="hides">补货功能</th>
    <th rowspan="2"  class="hides">下线补货占成</th>
</tr>
<tr class="shead">
   <th class="hides">最低</th>
   <th class="hides">最高</th>
</tr>
</thead>
<tbody>
   <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['fidgamecs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
     <tr <?php if (( $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['typeid'] == 1 && $this->_tpl_vars['config']['fasttype'] == 0 )): ?>style='display:none'<?php endif; ?> <?php if (( $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['typeid'] == 0 && $this->_tpl_vars['config']['slowtype'] == 0 )): ?>style='display:none'<?php endif; ?>>
    <th  typeid='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['typeid']; ?>
'><?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['typename']; ?>
</th>
    
    <td>
        <input type="text" class="upzc share" maxzc='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uupzc']; ?>
' />(0% 至 <?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
%)    
    </td>
    <td class="hides">
        <input type="text" class="zcmin share"  maxzc='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uzcmin']; ?>
'  />%
    </td> 
    <td class="hides">
        <input type="text" class="zc share"  maxzc='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uzc']; ?>
' />(最大<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['zc']; ?>
%)
    </td> 
     <td class="hides">
<select class="flytype"  val='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uflytype']; ?>
'>
     <?php if ($this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['flytype'] == 0): ?>
         <option value="0">关闭</option>
     <?php elseif ($this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['flytype'] == 1): ?>
        <option value="0">关闭</option>
        <option value="1">开放</option>
     <?php endif; ?>

     </select>
     </td>
    <td class="hides">
        <input type="text" class="flyzc share" maxzc='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['flyzc']; ?>
'  value='<?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['uflyzc']; ?>
' />(0% 至 <?php echo $this->_tpl_vars['fidgamecs'][$this->_sections['i']['index']]['flyzc']; ?>
%)
    </td>
   </tr>
   <?php endfor; endif; ?>
  
</tbody>
  </table>
<?php endif; ?>
  
  <div class="data_footer control"><input type="button" value="确定" class="button edit" /> <input type="button" value="取消"  class="close button"></div>