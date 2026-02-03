<?php /* Smarty version 2.6.18, created on 2024-12-23 23:22:32
         compiled from userinfo.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strtolower', 'userinfo.html', 85, false),array('modifier', 'count', 'userinfo.html', 96, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'headers.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style type="text/css">

    .button {
        background: url(../css/default/img/red/btn-bg.png) repeat-x;
        border: none;
        width: 66px;
        height: 18px;
        line-height: 18px;
        margin-right: 5px;
        color: #fff;
        letter-spacing: 3px;
    }
</style>

</head>
<body class='<?php echo $this->_tpl_vars['skin']; ?>
'>
    <script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='userinfo';</script>
<div class="user_info_table">
<table class="table user_info">
<thead><tr><th colspan="2">会员资料</th></tr></thead>
<tbody>
<tr><th>会员账号</th><td><?php echo $this->_tpl_vars['username']; ?>
</td></tr>
<tr><th>会员名称</th><td><?php echo $this->_tpl_vars['name']; ?>
</td></tr>
<tr><th>所属盘口</th><td><?php echo $this->_tpl_vars['panstr']; ?>
 盘</td></tr>
<tr><th>账户状态</th><td><?php echo $this->_tpl_vars['status']; ?>
</td></tr>
<tr><th>快开彩额度</th>
<td><label><?php echo $this->_tpl_vars['kmaxmoney']; ?>
</label>&nbsp;（余额:<label><?php echo $this->_tpl_vars['kmoney']; ?>
</label>）</td></tr>
</tbody>
</table>
</div>
<br/>

<div>
    <div class="tab-wrapper">
      <a id="info" href="javascript:void(0)" class="active">彩票类</a>
    </div>
</div><div class="info_body">
<div class="game_class">
<ul>
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
<a href="javascript:void(0);" gid='<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
' class="games"><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</a>
<?php endfor; endif; ?>
</li>
</ul>
</div>
<table class="list table data_table">
<thead>
        <Tr>
            <th > 玩法 </th>
            <th  style="display:none">保底赔率</th> 
            <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
            <th  class="<?php echo $this->_tpl_vars['i']; ?>
 p"><?php echo $this->_tpl_vars['i']; ?>
盘退水%</th>
            <?php endforeach; endif; unset($_from); ?>
            <th >单注最低</th>
            <th >单注最高</th>
            <th >单期最高</th>
            
            

           

        </Tr>
        <!--
           <tr> <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
            <th class="a p">A</th>
            <th class="a p">B</th>
            <?php endforeach; endif; unset($_from); ?> </tr>  -->

        </thead>

        <tbody>
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
        <?php $this->assign('pan', $this->_tpl_vars['game'][$this->_sections['i']['index']]['pan']); ?>
        <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['pan']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
        <tr class="gametr g<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
">
            <td class="<?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['class']; ?>
" gid='<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
'><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['name']; ?>
</td>
           
            
            

            <?php if ($this->_tpl_vars['pan'][$this->_sections['j']['index']]['abcd'] == 1): ?>
            <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k']):
?>
            <?php $this->assign('m', strtolower($this->_tpl_vars['k'])); ?>
            <?php if ($this->_tpl_vars['pan'][$this->_sections['j']['index']]['ab'] == 1): ?>
            <?php $this->assign('tmp1', "points".($this->_tpl_vars['m'])."a"); ?>
            <?php $this->assign('tmp2', "points".($this->_tpl_vars['m'])."b"); ?>
            <td><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']][$this->_tpl_vars['tmp1']]; ?>
/<?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']][$this->_tpl_vars['tmp2']]; ?>
</td>
            <?php else: ?>
            <?php $this->assign('tmp', "points".($this->_tpl_vars['m'])."0"); ?>
            <td><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']][$this->_tpl_vars['tmp']]; ?>
</td>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
            <?php else: ?>
            <?php $this->assign('tmp', count($this->_tpl_vars['span'])); ?>
            <?php $this->assign('tmp2', $this->_tpl_vars['tmp']*2); ?>
            <td colspan="<?php echo $this->_tpl_vars['tmp2']; ?>
"><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['pointsa0']; ?>
</td>
            <?php endif; ?>
            <td><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['minje']; ?>
</td>
            <td><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['maxje']; ?>
</td>
             <td><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['cmaxje']; ?>
</td>

        </tr>
        <?php endfor; endif; ?>
        <?php endfor; endif; ?>
        </tbody>
</table>
</div></body>
</html>