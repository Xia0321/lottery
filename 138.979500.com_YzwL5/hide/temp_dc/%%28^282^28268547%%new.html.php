<?php /* Smarty version 2.6.18, created on 2024-12-23 15:36:46
         compiled from new.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head><body>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';
var js=1;var sss='new';
</script>
<div class='main'>
<div class="top_info">
<span class="title">最新公告</span>
</div>
<div class="contents">
 <h3>欢迎! <LABEL><?php echo $this->_tpl_vars['username']; ?>
</LABEL></h3><BR />&nbsp;&nbsp;&nbsp;您上次登陆时间<label><?php echo $this->_tpl_vars['time']; ?>
</label>,来源IP:<?php echo $this->_tpl_vars['ip']; ?>
(<?php echo $this->_tpl_vars['addr']; ?>
)<BR /><BR /><BR />
 
 <table class="data_table data_list list"> 
<thead><tr><th>公司</th><th>对象</th><th>时间</th><th>公告详情</th></tr></thead>
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
<TR><td><?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['web']; ?>
</td><td><?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['agent']; ?>
</td><td><?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['time']; ?>
</td><TD style="text-align:left;padding-left:10px;"><?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['content']; ?>
</TD></TR>
<?php endfor; endif; ?>
</table>
	</div>
</div>
<div id='test'></div>
</body>
</html>