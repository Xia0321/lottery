<?php /* Smarty version 2.6.18, created on 2025-01-02 15:04:56
         compiled from gameset.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style>
.addtb{width:200px;display:none;position:absolute;background:#fff}
.addtb td{text-align:left;}
.s_tb .name{width:50px;}
.v {text-align:left;padding-left:5px;}
.v input{width:300px;}
.px{width:50px;}
tr:hover{background:#deedfe}
</style>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='gameset';</script>
</head>
<body>
<div class="main">
	<div class="top_info">
		<span class="title">彩种开放设置</span><span class="right"></span>
	</div>
 <table class="data_table s_tb" style="width: 1000px;">
 
<thead>
 <TR>
 <th  style='width:20%' >彩种</th><th  style='width:20%' >gid</th><th  style='width:20%' >总开关</th><th  style='width:20%' >开放</th><th  style='width:20%' >排序</th>
 </TR></thead>	 
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
   <TR>
       <th><label><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</label></th>
       <td><label><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
</label></td>
       <td class='v'><INPUT type='checkbox'  class="ifopen"   value="<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
" <?php if ($this->_tpl_vars['game'][$this->_sections['i']['index']]['ifopen'] == 1): ?>checked<?php endif; ?> /></td>
       <td class='v'><INPUT type='checkbox'  class="ifok"   value="<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
" <?php if ($this->_tpl_vars['game'][$this->_sections['i']['index']]['ifok'] == 1): ?>checked<?php endif; ?> /></td>
       <td><INPUT type='text'  class="px"   value="<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['xsort']; ?>
"  /></td>
   </TR>
 <?php endfor; endif; ?>
  </table>
  
		<div class="control" style="width: 1000px;">
			<input type="button" value="保存" class="button send">
		</div>
</div>

<div id='test'></div>

</body>
</html>