<?php /* Smarty version 2.6.18, created on 2026-02-02 07:12:01
         compiled from login.html */ ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>会员登陆</title>
<style type="text/css">
@charset "UTF-8";
* { box-sizing: border-box; }
body {
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: "Helvetica Neue", Helvetica, Arial, "Microsoft Yahei", sans-serif;
    color: #fff;
    position: relative;
    overflow: hidden;
}
body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 20% 50%, rgba(0, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 153, 255, 0.1) 0%, transparent 50%);
    z-index: 0;
    animation: pulse 8s ease-in-out infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}
.main {
    width: 100%;
    max-width: 1200px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    position: relative;
    z-index: 1;
}
.panel {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 60px;
    width: 100%;
}
.login {
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(20px);
    padding: 50px 40px;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5),
                0 0 30px rgba(0, 255, 255, 0.2),
                inset 0 0 50px rgba(0, 255, 255, 0.05);
    width: 100%;
    max-width: 400px;
    position: relative;
    animation: fadeIn 0.6s ease-out;
    border: 1px solid rgba(0, 255, 255, 0.2);
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.form_t {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(0, 255, 255, 0.3);
    position: relative;
}
.form_t::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #00ffff, transparent);
    box-shadow: 0 0 10px #00ffff;
}
.user_t {
    font-size: 28px;
    font-weight: bold;
    color: #00ffff;
    text-shadow: 0 0 10px rgba(0, 255, 255, 0.8),
                 0 0 20px rgba(0, 255, 255, 0.4);
    letter-spacing: 2px;
    text-transform: uppercase;
}
.user_f {
    width: 24px;
    height: 24px;
    background: url("../css/login/google_auth_icon.png") center/contain no-repeat;
    cursor: pointer;
    opacity: 0.6;
    transition: all 0.3s;
    filter: brightness(0) invert(1) sepia(1) saturate(5) hue-rotate(160deg);
}
.user_f:hover {
    opacity: 1;
    filter: brightness(0) invert(1) sepia(1) saturate(5) hue-rotate(160deg) drop-shadow(0 0 8px #00ffff);
}
.info {
    margin-bottom: 25px;
    position: relative;
}
.info label {
    display: none;
}
.info input {
    width: 100%;
    padding: 14px 16px;
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(0, 255, 255, 0.2);
    border-radius: 8px;
    font-size: 15px;
    outline: none;
    transition: all 0.3s;
    color: #fff;
    backdrop-filter: blur(5px);
}
.info input::placeholder {
    color: rgba(255, 255, 255, 0.4);
}
.info input:focus {
    border-color: #00ffff;
    background: rgba(0, 0, 0, 0.7);
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.4),
                inset 0 0 10px rgba(0, 255, 255, 0.1);
}
.info.code {
    display: flex;
    gap: 12px;
    align-items: flex-end;
}
.info.code > div {
    flex: 1;
}
.info.code input {
    width: 100%;
}
.info.code img {
    height: 46px;
    border-radius: 8px;
    cursor: pointer;
    border: 1px solid rgba(0, 255, 255, 0.3);
    transition: all 0.3s;
    background: rgba(255, 255, 255, 0.1);
    padding: 2px;
}
.info.code img:hover {
    border-color: #00ffff;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
    transform: scale(1.05);
}
.control {
    margin-top: 30px;
}
.control input {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #00ffff 0%, #0099ff 100%);
    color: #000;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-indent: 0;
    margin-top: 0;
    box-shadow: 0 5px 15px rgba(0, 255, 255, 0.4),
                0 0 20px rgba(0, 255, 255, 0.2);
}
.control input:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 255, 255, 0.6),
                0 0 30px rgba(0, 255, 255, 0.4);
    background: linear-gradient(135deg, #00ffff 0%, #00ccff 100%);
}
.control input:active {
    transform: translateY(0);
    box-shadow: 0 3px 10px rgba(0, 255, 255, 0.4);
}

/* Left Advertisement / QR Code */
.left_adv {
    position: static;
    height: auto;
    width: auto;
    color: #fff;
    text-align: center;
    animation: fadeIn 0.6s ease-out 0.2s backwards;
}
.appqr-wrapper {
    background: rgba(0, 0, 0, 0.3);
    padding: 20px;
    border-radius: 16px;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3),
                0 0 20px rgba(0, 255, 255, 0.1);
    border: 1px solid rgba(0, 255, 255, 0.2);
}
.appqr-wrapper .appqrcode {
    background: #fff;
    padding: 10px;
    border-radius: 10px;
    display: inline-block;
}
.appqr-wrapper .appqrcode img {
    width: 140px;
    height: 140px;
    display: block;
}
.appqr-wrapper .text {
    margin-top: 15px;
    font-weight: 500;
    font-size: 16px;
    line-height: 1.5;
    text-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
}
.appqr {
    display: none;
}

/* Responsiveness */
@media (max-width: 850px) {
    .panel {
        flex-direction: column-reverse;
        gap: 30px;
    }
    .left_adv {
        display: none;
    }
    .login {
        width: 90%;
        margin: 0 20px;
        padding: 40px 30px;
    }
    .user_t {
        font-size: 24px;
    }
}
@media (max-width: 480px) {
    .login {
        padding: 40px 30px;
        max-width: 90%;
    }
    .user_t {
        font-size: 24px;
    }
}
.facode {
    display: none;
}
.info.facode input {
    width: 100%;
    padding: 14px 16px;
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(0, 255, 255, 0.2);
    border-radius: 8px;
    font-size: 15px;
    outline: none;
    transition: all 0.3s;
    color: #fff;
    backdrop-filter: blur(5px);
}
.info.facode input::placeholder {
    color: rgba(255, 255, 255, 0.4);
}
.info.facode input:focus {
    border-color: #00ffff;
    background: rgba(0, 0, 0, 0.7);
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.4),
                inset 0 0 10px rgba(0, 255, 255, 0.1);
}
</style>
<script language="javascript" src="/js/jquery-1.8.3.js"></script>
<script language="javascript" src="/js/md5.js"></script>
<script language="javascript">
$(function(){
	$("#imgcode").click(function(){
		$(this).attr('src',"../imgcode.php?act=init&"+Math.random());
	});
	$("input:password").click(function(){
	     $(this).attr("placeholder","");
	});
	$("#password").blur(function(){
		 if($(this).val()==''){
	       $(this).attr("placeholder",$(this).attr("title"));
		 }else{
	       $("#pass").val(men_md5_password($("#password").val()));	
		 }
	});
	$("input:text").click(function(){
	     $(this).attr("placeholder","");
	});
	$("input:text").blur(function(){
		if($(this).val()==''){
	      $(this).attr("placeholder",$(this).attr("title"));
		}
	});
	$(".user_f").click(function(){
		 $('.info.facode').slideToggle();
	});
	top.document.title="Welcome";
	$("#username").focus();
});

function checkform(){
    if($("#username").val()==''){
	     alert("請輸入帳號");
		 $("#username").focus();
		 return false;
	}else if($("#password").val()==''){
	     alert("請輸入密碼");
		 $("#password").focus();
		 return false;
	}else if($("#code").val()==''){
	     alert("請輸入驗證碼");
		 $("#code").focus();
		 return false;
	}else{
		return true;
	}
}

function stop(){
   return false;
}

function hideinfo(){ if(event.srcElement.tagName=="A"){
   window.status=event.srcElement.innerText}
}
document.onmouseover=hideinfo; 
document.onmousemove=hideinfo;

</script> 
</head>
<body>
<div class="main">
    <div class="panel">
        <!-- Login Form Section -->
        <div class="login">
            <form method="post" id='form' name="form" onsubmit="return checkform();">
                <input type="hidden" name="xtype" value="login" />
                <input type="hidden" name='pass' id='pass' value="" />
                
                <div class="form_t">
                    <span class="user_t">会员登录</span>
                    <span class="user_f" title="Google Authenticator"></span>
                </div>
                
                <div class="info username">
                    <label>账号</label>
                    <input type="text" name="username" id="username" placeholder="请输入您的账号" title="请输入您的账号">
                </div>
                
                <div class="info password">
                    <label>密码</label>
                    <input type="password" name="password" id="password" placeholder="您的密码" title="您的密码">
                </div>
                
                <div class="info code">
                    <label>验证码</label>
                    <input type="text" name="code" id='code' autocomplete="off" placeholder="验证码" title="验证码">
                    <img src="../imgcode.php?act=init" alt="验证码" id="imgcode" title="看不清？点击更换一张验证图片" />
                </div>
                
                <div class="info facode">
                    <label>二次验证码</label>
                    <input type="text" name="facode" autocomplete="off" placeholder="二次验证码" maxlength="10" />
                </div>
                
                <div class="control">
                    <input type="submit" value="登录" >
                </div>
            </form>
        </div>

    </div>
</div>
</body>
</html>