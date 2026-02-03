<?php /* Smarty version 2.6.18, created on 2024-12-23 21:18:23
         compiled from other.html */ ?>
<!DOCTYPE html>
<html lang="zh-Hans">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta charset="UTF-8">
<meta content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0" name="viewport">
<title><?php echo $this->_tpl_vars['webname']; ?>
</title>
<style type="text/css">
html, body {
    height: 100%;
    width: 100%;
    position: fixed;
    overflow: hidden;
    -webkit-overflow-scrolling: touch;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
}

body {
    font-family: Tahoma,Helvetica,"Microsoft Yahei",STXihei,sans-serif;
    padding: 0;
    margin: 0;
    -webkit-overflow-scrolling: touch;
}
a {
    color: #666;
    -webkit-text-decoration: none;
    text-decoration: none;
}

a:-webkit-any-link {
    color: -webkit-link;
    cursor: pointer;
    text-decoration: underline;
}
ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
}

#root {
    width: 100%;
    height: 100%;
}
.ivfTfC {
    position: fixed;
    background-color: rgba(55, 55, 55, 0.7);
    top: 0px;
    right: 0px;
    bottom: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: 2;
    -webkit-transition: all 0.2s ease-in-out;
    -o-transition: all 0.2s ease-in-out;
    -webkit-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
}
.OSUUp {
    position: fixed;
    background-color: rgba(55, 55, 55, 0.7);
    top: 0px;
    right: 0px;
    bottom: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: 2;
    cursor: pointer;
    opacity: 1;
    visibility: visible;
    transition: all 0.2s ease-in-out 0s;
}

.efUsXr {
    position: fixed;
    width: 80%;
    height: 100%;
    top: 0px;
    transform: translate3d(0px, 0px, 0px);
    z-index: 999;
    box-shadow: rgba(55, 55, 55, 0.5) 0px 0px 16px;
    background-color: rgb(255, 255, 255) !important;
    overflow: hidden;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1) 0s;
}
.efUsXr .menu_navigation {
    position: relative;
    width: 100%;
    height: 50px;
    background: linear-gradient(135deg, rgb(19, 46, 123) 0%, rgb(0, 201, 202) 100%);
}

.efUsXr .menu_navigation .naviga2 {
    width: 10rem;
    height: 50px;
    text-align: center;
    font-weight: bold;
    line-height: 50px;
    color: rgb(255, 255, 255);
    margin: 0px auto;
}

.efUsXr .menu_type {
    position: relative;
    width: 100%;
    height: calc(100% - 56px);
    -webkit-tap-highlight-color: rgba(55, 55, 55, 0.3);
    overflow: auto;
}

.efUsXr .menu_type .mt_div {
    height: 50px;
    display: block;
    border-bottom: 1px solid #ccc;
}
.efUsXr .menu_type .mt_div .mtd_icon div {
    width: 30px;
    height: 30px;
    margin: 11px auto 0;
    background: url(/css/mobi/img/icon_menu.png);
    background-size: 457px 30px;
}
.efUsXr .menu_type .mt_div .mtd_icon {
    float: left;
    height: 100%;
    width: 60px;
}
.efUsXr .menu_type .mt_div .mtd_font .mtdf_1 {
    float: left;
    color: #999;
    line-height: 50px;
    font-size: 16px;
}
.efUsXr .menu_type .mt_div .mtd_icon1 div {
    background-position: 0px 0px;
}
.efUsXr .menu_type .mt_div .mtd_icon2 div {
    background-position: -31px 0px;
}
.efUsXr .menu_type .mt_div .mtd_icon4 div {
    background-position: -92px 0px;
}
.efUsXr .menu_type .mt_div .mtd_icon6 div {
    background-position: -153px 0px;
}
.efUsXr .menu_type .mt_div .mtd_icon7 div {
    background-position: -183px 0px;
}
.efUsXr .menu_type .mt_div .mtd_icon8 div {
    background-position: -214px 0px;
}
.efUsXr .menu_type .mt_div .mtd_icon9 div {
    background-position: -244px 0px;
}
.efUsXr .menu_type .mt_div .mtd_icon16 div {
    background: url(/css/mobi/img/ic_navi_168result.png) center / contain no-repeat;
}
.efUsXr .menu_type .mt_div .mtd_icon10 div {
    background-position: -275px 0px;
}
.efUsXr .menu_type .mt_div .mtd_icon15 div {
    background-position: -427px 0px;
}
.iJamhB {
    position: fixed;
    width: 80%;
    height: 100%;
    overflow: hidden;
    top: 0px;
    -webkit-transform: translate3d(-110%, 0px, 0px);
    -ms-transform: translate3d(-110%,0,0);
    transform: translate3d(-110%, 0px, 0px);
    background-color: #fff !important;
    z-index: 999;
    -webkit-box-shadow: 0 0 16px rgba(55,55,55,0.5);
    box-shadow: 0px 0px 16px rgba(55,55,55,0.5);
    -webkit-transition: transform 0.2s cubic-bezier(0.4,0,0.2,1);
    -o-transition: transform 0.2s cubic-bezier(0.4,0,0.2,1);
    -webkit-transition: -webkit-transform 0.2s cubic-bezier(0.4,0,0.2,1);
    -webkit-transition: transform 0.2s cubic-bezier(0.4,0,0.2,1);
    transition: transform 0.2s cubic-bezier(0.4,0,0.2,1);
}
.iJamhB .menu_navigation {
    position: relative;
    width: 100%;
    height: 50px;
    background: rgb(19,46,123);
    background: -o-linear-gradient(315deg,rgba(19,46,123,1) 0%,rgba(0,201,202,1) 100%);
    background: linear-gradient( 135deg,rgba(19,46,123,1) 0%,rgba(0,201,202,1) 100% );
    -webkit-filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#132e7b',endColorstr='#00c9ca',GradientType=1);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#132e7b',endColorstr='#00c9ca',GradientType=1);
}
.iJamhB .menu_navigation .naviga2 {
    margin: 0 auto;
    width: 10rem;
    height: 50px;
    text-align: center;
    font-weight: bold;
    line-height: 50px;
    color: #fff;
}
.iJamhB {
    position: fixed;
    width: 80%;
    height: 100%;
    top: 0px;
    transform: translate3d(-110%, 0px, 0px);
    z-index: 999;
    box-shadow: rgba(55, 55, 55, 0.5) 0px 0px 16px;
    background-color: rgb(255, 255, 255) !important;
    overflow: hidden;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1) 0s;
}

.rough_lines {
    width: 100%;
    height: 10px;
    background-color: #ebebeb;
    -webkit-box-shadow: 0px 1px 1px #bbb inset;
    box-shadow: inset 0px 1px 1px #bbb;
}

.iJamhB .menu_type {
    position: relative;
    width: 100%;
    height: calc(-56px + 100%);
    overflow: auto;
    -webkit-tap-highlight-color: rgba(55, 55, 55, 0.3);
}
.iJamhB .menu_type .mt_div {
    height: 50px;
    display: block;
    border-bottom: 1px solid #ccc;
}


.ilDBjU {
    background: rgb(19,46,123);
    background: -o-linear-gradient(315deg,rgba(19,46,123,1) 0%,rgba(0,201,202,1) 100%);
    background: linear-gradient( 135deg,rgba(19,46,123,1) 0%,rgba(0,201,202,1) 100% );
    -webkit-filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#132e7b',endColorstr='#00c9ca',GradientType=1);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#132e7b',endColorstr='#00c9ca',GradientType=1);
    height: 45px;
    position: relative;
    width: 100%;
}

.ilDBjU .pn_title {
    text-align: center;
    line-height: 45px;
    font-size: 18px;
    color: #fff;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
}
.ilDBjU .pn_title .menu {
    cursor: pointer;
    position: absolute;
    width: 29px;
    height: 35px;
    top: 0px;
    bottom: 0px;
    right: 10px;
    background: url(/css/mobi/img/icon_count.png) -224px -131px / 307px 217px;
    background-position: -224px -131px;
    border-radius: 50%;
    margin: auto;
}

.ilDBjU .pn_title .header-left-btn-link {
    float: left;
    color: white;
    cursor: pointer;
    text-align: left;
    margin: 0px 10px auto;
}
.ilDBjU .pn_title .header-left-btn-link > .btn-outline {
    max-width: 120px;
    text-overflow: ellipsis;
    white-space: nowrap;
    height: 16px;
    display: block;
    line-height: 16px;
    margin-top: 10px;
    padding: 0.25rem 0.5rem;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(255, 255, 255);
    border-image: initial;
    border-radius: 2rem;
    overflow: hidden;
}

.ilDBjU a {
    color: rgb(102, 102, 102);
    text-decoration: none;
}

.iBBuud {
    width: 100%;
    height: 35px;
    background-color: #fff;
    box-sizing: border-box;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    left: 0px;
    bottom: 0px;
    position: fixed;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
}

.iBBuud .betting_shortcut {
    border: none;
}
.iBBuud .betting_shortcut, .iBBuud .not_settlement_shortcut, .iBBuud .result_shortcut {
    width: 20%;
    border-left: 1px solid #ccc;
    height: 50px;
    float: left;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
}

.iBBuud .betting_shortcut div, .iBBuud .not_settlement_shortcut div, .iBBuud .result_shortcut div {
    width: 67px;
    margin: 3px auto 0;
    font-size: 16px;
    color: #666;
    line-height: 30px;
}
.iBBuud .betting_shortcut div div {
    background-position: 39px -38px;
}
.iBBuud .betting_shortcut div div, .iBBuud .not_settlement_shortcut div div, .iBBuud .result_shortcut div div {
    height: 30px;
    width: 30px;
    float: left;
    -webkit-transform: scale(0.8);
    -ms-transform: scale(0.8);
    transform: scale(0.8);
    margin: 0px 3px 0px 0px;
    background: url(/css/mobi/img/icon_step.png);
    background-size: 520px 74px;
}
.iBBuud .betting_shortcut div div {
    background-position: 39px -38px;
}


.iBBuud .result_shortcut div div {
    height: 30px;
    width: 30px;
    float: left;
    -webkit-transform: scale(0.8);
    -ms-transform: scale(0.8);
    transform: scale(0.8);
    margin: 0px 3px 0px 0px;
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA0kAAAJTBAMAAAAvbUA6AAAAFVBMVEVHcEwAl/EAl/EAl/EAl/EAl/EAl/FLFK/gAAAABnRSTlMAFm1L1Z1GdPbvAAAErElEQVR42u3cQUrzUBiG0VyNM0GKOhW7gyJ2KLgJV+DCugI3IK5BHUsRFyAx80rjUAWLfpDc3OJ5cCD8pPjnwPfixFR9tntd/dxqUWnMdrwCSqJESZREiZIoiRIlUaIkSqJESZREiZIoiRIlUaIkSqJESZREiZIoiRIlUaIkSqJESZREiZIoiRIlUaIkSqJESQVUewXxji+CD6QbStmbHgUf6Fw8uyRKokRJlESJkihREiVRoiRKokRJlESJkihREiVRoiRKokRJlESJkihREiVRoiRKokRJlESJkihREiVRoiRK+qXi/z7eJPpA11LK3vw0+MBq4eKJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlDRe9f/5rx6eRJ94oJRfaR584L0YJRfPLhXVjNIW9EhJlCjZJUp2iZKLJ0p2iZJdEiXZJUp2iZKLJ0p2iZJdEiVRskt2iZIoUbJLlOySKImSXbJLlESJkl2iZJdcPEqiZJfsEiVRomSXKNklSi6eKNklu0RJlCjZJUp2iZKLJ0p2iZJdEiXZJUp2iZKLJ0p2iZJdEiVRskt2iZIoUbJLlOySKImSXbJLlESJkl2iZJdcPEqiZJfsEiVRomSXKNklSi6eKNklu0RJlCjZJUp2iZKLp/Gqh/nYs9QehL7Ww9+jGaXvpfPoE08ZdunSxRMlSn5fouT3JUounijZJUp2SZRklyjZJUounijZJUp2SZREyS7ZJUqiRMkuUbJLoiRKdskuURIlSnaJkl1y8SiJkl2yS5REiZJdomSXKLl4omSX7BIlUaJklyjZJUounijZJUp2SZRklyjZJUounijZJUp2SZREyS7ZJUqiRMkuUbJLoiRKdskuURIlSnaJkl1y8SiJkl2yS5REiZJdomSXKLl4omSX7BIlUaJklyjZJUounijZJUp2SZRklyjZJUounijZJUp2SZREyS7ZJUqiRMkuUbJLoiRKdskuURIlSnaJkl1yTiiJkl2yS5REiZJdomSXXDxKomSX7BIlUaJklyjZJUounijZJbtESZQo2SVKdomSiydKdomSXRIl2SVKdomSiydKdomSXRIlUbJLdomSKFGyS5TskiiJkl2yS5REiZJdomSXXDxKomSX7BIlUaJklyjZJUounijZJbtESZQo2SVKdomSiydKdomSXRIl2SVKdqnn6kE+tbuLPrHe9A/LZV+ftB/9obqNP9Nr8JNSkUrVc4GfdN/bJzWNiydKlESJkiiJEiVREiVKoiRKlESJkiiJEiVREiVKoiRKlESJkiiJEiVREiVKoiRKlESJkiiJEiVREiVKoiRKlNRzf/v7eBMvKnNvcaW9K68tc7cvLp5dEiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKoiRKlERJlCiJkihREiVKKq309fup91FKTesdbFsfvUx/mTkmoYYAAAAASUVORK5CYII=) no-repeat center;
    background-size: contain;
}
.iBBuud .not_settlement_shortcut div div {
    background-position: 41px -7px;
}
.iBBuud a.betting_shortcut,.iBBuud a.not_settlement_shortcut,.iBBuud a.result_shortcut{text-decoration:none;}


.main-content {
    height: calc(-80px + 100%);
    position: fixed;
    top: 45px;
    width: 100%;
}

.main-content > div {
    height: 100%;
}

.bAHQck {
    display: none;
    position: absolute;
    z-index: 99999;
    width: 100%;
    overflow-y: auto;
    height: calc(100% - 55px);
    outline: none;
}

.bYxaNb {
    display: block;
    position: absolute;
    z-index: 99999;
    width: 100%;
    overflow-y: auto;
    height: calc(100% - 55px);
    outline: none;
}
.bYxaNb .ltn_name div {
    float: left;
    width: 33.33%;
    text-align: center;
    height: 50px;
    line-height: 50px;
    font-size: 16px;
    color: rgb(255, 255, 255);
    box-shadow: rgba(255, 255, 255, 0.34) 0px 0px 0px 0.2px, rgba(12, 12, 12, 0.45) 0px 0px 0px 0.5px;
    overflow: hidden;
    background: linear-gradient(135deg, rgb(7, 140, 171) 0px, rgb(2, 183, 192) 100%);
}

.ehMKLs {
    height: 55px;
    overflow: hidden;;
}

.ehMKLs > div {
    display: inline-flex;
    width: 49%;
    box-sizing: border-box;
    height: 100%;
}

.hfKWTC {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
    padding: 0px 10px;
    border-right: 1px solid rgb(204, 204, 204);
}
.bQzJaA {
    height: 13px;
    width: 16px;
    background: url(/css/mobi/img/icon-lottery.png) -6px 23px / 496px 63px;
    background-position: -6px 23px;
}

.jmLsNb {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
    padding: 0px 10px;
}
.jmLsNb input {
    color: rgb(102, 102, 102);
    font-weight: 400;
    font-size: 16px;
    background-color: rgba(255, 255, 255, 0);
    -webkit-appearance: none;
    height: 100%;
    border-width: initial;
    border-style: none;
    border-color: initial;
    border-image: initial;
}

.bQzJaA {
    height: 13px;
    width: 16px;
    background: url(/css/mobi/img/icon-lottery.png) -6px 23px / 496px 63px;
    background-position: -6px 23px;
}
.bAHQck {
    display: none;
    position: absolute;
    z-index: 99999;
    width: 100%;
    overflow-y: auto;
    height: calc(100% - 55px);
    outline: none;
}

.gpQeaD {
    height: calc(100% - 65px);
    background: #fff;
}
.fEtITC {
    display: flex;
    justify-content: space-around;
    -webkit-box-align: center;
    align-items: center;
    height: 45px;
    box-shadow: rgb(204, 204, 204) 0px 0px 5px;
    border-bottom: 1px solid rgb(204, 204, 204);
}
.cJXNwD {
    display: flex;
    justify-content: space-around;
    -webkit-box-align: center;
    align-items: center;
    height: 100%;
    flex: 2 1 0%;
}
.cJXNwD span.title {
    font-size: 12px;
    color: rgb(51, 51, 51);
}
.kEsQus {
    font-size: 14px;
    color: rgb(102, 102, 102);
}
.jewTjd {
    display: flex;
    justify-content: space-around;
    -webkit-box-align: center;
    align-items: center;
    height: 100%;
    flex: 5 1 0%;
}

.dZPZqu {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
    justify-content: space-around;
}
.dZPZqu .row {
    display: flex;
    justify-content: space-around;
}
.ctglGG {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    font-size: 12px;
    height: 25px;
    user-select: none;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: rgb(102, 102, 102) !important;
    background-color: transparent !important;
    border-radius: 5px;
    padding: 0px 10px;
    overflow: hidden;
    border-width: 1px !important;
    border-style: solid !important;
    border-color: rgb(204, 204, 204) !important;
    border-image: initial !important;
}
.rBoyH {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    font-size: 14px;
    height: 25px;
    user-select: none;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: rgb(255, 255, 255) !important;
    background-color: rgb(0, 102, 204) !important;
    border-radius: 5px;
    padding: 0px 10px;
    overflow: hidden;
    border-width: 1px !important;
    border-style: solid !important;
    border-color: rgb(0, 102, 204) !important;
    border-image: initial !important;
}
.gemcaE {
    height: calc(100% - 47px);
    overflow-x: hidden;
    overflow-y: auto;
    width: 100%;
}
.resize-triggers, .resize-triggers > div, .contract-trigger:before {
    content: " ";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    overflow: hidden;
    z-index: -1;
}

.resize-triggers > div {
    background: #eee;
    overflow: auto;
}

.resize-triggers, .resize-triggers > div, .contract-trigger:before {
    content: " ";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    overflow: hidden;
    z-index: -1;
}
.xucEQ {
    display: flex;
    justify-content: space-around;
    -webkit-box-align: center;
    align-items: center;
    border-bottom: 1px solid rgb(204, 204, 204);
}

.qiuselect{
    background: linear-gradient(rgb(70, 149, 230) 0px, rgb(0, 149, 214) 100%) !important;
}
.qiuselect .name,.qiuselect .b_odds{
    color:#fff !important;
}
.qiue {
    width: 80%;
    min-height: 40px;
    height: auto;
    display: -webkit-inline-box;
    display: -webkit-inline-flex;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    border: 1px solid #ccc;
    text-align: center;
    border-radius: 5px;
    margin: 0 3% 5% 3%;
    box-sizing: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    vertical-align: middle;
    cursor: pointer;
    background: initial;
}
.qiud {
    width: 40.2%;
    min-height: 40px;
    height: auto;
    display: -webkit-inline-box;
    display: -webkit-inline-flex;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    border: 1px solid #ccc;
    text-align: center;
    border-radius: 5px;
    margin: 0 3% 5% 3%;
    box-sizing: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    vertical-align: middle;
    cursor: pointer;
    background: initial;
}

.qiua {
    width: 40.2%;
    min-height: 50px;
    height: auto;
    display: -webkit-inline-box;
    display: -webkit-inline-flex;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    border: 1px solid #ccc;
    text-align: center;
    border-radius: 5px;
    margin: 0 3% 5% 3%;
    box-sizing: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    vertical-align: middle;
    cursor: pointer;
    background: initial;
}
.qiub {
    width: 25%;
    min-height: 50px;
    height: auto;
    display: inline-flex;
    flex-direction: column;
    -webkit-box-pack: center;
    justify-content: center;
    text-align: center;
    box-sizing: border-box;
    user-select: none;
    vertical-align: middle;
    cursor: pointer;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(204, 204, 204);
    border-image: initial;
    border-radius: 5px;
    margin: 0px 3% 5%;
    background: initial;
}
.qiuc {
    width: 25%;
    min-height: 50px;
    height: auto;
    display: -webkit-inline-box;
    display: -webkit-inline-flex;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    border: 1px solid #ccc;
    text-align: center;
    border-radius: 5px;
    margin: 0 3% 5% 3%;
    box-sizing: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    vertical-align: middle;
    cursor: pointer;
    background: initial;
}
.qiua .b_odds,.qiub .b_odds,.qiuc .b_odds,.qiud .b_odds {
    color: #ff4d4d;
    font-size: 16px;
}


.borange{
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(255, 154, 0) 0px, rgb(255, 102, 0));
}
.fgreen{
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: green;
    border-radius: 50%;
    background: none;
}

.fblue{
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: blue;
    border-radius: 50%;
    background: none;
}

.fred {
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: red;
    border-radius: 50%;
    background: none;
}
.bgreen{
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(89, 225, 75) 1%, rgb(58, 193, 44));
}

.bblue{
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
}

.bred {
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(250, 116, 118) 0px, rgb(238, 9, 9));
}
.bblack {
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: rgb(0, 0, 0);
    border-radius: 50%;
    background: none;
}
.b1511 {
    height: 30px;
    width: 30px;
    margin-right: 5px;
    display: inline-block;
    background: url(/css/mobi/img/icon-lottery.png) 98px 0px / 487px 60px;
    background-position: 98px 0px;
}
.b1512 {
    height: 30px;
    width: 30px;
    margin-right: 5px;
    display: inline-block;
    background: url(/css/mobi/img/icon-lottery.png) 65px 0px / 487px 60px;
    background-position: 65px 0px;
}
.b1513 {
    height: 30px;
    width: 30px;
    margin-right: 5px;
    display: inline-block;
    background: url(/css/mobi/img/icon-lottery.png) 32px 0px / 487px 60px;
    background-position: 32px 0px;
}
.b1514 {
    height: 30px;
    width: 30px;
    margin-right: 5px;
    display: inline-block;
    background: url(/css/mobi/img/icon-lottery.png) 98px -30px / 487px 60px;
    background-position: 98px -30px;
}
.b1515 {
    height: 30px;
    width: 30px;
    margin-right: 5px;
    display: inline-block;
    background: url(/css/mobi/img/icon-lottery.png) 65px -30px / 487px 60px;
    background-position: 65px -30px;
}
.b1516 {
    height: 30px;
    width: 30px;
    margin-right: 5px;
    display: inline-block;
    background: url(/css/mobi/img/icon-lottery.png) 32px -30px / 487px 60px;
    background-position: 32px -30px;
}
.b161a {
    text-align: center;
    margin-right: 2px;
    font-size: 12px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(139, 224, 255) 1%, rgb(90, 214, 255));
}

.b161b {
    text-align: center;
    margin-right: 2px;
    font-size: 12px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
}

.b163 {
    width: 30px;
    height: 30px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 15px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
    margin: auto;
}
.bsx0 {
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px 0px;
}
.bsx1 {
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px -30px;
}
.bsx2{
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px -60px;
}
.bsx3 {
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px -90px;
}
.bsx4 {
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px -120px;
}
.bsx5 {
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px -150px;
}
.bsx6 {
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px -180px;
}
.bsx7 {
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px -210px;
}
.bsx8 {
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px -240px;
}
.bsx9 {
    text-align: center;
    margin-right: 2px;
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    vertical-align: middle;
    background-image: url(/css/default/img/ball/ball_cqhlsx.png);
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: 0px -270px;
}
.b101 {
    width: 30px;
    height: 30px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 15px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
    margin: auto;
}
.b121 {
    width: 23px;
    height: 23px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 12px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
    margin: auto;
}
.b121red {
    width: 23px;
    height: 23px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 12px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(250, 116, 118) 0px, rgb(238, 9, 9));
    margin: auto;
}
.b103 {
    width: 23px;
    height: 23px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 12px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
    margin: auto;
}

.b103red {
    width: 23px;
    height: 23px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 12px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(250, 116, 118) 0px, rgb(238, 9, 9));
    margin: auto;
}

.b10710 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(50, 197, 51);
    border-radius: 4px;
}
.b10709 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(121, 0, 7);
    border-radius: 4px;
}
.b10708 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(255, 0, 26);
    border-radius: 4px;
}
.b10707 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(227, 227, 227);
    border-radius: 4px;
}
.b10706 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(66, 36, 248);
    border-radius: 4px;
}
.b10705 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(119, 255, 253);
    border-radius: 4px;
}
.b10704 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(255, 112, 34);
    border-radius: 4px;
}
.b10703 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(77, 77, 77);
    border-radius: 4px;
}
.b10702 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(0, 140, 250);
    border-radius: 4px;
}
.b10701 {
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    text-indent: -2px;
    font-size: 14px;
    font-weight: bold;
    font-style: italic;
    text-shadow: rgb(0, 0, 0) 0px 0px 2px;
    background-color: rgb(255, 253, 60);
    border-radius: 4px;
}

.b107dx0{
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-right: 2px;
    background-color: rgb(255, 167, 53);
    border-radius: 4px;
}

.b107dx1{
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-right: 2px;
    background-color: rgb(0, 108, 218);
    border-radius: 4px;
}

.b107ds1{
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-right: 2px;
    background-color: rgb(255, 167, 53);
    border-radius: 4px;
}

.bzh {
    font-size: 15px;
    font-weight: bold;
    min-width: 40px;
    text-align: center;
    color: rgb(255, 0, 0);
}

.b107ds0{
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-right: 2px;
    background-color: rgb(0, 108, 218);
    border-radius: 4px;
}

.b107lh0{
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-right: 2px;
    background-color: rgb(255, 167, 53);
    border-radius: 4px;
}

.b107lh1{
    height: 23px;
    width: 23px;
    line-height: 23px;
    color: rgb(255, 255, 255);
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-right: 2px;
    background-color: rgb(0, 108, 218);
    border-radius: 4px;
}

.b101dx0 {
    width: 30px;
    height: 30px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 15px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(255, 154, 0) 0px, rgb(255, 102, 0));
    margin: auto;
}
.b101dx1{
    width: 30px;
    height: 30px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 15px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
    margin: auto;
}
.b101dx2{
    width: 30px;
    height: 30px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 15px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(89, 225, 75) 1%, rgb(58, 193, 44));
    margin: auto;
}
.b101ds1 {
    width: 30px;
    height: 30px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 15px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(255, 154, 0) 0px, rgb(255, 102, 0));
    margin: auto;
}
.b101ds0{
    width: 30px;
    height: 30px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 15px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
    margin: auto;
}
.b101ds2{
    width: 30px;
    height: 30px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    font-size: 15px;
    -webkit-box-pack: center;
    justify-content: center;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(89, 225, 75) 1%, rgb(58, 193, 44));
    margin: auto;
}
.b101qt0{
    font-size: 13px;
    font-weight: bold;
    min-width: 20px;
    text-align: left;
    color: rgb(255, 0, 0);
}
.b101qt1{
    font-size: 13px;
    font-weight: bold;
    min-width: 20px;
    text-align: left;
    color: rgb(0, 102, 204);
}
.b101qt2{
    font-size: 13px;
    font-weight: bold;
    min-width: 20px;
    text-align: left;
    color: rgb(0, 221, 52);
}
.b101qt3{
    font-size: 13px;
    font-weight: bold;
    min-width: 20px;
    text-align: left;
    color: rgb(255, 155, 0);
}
.b101qt4{
    font-size: 13px;
    font-weight: bold;
    min-width: 20px;
    text-align: left;
    color: rgb(102, 102, 102);
}
.b161ds0 {
    color: rgb(255, 255, 255);
    width: 20px;
    height: 26px;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    display: inline-flex;
    font-size: 13px;
    border-radius: 6px;
    background: linear-gradient(rgb(255, 154, 0) 0px, rgb(255, 102, 0));
}

.b161ds1 {
    color: rgb(255, 255, 255);
    width: 20px;
    height: 26px;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    display: inline-flex;
    font-size: 13px;
    border-radius: 6px;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
}
.b161dx0 {
    color: rgb(255, 255, 255);
    width: 20px;
    height: 26px;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    display: inline-flex;
    font-size: 13px;
    border-radius: 6px;
    background: linear-gradient(rgb(255, 154, 0) 0px, rgb(255, 102, 0));
}
.b161dx1 {
    color: rgb(255, 255, 255);
    width: 20px;
    height: 26px;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    display: inline-flex;
    font-size: 13px;
    border-radius: 6px;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
}
.b161wh0{
    font-size: 15px;
    width: 16px;
    color: rgb(255, 186, 0);
}
.b161wh1{
    font-size: 15px;
    width: 16px;
    color: rgb(180, 85, 38);
}
.b161wh2{
    font-size: 15px;
    width: 16px;
    color: rgb(0, 136, 254);
}
.b161wh3{
    font-size: 15px;
    width: 16px;
    color: rgb(253, 115, 2);
}
.b161wh4{
    font-size: 15px;
    width: 16px;
    color: rgb(87, 87, 87);
}
.b161wh5{
    font-size: 15px;
    width: 16px;
    color: red;
}
.b161duo0{
    color: rgb(255, 255, 255);
    width: 60px;
    height: 26px;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    display: inline-flex;
    font-size: 13px;
    border-radius: 6px;
    background: linear-gradient(rgb(97, 156, 255) 0px, rgb(10, 94, 255));
}
.b161duo1{
    color: rgb(255, 255, 255);
    width: 60px;
    height: 26px;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    display: inline-flex;
    font-size: 13px;
    border-radius: 6px;
    background: linear-gradient(rgb(255, 154, 0) 0px, rgb(255, 102, 0));
}

.b161duo2{
    color: rgb(255, 255, 255);
    width: 60px;
    height: 26px;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    display: inline-flex;
    font-size: 13px;
    border-radius: 6px;
    background: linear-gradient(rgb(89, 225, 75) 1%, rgb(58, 193, 44));
}
.b151dx0{
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(255, 154, 0) 0px, rgb(255, 102, 0));
}
.b151dx1{
    text-align: center;
    margin-right: 2px;
    font-size: 15px;
    display: inline-block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    color: rgb(255, 255, 255);
    border-radius: 50%;
    background: linear-gradient(rgb(255, 154, 0) 0px, rgb(255, 102, 0));
}
.eZghFE {
    flex-direction: column;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    text-align: center;
    position: relative;
}
.eZghFE .txt {
    font-size: 0.8125rem;
    display: block;
}
.ettwvL {
    -webkit-box-flex: 1;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.ettwvL .table-header {
    height: 50px;
    display: flex;
    -webkit-box-pack: justify;
    justify-content: space-between;
    background-color: rgb(255, 255, 255);
    border-bottom: 1px solid rgb(204, 204, 204);
    border-top: 1px solid rgb(204, 204, 204);
}
.ettwvL .table-header > div {
    color: rgb(102, 102, 102);
    box-sizing: border-box;
    display: flex;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    white-space: nowrap;
    text-overflow: ellipsis;
    border-left: 1px solid rgb(204, 204, 204);
    overflow: hidden;
}
.ettwvL .col3 {
    width: 30%;
}
.ettwvL .col1-5 {
    width: 15%;
}
.ettwvL .col {
    font-size: 10px;
    word-break: break-all;
    overflow-wrap: break-word;
}
.ettwvL .col2 {
    width: 20%;
}
.ettwvL .table-content {
    overflow: auto;
    flex: 1 1 0%;
}

.ettwvL .table-content > .table-row {
    display: flex;
    width: 100%;
    min-height: 70px;
    -webkit-box-align: center;
    align-items: center;
    box-sizing: border-box;
    padding-top: 5px;
    padding-bottom: 5px;
    border-bottom: 1px solid rgb(204, 204, 204);
}

.ettwvL .table-content > .table-row > * {
    text-align: center;
}
.green_color {
    color: rgb(8, 161, 0) !important;
}
.blue_color {
    color: rgb(0, 59, 175) !important;
}
.red_color {
    color: rgb(255, 0, 0) !important;
}
.ettwvL .table-content > .no_date {
    height: 70px;
    color: rgb(153, 153, 153);
    font-size: 18px;
    display: flex;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    border-bottom: 1px solid rgb(204, 204, 204);
}

.ettwvL .table-footer.center-row {
    height: 90px;
    display: block;
}

.ettwvL .table-footer {
    display: flex;
    height: 50px;
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
    box-shadow: rgba(55, 55, 55, 0.2) 0px -3px 5px;
}
.ettwvL .table-footer .footer-row {
    width: 100%;
    height: 30px;
    line-height: 30px;
    color: rgb(153, 153, 153);
}
.ettwvL .table-footer .footer-row > div {
    width: 50%;
    text-align: right;
    display: inline-block;
    vertical-align: middle;
}
.ettwvL .table-footer .footer-row span {
    width: 50%;
    display: inline-block;
    vertical-align: middle;
    padding-left: 10px;
    box-sizing: border-box;
}

.jEffvY {
    height: 100%;
    overflow-y: scroll;
    display: flex;
    flex-direction: column;
}
.jEffvY .contact_type1.mini {
    margin: 0px auto;
}

.jEffvY .contact_type1 {
    width: 100%;
    margin: 5px auto 0px;
}

.jEffvY .contact_type1.mini .contact_info1 {
    height: 25px;
}

.jEffvY .contact_type1 .contact_info1 {
    width: 100%;
    height: 40px;
}
.jEffvY .contact_type1 .contact_info1 .contact_name1:last-of-type {
    padding-left: 1rem;
    color: rgb(21, 117, 193) !important;
}

.jEffvY .contact_type1 .contact_info1 .contact_name1:first-of-type {
    padding-left: 1.5rem;
    width: 5rem;
}

.jEffvY .contact_type1.mini .contact_info1 .contact_name1 {
    line-height: 25px;
    font-size: 13px;
}

.jEffvY .contact_type1 .contact_info1 div {
    float: left;
    line-height: 40px;
}
.jEffvY .personal_Navigation {
    height: 45px;
    position: relative;
    width: 100%;
    z-index: 1;
    background: linear-gradient(135deg, rgb(22, 46, 119) 0%, rgb(34, 201, 203) 100%);
}
.jEffvY .personal_Navigation .pn_title2 {
    text-align: left;
    line-height: 45px;
    font-size: 18px;
    color: rgb(255, 255, 255);
    margin-left: 20px;
}
.jEffvY .personal_Navigation .dropbtn {
    float: right;
    width: 45px;
    height: 45px;
    background: url(/css/mobi/img/ic_down.png) center center;
    background-position: center center;
    margin: -45px 0px 0px;
    border-width: initial;
    border-style: none;
    border-color: initial;
    border-image: initial;
}
.jEffvY .personal-info-scroll {
    width: 100%;
    position: relative;
    overflow: auto;
}
.jEffvY .personal-info-scroll .no_date1 {
    height: 50px;
    background-color: rgb(255, 255, 255);
    box-sizing: border-box;
    border-bottom: 1px solid rgb(204, 204, 204);
}
.jEffvY .personal-info-scroll .info_table_title1 {
    min-height: 50px;
}
.rough_lines {
    width: 100%;
    height: 10px;
    background-color: rgb(235, 235, 235);
    box-shadow: rgb(187, 187, 187) 0px 1px 1px inset;
}
.jEffvY .personal-info-scroll .no_date1 .nd_div1 {
    padding-top: 5px;
    text-align: center;
    color: rgb(153, 153, 153);
    font-size: 18px;
    line-height: 40px;
    margin: 0px auto;
}

.jEffvY .personal-info-scroll .info_table_title1 div {
    float: left;
    text-align: center;
    line-height: 1.6em;
    height: 50px;
    color: rgb(102, 102, 102);
    box-sizing: border-box;
    border-left: 1px solid rgb(204, 204, 204);
    border-bottom: 1px solid rgb(204, 204, 204);
}
.jEffvY .personal-info-scroll .info_table_title1.ab{
    height: 100px !important;
} 
.jEffvY .personal-info-scroll .info_table_title1.ab div{
    height: 100px !important;
} 


.jEffvY .personal-info-scroll .ir_4 {
    width: 25%;
}

.jEffvY .personal_Navigation #myDropdown.dropdown-content {
    font-size: 0.9rem;
    width: 100%;
    height: 307px;
    overflow: scroll;
    background: rgba(0, 0, 0, 0.3);
}

.jEffvY .personal_Navigation #myDropdown.dropdown-content div {
    float: left;
    width: 33.3%;
    text-align: center;
    line-height: 50px;
    color: rgb(102, 102, 102);
    box-sizing: border-box;
    background: linear-gradient(135deg, rgb(7, 140, 171) 0px, rgb(2, 183, 192) 100%);
}

.jEffvY .personal_Navigation #myDropdown.dropdown-content a {
    color: rgb(255, 255, 255);
    text-decoration: none;
}

.ghAhQy {
    width: 93%;
    position: relative;
    margin: 0px auto;
}
.ghAhQy .password-form {
    padding: 12px 0px;
}
.ghAhQy .first-time {
    text-align: center;
    margin-top: 10px;
}

form {
    display: block;
    margin-top: 0em;
}
.cwGwpk {
    padding: 8px 0px;
}
.cwGwpk .rnti_text {
    background-color: rgb(255, 255, 255);
    height: 19px;
    position: relative;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(204, 204, 206);
    border-image: initial;
    border-radius: 7px;
    padding: 15px 10px;
}
.cwGwpk .rnti_text .rntit_count {
    margin-top: -2px;
    color: rgb(153, 153, 153);
    font-size: 16px;
    margin-left: 5px;
    width: 75px;
    float: left;
    position: absolute;
}
.cwGwpk .rnti_text input {
    box-sizing: border-box;
    height: 35px;
    float: right;
    width: calc(80% - 2rem);
    font-size: 15px;
    color: blue !important;
    outline: medium;
    margin: -10px 0px 0px;
    border-width: initial;
    border-style: none;
    border-color: initial;
    border-image: initial;
}
input[type="password" i] {
    -webkit-text-security: disc !important;
}
button:disabled {
    color: graytext;
}
.jamQxo {
    display: flex;
    padding: 8px 0px;
}
.jamQxo > button:disabled {
    opacity: 0.5;
}

.jamQxo > button:first-child {
    margin-left: 0px;
}

.jamQxo > button {
    background-color: rgb(32, 97, 179);
    color: rgb(255, 255, 255);
    width: 100%;
    text-align: center;
    font-size: 18px;
    display: block;
    border-radius: 7px;
    border-width: initial;
    border-style: none;
    border-color: initial;
    border-image: initial;
    padding: 10px 0px;
    margin: 0px 8px;
}

.jamQxo > button:last-child {
    margin-right: 0px;
}

.cusIYt {
    color: rgb(255, 255, 255);
    font-size: 16px;
    user-select: none;
    cursor: pointer;
}
.cusIYt .arrow {
    width: 20px;
    height: 10px;
    display: inline-block;
    vertical-align: middle;
    transform: rotate(0deg);
    background: url(/css/mobi/img/sprites.png) -34px -117px / 410px 130px no-repeat;
    background-position: -34px -117px;
    transition: transform 0.1s ease-out 0s;
}
.dibfbQ {
    color: rgb(255, 255, 255);
    font-size: 16px;
    user-select: none;
    cursor: pointer;
}

.dibfbQ .arrow {
    width: 20px;
    height: 10px;
    display: inline-block;
    vertical-align: middle;
    transform: rotate(180deg);
    background: url(/css/mobi/img/sprites.png) -34px -117px / 410px 130px no-repeat;
    background-position: -34px -117px;
    transition: transform 0.1s ease-out 0s;
}
.jDLkaR {
    display: none;
    position: absolute;
    z-index: 99999;
    outline: none;
    width: 100%;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    height: 100%;
}
.iLhoMz{
    display: block;
    position: absolute;
    z-index: 99999;
    outline: none;
    width: 100%;
    overflow-y: auto;
    -webkit-overflow-scrolling:touch;
    height: 100%;
}
.iLhoMz div{
    display: block;
}
.iLhoMz .ltn_name div {
    float: left;
    width: 33.33%;
    text-align: center;
    height: 50px;
    line-height: 50px;
    font-size: 16px;
    color: rgb(255, 255, 255);
    box-shadow: rgba(255, 255, 255, 0.34) 0px 0px 0px 0.2px, rgba(12, 12, 12, 0.45) 0px 0px 0px 0.5px;
    overflow: hidden;
    background: linear-gradient(135deg, rgb(7, 140, 171) 0px, rgb(2, 183, 192) 100%);
}

.dESQLw .scroll-wrapper {
    width: 100%;
    height: 90%;
    overflow-y: auto;
    position: fixed;
}

.h2_font {
    font-size: 14px;
    padding-left: 20px;
}


.jTVanm {
    display: flex;
    height: 3rem;
    line-height: 3rem;
    background-color: rgb(255, 255, 255);
}

.jTVanm .page-info, .jTVanm .page-navi {
    font-size: 14px;
}

.jTVanm > div {
    -webkit-box-flex: 1;
    flex-grow: 1;
    text-align: center;
    position: relative;
}

.jTVanm .page-info .total-row {
    margin-right: 10px;
}
.jTVanm .page-jump {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 25px;
    right: 0px;
    margin: auto;
}

.jTVanm .page-jump input {
    width: 100%;
    height: 23px;
    font-size: 14px;
    font-weight: bold;
    text-align: center;
    box-sizing: border-box;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(204, 204, 204);
    border-image: initial;
    border-radius: 4px;
    margin: 0px;
    background: transparent;
}



.jTVanm .page-navi span.prev, .jTVanm .page-navi span.next {
    background-color: rgb(32, 97, 179);
    position: relative;
    padding: 3px 10px;
    border-radius: 4px;
    margin: 0px 15px;
}
.jTVanm .page-navi span.prev a, .jTVanm .page-navi span.next a {
    color: rgb(255, 255, 255);
}
.jTVanm .page-navi span.disabled {
    pointer-events: none;
    background-color: rgb(204, 204, 204) !important;
    color: rgb(66, 66, 66) !important;
}
.bxmSdB {
    display: flex;
    flex-direction: column;
}
.bpTAPs {
    display: flex;
    height: 48px;
    background-color: rgb(255, 255, 255);
    -webkit-box-pack: justify;
    justify-content: space-between;
    padding: 0px;
    margin: 0px;
}
.bpTAPs li {
    height: 100%;
    flex: 1 1 0%;
    list-style: none;
}
.bpTAPs li a.active {
    color: rgb(255, 255, 255);
    background-color: rgb(20, 60, 127);
}

.bpTAPs li a {
    display: inline-flex;
    width: 100%;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    height: 100%;
}

.ilDBjU .pn_title .lb_back {
    width: 19px;
    height: 30px;
    float: left;
    position: absolute;
    cursor: pointer;
    user-select: none;
    background: url(/css/mobi/img/icon_count.png) 20px -51px / 412px 310px;
    background-position: 20px -51px;
    margin: 10px 0px 0px 12px;
}



</style>
<script language="javascript" src="/js/jquery-1.8.3.min.js"></script>
<script language="javascript" src="/js/jquery.cookie.js"></script>
<script language="javascript" src="/js/md5.js"></script>
</head>
<body >
<script id=myjs language="javascript">var mulu="<?php echo $this->_tpl_vars['mulu']; ?>
";
var js=1;var sss='other';
</script>  
<div id="root">
  <div class="home">
    <div class="oc2pj9-1 ilDBjU">
      <div class="pn_title">
        <?php if (( $this->_tpl_vars['type'] == 'baoweek' )): ?>
           <a style="display: none;"><div class="lb_back"></div></a>
        <?php endif; ?>
        <div style="flex: 1 1 0%;">
          <?php if (( $this->_tpl_vars['type'] == 'kj' )): ?>
          <a href="<?php echo $this->_tpl_vars['kfurl']; ?>
" class="header-left-btn-link" target="_blank" title="全国开奖网"><span class="btn-outline">全国开奖网</span></a>
          <?php endif; ?>
          <?php if (( $this->_tpl_vars['type'] == 'rule' )): ?>
          
          <?php endif; ?>
        </div>
        <div style="flex: 1 1 0%;">
          <div style="color: white;">
            <?php if (( $this->_tpl_vars['type'] == 'kj' )): ?>
            开奖结果
            <?php elseif (( $this->_tpl_vars['type'] == 'wjs' )): ?>
            未结明细
            <?php elseif (( $this->_tpl_vars['type'] == 'changepass' )): ?>
            修改密码
            <?php elseif (( $this->_tpl_vars['type'] == 'baoday' )): ?>
            今天已结
            <?php elseif (( $this->_tpl_vars['type'] == 'baoweek' )): ?>
            两周报表
            <?php elseif (( $this->_tpl_vars['type'] == 'rule' )): ?>
            规则
            <?php elseif (( $this->_tpl_vars['type'] == 'userinfo' )): ?>
            个人资讯
            <?php endif; ?>
          </div>
        </div>
        <div style="flex: 1 1 0%;">
          <div class="menu">
          </div>
        </div>
      </div>
    </div>
    <style type="text/css">
.gfEgAp {
    font-size: 16px;
    user-select: none;
    cursor: pointer;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
    width: 100%;
    color: rgb(255, 255, 255);
}
.gfEgAp .text {
    margin-right: 8px;
}
.gfEgAp .arrow {
    width: 8px;
    height: 8px;
    display: inline-block;
    vertical-align: middle;
    transform-origin: center center;
    transform: rotate(225deg) translate(-2px, -2px);
    border-style: solid;
    border-color: rgb(255, 255, 255);
    border-width: 0px 2px 2px 0px;
    transition: transform 0.1s ease-out 0s;
}
.epExtR {
    font-size: 16px;
    user-select: none;
    cursor: pointer;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
    width: 100%;
    color: rgb(255, 255, 255);
}
.epExtR .text {
    margin-right: 8px;
}
.epExtR .arrow {
    width: 8px;
    height: 8px;
    display: inline-block;
    vertical-align: middle;
    transform-origin: center center;
    transform: rotate(45deg) translate(-2px, -2px);
    border-style: solid;
    border-color: rgb(255, 255, 255);
    border-width: 0px 2px 2px 0px;
    transition: transform 0.1s ease-out 0s;
}
     .jhcQwh {
    height: 45px;
    position: fixed;
    top: 0px;
    display: inline-flex;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    box-sizing: border-box;
    color: rgb(255, 255, 255);
    padding: 0px 10px;
}  
.kIYJcB {
    font-size: 16px;
    user-select: none;
    cursor: pointer;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
    width: 100%;
    color: rgb(136, 136, 136);
}
.kIYJcB .text {
    margin-right: 8px;
}
.kIYJcB .arrow {
    width: 8px;
    height: 8px;
    display: inline-block;
    vertical-align: middle;
    transform-origin: center center;
    transform: rotate(45deg) translate(-2px, -2px);
    border-style: solid;
    border-color: rgb(136, 136, 136);
    border-width: 0px 2px 2px 0px;
    transition: transform 0.1s ease-out 0s;
}

.jUzzot {
    font-size: 16px;
    user-select: none;
    cursor: pointer;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
    width: 100%;
    color: rgb(136, 136, 136);
}

.jUzzot .text {
    margin-right: 8px;
}
.jUzzot .arrow {
    width: 8px;
    height: 8px;
    display: inline-block;
    vertical-align: middle;
    transform-origin: center center;
    transform: rotate(225deg) translate(-2px, -2px);
    border-style: solid;
    border-color: rgb(136, 136, 136);
    border-width: 0px 2px 2px 0px;
    transition: transform 0.1s ease-out 0s;
}
    </style>
    <div class="main-content">
        <?php if (( $this->_tpl_vars['type'] == 'kj' )): ?>
      <div>
        <div class="sc-1a472n4-0 ehMKLs">
         <div class="i1h1lp-1 jhcQwh gamemenu" style="position: relative;"><div class="i1h1lp-2 kIYJcB"><span class="text"><?php echo $this->_tpl_vars['gname']; ?>
</span><span class="arrow"></span></div></div>
          <div class="sc-1a472n4-2 jmLsNb">
            <input tabindex="1" type="date" class="dates" value="<?php echo $this->_tpl_vars['dates']; ?>
">
            <div class="sc-1a472n4-3 bQzJaA">
            </div>
          </div>
        </div>
       
        <div class="rough_lines">
        </div>
        <div class="sc-1a472n4-4 gpQeaD">
          <div class="zyzeo4-2 fEtITC">
            <div class="zyzeo4-5 cJXNwD">
              <div class="zyzeo4-3 kEsQus">
                期数
              </div>
              <div class="zyzeo4-3 kEsQus">
                时间
              </div>
            </div>
            <div class="zyzeo4-5 jewTjd kjbt">
            </div>
          </div>
   
<div class="zyzeo4-0 gemcaE" style="position: relative;">
            <div style="overflow: visible; ">
              <div aria-label="grid" aria-readonly="true" class="ReactVirtualized__Grid ReactVirtualized__List" role="grid" tabindex="0" style="box-sizing: border-box; direction: ltr; height: 570px; position: relative; will-change: transform; overflow: hidden auto;">
                <div class="ReactVirtualized__Grid__innerScrollContainer kjlist" role="rowgroup" style="width: 100%; overflow: hidden; position: relative;">
                  <div class="zyzeo4-1 xucEQ" style="height: 70px; left: 0px; position: absolute; top: 980px; width: 100%;">
                    <div class="zyzeo4-5 cJXNwD">
                      <span class="title">735728</span><span class="title">09:30</span>
                    </div>
                    <div class="zyzeo4-5 jewTjd">
                      <div class="pk10 sc-1dsf9oj-1 kkdzbL">
                        6
                      </div>
                      <div class="pk10 sc-1dsf9oj-1 hILneq">
                        9
                      </div>
                      <div class="pk10 sc-1dsf9oj-1 gUOQKP">
                        3
                      </div>
                      <div class="pk10 sc-1dsf9oj-1 imarRC">
                        4
                      </div>
                      <div class="pk10 sc-1dsf9oj-1 hdvgbX">
                        2
                      </div>
                      <div class="pk10 sc-1dsf9oj-1 giqFCZ">
                        1
                      </div>
                      <div class="pk10 sc-1dsf9oj-1 vnjNK">
                        10
                      </div>
                      <div class="pk10 sc-1dsf9oj-1 jsCbDZ">
                        7
                      </div>
                      <div class="pk10 sc-1dsf9oj-1 kVqski">
                        5
                      </div>
                      <div class="pk10 sc-1dsf9oj-1 imJTGY">
                        8
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="resize-triggers">
              <div class="expand-trigger">
                <div style="width: 401px; height: 571px;">
                </div>
              </div>
              <div class="contract-trigger">
              </div>
            </div>
          </div>


        </div>
      </div>
      <?php elseif (( $this->_tpl_vars['type'] == 'wjs' )): ?>
<div class="sc-1fs8umj-0 ettwvL"><div class="table-header"><div class="col col2">注单号</div><div class="col col3">类型</div><div class="col col1-5">玩法</div><div class="col col1-5">下注金额</div><div class="col col2">可赢金额</div></div><div class="table-content wjslist"><div class="no_date">暂无数据</div></div><div class="table-footer center-row"><div class="footer-row"><div>注数</div><span>0</span></div><div class="footer-row"><div>下注金额(计)</div><span>0</span></div><div class="footer-row"><div>结果(总计)</div><span>0</span></div></div></div>
      <?php elseif (( $this->_tpl_vars['type'] == 'userinfo' )): ?>
      <div class="sc-1mp5qhc-0 jEffvY userinfocon" ></div>
      <?php elseif (( $this->_tpl_vars['type'] == 'changepass' )): ?>
      <div class="ggagnp-0 ghAhQy passcon">
	  
	  </div>
      <?php elseif (( $this->_tpl_vars['type'] == 'rule' )): ?>
<div class="bibxzc-0 dESQLw">
    <div class="i1h1lp-1 jhcQwh gamemenu"><div class="i1h1lp-2 epExtR"><span class="text" style="color: white !important;"><?php echo $this->_tpl_vars['gname']; ?>
</span><span class="arrow" style="border-color: white !important;"></span></div></div>
    <div class="scroll-wrapper rulecon" style="height: calc((100% - 45px) - 35px);word-break: break-word;-ms-text-size-adjust: none;-moz-text-size-adjust: none;-webkit-text-size-adjust: none;font-size:13px;">
     
    </div>
</div>
      <?php elseif (( $this->_tpl_vars['type'] == 'baoday' )): ?>
<div class="sc-1fs8umj-0 ettwvL"><div class="table-header"><div class="col col2">注单号</div><div class="col col3">类型</div><div class="col col1-5">玩法</div><div class="col col1-5">下注金额</div><div class="col col2">结果</div></div><div class="table-content wjslist"><div class="no_date">暂无数据</div></div><div class="table-footer center-row"><div class="footer-row"><div>注数</div><span>0</span></div><div class="footer-row"><div>下注金额(计)</div><span>0</span></div><div class="footer-row"><div>结果(总计)</div><span>0</span></div></div>
<div class=""><div class="pagination u42plk-0 jTVanm"><div class="page-info"><span class="total-row">共 2 笔记录</span><span class="total-page">共 1 页</span></div><div class="page-navi single-page"><form class="page-jump"><input type="text" placeholder="1" v='1' value="1"></form><span class="prev"><a>前一页</a></span><span class="next"><a>后一页</a></span></div></div></div></div> 
     <?php elseif (( $this->_tpl_vars['type'] == 'baoweek' )): ?>
     <style type="text/css">
.ettwvL .table-header {
    height: 50px;
    display: flex;
    -webkit-box-pack: justify;
    justify-content: space-between;
    background-color: rgb(255, 255, 255);
    border-bottom: 1px solid rgb(204, 204, 204);
    border-top: 1px solid rgb(204, 204, 204);
}
.ettwvL .table-header > div {
    color: rgb(102, 102, 102);
    box-sizing: border-box;
    display: flex;
    -webkit-box-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    align-items: center;
    white-space: nowrap;
    text-overflow: ellipsis;
    border-left: 1px solid rgb(204, 204, 204);
    overflow: hidden;
}

.ettwvL .col3 {
    width: 30%;
}
.ettwvL .col1 {
    width: 10%;
}
.ettwvL .col2 {
    width: 20%;
}
.ettwvL .col {
    font-size: 10px;

    word-break: break-all;
    overflow-wrap: break-word;
}
.ettwvL .table-content > .table-row {
    display: flex;
    width: 100%;
    min-height: 70px;
    -webkit-box-align: center;
    align-items: center;
    box-sizing: border-box;
    padding-top: 5px;
    padding-bottom: 5px;
    border-bottom: 1px solid rgb(204, 204, 204);
    text-decoration: none;
    color: rgb(102, 102, 102);
}
.ettwvL .table-footer {
    display: flex;
    height: 50px;
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
    box-shadow: rgba(55, 55, 55, 0.2) 0px -3px 5px;
}
.ettwvL .table-footer > .col {
    text-align: center;
    font-size: 10px;
    font-weight: bold;
}
     </style>
<div class="sc-1x64nms-1 bxmSdB baozong"><ul class="sc-1x64nms-0 bpTAPs baoctrl"><li><a class="active">本周</a></li><li><a class="">上周</a></li></ul><div class="rough_lines"></div><div class="sc-1fs8umj-0 ettwvL thisweek"><div class="table-header"><div class="col col3">日期</div><div class="col col1">注数</div><div class="col col2">下注金额</div><div class="col col2">有效金额</div><div class="col col2">佣金</div><div class="col col2">盈亏</div></div><div class="table-content"><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span class="">0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span>0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span>0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span>0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span class="">0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span class="">0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span class="">0.0</span></div></a></div><div class="table-footer"><div class="col col3">总计</div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span>0.0</span></div></div></div><div class="sc-1fs8umj-0 ettwvL lastweek" style="display: none;"><div class="table-header"><div class="col col3">日期</div><div class="col col1">注数</div><div class="col col2">下注金额</div><div class="col col2">有效金额</div><div class="col col2">佣金</div><div class="col col2">盈亏</div></div><div class="table-content"><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span class="">0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span>0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span>0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span>0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span class="">0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span class="">0.0</span></div></a><a class="table-row" href="javascript:void(0);"><div class="col col3 green_color"></div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span class="">0.0</span></div></a></div><div class="table-footer"><div class="col col3">总计</div><div class="col col1">0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2">0.0</div><div class="col col2"><span>0.0</span></div></div></div></div>
<div class="sc-1fs8umj-0 ettwvL detail" style="display: none;"><div class="table-header"><div class="col col2">注单号</div><div class="col col3">类型</div><div class="col col1-5">玩法</div><div class="col col1-5">下注金额</div><div class="col col2">结果</div></div><div class="table-content wjslist"><div class="no_date">暂无数据</div></div><div class="table-footer center-row"><div class="footer-row"><div>注数</div><span>0</span></div><div class="footer-row"><div>下注金额(计)</div><span>0</span></div><div class="footer-row"><div>结果(总计)</div><span>0</span></div></div>
<div class=""><div class="pagination u42plk-0 jTVanm"><div class="page-info"><span class="total-row">共 2 笔记录</span><span class="total-page">共 1 页</span></div><div class="page-navi single-page"><form class="page-jump"><input type="text" placeholder="1" v='1' value="1"></form><span class="prev"><a>前一页</a></span><span class="next"><a>后一页</a></span></div></div></div></div> 
      <?php endif; ?>

    </div>

    <div class="sc-1ng8zp5-0 iBBuud menulist">
      <a class="betting_shortcut" type="/creditmobile/load" href="javascript:void(0)">
      <div>
        <div>
        </div>
        游戏
      </div>
      </a><a class="result_shortcut" type="/creditmobile/dresult" href="javascript:void(0)">
      <div>
        <div>
        </div>
        开奖
      </div>
      </a><a class="not_settlement_shortcut" type="/creditmobile/report" href="javascript:void(0)">
      <div>
        <div>
        </div>
        未结
      </div>
      </a>
    </div>
  </div>
  <div class="slevm5-0 ivfTfC zhao" data-show="false">
  </div>

  <div class="slevm5-1 iJamhB menulist" data-show="false">
    <div class="menu_navigation">
      <div class="naviga2">
        <?php echo $this->_tpl_vars['username']; ?>

      </div>
    </div>
    <div class="rough_lines">
    </div>
    <div class="menu_type">
      <a class="mt_div" type="/creditmobile/home" href="javascript:void(0)">
      <div class="mtd_icon mtd_icon1">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          主页
        </div>
      </div>
      </a><a class="mt_div" type="/creditmobile/userinfo" href="javascript:void(0)">
      <div class="mtd_icon mtd_icon2">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          个人资讯
        </div>
      </div>
      </a><a class="mt_div" type="/creditmobile/password" href="javascript:void(0)">
      <div class="mtd_icon mtd_icon4">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          修改密码
        </div>
      </div>
      </a><a class="mt_div" type="/creditmobile/report" href="javascript:void(0)">
      <div class="mtd_icon mtd_icon6">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          未结明细
        </div>
      </div>
      </a><a class="mt_div" type="/creditmobile/todayreport" href="javascript:void(0)">
      <div class="mtd_icon mtd_icon7">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          今天已结
        </div>
      </div>
      </a><a class="mt_div" type="/creditmobile/history" href="javascript:void(0)">
      <div class="mtd_icon mtd_icon8">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          两周报表
        </div>
      </div>
      </a><a class="mt_div" type="/creditmobile/dresult" href="javascript:void(0)">
      <div class="mtd_icon mtd_icon9">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          开奖结果
        </div>
      </div>
      </a><a class="mt_div"  target="_blank" href="<?php echo $this->_tpl_vars['kfurl']; ?>
">
      <div class="mtd_icon mtd_icon16">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          全国开奖网
        </div>
      </div>
      </a><a class="mt_div" type="/creditmobile/rule"  type="" href="javascript:void(0)">
      <div class="mtd_icon mtd_icon10">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          规则
        </div>
      </div>
      </a><a class="mt_div" type="logout" href="javascript:void(0)">
      <div class="mtd_icon mtd_icon15">
        <div>
        </div>
      </div>
      <div class="mtd_font">
        <div class="mtdf_1">
          退出
        </div>
      </div>
      </a>
    </div>
  </div>
</div>
<div id="modal">
</div>

<style type="text/css">

.swal-overlay--show-modal {
    opacity: 1;
    pointer-events: auto;
}


.swal-overlay {
    position: fixed;
    background-color: rgba(0,0,0,.4);
    top: 0px;
    right: 0px;
    bottom: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: 100;
    cursor: pointer;
    opacity: 1;
    visibility: visible;
    transition: all 0.2s ease-in-out 0s;

}
.swal-overlay--show-modal .swal-modal {
    opacity: 1;
    pointer-events: auto;
    box-sizing: border-box;
    -webkit-animation: showSweetAlert .3s;
    animation: showSweetAlert .3s;
    will-change: transform;
}
.swal-modal {
    width: 478px;
    opacity: 0;
    pointer-events: none;
    background-color: #fff;
    text-align: center;
    border-radius: 5px;
    position: static;
    margin: 20px auto;
    display: inline-block;
    vertical-align: middle;
    -webkit-transform: scale(1);
    transform: scale(1);
    -webkit-transform-origin: 50% 50%;
    transform-origin: 50% 50%;
    z-index: 10001;
    transition: opacity .2s,-webkit-transform .3s;
    transition: transform .3s,opacity .2s;
    transition: transform .3s,opacity .2s,-webkit-transform .3s;
}


.swal-modal {
    width: calc(100% - 20px);
    margin-left: 10px;
    margin-top:200px;
}

.swal-icon:first-child {
    margin-top: 32px;
}

.swal-icon {
    width: 80px;
    height: 80px;
    border-width: 4px;
    border-style: solid;
    border-radius: 50%;
    padding: 0;
    position: relative;
    box-sizing: content-box;
    margin: 20px auto;
    border-color: #f27474;
}



.swal-icon--error__x-mark {
    position: relative;
    display: block;
    -webkit-animation: animateXMark .5s;
    animation: animateXMark .5s;
}

.swal-icon--error__line--left {
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
    left: 17px;
}

.swal-icon--error__line {
    position: absolute;
    height: 5px;
    width: 47px;
    background-color: #f27474;
    display: block;
    top: 37px;
    border-radius: 2px;
}

.swal-icon--error__line--right {
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
    right: 16px;
}

.swal-title:not(:last-child) {
    margin-bottom: 13px;
}

.swal-title:not(:first-child) {
    padding-bottom: 0;
}


.swal-title {
    color: rgba(0,0,0,.65);
    font-weight: 600;
    text-transform: none;
    position: relative;
    display: block;
    padding: 13px 16px;
    font-size: 27px;
    line-height: normal;
    text-align: center;
    margin-bottom: 0;
}
.swal-text {
    font-size: 16px;
    position: relative;
    float: none;
    line-height: normal;
    vertical-align: top;
    text-align: left;
    display: inline-block;
    margin: 0;
    padding: 0 10px;
    font-weight: 400;
    color: rgba(0,0,0,.64);
    max-width: calc(100% - 20px);
    overflow-wrap: break-word;
    box-sizing: border-box;
}
.swal-footer {
    text-align: right;
    padding-top: 13px;
    margin-top: 13px;
    padding: 13px 16px;
    border-radius: inherit;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}

.swal-button-container {
    margin: 5px;
    display: inline-block;
    position: relative;
}

.swal-button {
    background-color: #7cd1f9;
    color: #fff;
    border: none;
    box-shadow: none;
    border-radius: 5px;
    font-weight: 600;
    font-size: 14px;
    padding: 10px 24px;
    margin: 0;
    cursor: pointer;
}

.swal-button__loader {
    position: absolute;
    height: auto;
    width: 43px;
    z-index: 2;
    left: 50%;
    top: 50%;
    -webkit-transform: translateX(-50%) translateY(-50%);
    transform: translateX(-50%) translateY(-50%);
    text-align: center;
    pointer-events: none;
    opacity: 0;
}
.swal-button__loader div {
    display: inline-block;
    float: none;
    vertical-align: baseline;
    width: 9px;
    height: 9px;
    padding: 0;
    border: none;
    margin: 2px;
    opacity: .4;
    border-radius: 7px;
    background-color: hsla(0,0%,100%,.9);
    transition: background .2s;
    -webkit-animation: swal-loading-anim 1s infinite;
    animation: swal-loading-anim 1s infinite;
}
</style>
<div class="swal-overlay swal-overlay--show-modal errmsg" style="display: none;">
  <div class="swal-modal" role="dialog" aria-modal="true"><div class="swal-icon swal-icon--error">
    <div class="swal-icon--error__x-mark">
      <span class="swal-icon--error__line swal-icon--error__line--left"></span>
      <span class="swal-icon--error__line swal-icon--error__line--right"></span>
    </div>
  </div><div class="swal-title" style="">错误</div><div class="swal-text" style="">投注金额最低为1</div><div class="swal-footer"><div class="swal-button-container">

    <button class="swal-button swal-button--confirm sweet-alert-btn-undefined">确定</button>

    <div class="swal-button__loader">
      <div></div>
      <div></div>
      <div></div>
    </div>

  </div></div></div></div>
<style type="text/css">
.swal-icon--success {
    border-color: #a5dc86;
}
.swal-icon--success__line--long {
    width: 47px;
    right: 8px;
    top: 38px;
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
    -webkit-animation: animateSuccessLong .75s;
    animation: animateSuccessLong .75s;
}
.swal-icon--success__line {
    height: 5px;
    background-color: #a5dc86;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 2;
}

.swal-icon--success__line--tip {
    width: 25px;
    left: 14px;
    top: 46px;
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
    -webkit-animation: animateSuccessTip .75s;
    animation: animateSuccessTip .75s;
}

.swal-icon--success__ring {
    width: 80px;
    height: 80px;
    border: 4px solid hsla(98,55%,69%,1);
    border-radius: 50%;
    box-sizing: content-box;
    position: absolute;
    left: -4px;
    top: -4px;
    z-index: 2;
}



.swal-icon--success__hide-corners {
    width: 5px;
    height: 90px;
    background-color: #fff;
    padding: 1px;
    position: absolute;
    left: 28px;
    top: 8px;
    z-index: 1;
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
}

.ghAhQy .first-time {
    text-align: center;
    margin-top: 10px;
}



</style>
<div class="swal-overlay swal-overlay--show-modal successmsg" tabindex="-1" style="display: none;">
  <div class="swal-modal" role="dialog" aria-modal="true"><div class="swal-icon swal-icon--success">
    <span class="swal-icon--success__line swal-icon--success__line--long"></span>
    <span class="swal-icon--success__line swal-icon--success__line--tip"></span>

    <div class="swal-icon--success__ring"></div>
    <div class="swal-icon--success__hide-corners"></div>
  </div><div class="swal-title" style="">成功</div><div class="swal-text" style="">更新成功</div><div class="swal-footer"><div class="swal-button-container">

    <button class="swal-button swal-button--confirm sweet-alert-btn-undefined setlogin">转到登录</button>

    <div class="swal-button__loader">
      <div></div>
      <div></div>
      <div></div>
    </div>

  </div></div></div></div>



<style type="text/css">
.hTJmgb {
    transform: translateX(0px);
    opacity: 1;
    width: 85%;
    height: 100%;
    position: fixed;
    top: 0px;
    right: 0px;
    bottom: 0px;
    z-index: 30;
    box-sizing: border-box;
    overflow: auto;
    background: rgb(255, 255, 255);
    padding: 1rem 0.5rem;
    transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1) 0s, opacity 250ms cubic-bezier(0.4, 0, 0.2, 1) 0s;
}


.hTJmgb .game-div .game-title {
    font-weight: bold;
    font-size: 0.625rem;
    position: relative;
    text-align: center;
    margin: 0.5rem 0.25rem 0.2rem;
    padding: 0px 0.5rem;
}


.hTJmgb .game-div .game-title::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 0px;
    top: 50%;
    left: 0px;
    right: 0px;
    transform: translateY(-50%);
    z-index: -1;
    border-top: 1px solid rgba(180, 180, 180, 0.62);
}

.hTJmgb .game-div .game-title::after {
    content: "";
    position: absolute;
    width: 30%;
    left: 0px;
    right: 0px;
    top: 0px;
    bottom: 0px;
    background-color: rgb(255, 255, 255);
    z-index: -1;
    margin: auto;
}



.hTJmgb .game-div .game-item-wrapper {
    display: flex;
    font-size: 0.625rem;
    flex-flow: row wrap;
}

.hTJmgb .game-div .game-item-wrapper > .game-item {
    cursor: pointer;
    text-align: center;
    height: 1.8rem;
    line-height: 1.8rem;
    background-color: rgb(242, 242, 242);
    -webkit-box-flex: 0;
    flex-grow: 0;
    flex-shrink: 1;
    flex-basis: calc(33.3333% - 0.5rem);
    box-sizing: border-box;
    position: relative;
    border-radius: 0.25rem;
    margin: 0.25rem;
    overflow: hidden;
}

.hTJmgb .game-div .game-item-wrapper > .game-item::before {
    content: "";
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.5rem;
    position: absolute;
    width: 40px;
    height: 12px;
    color: rgb(255, 255, 255);
    top: 0px;
    right: 0px;
    transform: rotate(45deg) translate(26%, -58%);
}

.iULZxB {
    transform: translateX(100%);
    opacity: 0;
    width: 85%;
    height: 100%;
    position: fixed;
    top: 0px;
    right: 0px;
    bottom: 0px;
    z-index: 30;
    box-sizing: border-box;
    overflow: auto;
    background: rgb(255, 255, 255);
    padding: 1rem 0.5rem;
    transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1) 0s, opacity 250ms cubic-bezier(0.4, 0, 0.2, 1) 0s;
}

</style>


<div class="i1h1lp-3 iULZxB gamelist" tabindex="1">
    <div class="game-div">
        <h3 class="game-title">快开彩系列</h3>
        <div class="game-item-wrapper">
            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['gamecs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><span class="game-item" gid="<?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gid']; ?>
"><?php echo $this->_tpl_vars['gamecs'][$this->_sections['i']['index']]['gname']; ?>
</span><?php endfor; endif; ?>
        </div>
    </div>
</div>


<script type="text/javascript">
var style = '<?php echo $this->_tpl_vars['class']; ?>
';//alert(style);
var ngid= <?php echo $this->_tpl_vars['gid']; ?>
;//alert(ngid);
var globalpath = '<?php echo $this->_tpl_vars['globalpath']; ?>
';//alert(globalpath);
var kjurl='<?php echo $this->_tpl_vars['kjurl']; ?>
';//alert(kjurl);
var fenlei='<?php echo $this->_tpl_vars['fenlei']; ?>
';//alert(fenlei);
var type = '<?php echo $this->_tpl_vars['type']; ?>
';//alert(type);
var app='<?php echo $this->_tpl_vars['app']; ?>
';//alert(app);
sma=[];
sma['紅'] = new Array(1,2,7,8,12,13,18,19,23,24,29,30,34,35,40,45,46); 
sma['藍'] = new Array(3,4,9,10,14,15,20,25,26,31,36,37,41,42,47,48); 
sma['綠'] = new Array(5,6,11,16,17,21,22,27,28,32,33,38,39,43,44,49); 
 </script>
</body>
</html>