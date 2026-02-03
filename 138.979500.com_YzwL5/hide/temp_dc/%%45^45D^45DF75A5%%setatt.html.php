<?php /* Smarty version 2.6.18, created on 2025-01-02 15:28:25
         compiled from setatt.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style>
td,th{height:30px;line-height:30px;}
input.txt2{width:40px;}
.setatt2 input.txt2{width:45px;}
.hide{display:none;}
</style>
</head>
<body>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='setatt';</script>
<div class="main">
	<div class="top_info">
		<span class="title">赔率退水参数</span><span class="right"></span>
	</div>
	<div class="contents param_panel">	
		<div class="game_class">
				<ul>
<?php if ($this->_tpl_vars['config']['fasttype'] == 1): ?>
<li><span>快开彩</span>
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
    <?php if ($this->_tpl_vars['game'][$this->_sections['i']['index']]['fast'] == 1): ?><a href="javascript:void(0)" class="g<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
"  gid='<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
'><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</a><?php endif; ?>
 <?php endfor; endif; ?>
</li>
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['slowtype'] == 1): ?>
<li><span>低频彩</span>
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
    <?php if ($this->_tpl_vars['game'][$this->_sections['i']['index']]['fast'] == 0): ?><a href="javascript:void(0)" class="g<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
" gid='<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
'><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</a><?php endif; ?>
 <?php endfor; endif; ?>
</li>
<?php endif; ?>
				</ul>
			</div>
		
<table class="data_table list setatt setatt2 patt">
<thead>
<TR> <Th colspan="10"></Th><th colspan="4">模式1<input type="button" class="btn1 btnf modetb" v='1' value="同步其他模式" /></th>  <th colspan="4" class="hide">模式2<input type="button" class="btn1 btnf modetb" v='2' value="同步其他模式" /></th><th colspan="4" class="hide">模式3<input type="button" class="btn1 btnf modetb" v='3' value="同步其他模式" /></th><th colspan="4"  class="hide">模式4<input type="button" class="btn1 btnf modetb" v='4' value="同步其他模式" /></th><th colspan="4"  class="hide">模式5<input type="button" class="btn1 btnf modetb" v='5' value="同步其他模式" /></th></TR>
  <TR>
   <th><input type="checkbox"  class="all" /></th>
     <th  class='hide'>ID</th><th>大类</th><th>类别</th><th>退水最大值</th><th>退水调节差</th><th>赔率调节差</th><th>赔率调节差2</th><th>赔率差最大</th><th>补货赔率调整</th><th>补货赔率调整开关</th>
     <th class="hide">A盘差</th><th>B盘差</th><th>C盘差</th><th>D盘差</th><th>AB差</th>
     <th class="hide" >A盘差</th><th class="hide">B盘差</th><th class="hide">C盘差</th><th class="hide">D盘差</th><th class="hide">AB差</th>
     <th class="hide">A盘差</th><th class="hide">B盘差</th><th class="hide">C盘差</th><th class="hide">D盘差</th><th class="hide">AB差</th>
     <th class="hide">A盘差</th><th class="hide">B盘差</th><th class="hide">C盘差</th><th class="hide">D盘差</th><th class="hide">AB差</th>
     <th class="hide">A盘差</th><th class="hide">B盘差</th><th class="hide">C盘差</th><th class="hide">D盘差</th><th class="hide">AB差</th>
     

	</TR></thead>

  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['cs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
     <td><input type="checkbox" /><input type="button" class="btn1 btnf patttb" value="同选中" /></td>
     <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['class']; ?>
</td>
        <td class="bcs"><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['bcname']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['name']; ?>
</td>
         <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['points']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['pointsatt']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['peilvatt']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['peilvatt1']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['maxatt']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['flypeilv']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['flyifok']; ?>
[0/1]</td>

        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['a1']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['b1']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['c1']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['d1']; ?>
</td>
        <td><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['ab1']; ?>
</td>
        
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['a2']; ?>
</td>
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['b2']; ?>
</td>
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['c2']; ?>
</td>
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['d2']; ?>
</td>
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['ab2']; ?>
</td>
        
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['a3']; ?>
</td>
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['b3']; ?>
</td>
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['c3']; ?>
</td>
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['d3']; ?>
</td>
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['ab3']; ?>
</td>
        
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['a4']; ?>
</td>
        <td class="hide"><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['b4']; ?>
</td>
        <td class="hide"><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['c4']; ?>
</td>
        <td class="hide"><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['d4']; ?>
</td>
        <td class="hide"><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['ab4']; ?>
</td> 
        
        <td class='hide'><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['a5']; ?>
</td>
        <td class="hide"><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['b5']; ?>
</td>
        <td class="hide"><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['c5']; ?>
</td>
        <td class="hide"><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['d5']; ?>
</td>
        <td class="hide"><?php echo $this->_tpl_vars['cs'][$this->_sections['i']['index']]['ab5']; ?>
</td>
     </TR>
  <?php endfor; endif; ?>
</table>
	</div>
		<div class="control">
			<input type="button" gid="<?php echo $this->_tpl_vars['gid']; ?>
" class="button yiwotongbu" value="同步<?php echo $this->_tpl_vars['flname']; ?>
">&nbsp;&nbsp;&nbsp;<input type="button" value="保存" class="button send">
		</div>
</div>
<script language="javascript">
var gid=<?php echo $this->_tpl_vars['gid']; ?>
;
</script>
<div id='test'></div>
</body>
</html>