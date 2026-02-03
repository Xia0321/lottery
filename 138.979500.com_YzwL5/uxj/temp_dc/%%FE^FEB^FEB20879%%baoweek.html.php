<?php /* Smarty version 2.6.18, created on 2024-12-23 23:49:07
         compiled from baoweek.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'headers.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style  type="text/css">
.page_info .red{color:red !important}
</style>
</head>
<body class="<?php echo $this->_tpl_vars['skin']; ?>
">
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='baoweek';</script>
<div class="main history">
<div class="search">
<span class="title">两周报表</span>
</div>
<table class="list table">
<thead><tr><th class="date">日期</th><th class="count">注数</th><th class="amount">下注金额</th><th class="amount">有效金额</th><th class="cm">佣金</th><th class="dividend">盈亏</th></tr></thead>
<tbody>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['upbao']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr date='<?php echo $this->_tpl_vars['upbao'][$this->_sections['i']['index']]['date']; ?>
'><td clss="date"><a href="javascript:void(0)"><?php echo $this->_tpl_vars['upbao'][$this->_sections['i']['index']]['date']; ?>
 星期<?php echo $this->_tpl_vars['upbao'][$this->_sections['i']['index']]['week']; ?>
</a></td><td><?php echo $this->_tpl_vars['upbao'][$this->_sections['i']['index']]['zs']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['upbao'][$this->_sections['i']['index']]['zje']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['upbao'][$this->_sections['i']['index']]['zje']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['upbao'][$this->_sections['i']['index']]['points']; ?>
</td><td><a href="javascript:void(0)" class="color result"><?php echo $this->_tpl_vars['upbao'][$this->_sections['i']['index']]['rs']; ?>
</a></td></tr>
<?php endfor; endif; ?>
</tbody>
<tfoot>
<tr><th>上周</th><td><?php echo $this->_tpl_vars['t']['uzs']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['t']['uzje']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['t']['uzje']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['t']['upoints']; ?>
</td><td class="result color"><?php echo $this->_tpl_vars['t']['urs']; ?>
</td></tr>
</tfoot>
</table>
<table class="list mt table">
<thead><tr><th class="date">日期</th><th class="count">注数</th><th class="amount">下注金额</th><th class="amount">有效金额</th><th class="cm">佣金</th><th class="dividend">盈亏</th></tr></thead>
<tbody>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['bao']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr date='<?php echo $this->_tpl_vars['bao'][$this->_sections['i']['index']]['date']; ?>
'><td clss="date"><a href="javascript:void(0)"><?php echo $this->_tpl_vars['bao'][$this->_sections['i']['index']]['date']; ?>
 星期<?php echo $this->_tpl_vars['bao'][$this->_sections['i']['index']]['week']; ?>
</a></td><td><?php echo $this->_tpl_vars['bao'][$this->_sections['i']['index']]['zs']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['bao'][$this->_sections['i']['index']]['zje']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['bao'][$this->_sections['i']['index']]['zje']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['bao'][$this->_sections['i']['index']]['points']; ?>
</td><td><a href="javascript:void(0)" class="color result"><?php echo $this->_tpl_vars['bao'][$this->_sections['i']['index']]['rs']; ?>
</a></td></tr>
<?php endfor; endif; ?>
</tbody>
<tfoot>
<tr><th>本周</th><td><?php echo $this->_tpl_vars['t']['zs']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['t']['zje']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['t']['zje']; ?>
</td><td class="money"><?php echo $this->_tpl_vars['t']['points']; ?>
</td><td class="result color"><?php echo $this->_tpl_vars['t']['rs']; ?>
</td></tr>
</tfoot>
</table>
</div>
<style>
    .history td {
        width: 16.67%;
    }
</style>
</body>

</html>