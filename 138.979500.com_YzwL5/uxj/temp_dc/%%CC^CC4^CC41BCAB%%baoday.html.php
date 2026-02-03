<?php /* Smarty version 2.6.18, created on 2024-12-23 23:22:54
         compiled from baoday.html */ ?>
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
';var js=1;var sss='baoday';</script>
<div class="main report">
<div class="search">
<span class="title" date='<?php echo $this->_tpl_vars['date']; ?>
'><?php echo $this->_tpl_vars['date']; ?>
 星期<?php echo $this->_tpl_vars['week']; ?>
 报表</span>
</div>
<table class="list table">
<thead><tr><th>注单号</th><th>时间</th><th>类型</th><th style="width:250px !important;">玩法</th><th>盘口</th><th>下注金额</th><th>退水(%)</th><th>结果</th></tr></thead>
<tbody>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['lib']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
  <td><?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['tid']; ?>
</td>
  <td class="time"><?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['time']; ?>
</td>
  <td class="period"><span class="lottery"><?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['gid']; ?>
</span><br /><span class="draw_number">第 <?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['qishu']; ?>
 期</span></td>
  <td class="contents"><span class="text"><?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['wf']; ?>
<?php if ($this->_tpl_vars['lib'][$this->_sections['i']['index']]['content'] != ''): ?>:<?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['content']; ?>
<?php endif; ?></span> @ <span class="odds"><?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['peilv1']; ?>
<?php if ($this->_tpl_vars['lib'][$this->_sections['i']['index']]['peilv2'] > 1): ?>/<?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['peilv2']; ?>
<?php endif; ?></span></td>
  <td class="range"><?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['abcd']; ?>
</td>
  <td class="amount"><?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['je']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['points']; ?>
%</td>
  <td class="result color"><?php echo $this->_tpl_vars['lib'][$this->_sections['i']['index']]['rs']; ?>
</td>
</tr>
<?php endfor; endif; ?>

</tbody>
<tfoot>
<tr><td></td><td></td><td></td><td></td><th>总计</th><td><?php echo $this->_tpl_vars['total']['je']; ?>
</td><td></td><td class="result color"><?php echo $this->_tpl_vars['total']['jg']; ?>
</td></tr>
</tfoot>
</table>
<div class="page">
<div class="page_info" rcount='<?php echo $this->_tpl_vars['rcount']; ?>
' psize='<?php echo $this->_tpl_vars['psize']; ?>
' tpage='<?php echo $this->_tpl_vars['tpage']; ?>
'>
<span class="record">共 <?php echo $this->_tpl_vars['rcount']; ?>
 笔记录</span>
<span class="page_count">共 1 页</span>
<span class="page_control">
<a class="previous" href='jsavscript:void(0)'>前一页</a>『
<span class="current">1</span>
』<a class="next" href='jsavscript:void(0)'>后一页</a>
</span>
</div>
</div>
</div>
</body>
</html>