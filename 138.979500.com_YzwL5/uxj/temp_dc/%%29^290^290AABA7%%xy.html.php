<?php /* Smarty version 2.6.18, created on 2024-12-23 16:03:56
         compiled from xy.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php if ($this->_tpl_vars['rkey'] == 0): ?>oncontextmenu="return false"<?php endif; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $this->_tpl_vars['title']; ?>
</title>
<script language="javascript" src="/js/jquery-1.8.3.min.js"></script>
<link href="/css/default/agreement.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['mess']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
//alert("<?php echo $this->_tpl_vars['mess'][$this->_sections['i']['index']]['content']; ?>
");
<?php endfor; endif; ?>

</script>
<?php if (( $this->_tpl_vars['status'] != 1 )): ?>
<script type="text/javascript">
    alert("抱歉!你的帐号已被冻结（只限结帐功能可用），请和上级联系。<?php echo $this->_tpl_vars['status']; ?>
");
</script>
<?php endif; ?>
<body class="<?php echo $this->_tpl_vars['skin']; ?>
">
<script type="text/javascript" id="myjs">var mulu='<?php echo $this->_tpl_vars['mulu']; ?>
';
var js=1;
var sss='xy';
</script>
<FORM id='form' name='form' >
<div class="user_win">
    <div class="agree_win"> 
        <div class="user_logo"></div>
        <ul>
            <li class="user_wintitle">用户协议</li>
            <li class="user_winmain">
                <div class="win_info">
                    <ul>
                        <li>● 01. 为避免出现争议，请您务必在下注之后查看「下注状况」。</li>
                        <li>● 02. 任何投诉必须在开奖之前，本系统不接受任何开奖之后的投诉。</li>
                        <li>● 03. 公布赔率时出现的任何打字错误或非故意人为失误，所有（相关）注单一律不算。</li>
                        <li>● 04. 公布之所有赔率为浮动赔率，下注时请确认当前赔率及金额，下注确认后一律不能修改。</li>
                        <li>● 05. 开奖后接受的投注，一律视为投机漏洞，[本局注单一律不返还本金及盈利]，敬请会员遵守游戏规则。</li>
                        <li>● 06. 若本后台发现客户以不正当的手法投注或投注注单不正常，后台将有权「取消」相应之注单，客户不得有任何异议。</li>
                        <li>● 07. 如因软件或线路问题导致交易内容或其他与账号设定不符合的情形，请在开奖前立即与本后台联络反映问题，否则本后台将以资料库中的数据为准。</li>
                        <li>● 08. 倘若发生遭黑客入侵破坏行为或不可抗拒之灾害致网站故障或资料损坏、数据丢失等情况，后台将以资料库数据为依据。</li>
                        <li>● 09. 各级管理人员及客户必须对本系统各项功能进行了解及熟悉，任何违反正常使用的操作，后台概不负责。</li>
                        <li>● 10. 请认真了解各款彩票游戏规则。</li>
                        <li class="ftcolor_red">● 11. 如果会员信用额度超额或者为负数引起的争议，一律以公司处理为准。</li>
                        <li>● 12. 客户有责任确保自己的账户及密码保密，如果因客户的账户、密码简单，或因泄露导致被盗用，造成的损失由客户本人承担；同时应立即通知本公司，并更改其个人详细资料。</li>
                        <li>● 13. 若官方福彩中心开奖错误导致本系统采集数据同时出错情况下当期错误的所有注单以福彩中心官方网站更改后的数据为标准重新结算！在此特别声明，客户不得有任何异议。</li>
                        <li>以上协议解释权归本系统所有。<input id='txt' type="text" value='' style="width:0px;height:0px;border:none;" /></li>
                          <li class="user_winbu"><div><span><a class="no" href="/uxj/top.php?logout=yes">不同意</a><a class="yes" href="/uxj/top.php">同意</a></span></div></li>
                    </ul>
                </div>
            </li>
            <li class="user_winfooter"></li>        
        </ul>
    </div>
</div>
 <input type="submit" value=''  style="border:none;width:0px;height:0px;" />
</FORM>
</body>
</html>