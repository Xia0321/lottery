<?php /* Smarty version 2.6.18, created on 2024-12-23 15:36:48
         compiled from caopan.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header2.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script language="javascript" src="/js/md5.js"></script>
<link href="/css/admins.css" type="text/css" rel="stylesheet" />
<style>
.page{table-layout: fixed;}
.page td:hover{background:#FCF}
.recordtb .e tr:hover{background:#FCF}
.recordtb .l tr:hover{background:#FCF}
body{font-size:12px;}
</style>
</head>
<body>
<script id=myjs language="javascript">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';var js=1;var sss='caopan';</script>
<div class="main">
	<div class="top_info">
		<span class="title">操盘员</span><span class="right"></span>
	</div>
   <table class="data_table data_list list">
   <thead>
   <TR>
   
<th >管理员</th><th>新增时间</th><th>登陆次数</th><th>最后登录IP</th><th>最后登录时间</th><th>最后修改密码时间</th><th>操作</th>

   </TR>   </thead>  
   <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['data']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
   <tr><td ><?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminname']; ?>
</td><td><?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['regtime']; ?>
</td><td><?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['logintimes']; ?>
</td><td><?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['lastloginip']; ?>
[<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['lastloginfrom']; ?>
]</td><td><?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['lastlogintime']; ?>
</td><td><?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['passtime']; ?>
</td>
   <td style="width:200px;">
   <?php if ($this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid'] != 10000): ?>
   <input type='button' class='del btn1 btnf' value='删除' id=del"<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid']; ?>
" aid="<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid']; ?>
" />
   <?php endif; ?>
   <input class='edit btn1 btnf' id=edit"<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid']; ?>
" value='修改密码' aid="<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid']; ?>
" type='button' />
   <?php if ($this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid'] == 10000): ?>
   <input class='add btn1 btnf' value='新增'  id=add"<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid']; ?>
" type='button' />
   <?php endif; ?>
   <input class='record btn1 btnf' value='记录' aid='<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid']; ?>
' username='<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminname']; ?>
'  id=showrecord"<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid']; ?>
" type='button' />
       <input class='logininfo btn1 btnf' value='日志' aid='<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid']; ?>
' username='<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminname']; ?>
'   id="showlogininfo<?php echo $this->_tpl_vars['data'][$this->_sections['i']['index']]['adminid']; ?>
" type='button' />
   </td>
   </tr>
   <?php endfor; endif; ?>
   </table>
   <table class="data_table data_list list pages" style="margin-top:20px;">
    <?php $_from = $this->_tpl_vars['page']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
     <?php if ($this->_tpl_vars['k'] == 0): ?><thead><?php endif; ?>
      <tr>
          <?php $_from = $this->_tpl_vars['i']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['h'] => $this->_tpl_vars['j']):
?>
            <?php if (( $this->_tpl_vars['k'] == 0 | $this->_tpl_vars['h'] == 0 )): ?> <Th><?php echo $this->_tpl_vars['j']; ?>
</Th><?php else: ?>
            <TD><?php echo $this->_tpl_vars['j']; ?>
</TD>
            <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>
      </tr>
      <?php if ($this->_tpl_vars['k'] == 0): ?></thead><?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
   </table>

  <input type="hidden" value="<?php echo $this->_tpl_vars['aid']; ?>
" name='aid' id='aid' />
</div>


<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable addtb" tabindex="-1" role="dialog" aria-describedby="shares" aria-labelledby="ui-id-1" style="position: absolute; height: 152.28px; width: 360px; top: 257px; left: 564.5px; display:none; right: auto; bottom: auto;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">2988316036#明细</span><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close"><span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">Close</span></button></div><div id="shares" class="popdiv ui-dialog-content ui-widget-content" style="display: block; width: auto; min-height: 96px; max-height: 346px; height: auto;"><table class="data_table">
<tbody>
<TR><Th>操盘员</Th><TD><label id='user0'><?php echo $this->_tpl_vars['username']; ?>
</label><input type="text" id='user1' maxlength="10" class="txt1" style="width:80px;" /><input type="hidden" id='action' value="add" /></TD></TR>
      <TR><Th>密码</Th><TD><input type="password" id='pass1x' class="intext" /></TD></TR>
      <TR><Th>重复密码</Th><TD><input type="password" id='pass2x' class="intext" /></TD></TR>
      <TR><th colspan="2" class="tcenter"><input type="button" id='addbtn' class="btn1 btnf" value="新增子帐号" />&nbsp;<input type="button" id='closebtn' class="btn1 btnf" value="关闭" /></th></TR>
</tbody>
</table></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-draggable ui-resizable recordtb" tabindex="-1" role="dialog" aria-describedby="shares" aria-labelledby="ui-id-1" style="position: absolute; height:400px; width: 360px; top: 257px; left: 564.5px; display:none; right: auto; bottom: auto;"><div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix ui-draggable-handle"><span id="ui-id-1" class="ui-dialog-title">2988316036# 占成明细</span><button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close"><span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">Close</span></button></div><div id="shares" class="popdiv ui-dialog-content ui-widget-content" style="display: block; width: auto; min-height: 96px; max-height: 346px; height: auto;">
<table class="data_table list ltb">
</table></div><div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div><div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div></div>
<div id='test'></div>
</body>
</html>