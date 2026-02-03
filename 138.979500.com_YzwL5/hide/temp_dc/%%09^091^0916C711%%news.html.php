<?php /* Smarty version 2.6.18, created on 2025-01-02 15:20:48
         compiled from news.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style type="text/css">
.conwidth{width:400px;padding-left:10px;text-align:left;}
</style>
</head>
<body>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='news';</script>
<div class="main">
	<div class="top_info">
		<span class="title">消息发布</span><span class="right"></span>
	</div>
	<form method="POST" style="margin:0">
		<input type="hidden" value="addnews" name="xtype">
		<table class="data_table data_list list news_tb">
		<tr>
        <td colspan="2">发布公司:
        <select name=wid>
        <OPTION value="0">所有公司</OPTION>
        <?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['web']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
        <OPTION value="<?php echo $this->_tpl_vars['web'][$this->_sections['k']['index']]['wid']; ?>
"><?php echo $this->_tpl_vars['web'][$this->_sections['k']['index']]['webname']; ?>
</OPTION>
        <?php endfor; endif; ?>
        </select>
        </td><td colspan="6">参数:{期数}、{公司名称}、{开盘时间}、{关盘时间}、{开奖时间}</td>
		  <td colspan="1"><textarea cols="50" rows="5" id=content name="content"></textarea></td>
          
          <td colspan="2">
				<input type="submit" value="发布" class="button">
			</td>
            
		</tr>
		<thead>
		<tr>
			<th>
				<input type="checkbox" id="clickall">全选
			</th>
			<th>
				序号
			</th>
            <th>
				公司
			</th>
           <th>
				包含参数
			</th>
			<th>
				显示
			</th>
			<th>
				发布对象
			</th>
			<th>
				滚动
			</th>
			<th>
				弹出
			</th>
			<th class="conwidth">
				内容
			</th>
			<th>
				时间
			</th>
			<th>
				<input type="button" class="btn3 btnf dels" id="delall" value="删除选中">
			</th>
		</tr>
        </thead>
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
		<tr>
			<td>
				<input class='chk' type="checkbox" value='<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>
' />
			</td>
			<td>
				<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['id']; ?>

			</td>
            <td>
               <select class=wid>
                <OPTION <?php if ($this->_tpl_vars['news'][$this->_sections['i']['index']]['wid'] == 0): ?>selected<?php endif; ?> value="0">所有公司</OPTION>
        <?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['web']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
        <OPTION <?php if ($this->_tpl_vars['web'][$this->_sections['k']['index']]['wid'] == $this->_tpl_vars['news'][$this->_sections['i']['index']]['wid']): ?>selected<?php endif; ?> value="<?php echo $this->_tpl_vars['web'][$this->_sections['k']['index']]['wid']; ?>
"><?php echo $this->_tpl_vars['web'][$this->_sections['k']['index']]['webname']; ?>
</OPTION>
        <?php endfor; endif; ?>
        </select>
            </td>
           <td>
				<input type="checkbox" class="cs" value='1' <?php if ($this->_tpl_vars['news'][$this->_sections['i']['index']]['cs'] == 1): ?>checked<?php endif; ?> />
			</td> 
			<td>
				<input type="checkbox" class="ifok" value='1' <?php if ($this->_tpl_vars['news'][$this->_sections['i']['index']]['ifok'] == 1): ?>checked<?php endif; ?> />
			</td>
			<td>
			  <SELECT class="agent">
                <OPTION value="2" <?php if ($this->_tpl_vars['news'][$this->_sections['i']['index']]['agent'] == 2): ?>selected<?php endif; ?>>全部</OPTION>
                <OPTION value="1" <?php if ($this->_tpl_vars['news'][$this->_sections['i']['index']]['agent'] == 1): ?>selected<?php endif; ?>>代理</OPTION>
                <OPTION value="0" <?php if ($this->_tpl_vars['news'][$this->_sections['i']['index']]['agent'] == 0): ?>selected<?php endif; ?>>会员</OPTION>
              </SELECT>
            
			
            </td>
			<td>
				<input type="checkbox" class="gundong" value='1' <?php if ($this->_tpl_vars['news'][$this->_sections['i']['index']]['gundong'] == 1): ?>checked<?php endif; ?> />
			</td>
			<td>
				<input type="checkbox" class="alert" value='1' <?php if ($this->_tpl_vars['news'][$this->_sections['i']['index']]['alert'] == 1): ?>checked<?php endif; ?> />
			</td>
			<td class="conwidth" style="text-align:left">
				<textarea cols="50" class="con" rows="6"><?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['content']; ?>
</textarea>
			</td>
			<td>
				<input type="text" class="time" value='<?php echo $this->_tpl_vars['news'][$this->_sections['i']['index']]['time']; ?>
' />
			</td>
			<td>
				<input type="button" class="edit btn1 btnf" value="修改">&nbsp;&nbsp;<input type="button" class="dels btn1 btnf" value="删除">
			</td>
		</tr>
<?php endfor; endif; ?>
		</table>
	</form>
</div>
<div id="test">
</body>
</html>