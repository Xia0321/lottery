<?php /* Smarty version 2.6.18, created on 2025-01-02 15:13:29
         compiled from suseradduser.html */ ?>
<table class="data_table info_table user_panel addtb list">
<thead><tr><th colspan="2">新增会员</th></tr></thead>
<tbody>
      <TR>
    <th><?php echo $this->_tpl_vars['layernamefu']; ?>
账号：</th>
    <TD><label><?php echo $this->_tpl_vars['fname']; ?>
</label><input type="hidden" value="adduser" name='xtype' id='xtype' /><input type="hidden" value="<?php echo $this->_tpl_vars['action']; ?>
" name='action' id='action' /></td>
     </TR>
   <tr>
    <th>会员帐号：</th>
    <TD>
     <input type="text" name='username' id='username' class="input" style="width:100px;" maxlength="<?php echo $this->_tpl_vars['namelength']; ?>
" /><span id="usernameMsg"></span>&nbsp;&nbsp;<select name='status'  class='hides' layer='<?php echo $this->_tpl_vars['layer']; ?>
'   id='status'>
      <option   value="1" selectef>可用</option>
      <option value="0"  >禁用</option>
      <option value="2"  >暂停</option>
     </select></td>
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
' style="width:300px;"   /></td>
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
    <th>登入密码：</th>
    <TD><input type="text" name=password id=password class="input" /> <input type="hidden" name=userpass id=userpass class="input" /></td>
    </TR>
   <TR>
    <th>会员名称：</th>
    <TD><input type="text" name='name' id='name'  class="input"  /></td>

   </tr>
<tr style="display: none;"><th>额度模式</th><td>
    <?php if ($this->_tpl_vars['fudong'] == 0): ?>    
    <label><input type="radio" name="fudong" value="0" class="fudong" checked="checked">信用模式</label>
    <label><input type="radio" name="fudong" class="fudong" value="1">现金模式</label>
    <?php else: ?>
    <label><input type="radio" name="fudong" value="0" class="fudong" disabled="disabled">信用模式</label>
    <label><input type="radio" name="fudong" class="fudong" value="1" disabled="disabled"  checked="checked">现金模式</label>
    <?php endif; ?>
</td></tr>

   <TR  class="modetr"   style="display: none;">
    <th>信用额度[低频]：</th>
    <TD ><input type="text" name="maxmoney" id='maxmoney' class="input" value="" />&nbsp;&nbsp;<span id="dx" class="dx"></span>
     &nbsp;&nbsp;[上级余额
     <label><?php echo $this->_tpl_vars['maxmoney']; ?>
</label>
     ]</td>
   </tr>
   <TR  class="kmodetr"  <?php if ($this->_tpl_vars['config']['fasttype'] != 1): ?>style='display:none;'<?php endif; ?>>
    <th>快开彩额度：</th>
    <TD ><input type="text" name="kmaxmoney" id='kmaxmoney' class="input" value="" />&nbsp;&nbsp;<span id="dxk" class="dx"></span>
     &nbsp;&nbsp;[上级余额
     <label><?php echo $this->_tpl_vars['kmaxmoney']; ?>
</label>
     ]</td>
   </tr>

   <TR  class="fmodetr" style="display:none;">
    <th>快开彩额度：</th>
    <TD ><input type="text" name="fmaxmoney" id='fmaxmoney' class="input" value="" />&nbsp;&nbsp;<span id="dxf" class="dx"></span>
     &nbsp;&nbsp;[上级余额
     <label><?php echo $this->_tpl_vars['kmaxmoney']; ?>
</label>
     ]</td>
   </tr>

   


   <TR>
    <th>开放盘口：</th>
    <TD> <?php $_from = $this->_tpl_vars['pan']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
     <input  class="pantype" type="checkbox" value="<?php echo $this->_tpl_vars['i']; ?>
"   />
     <?php echo $this->_tpl_vars['i']; ?>

     
     <?php endforeach; endif; unset($_from); ?> <select name="defaultpan" id='defaultpan' class="hide"></select></td>
 
   </tr>
   <TR>
    <th>赚取退水：</th>
    <TD> 
      <select name="liushui" id="liushui">
        
                                  <option value="0">水全退到底</option>
                                  <option value="0.1">赚取 0.1% 退水</option>
                                  <option value="0.3">赚取 0.3% 退水</option>
                                  <option value="0.5">赚取 0.5% 退水</option>
                                  <option value="1">赚取 1.0% 退水</option>
                                  <option value="1.5">赚取 1.5% 退水</option>
                                  <option value="2">赚取 2.0% 退水</option>
                                  <option value="2.5">赚取 2.5% 退水</option>
                                  <option value="100">赚取所有退水</option>
                              
               </select>
</td>
 
   </tr>




 <TR style="display:none;">
    <th>默认彩种：</th>
    <TD><select name="mgid" id=mgid>
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
<?php if ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['ifok'] == 1): ?>
<option value="<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
" <?php if ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid'] == $this->_tpl_vars['mgid']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
</option>
<?php endif; ?>
<?php endfor; endif; ?>
     </select></td>
    </TR>

     
     <tr class='hides'>
    <th>&nbsp;</th>
   
    <TD><input type="button" class="btn1 btnf add"  value="新增<?php echo $this->_tpl_vars['layername']; ?>
"  style="margin-right:20px;" /><input type="button" class="btn1 btnf close"  value="关闭窗口" /></td>
   </tr>
   <TR  class='hides'>
    <Td colspan="2">
   <label>如果锁定占成，不管走补功能开放与否，该帐户将不能走补！</label>---会员略过!<BR />
    <label>本级收补占成=收补占成-直属下级收补占成，如果是直属下级走补，本级收补占成=收补占成-0</label>---会员略过!<BR />
    <label>如果直属下级的【上级占成】设为0，本级将没有收补占成</label>---会员略过!
    </Td>
    </TR>
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
   <tr <?php if ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['ifok'] == 0): ?>style="display:none"<?php endif; ?>>
    <th gid='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
'><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
</th>
    <td><select class="ifok"  val='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['ifok']; ?>
'>
    <?php if ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['ifok'] == 0): ?>
      <option value="0">关</option>
     <?php else: ?>
      <option value="0">关</option>
      <option value="1">开</option>
     <?php endif; ?>
     </select></td>
     
    <td>
        <input type="text" class="upzc share" maxzc='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='0' />(0% 至 <?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
%)    
    </td>
    <td  class="hides">
        <input type="text" class="zcmin share"  maxzc='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='0' />%
    </td> 
    <td  class="hides">
        <input type="text" class="zc share"  maxzc='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
' />(最大<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
%)
    </td> 
     <td  class="hides">
<select class="flytype"  val='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flytype']; ?>
'>
     <?php if ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flytype'] == 0): ?>
         <option value="0">关闭</option>
     <?php elseif ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flytype'] == 1): ?>
        <option value="0">关闭</option>
        <option value="1">内补</option>
     <?php elseif ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flytype'] == 2): ?>
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
        <input type="text" class="flyzc share" maxzc='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flyzc']; ?>
'  value='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flyzc']; ?>
' />(0% 至 <?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flyzc']; ?>
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
  <tr <?php if (( $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['typeid'] == 1 && $this->_tpl_vars['config']['fasttype'] == 0 )): ?>style='display:none'<?php endif; ?> <?php if (( $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['typeid'] == 0 && $this->_tpl_vars['config']['slowtype'] == 0 )): ?>style='display:none'<?php endif; ?>>
    <th  typeid='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['typeid']; ?>
'><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['typename']; ?>
</th>
    
    <td>
        <input type="text" class="upzc share" maxzc='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='0' />(0% 至 <?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
%)    
    </td>
    <td class="hides">
        <input type="text" class="zcmin share"  maxzc='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='0' />%
    </td> 
    <td class="hides">
        <input type="text" class="zc share"  maxzc='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
' value='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
' />(最大<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['zc']; ?>
%)
    </td> 
     <td class="hides">
<select class="flytype"  val='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flytype']; ?>
'>
     <?php if ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flytype'] == 0): ?>
         <option value="0">关闭</option>
     <?php elseif ($this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flytype'] == 1): ?>
        <option value="0">关闭</option>
        <option value="1">开放</option>
     <?php endif; ?>

     </select>
     </td>
    <td class="hides">
        <input type="text" class="flyzc share" maxzc='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flyzc']; ?>
'  value='<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flyzc']; ?>
' />(0% 至 <?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['flyzc']; ?>
%)
    </td>
   </tr>
   <?php endfor; endif; ?>
  
</tbody>
  </table>
<?php endif; ?>
  <div class="data_footer control"><input type="button" value="确定" class="button add"> <input type="button" value="取消"  class="close button"></div>