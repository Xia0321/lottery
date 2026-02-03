<?php /* Smarty version 2.6.18, created on 2025-01-02 15:05:08
         compiled from suserlist.html */ ?>
 <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['user']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
   <TR layer='<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['layer']; ?>
' uid="<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['userid']; ?>
" fid="<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['fid']; ?>
" fname="<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['fname']; ?>
" username='<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['username']; ?>
' ifagent='<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['ifagent']; ?>
' wid='<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['wid']; ?>
' class="in<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['userid']; ?>
" fudong='<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['fudong']; ?>
' fids='<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['userid']; ?>
,<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['fids']; ?>
' types='<?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['ifagent'] == 1): ?>ag<?php else: ?>us<?php endif; ?>'>
    
      <td ><input type="checkbox" value="<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['userid']; ?>
" /></td>
     <td class="online"><?php if (( $this->_tpl_vars['user'][$this->_sections['i']['index']]['online'] == 0 )): ?><span class='s0'></span><?php else: ?><span class='s1 zhuxiao' title='注销'></span><?php endif; ?></td>
     <td class="parent"><?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['layer'] == 1): ?>admin<?php else: ?><a href='javascript:void(0);' class="upuser"><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['upuser']; ?>
</a><?php endif; ?></TD>
  
     
     <td class="type"><?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['fudong'] == 0): ?>信用<?php else: ?>现金<?php endif; ?><BR /><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['layername']; ?>
</td>
     <td class="username"><a href='javascript:void(0);' <?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['ifagent'] == 1): ?> class='showdown' layertype=0 <?php endif; ?>><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['username']; ?>
 [<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['name']; ?>
]</a><?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['layer'] == 1): ?>-<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['web']; ?>
<?php endif; ?></td>
     <?php if ($this->_tpl_vars['config']['fasttype'] == 1 | $this->_tpl_vars['user'][$this->_sections['i']['index']]['fudong'] == 0): ?>
     <td class="account">
         <?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['ifagent'] == 1): ?>
           <a href='javascript:void(0);'  class="kmaxmoney"><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['kmoney']; ?>
</a> 
         <?php else: ?>
           <a href='javascript:void(0);'  class="kmaxmoney"><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['kmoney']; ?>
</a> 
         <?php endif; ?>   
     </td>
     <?php endif; ?>
     <?php if ($this->_tpl_vars['config']['slowtype'] == 1): ?>
     <td class="account" style="display: none;">
         <?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['ifagent'] == 1): ?>
           <a href='javascript:void(0);'  class="maxmoney"><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['money']; ?>
</a> 
         <?php else: ?>
           <a href='javascript:void(0);'  class="maxmoney"><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['money']; ?>
</a> 
         <?php endif; ?>  
     </td>
     <?php endif; ?>

     <td class="share"><a href='javascript:void(0);' class="zcmx">明细</a></td>
     <?php if (( $this->_tpl_vars['user'][$this->_sections['i']['index']]['ifagent'] == 0 )): ?>
        <td class="branch"><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['pan']; ?>
</td>
     <?php else: ?>
        <!--<td class="branch"><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['downnum']; ?>
</td>-->
        <td class="branch"><a href='javascript:void(0);' class='showdown' layertype=0><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['downnumag']; ?>
</a></td>
        <td class="branch"><a href='javascript:void(0);' class='showdown' layertype=1><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['downnumu']; ?>
</a></td>
        <td class="new">
        
        <?php if (( $this->_tpl_vars['user'][$this->_sections['i']['index']]['layer']+1 ) < $this->_tpl_vars['user'][$this->_sections['i']['index']]['maxlayer']): ?><a href='javascript:void(0);' class='bu_ico ico_dl add' types='ag'>代理</a><?php endif; ?>
        <a href='javascript:void(0);' class='bu_ico ico_hy add' types='us' >会员</a>
        </td>
     <?php endif; ?>   

     <td class="create"><?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['regtime']; ?>
</td>
     <td class="status"><input type="button" class="s<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['status']; ?>
" v="<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['status']; ?>
" value="<?php echo $this->_tpl_vars['user'][$this->_sections['i']['index']]['statusz']; ?>
"></td>
  
     <TD class="op">
      <a href='javascript:void(0);'  class="modify edit"  >修改</a>
      <a href='javascript:void(0);'  class="commission setpoints"  >退水</a>
      <a href='javascript:void(0);'  class="login_log info logininfo" >日志</a>
      <a href='javascript:void(0);'  class="my moneylog" >资金</a>          
      <a href='javascript:void(0);'  class="op_log record"  >记录</a>
      <a href='javascript:void(0);'  class="copy"  style="display: none;">复制</a>
      <?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['ifagent'] == 1): ?>
        <a href='javascript:void(0);'  class="showson" >子帐号</a>
      <?php endif; ?>
      <BR />
      <a href='javascript:void(0);'  class="resetpoints"  title="恢复退水与上级相同" >恢复限额</a>
      
      <a href='javascript:void(0);'  class="cpass"  >改密</a>

    <?php if ($this->_tpl_vars['hides'] == 1): ?>
       <a href='javascript:void(0);'  class="editmoney"  >改信用</a>
       <?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['layer'] == 1): ?>
         <a href='javascript:void(0);'  class="resetpl"  >复赔</a>
       <?php endif; ?>
       <a href='javascript:void(0);'  class="ss"  >消息</a>
       <a href='javascript:void(0);'  class="jzftime"  >校正时间</a>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['user'][$this->_sections['i']['index']]['ifagent'] == 0): ?>
       <a href='javascript:void(0);'  class="userzd"  >注单</a>
       <a href='javascript:void(0);'  class="deluserbao"  >清除报表</a>
    <?php endif; ?>
     </TD>
  
   </TR>
   <?php endfor; endif; ?>
   
   <input type="hidden" class='pageinfo' pcount='<?php echo $this->_tpl_vars['pcount']; ?>
' rcount='<?php echo $this->_tpl_vars['rcount']; ?>
' upage='<?php echo $this->_tpl_vars['upage']; ?>
' />