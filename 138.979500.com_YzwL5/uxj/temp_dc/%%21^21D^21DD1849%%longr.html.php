<?php /* Smarty version 2.6.18, created on 2024-12-23 16:03:59
         compiled from longr.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substr', 'longr.html', 33, false),)), $this); ?>

   <?php if (( $this->_tpl_vars['fenlei'] == 151 || $this->_tpl_vars['tu'] == '2' )): ?>  
 <thead><tr><th colspan="6" class='table_side'>近期开奖结果</th></tr>

  <?php if ($this->_tpl_vars['tu'] == 2): ?>
  <tr><td colspan="5">结果=<?php echo $this->_tpl_vars['ftnum']; ?>
&nbsp;&nbsp;&nbsp;<input type='button' class="ftlu" value='番路' /></td></tr>
  <tr class="tit_bg">
                    <td class="">期数</td>
                    <td class="">结果</td>

                    <td class="">番</td>
                    <td class="">单双</td>
                    <td>大小</td>
                </tr><?php endif; ?>
 </thead>
      
  <?php if (( $this->_tpl_vars['fenlei'] == 151 )): ?>  
  <tbody>
      <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['kj']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <tr class="trhm"><td class='period'><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['qs']; ?>
期</td>
    <td class='name'><?php if ($this->_tpl_vars['kj'][$this->_sections['i']['index']]['m1'] != ''): ?><span class='b<?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['m1']; ?>
'><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['m1']; ?>
</span><?php endif; ?></td>
    <td class='name'><?php if ($this->_tpl_vars['kj'][$this->_sections['i']['index']]['m1'] != ''): ?><span class='b<?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['m2']; ?>
'><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['m2']; ?>
</span><?php endif; ?></td>
    <td class='name'><?php if ($this->_tpl_vars['kj'][$this->_sections['i']['index']]['m1'] != ''): ?><span class='b<?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['m3']; ?>
'><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['m3']; ?>
</span><?php endif; ?></td>
    <td class="other"><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['m1']+$this->_tpl_vars['kj'][$this->_sections['i']['index']]['m2']+$this->_tpl_vars['kj'][$this->_sections['i']['index']]['m3']; ?>
</td>
    <td class="other<?php if ($this->_tpl_vars['kj'][$this->_sections['i']['index']]['dx'] == '大'): ?> D<?php endif; ?>"><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['dx']; ?>
</td>
     </tr>
     <?php endfor; endif; ?>
     </tbody>
     <?php else: ?>
       <tbody id="FANTResults">
     
      <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['kj']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <tr><td class="ins" ><?php echo ((is_array($_tmp=$this->_tpl_vars['kj'][$this->_sections['i']['index']]['qishu'])) ? $this->_run_mod_handler('substr', true, $_tmp, '-3') : substr($_tmp, '-3')); ?>
期</td>
      <th><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['z']; ?>
</th>
      <th class="ico"><i class="b b<?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['mft']; ?>
"><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['mft']; ?>
</i></th>
<th class="cnt ds_<?php if ($this->_tpl_vars['kj'][$this->_sections['i']['index']]['ftds'] == '单'): ?>D<?php else: ?>S<?php endif; ?>"><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['ftds']; ?>
</th><th class="cnt dx_<?php if ($this->_tpl_vars['kj'][$this->_sections['i']['index']]['ftds'] == '单'): ?>D<?php else: ?>X<?php endif; ?>"><?php echo $this->_tpl_vars['kj'][$this->_sections['i']['index']]['ftdx']; ?>
</th> 
</tr>
     <?php endfor; endif; ?>
    </tbody>
  <?php endif; ?>




       
   <?php else: ?>

     <thead><tr><th colspan="2" class='table_side'>两面长龙排行</th></tr></thead>
     <tbody>    
     <tr style='display:none;'>
     <th colspan="2" class="longrth">
       <input type="radio" name='longfs' class="ra" value="2" />
       无出&nbsp;&nbsp;
       <input type="radio" name='longfs' class="ra"   checked value="1"/>
       连出
      
    </th></tr>
    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['buz']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <?php if (( $this->_tpl_vars['buz'][$this->_sections['i']['index']]['bname'] != '2字和数' & $this->_tpl_vars['buz'][$this->_sections['i']['index']]['bname'] != '3字和数' )): ?>
    <tr class="buz">
      <?php if (( $this->_tpl_vars['buz'][$this->_sections['i']['index']]['bname'] == "冠亚军组合" )): ?>
     <th class="l"><a href='javascript:void(0)' ><?php echo $this->_tpl_vars['buz'][$this->_sections['i']['index']]['pname']; ?>
</a></th>
      <?php else: ?>
     <th class="l"><a href='javascript:void(0)' ><?php echo $this->_tpl_vars['buz'][$this->_sections['i']['index']]['name']; ?>
&nbsp;:&nbsp;<?php echo $this->_tpl_vars['buz'][$this->_sections['i']['index']]['pname']; ?>
</a></th>  
      <?php endif; ?>
     <td class="r"><label><?php echo $this->_tpl_vars['buz'][$this->_sections['i']['index']]['qishu']; ?>
</label>&nbsp;期</td>
    </tr>
    <?php endif; ?>
    <?php endfor; endif; ?>
    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['z']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <?php if (( $this->_tpl_vars['z'][$this->_sections['i']['index']]['bname'] != '2字和数' & $this->_tpl_vars['z'][$this->_sections['i']['index']]['bname'] != '3字和数' )): ?>
    <tr class="zz">
      <?php if (( $this->_tpl_vars['z'][$this->_sections['i']['index']]['bname'] == "冠亚军组合" || $this->_tpl_vars['z'][$this->_sections['i']['index']]['bname'] == "总和龙虎" )): ?>
     <th class="l"><a href='javascript:void(0)' ><?php echo $this->_tpl_vars['z'][$this->_sections['i']['index']]['pname']; ?>
</a></th>
      <?php else: ?>
     <th class="l"><a href='javascript:void(0)' ><?php echo $this->_tpl_vars['z'][$this->_sections['i']['index']]['name']; ?>
&nbsp;:&nbsp;<?php echo $this->_tpl_vars['z'][$this->_sections['i']['index']]['pname']; ?>
</a></th>  
      <?php endif; ?>
     <td class="r"><label><?php echo $this->_tpl_vars['z'][$this->_sections['i']['index']]['qishu']; ?>
</label>
      &nbsp;期</td>
    </tr>
    <?php endif; ?>
    <?php endfor; endif; ?>
     </tbody>
    <?php endif; ?>
    
