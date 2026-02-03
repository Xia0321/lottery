<?php /* Smarty version 2.6.18, created on 2024-12-23 21:18:25
         compiled from userinfo.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strtolower', 'userinfo.html', 85, false),)), $this); ?>
<div class="contact_type1 mini">
    <div class="contact_info1" style="border-bottom: 1px solid rgb(224, 224, 224);">
        <div class="contact_name1">
            会员名称:
        </div>
        <div class="contact_name1">
            <?php echo $this->_tpl_vars['username']; ?>

        </div>
    </div>
    <div class="contact_info1" style="border-bottom: 1px solid rgb(224, 224, 224);">
        <div class="contact_name1">
            会员名称:
        </div>
        <div class="contact_name1" style="color: rgb(0, 0, 0);">
            <?php echo $this->_tpl_vars['name']; ?>

        </div>
    </div>
    <div class="contact_info1" style="border-bottom: 1px solid rgb(224, 224, 224);">
        <div class="contact_name1">
            所属盘口:
        </div>
        <div class="contact_name1" style="color: rgb(0, 0, 0);">
            <?php echo $this->_tpl_vars['panstr']; ?>
盘
        </div>
    </div>
    <div class="contact_info1" style="border-bottom: 1px solid rgb(224, 224, 224);">
        <div class="contact_name1">
            默认盘口:
        </div>
        <div class="contact_name1" style="color: rgb(0, 0, 0);">
            <select id = "abcd" class="abcd">
                    <?php $_from = $this->_tpl_vars['span']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?><OPTION value="<?php echo $this->_tpl_vars['i']; ?>
" <?php if (( $this->_tpl_vars['i'] == $this->_tpl_vars['defaultpan'] )): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['i']; ?>
盘</OPTION><?php endforeach; endif; unset($_from); ?>
            </select>&nbsp;&nbsp;<button class="button qd">设置默认盘口</button>
        </div>
    </div>
    <div class="contact_info1" style="border-bottom: 1px solid rgb(224, 224, 224);">
        <div class="contact_name1">
            账户状态:
        </div>
        <div class="contact_name1" style="color: rgb(0, 0, 0);">
            <?php echo $this->_tpl_vars['status']; ?>

        </div>
    </div>
<?php if ($this->_tpl_vars['fudong'] == 1): ?>
    <div class="contact_info1" style="border-bottom: 1px solid rgb(224, 224, 224);">
        <div class="contact_name1">
            额度 :
        </div>
        <div class="contact_name1" style="color: rgb(0, 0, 0);">
            <?php echo $this->_tpl_vars['kmaxmoney']; ?>
 (余额：<?php echo $this->_tpl_vars['kmoney']; ?>
)
        </div>
    </div>
<?php else: ?>
    <div class="contact_info1" style="border-bottom: 1px solid rgb(224, 224, 224);">
        <div class="contact_name1">
            快开彩额度 :
        </div>
        <div class="contact_name1" style="color: rgb(0, 0, 0);">
            <?php echo $this->_tpl_vars['kmaxmoney']; ?>
 (余额：<?php echo $this->_tpl_vars['kmoney']; ?>
)
        </div>
    </div>
    <div class="contact_info1" style="border-bottom: 1px solid rgb(224, 224, 224);">
        <div class="contact_name1">
            低频彩额度 :
        </div>
        <div class="contact_name1" style="color: rgb(0, 0, 0);">
            <?php echo $this->_tpl_vars['maxmoney']; ?>
 (余额：<?php echo $this->_tpl_vars['money']; ?>
)
        </div>
    </div>
<?php endif; ?>
</div>
<div class="personal_Navigation">
    <div class="pn_title2">
        
    </div>
    <button class="dropbtn"></button>
    <div id="myDropdown" class="dropdown-content" style="display: none;">
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
        <div gid='<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
'>
            <a><?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gname']; ?>
</a>
        </div>
        <?php endfor; endif; ?>
    </div>
</div>
<?php $this->assign('dp', strtolower($this->_tpl_vars['defaultpan'])); ?>
<div class="personal-info-scroll">
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
    <div class="g<?php echo $this->_tpl_vars['game'][$this->_sections['i']['index']]['gid']; ?>
 conlist">
        <div class="no_date1">
            <div class="nd_div1">
                <?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['name']; ?>

            </div>
        </div>
        <div class="info_table_title1 <?php if ($this->_tpl_vars['pan'][$this->_sections['j']['index']]['ab'] == 1): ?>ab<?php endif; ?>">
            <div class="ir_4">
                <?php echo $this->_tpl_vars['defaultpan']; ?>
盘退水<br>

                <span style="color: rgb(21, 117, 193);">
                 <?php if ($this->_tpl_vars['pan'][$this->_sections['j']['index']]['ab'] == 1): ?>
                     <?php $this->assign('tmp', "points".($this->_tpl_vars['dp'])."a"); ?>
                     <?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']][$this->_tpl_vars['tmp']]; ?>
%<BR>
                     <?php $this->assign('tmp', "points".($this->_tpl_vars['dp'])."b"); ?>
                     <?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']][$this->_tpl_vars['tmp']]; ?>
%
                 <?php else: ?>
                     <?php $this->assign('tmp', "points".($this->_tpl_vars['dp'])."0"); ?>
                     <?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']][$this->_tpl_vars['tmp']]; ?>
%
                 <?php endif; ?>
                </span>
            </div>
            <div class="ir_4">
                单注最低<br>
                <span style="color: rgb(21, 117, 193);"><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['minje']; ?>
</span>
            </div>
            <div class="ir_4">
                单注最高<br>
                <span style="color: rgb(21, 117, 193);"><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['maxje']; ?>
</span>
            </div>
            <div class="ir_4">
                单期最高<br>
                <span style="color: rgb(21, 117, 193);"><?php echo $this->_tpl_vars['pan'][$this->_sections['j']['index']]['cmaxje']; ?>
</span>
            </div>
        </div>
        <div class="rough_lines">
        </div>
    </div>
      <?php endfor; endif; ?>
    <?php endfor; endif; ?>

</div>