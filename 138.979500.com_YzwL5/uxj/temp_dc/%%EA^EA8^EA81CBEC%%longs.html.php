<?php /* Smarty version 2.6.18, created on 2024-12-23 23:22:55
         compiled from longs.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substr', 'longs.html', 60, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'headers.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="../css/default/jquery-ui.css" />
<?php if (( $this->_tpl_vars['gid'] == 101 )): ?>
<link href="/css/default/hlsx.css" rel="stylesheet" type="text/css">
<?php elseif (( $this->_tpl_vars['nc'] == 1 )): ?>
<link href="/css/default/135.css" rel="stylesheet" type="text/css">
<?php else: ?>
<link href="/css/default/<?php echo $this->_tpl_vars['fenlei']; ?>
.css" rel="stylesheet" type="text/css">
<?php endif; ?>

<style type="text/css">
.list td.he{color:blue !important;}
.list td.g1{color:red !important}
.list td.g2{color:black;}
.list td.g3{color:blue !important}
label.sxs{background: none !important;color: #000 !important;font-size:13px !important;}
</style>
</head>
<body class='<?php echo $this->_tpl_vars['skin']; ?>
' style="background:#fff">
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='longs';</script>
<select id="game" name="game">
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
<option value="<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
" <?php if (( $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid'] == $this->_tpl_vars['gid'] )): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</option>
<?php endfor; endif; ?>

</select>
<?php if ($this->_tpl_vars['fast'] == 1): ?>
日期：<input id="date" value="<?php echo $this->_tpl_vars['thisday']; ?>
" />
<?php endif; ?>
<div id="drawTable" class="contents">
<table class="list table_ball table">
<thead><tr><th>期数</th><th>开奖时间</th>
    <th colspan="<?php echo $this->_tpl_vars['mnums']; ?>
">开出号码</th>
    <?php if ($this->_tpl_vars['fenlei'] == 107): ?>
    <th colspan="3" class="strong">冠亚军和</th><th colspan="5" class="strong">1～5 龙虎</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>结果=<?php echo $this->_tpl_vars['ftnum']; ?>
</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>番</th></tr>
    <?php elseif ($this->_tpl_vars['fenlei'] == 101): ?>
    <th colspan="3" class="strong">总和</th><th>龙虎</th><th>前三</th><th>中三</th><th>后三</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>结果=<?php echo $this->_tpl_vars['ftnum']; ?>
</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>番</th></tr>
    <?php elseif ($this->_tpl_vars['fenlei'] == 163): ?>
    <th colspan="3" class="strong">总和</th><th>前三</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>结果=<?php echo $this->_tpl_vars['ftnum']; ?>
</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>番</th></tr>
    <?php elseif ($this->_tpl_vars['fenlei'] == 103): ?>
    <th colspan="4" class="strong">总和</th><th colspan="4">1～4 龙虎</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>结果=<?php echo $this->_tpl_vars['ftnum']; ?>
</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>番</th></tr>
    <?php elseif ($this->_tpl_vars['fenlei'] == 121): ?>
    <th colspan="4" class="strong">总和</th><th>龙虎</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>结果=<?php echo $this->_tpl_vars['ftnum']; ?>
</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>番</th></tr>
    <?php elseif ($this->_tpl_vars['fenlei'] == 161): ?>  
    <th colspan="4" class="strong">总和</th><th colspan="2" class="strong">比数量</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>结果=<?php echo $this->_tpl_vars['ftnum']; ?>
</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>番</th></tr>
    <?php elseif ($this->_tpl_vars['fenlei'] == 151): ?>    
    <th colspan="2" class="strong">总和</th>
    <?php elseif ($this->_tpl_vars['fenlei'] == 100): ?>
    <th colspan="3" class="strong">总和</th><th colspan="6" class="strong">特码</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>结果=<?php echo $this->_tpl_vars['ftnum']; ?>
</th><th <?php if (( $this->_tpl_vars['ft'] != 1 )): ?>style='display:none;'<?php endif; ?>>番</th></tr>
    <?php endif; ?>
</thead>
<tbody>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<td class="period"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['qishu']; ?>
</td>
<td class="drawTime"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['time']; ?>
</td>
<?php $_from = $this->_tpl_vars['list'][$this->_sections['i']['index']]['ma']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k']):
?>
<td class="name ballname"><?php if ($this->_tpl_vars['k'] != ''): ?><span class="b<?php echo ((is_array($_tmp=$this->_tpl_vars['k'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 2) : substr($_tmp, 0, 2)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['k'])) ? $this->_run_mod_handler('substr', true, $_tmp, 0, 2) : substr($_tmp, 0, 2)); ?>
</span><?php if (( $this->_tpl_vars['fenlei'] == 100 )): ?><label class="sxs"><?php echo ((is_array($_tmp=$this->_tpl_vars['k'])) ? $this->_run_mod_handler('substr', true, $_tmp, 3) : substr($_tmp, 3)); ?>
</label><?php endif; ?><?php endif; ?></td>
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['list'][$this->_sections['i']['index']]['m']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n']):
?>
 <td class="other
   <?php if (( $this->_tpl_vars['n'] == '和' )): ?> 
     he
   <?php elseif (( $this->_tpl_vars['n'] == "大" | $this->_tpl_vars['n'] == "合单" | $this->_tpl_vars['n'] == "尾大" | $this->_tpl_vars['n'] == "家" | $this->_tpl_vars['n'] == "单(多)" | $this->_tpl_vars['n'] == "前(多)" | $this->_tpl_vars['n'] == "龙" | $this->_tpl_vars['n'] == "单" )): ?>
     g1
     <?php elseif (( $this->_tpl_vars['n'] == "小" | $this->_tpl_vars['n'] == "合双" | $this->_tpl_vars['n'] == "尾小" | $this->_tpl_vars['n'] == "野" | $this->_tpl_vars['n'] == "双(多)" | $this->_tpl_vars['n'] == "后(多)" | $this->_tpl_vars['n'] == "虎" | $this->_tpl_vars['n'] == "双" )): ?>
     g2
     <?php elseif (( $this->_tpl_vars['n'] == "单双(和)" | $this->_tpl_vars['n'] == "前后(和)" )): ?>
     g3
     <?php elseif (( $this->_tpl_vars['n'] == "豹子" | $this->_tpl_vars['n'] == "顺子" | $this->_tpl_vars['n'] == "对子" | $this->_tpl_vars['n'] == "半顺" | $this->_tpl_vars['n'] == "杂六" )): ?>
      others
   <?php endif; ?>"><?php echo $this->_tpl_vars['n']; ?>
</td>
<?php endforeach; endif; unset($_from); ?>
</tr>
<?php endfor; endif; ?>
</tbody></table>
</div>
</div>
<script type="text/javascript">
var fenlei=<?php echo $this->_tpl_vars['fenlei']; ?>
;
var ngid = <?php echo $this->_tpl_vars['gid']; ?>
;
</script>
</body>
</html>