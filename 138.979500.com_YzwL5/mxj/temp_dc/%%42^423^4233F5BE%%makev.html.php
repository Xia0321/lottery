<?php /* Smarty version 2.6.18, created on 2024-12-22 18:21:31
         compiled from makev.html */ ?>
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

.efUsXr.lastzd{
    width:100%;
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

.epExtR .text {
    margin-right: 8px;
}

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


.exdTne {
    height: 45px;
    left: 50%;
    -webkit-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    transform: translateX(-50%);
    position: fixed;
    top: 0px;
    display: -webkit-inline-box;
    display: -webkit-inline-flex;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    padding: 0 10px;
}

.fRfaQM {
    color: #fff;
    font-size: 0.75rem;
    line-height: 1rem;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    cursor: pointer;
    padding: 0 0.4rem;
    border: 1px solid white;
    border-radius: 3rem;
}


.bfHvQP {
    -webkit-transition: height 0.4s ease-in-out;
    transition: height 0.4s ease-in-out;
    height: 45px;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
}

.bfHvQP :not(:last-child).info_col {
    border-right: solid 1px #ccc;
}

.bfHvQP .info_col {
    display: -webkit-inline-box;
    display: -webkit-inline-flex;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: calc(33.3333%);
    -webkit-transition: -webkit-transform 0.4s ease-in-out;
    -webkit-transition: transform 0.4s ease-in-out;
    transition: transform 0.4s ease-in-out;
    -webkit-transform-origin: left top 0px;
    -ms-transform-origin: left top;
    transform-origin: left top 0px;
    -webkit-transform: scaleY(1);
    -ms-transform: scaleY(1);
    transform: scaleY(1);
    box-sizing: border-box;
}

.bfHvQP .quota_font {
    font-size: 0.9rem;
    color: #666;
}

.bfHvQP .font_color2 {
    font-size: 0.9rem;
    color: rgb(255, 121, 76);
}

.bfHvQP .font_color1 {
    font-size: 0.9rem;
    color: #999;
}

.jdQhcW {
    height: 0px;
    display: flex;
    transition: height 0.4s ease-in-out 0s;
}

.jdQhcW .info_col:not(:last-child) {
    border-right: 1px solid rgb(204, 204, 204);
}
.jdQhcW .info_col {
    display: inline-flex;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    width: calc(33.3333%);
    transform-origin: left top;
    transform: scaleY(0);
    box-sizing: border-box;
    transition: transform 0.4s ease-in-out 0s;
}


.fCZmpJ {
    width: 100%;
    height: 15px;
    background-color: #ebebeb;
    -webkit-box-shadow: 0 0.5px 1px #bbb inset;
    box-shadow: inset 0px 0.5px 1px #bbb;
}
.fCZmpJ .uq_icon {
    width: 62px;
    height: 16px;
    background: url(/css/mobi/img/sprites.png);
    background-size: 355px 110px;
    background-position: -65px 16px;
    -webkit-transform: scale(0.8);
    -ms-transform: scale(0.8);
    transform: scale(0.8);
    -webkit-transform-origin: 50% top 0px;
    -ms-transform-origin: top;
    transform-origin: 50% top 0px;
    margin: -1px auto 0;
}



.resultother {
    height: 35px;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding-left: 15px;
    padding-right: 15px;
    box-sizing: border-box;
    border-bottom: 1px solid #ccc;
}
.resultother .draw {
    display: inline-block;
    line-height: 35px;
    color: #999;
    margin-right: 8px;
    max-width: 120px;
}
.resultother .draw span {
    font-size: 15px;
    color: #1378bd;
}
@media (max-width: 570px) .result161 {
    padding-left: 10px;
    padding-right: 10px;
}

.result161 {
    height: 70px;
    display: flex;
    -webkit-box-pack: justify;
    justify-content: space-between;
    padding-left: 10px;
    padding-right: 10px;
    box-sizing: border-box;
    background-color: rgb(255, 255, 255);
    border-bottom: 1px solid rgb(234, 234, 234);
}

.result161 .draw {
    display: inline-block;
    line-height: 35px;
    color: #999;
    margin-right: 8px;
    max-width: 120px;
}
.result161 .draw span {
    font-size: 15px;
    color: #1378bd;
}
.result161 .kjresult div{
    margin-right: 2px;
}

.hDSAeE {
    display: -webkit-inline-box;
    display: -webkit-inline-flex;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    width: auto;
    margin-right: auto;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-flex-flow: row wrap;
    -ms-flex-flow: row wrap;
    flex-flow: row wrap;
}

.hDSAeE > div {
    margin-right: 2px;
}

.hDSAeE > div:last-child {
    margin-right: 0px;
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
.ncs01{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -126px 1px;
}
.ncs02{
    display: inline-block;
    height: 30px;
    width: 28px;
    background: url(/css/default/img/ball/nc.png) -152px 1px / 487px 60px;
    background-position: -152px 1px;
}
.ncs03{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -179px 1px / 487px 60px;
    background-position: -179px 1px;
}
.ncs04{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -206px 1px;
}
.ncs05{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -233px 1px;
}
.ncs06{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -254px 1px;
}
.ncs07{
    display: inline-block;
    height: 30px;
    width: 28px;
    background: url(/css/default/img/ball/nc.png) -279px 1px / 487px 60px;
    background-position: -279px 1px;
}
.ncs08{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -333px 1px / 487px 60px;
    background-position: -306px 1px;
}

.ncs09{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -333px 1px;
}
.ncs10{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -358px 1px;
}
.ncs11{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -126px -29px;
}

.ncs12{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -153px -29px;
}
.ncs13{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -178px -29px;
}
.ncs14{
    display: inline-block;
    height: 30px;
    width: 28px;
    background: url(/css/default/img/ball/nc.png) -204px -29px / 487px 60px;
    background-position: -204px -29px;
}
.ncs15{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -232px -29px / 487px 60px;
    background-position: -232px -29px;
}
.ncs16{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -257px -29px;
}
.ncs17{
    display: inline-block;
    height: 30px;
    width: 28px;
    background: url(/css/default/img/ball/nc.png) -281px -29px / 487px 60px;
    background-position: -281px -29px;
}
.ncs18{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -309px -29px;
}
.ncs19{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -337px -29px;
}
.ncs20{
    display: inline-block;
    height: 30px;
    width: 26px;
    background: url(/css/default/img/ball/nc.png) -126px 1px / 487px 60px;
    background-position: -360px -29px;
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
    display: inline-flex;
}
.b121 {
    width: 23px;
    height: 23px;
    display: inline-flex;
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
    display: inline-flex;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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
    height: 22px;
    width: 22px;
    line-height: 22px;
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


.jvJTfN {
    height: 35px;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    padding-left: 15px;
    padding-right: 15px;
}
.jvJTfN .draw {
    display: inline-block;
    line-height: 35px;
    color: #999;
    margin-right: 8px;
}

.jvJTfN .draw span {
    font-size: 15px;
    color: #1378bd;
}

.jvJTfN .timer-info {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
}

.jvJTfN .timer-info > div {
    margin-right: 4px;
}

.jvJTfN .close, .jvJTfN .open {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    color: #666;
    font-size: 15px;
}
.jvJTfN .close span {
    margin-left: 3px;
    color: rgb(255, 91, 38);
}
.jvJTfN .open span {
    margin-left: 3px;
    color: #00cd22;
}

.jvJTfN .refresh {
    width: 50px;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: space-around;
    -webkit-justify-content: space-around;
    -ms-flex-pack: space-around;
    justify-content: space-around;
}

.jvJTfN .refresh div {
    background: url(/css/mobi/img/icon_count.png);
    background-position: -10px -248px;
    background-size: 636px 476px;
    height: 30px;
    width: 30px;
    -webkit-transform: scale(0.7);
    -ms-transform: scale(0.7);
    transform: scale(0.7);
    margin-right: -5px;
}

.jvJTfN .refresh span {
    width: 20px;
    text-align: center;
}


.rough_lines {
    width: 100%;
    height: 10px;
    background-color: #ebebeb;
    -webkit-box-shadow: 0px 1px 1px #bbb inset;
    box-shadow: inset 0px 1px 1px #bbb;
}

.kDGwcG {
    -webkit-transition: height 0.4s ease-in-out;
    transition: height 0.4s ease-in-out;
    height: calc(-140px + 100%);
}
.ifxNlC {
    float: left;
    width: 26%;
    background: rgb(0,99,184);
    position: relative;
    height: 100%;
}

.ifxNlC .height_overflow {
    overflow: auto;
    height: 70%;
}

.ifxNlC .lrm_back {
    display: block;
    user-select: none;
    cursor: pointer;
    background: rgb(0, 51, 114);
}

.ifxNlC .lrm_two_sides {
    display: block;
    height: 51px;
    text-align: center;
    font-size: 17px;
    color: #fff;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    cursor: pointer;
}

.ifxNlC .lrm_two_sides > div {
    display: table;
    width: 100%;
    height: 100%;
}

.ifxNlC .lrm_two_sides > div span {
    display: table-cell;
    vertical-align: middle;
    text-align: center;
    word-break: break-all;
    word-wrap: break-word;
}
.ifxNlC a.lrm_two_sides {
    text-decoration: none;
}

.ifxNlC .menu_lines {
    box-shadow: -1px 1px 0px 0.2px rgb(255,255,255), 0px 0px 1px 0.5px rgba(0,0,0,0.67);
    width: 100%;
}

.gVBorT {
    position: relative;
    height: 40px;
    line-height: 36px;
    width: 100%;
    background: white;
}
.gVBorT ul {
    position: absolute;
    width: 100%;
    overflow-x: scroll;
    overflow-y: hidden;
    list-style: none;
    margin: 0px;
    padding: 0px;
}

.gVBorT ul > li {
    font-size: 14px;
    text-align: center;
    display: inline;
    white-space: nowrap;
    list-style: none;
    margin: 0px;
    /*padding: 0px 4px;*/
    width: 25%
}
.gVBorT ul > li > span.active {
    border-width: 1.5px;
    border-style: solid;
    border-color: rgb(4, 186, 238);
    border-image: initial;
    background: white;
}
.gVBorT ul > li > span {
    display: inline-block;
    height: 20px;
    line-height: 20px;
    background: rgb(235, 235, 235);
    /*padding: 0px 5px;*/
    width: 17%
}

.gVBorT .mask-left.hidden, .gVBorT .mask-right.hidden {
    opacity: 0;
}

.gVBorT .mask-left {
    left: 0px;
    background: linear-gradient(-90deg, rgba(255, 255, 255, 0), rgb(255, 255, 255));
}

.gVBorT .mask-left, .gVBorT .mask-right {
    display: block;
    position: absolute;
    top: 0px;
    width: 40px;
    height: 100%;
    opacity: 1;
    user-select: none;
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0s;
}

.gVBorT .mask-right {
    right: 0px;
    background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgb(255, 255, 255));
}


.btIRSJ {
    box-sizing: border-box;
    padding-bottom: 120px;
    float: left;
    width: 74%;
    overflow: auto;
    text-align: center;
    position: relative;
    height: 100%;
    background-color: #ebebeb;
}

.btIRSJ .bcn_center2 {
    background-color: #fff;
    padding: 4px 0;
}
.btIRSJ .bcn_title {
    text-align: center;
    height: 25px;
    padding-top: 0.6rem;
    position: relative;
}
.btIRSJ .bt_icon.open {
    -webkit-transform: rotate(180deg) scale(0.9);
    -ms-transform: rotate(180deg) scale(0.9);
    transform: rotate(180deg) scale(0.9);
}
.btIRSJ .bcn_number_type {
    overflow: hidden;
    padding: 4px 0;
    -webkit-transition: height 0.2s ease-in-out,opacity 0.2s ease-in;
    transition: height 0.2s ease-in-out,opacity 0.2s ease-in;
}

.btIRSJ .bt_icon.open {
    transform: rotate(180deg) scale(0.9);
}

.btIRSJ .bt_icon {
    display: block;
    float: left;
    position: absolute;
    top: 3px;
    left: 14px;
    height: 30px;
    width: 30px;
    transform: scale(0.9);
    background: url(/css/mobi/img/sprites.png) -261px 30px / 485px 145px;
    background-position: -261px 30px;
    transition: transform 0.2s ease-in-out 0s;
}

.hokpMe {
    margin-top: 5px;
    color: #000;
    font-size: 0.8rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
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
    /*-webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;*/
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
    float: left;
}
.qiud {
    width: 40.2%;
    min-height: 40px;
    height: auto;
    display: -webkit-inline-box;
    display: -webkit-inline-flex;
    display: -ms-inline-flexbox;
    display: inline-flex;
    /*-webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;*/
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
    float: left;
}

.qiua {
    width: 40.2%;
    min-height: 50px;
    height: auto;
    display: inline-flex;
    flex-direction: column;
    -webkit-box-pack: center;
    justify-content: center;
    border: 1px solid rgb(204, 204, 204);
    text-align: center;
    border-radius: 5px;
    margin: 0px 3% 5%;
    box-sizing: border-box;
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
    border: 1px solid rgb(204, 204, 204);
    text-align: center;
    border-radius: 5px;
    margin: 0px 3% 5%;
    box-sizing: border-box;
    user-select: none;
    vertical-align: middle;
    cursor: pointer;
    background: initial;
}
.qiuc {
width: 25%;
    min-height: 50px;
    height: auto;
    display: inline-flex;
    flex-direction: column;
    -webkit-box-pack: center;
    justify-content: center;
    border: 1px solid rgb(204, 204, 204);
    text-align: center;
    border-radius: 5px;
    margin: 0px 3% 5%;
    box-sizing: border-box;
    user-select: none;
    vertical-align: middle;
    cursor: pointer;
    background: initial;
}
.qiua .b_odds,.qiub .b_odds,.qiuc .b_odds,.qiud .b_odds {
color: rgb(255, 77, 77);
    font-size: 16px;
}
.bcn_center2 .b_text {
margin-top: 5px;
    color: rgb(102, 102, 102);
    font-size: 0.8rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}


.iJamhB.bet-page-full {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0px;
    left: 0px;
    right: 0px;
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

.main-content {
    height: calc(-80px + 100%);
    position: fixed;
    top: 45px;
    width: 100%;
}
.main-content > div {
    height: 100%;
}
.ettwvL .table-header {
    height: 50px;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    border-bottom: 1px solid #ccc;
    border-top: 1px solid #ccc;
    background-color: #fff;
}
.ettwvL .table-header > div {
    color: #666;
    border-left: 1px solid #ccc;
    box-sizing: border-box;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}

.ettwvL .table-content {
    overflow: auto;
    -webkit-flex: 1;
    -ms-flex: 1;
    flex: 1;
}

.dmUHEy {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0px;
    left: 0px;
    z-index: 3;
}

.bOxvrp {
    text-align: center;
    line-height: 45px;
    position: relative;
    top: 0px;
    left: 0px;
    width: 100%;
    color: white;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px 0px;
    z-index: 10;
    background: linear-gradient(135deg, rgb(19, 46, 123) 0%, rgb(0, 201, 202) 100%);
}

.bOxvrp .backButton {
    position: absolute;
    left: 0px;
    top: 0px;
    padding: 0px 10px;
}

.bOxvrp .title {
    height: 45px;
    font-size: 1rem;
}

.fpPAXM {
    display: flex;
    flex-direction: row;
    -webkit-box-pack: center;
    justify-content: center;
}

.bcFIVc {
    width: 20px;
    height: 10px;
    display: inline-block;
    vertical-align: middle;
    transform: rotate(90deg);
    background: url(/css/mobi/img/sprites.png) -34px -117px / 410px 130px no-repeat;
    background-position: -34px -117px;
    transition: transform 0.1s ease-out 0s;
}

.hCMaPW.active {
    border-bottom: 3px solid white;
}

.hCMaPW {
    width: 50%;
    height: 40px;
    font-size: 0.8rem;
    line-height: 40px;
    box-sizing: border-box;
    text-align: center;
}

.kkjbvG {
    position: relative;
    display: block;
    background-color: rgb(248, 249, 251);
    height: calc((100vh - 45px) - 40px);
    overflow-x: hidden;
    overflow-y: scroll;
}
.kkjbvG > div {
    height: 100%;
}
.WQWtT {
    height: 100%;
    overflow: auto;
}
.WQWtT > ul {
    margin: 0px;
    padding: 0px;
}
.WQWtT > ul > li {
    clear: both;
    overflow: hidden;
    background: white;
    border-bottom: 1px solid rgb(238, 238, 238);
}

.WQWtT > ul > li > div:first-child {
    float: left;
    width: 50%;
    text-align: center;
    height: 45px;
    line-height: 45px;
    font-size: 18px;
}

.WQWtT > ul > li > div:last-child {
    float: right;
    width: 49.5%;
    text-align: center;
    height: 45px;
    line-height: 45px;
    font-size: 18px;
    color: red;
    border-left: 1px solid rgb(238, 238, 238);
}

.csoFvQ {
    height: 100%;
    overflow: auto;
}
.jKrOPS {
    text-align: center;
    height: 50px;
    line-height: 50px;
    font-size: 22px;
    background: white;
}
.hEaWLh ul {
    clear: both;
    overflow: hidden;
    background: white;
    margin: 0px;
    padding: 0px;
}

.hEaWLh ul > li.active {
    background: rgb(0, 68, 119);
}
.hEaWLh ul > li.active > a {
    color: white;
}
.lzr li.chu{
    color:orange;
}

.hEaWLh ul > li {
    width: 20%;
    float: left;
    text-align: center;
    height: 50px;
    line-height: 50px;
    box-sizing: border-box;
    font-size: 16px;
    border-width: 0.5px;
    border-style: solid;
    border-color: rgb(234, 234, 234);
    border-image: initial;
}

.hkLcFi ul {
    clear: both;
    margin: 0px;
    padding: 0px;
    overflow: hidden;
    background: white;
}
.hkLcFi ul > li {
    width: 10%;
    float: left;
    height: 50px;
    box-sizing: border-box;
    padding-left: 5px;
    padding-top: 12px;
    line-height: 18px;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(234, 234, 234);
    border-image: initial;
}
.iabIyi ul {
    clear: both;
    margin: 0px;
    padding: 0px;
    overflow: hidden;
    background: white;
}
.iabIyi ul > li {
    width: 10%;
    float: left;
    height: 50px;
    box-sizing: border-box;
    text-align: center;
    line-height: 50px;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(234, 234, 234);
    border-image: initial;
}
.jKrOPS {
    text-align: center;
    height: 50px;
    line-height: 50px;
    font-size: 22px;
    background: white;
}
.egcjFr ul {
    clear: both;
    overflow: hidden;
    background: white;
    margin: 0px;
    padding: 0px;
}
.egcjFr ul > li.active {
    background: rgb(0, 68, 119);
}
.egcjFr ul > li {
    width: 20%;
    float: left;
    text-align: center;
    height: 50px;
    line-height: 50px;
    box-sizing: border-box;
    font-size: 16px;
    border-width: 0.5px;
    border-style: solid;
    border-color: rgb(234, 234, 234);
    border-image: initial;
}
.egcjFr ul > li.active > a {
    color: white;
}
.kPKqVh > ul {
    clear: both;
    display: flex;
    flex-direction: row-reverse;
    flex-wrap: wrap;
    margin: 0px;
    padding: 0px;
    overflow: hidden;
    background: white;
}

.kPKqVh > ul > li:nth-of-type(2n+1) {
    color: rgb(0, 68, 119);
}
.kPKqVh > ul > li:nth-of-type(2n) {
    color: rgb(243, 129, 2);
}

.kPKqVh > ul > li {
    width: 10%;
    float: left;
    text-align: center;
    line-height: 45px;
    box-sizing: border-box;
    font-size: 16px;
    border-width: 0.5px;
    border-style: solid;
    border-color: rgb(234, 234, 234);
    border-image: initial;
}
.tongjidiv li{
    list-style: none;
}

.dvoVQg.bet-page.short-list {
    bottom: 0px;
    width: 100%;
    position: absolute;
    height: initial;
    top: initial;
    background: #fff;
    z-index: 30;
}
.dvoVQg.bet-page {
    transform: translate3d(0px, 0px, 0px);
    background-color: rgb(255, 255, 255);
    z-index: 30;
    box-shadow: none;
    border-radius: 10px 10px 0px 0px;
    animation: 300ms cubic-bezier(0.4, 0, 0.2, 1) 0s 1 normal forwards running fadeInUp;
}
.eoLeHJ > ul > li.btn {
    margin: 0px;
    list-style: none;
}
.eoLeHJ > ul > li {
    width: 16.66%;
    float: left;
    font-size: 12px;
    height: 24px;
    box-sizing: border-box;
    padding: 0px 5px;
}
.eoLeHJ > ul > li.btn > a {
    width: 100%;
    text-align: center;
    height: 22px;
    display: inline-block;
    line-height: 21px;
    font-size: 12px;
    border-radius: 12px;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(4, 186, 238);
    border-image: initial;
}

.eoLeHJ > ul > li.btn-edit > a {
    text-align: center;
    height: 22px;
    display: inline-block;
    line-height: 21px;
    font-size: 12px;
}
.jNzwRs {
    width: 100%;
    position: relative;
    margin: 0px auto;
}
.jNzwRs > .payment-footer {
    height: 90px;
    padding-top: 27px;
    box-shadow: rgba(0, 0, 0, 0.1) 0px -8px 16px;
    margin: auto;
}
.jNzwRs > .payment-footer .sperate-line {
    border-bottom: 1px solid rgb(234, 234, 234);
    border-top: none;
    border-left: none;
    border-right: none;
    padding: 0px;
    margin: 8px 10px;
}
.jNzwRs > .payment-footer .bets .bets-input-value {
    width: calc(55% - 80px);
    height: 32px;
    box-sizing: border-box;
    float: left;
    margin-right: 5px;
    border-radius: 24px;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(118, 118, 118);
    border-image: initial;
    padding: 0px 10px;
}
.jNzwRs > .payment-footer .bets {
    height: 32px;
    padding: 0px 10px;
}
.jNzwRs > .payment-footer .bets .cancel-btn {
    font-family: Tahoma;
    color: red;
    font-size: 14px;
    float: left;
    margin-right: 8px;
    margin-top: 6px;
    font-weight: bold;
}
.jNzwRs > .payment-footer .bets .result-btn {
    color: white;
    font-size: 14px;
    font-weight: bold;
    width: calc(45% - 22px);
    white-space: nowrap;
    height: 32px;
    background: linear-gradient(121deg, rgb(76, 152, 242), rgb(70, 123, 185), rgb(65, 102, 144)) white;
    border-width: 1px;
    border-style: solid;
    border-color: initial;
    border-image: initial;
    padding: 5px 11px;
    border-radius: 25px;
}
.fhCqRY {
    width: 52px;
    height: 32px;
    display: inline-block;
    float: left;
    margin-right: 8px;
}
.fhCqRY .styled-checkbox {
    position: absolute;
    opacity: 0;
}
input[type="checkbox" i] {
    background-color: initial;
    cursor: default;
    -webkit-appearance: checkbox;
    box-sizing: border-box;
    margin: 3px 3px 3px 4px;
    padding: initial;
    border: initial;
}
.fhCqRY .styled-checkbox + label::before {
    content: "";
    margin-right: 0px;
    display: inline-block;
    vertical-align: text-top;
    width: 20px;
    height: 20px;
    background: white;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(221, 221, 221);
    border-image: initial;
    border-radius: 4px;
}
.fhCqRY .styled-checkbox:checked + label::after {
    content: "";
    position: absolute;
    left: 6px;
    top: 10px;
    width: 2px;
    height: 2px;
    box-shadow: rgb(118, 118, 118) 2px 0px 0px, rgb(118, 118, 118) 4px 0px 0px, rgb(118, 118, 118) 4px -2px 0px, rgb(118, 118, 118) 4px -4px 0px, rgb(118, 118, 118) 4px -6px 0px, rgb(118, 118, 118) 4px -8px 0px;
    transform: rotate(45deg);
    background: rgb(118, 118, 118);
}
.fhCqRY .styled-checkbox + label {
    position: relative;
    cursor: pointer;
    float: left;
    top: 6px;
    padding: 0px;
}
.fhCqRY > ul {
    float: right;
    font-size: 12px;
    margin: 1px 0px 0px;
    padding: 0px;
}
.fhCqRY > ul > li {
    line-height: 14px;
}
.fhCqRY li{
    list-style: none;
}
.eoLeHJ {
    line-height: 22px;
    overflow: hidden;
    padding: 0px 6px;
}
.eoLeHJ > ul {
    margin: 0px;
    padding: 0px;
    list-style: none;
}
.eoLeHJ > ul > li.btn-edit {
    text-align: center;
    margin: 0px;
    list-style: none;
}
.gioHal {
    position: relative;
}

.gioHal .bet-list-content {
    display: none;
}
.ilDBjU {
    height: 45px;
    position: relative;
    width: 100%;
    background: linear-gradient(135deg, rgb(19, 46, 123) 0%, rgb(0, 201, 202) 100%);
}
.gioHal .pn_title {
    font-size: 14px;
}

.ilDBjU .pn_title {
    text-align: center;
    line-height: 45px;
    font-size: 18px;
    color: rgb(255, 255, 255);
    display: flex;
}
.ilDBjU .pn_title .close-toggle {
    width: 20px;
    height: 10px;
    display: inline-block;
    vertical-align: middle;
    position: absolute;
    top: 18px;
    left: 10px;
    background: url(/css/mobi/img/sprites.png) -34px -117px / 410px 130px no-repeat;
    background-position: -34px -117px;
}

.ilDBjU .pn_title .header-right-btn {
    float: right;
    color: white;
    cursor: pointer;
    margin: 0px 10px auto;
}
.ilDBjU .pn_title .btn-outline {
    padding: 0.25rem 0.5rem;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(255, 255, 255);
    border-image: initial;
    border-radius: 2rem;
}
.mPmJb {
    background-color: rgb(235, 235, 235);
    height: 180px;
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

.resize-triggers {
    animation: 1ms resizeanim;
    visibility: hidden;
    opacity: 0;
}
.gioHal .bets-showList-btn {
    position: absolute;
    bottom: -14px;
    left: calc(50% - 44px);
    font-size: 12px;
    width: 88px;
    height: 22px;
    color: white;
    text-align: center;
    line-height: 22px;
    z-index: 20;
    background: rgb(158, 158, 158);
    border-radius: 12px;
}
..jNzwRs {
    width: 100%;
    position: relative;
    margin: 0px auto;
}
.jNzwRs > .payment-footer {
    height: 90px;
    padding-top: 27px;
    box-shadow: rgba(0, 0, 0, 0.1) 0px -8px 16px;
    margin: auto;
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
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease-in-out 0s;
}

</style>
<script language="javascript" src="/js/jquery-1.8.3.min.js"></script>
<script language="javascript" src="/js/jquery.cookie.js"></script>
</head>
<body >
<script id=myjs language="javascript">var mulu="<?php echo $this->_tpl_vars['mulu']; ?>
";
var js=1;var sss='makev';
</script>    
<div id="root">
	<div class="home">
		<div class="oc2pj9-1 ilDBjU tops">
			<div class="pn_title">
				<div style="flex:1 1 0%;">
				</div>
				<div style="flex:1 1 0%;">
					<div style="color: white;">
					</div>
				</div>
				<div style="flex:1 1 0%;">
					<div class="menu">
					</div>
				</div>
			</div>
		</div>
        <div class="backtz" style='display: none;'><div class="sc-1rpj7be-0 dlrSez"><div class="sc-1rpj7be-1 bdqtbC back"></div></div></div>
<div class="bet-page short-list slevm5-2 dvoVQg tz" style="height: 0px;display: none;">
    <div class="sc-1kapr6r-4 gioHal">
        <div class="bet-list-content ">
            <div class="oc2pj9-1 ilDBjU">
                <div class="pn_title">
                    <a class="close-toggle"></a>
                    <div style="flex: 1 1 0%;">
                    </div>
                    <div style="flex: 1 1 0%;">
                        北京赛车(PK10)
                    </div>
                    <div style="flex: 1 1 0%;">
                        <a>
                        <div class="header-right-btn">
                            <span class="btn-outline">编辑</span>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="sc-1kapr6r-1 mPmJb" style="position: relative;">
                <div style="overflow: visible; height: 0px; width: 0px;">
                    <div aria-label="grid" aria-readonly="true" class="ReactVirtualized__Grid ReactVirtualized__List" role="grid" tabindex="0" style="box-sizing: border-box; direction: ltr; height: 0px; position: relative; width: 0px; will-change: transform; overflow: hidden auto;">
                    </div>
                </div>
                <div class="resize-triggers">
                    <div class="expand-trigger">
                        <div style="width: 1px; height: 1px;">
                        </div>
                    </div>
                    <div class="contract-trigger">
                    </div>
                </div>
            </div>
        </div>
        <a class="bets-showList-btn tzzs">已选0注</a>
    </div>
    <div class="sc-1kapr6r-5 jNzwRs">
        <div class="payment-footer">
            <div class="sc-1kapr6r-2 eoLeHJ">
                <ul class="jelist">
                    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['fastje']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    <li class="btn"><a><?php echo $this->_tpl_vars['fastje'][$this->_sections['i']['index']]['je']; ?>
</a></li>
                    <?php endfor; endif; ?>
                    <li class="btn-edit editje"><a>编辑</a></li>
                </ul>
            </div>
            <hr class="sperate-line">
            <div class="bets">
                <input class="bets-input-value je" placeholder="输入金额" min="0" type="number" pattern="\d*" step="1" value="">
                <div class="sc-1kapr6r-3 fhCqRY">
                    <input class="styled-checkbox yushei" type="checkbox" id="enablePresetAmount"><label for="enablePresetAmount" class="yushe"></label>
                    <ul>
                        <li>预设</li>
                        <li>金额</li>
                    </ul>
                </div>
                <a class="cancel-btn">取消</a>
                <a class="result-btn jeqr" v="0">确认 0</a>
            </div>
        </div>
    </div>
    <div class="slevm5-0 ivfTfC" data-show="false">
    </div>
</div>



		<div class="main-content">
       
<!--长龙-->
<style type="text/css">

.dlrSez {
    position: relative;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 45px;
    background-image: linear-gradient(135deg, rgb(19, 46, 123) 0%, rgb(0, 201, 202) 100%);
    z-index: 10;
    display: flex;
    flex-direction: row;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
    box-sizing: border-box;
    padding: 0px 12px;
    flex: 0 0 auto;
}

.bdqtbC {
    font-size: 14px;
    flex: 0 0 50%;
}

.bdqtbC::before {
    content: "";
    vertical-align: middle;
    display: inline-block;
    width: 14px;
    height: 14px;
    margin-right: 4px;
    background: url(/css/mobi/img/ic_back.png) center center / contain no-repeat;
}

.bdqtbC::after {
    content: "返回";
    color: rgb(255, 255, 255);
    font-size: 14px;
    vertical-align: middle;
}
.eXdSpM {
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: auto;
    background: linear-gradient(rgb(235, 235, 235), 30%, white 100%);
}
.gSbRmf {
    height: 45px;
    display: flex;
    transition: height 0.4s ease-in-out 0s;
    flex: 0 0 auto;
}
.gSbRmf .quota_font {
    font-size: 0.9rem;
    color: rgb(102, 102, 102);
}
.gSbRmf .info_col:not(:last-child) {
    border-right: 1px solid rgb(204, 204, 204);
}
.gSbRmf .info_col {
    display: inline-flex;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    width: calc(33.3333%);
    transform-origin: left top;
    transform: scaleY(1);
    box-sizing: border-box;
    transition: transform 0.4s ease-in-out 0s;
}
.dRtNjK {
    height: 0px;
    display: flex;
    transition: height 0.4s ease-in-out 0s;
    flex: 0 0 auto;
}
.gSbRmf .font_color1 {
    font-size: 0.9rem;
    color: rgb(153, 153, 153);
}
.dRtNjK .info_col:not(:last-child) {
    border-right: 1px solid rgb(204, 204, 204);
}
.dRtNjK .info_col {
    display: inline-flex;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    width: calc(33.3333%);
    transform-origin: left top;
    transform: scaleY(0);
    box-sizing: border-box;
    transition: transform 0.4s ease-in-out 0s;
}
.dRtNjK .quota_font {
    font-size: 0.9rem;
    color: rgb(102, 102, 102);
}
.dRtNjK .font_color1 {
    font-size: 0.9rem;
    color: rgb(153, 153, 153);
}
.fCZmpJ {
    width: 100%;
    height: 15px;
    background-color: rgb(235, 235, 235);
    box-shadow: rgb(187, 187, 187) 0px 0.5px 1px inset;
}

.fCZmpJ .uq_icon {
    width: 62px;
    height: 16px;
    transform: scale(0.8);
    transform-origin: center top;
    background: url(/css/mobi/img/sprites.png) -65px 16px / 355px 110px;
    background-position: -65px 16px;
    margin: -1px auto 0px;
}

.hSCNUx {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    flex: 0 0 auto;
}
.hiSYfC {
    font-size: 13px;
    color: grey;
    margin: 10px;
}
.iEjSqT {
    display: flex;
    width: 40px;
    height: 25px;
    -webkit-box-align: center;
    align-items: center;
}

.krtGYb {
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
    width: 100%;
    padding-right: 8px;
    height: 25px;
    background-color: white;
    border-radius: 5px;
}
.krtGYb > select {
    width: 100%;
    font-size: 11px;
    font-weight: bold;
    color: grey;
    left: 5px;
    background-color: transparent;
    position: relative;
    -webkit-appearance: none;
    border-width: 0px;
    border-style: initial;
    border-color: initial;
    border-image: initial;
    outline: none;
}
.krtGYb > select option {
    font-weight: normal;
    display: block;
    white-space: pre;
    min-height: 1.2em;
    padding: 0px 2px 1px;
}
.lmqiWT {
    width: 100%;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    flex-direction: column;
    overflow: auto;
}
.ifVizG {
    width: 100%;
    height: 200px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    flex-direction: column;
    margin-top: 50px;
    flex: 0 0 auto;
}
.cBIjaF {
    width: 85%;
    height: 135px;
    margin-top: 8px;
    margin-bottom: 8px;
    border-width: 1px;
    border-style: solid;
    border-color: lightgrey;
    border-image: initial;
}

.hBroCu {
    color: white;
    position: relative;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
    margin-bottom: -22px;
}

.hBroCu > .title {
    margin-left: 10px;
    font-size: 14px;
    font-weight: bold;
}

.hBroCu > .tag-text {
    font-size: 10px;
    margin-right: 10px;
}

.iTeeJf {
    height: 27px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: end;
    justify-content: flex-end;
    background-color: rgb(62, 62, 62);
}

.fKDGpr {
    width: 124px;
    height: 27px;
}

.gKQGuI {
    width: 100%;
    height: 110px;
    background-color: white;
    display: flex;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
}

.bDezmW {
    width: 95%;
    padding-top: 5px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
}

.dCWcvu {
    color: grey;
    font-size: 12px;
}
.dhKHfI {
    display: flex;
    flex-direction: row;
    -webkit-box-align: center;
    align-items: center;
}

text {
    display: block;
    white-space: nowrap;
}
tspan {
    white-space: inherit;
}


.pk-icon {
    width: calc(52%);
}

.jFmBfH {
    color: lightgrey;
    font-size: 9px;
    padding-left: 3px;
}
.gcvrPj {
    width: 95%;
    margin-top: 5px;
    margin-bottom: 8px;
    display: flex;
    flex-direction: row;
    align-items: flex-end;
}

.gYsRSk {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}
.gYsRSk > .red100 {
    width: calc(0% + 13px);
    margin-right: -6px;
}
.hjBytt {
    width: 100%;
    height: 2px;
    background: rgb(213, 16, 0);
}
.hjBytt .colorr{
    width: 50%;
    height: 2px;
    float: right;
    background: rgb(0, 108, 216);
}
.iOjRYn {
    width: 95%;
    display: flex;
    flex-direction: row;
    -webkit-box-pack: justify;
    justify-content: space-between;
    margin: 2px;
}

.fwuVtQ {
    width: 47%;
    height: 30px;
    display: flex;
    position: relative;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    border-width: 1px;
    border-style: solid;
    border-color: lightgrey;
    border-image: initial;
}
.dCWcvu.red {
    color: rgb(213, 16, 0);
}
.dCWcvu.blue {
    color: rgb(0, 108, 216);
}
.dCWcvu {
    color: grey;
    font-size: 12px;
}
.loWSJd {
    width: 100%;
    height: inherit;
    display: flex;
    position: absolute;
    align-items: flex-end;
    -webkit-box-pack: end;
    justify-content: flex-end;
}
.loWSJd.isSelected {
    border-width: 2px;
    border-style: solid;
    border-color: rgb(53, 168, 224);
    border-image: initial;
}

.loWSJd.isSelected::before {
    content: "";
    display: inline-block;
    width: 6px;
    height: 3px;
    transform: rotate(125deg);
    margin-right: -12px;
    margin-bottom: 3px;
    border-top: 1.5px solid rgb(255, 255, 255);
    border-right: 1.5px solid rgb(255, 255, 255);
}
.loWSJd.isSelected::after {
    content: "";
    right: 1px;
    bottom: 1px;
    border-bottom: 12px solid rgb(53, 168, 224);
    border-left: 12px solid transparent;
}



</style>
<div class="qhowzd-0 eXdSpM clmake" style="display: none;">
    <div class="bet-page-full slevm5-1 lastzd iJamhB" style="height: 100%;" data-show="false">
                    <div class="oc2pj9-1 ilDBjU">
                        <div class="pn_title">
                            <a>
                            <div class="lb_back">
                            </div>
                            </a>
                            <div style="flex:1 1 0%;">
                            </div>
                            <div style="flex:1 1 0%;">
                                注单列表
                            </div>
                            <div style="flex:1 1 0%;">
                            </div>
                        </div>
                    </div>
                    <div class="main-content">
                        <div class="sc-1fs8umj-0 ettwvL">
                            <div class="report-table table-header flex">
                                <div class="col col2">
                                    注单号
                                </div>
                                <div class="col col2">
                                    期数
                                </div>
                                <div class="col col2">
                                    玩法
                                </div>
                                <div class="col col2">
                                    金额
                                </div>
                            </div>
                            <div class="report-table table-content lastzdlist">
                           </div>

                        </div>
                    </div>

                    

                </div>
    <div class="sc-1x3n9hq-0 gSbRmf edus">
        <div class="info_col">
            <div class="quota_font">
                快开彩额度
            </div>
            <div class="font_color1 money">
                <?php if (( $this->_tpl_vars['gid'] != 100 || $this->_tpl_vars['fudong'] == 1 )): ?><?php echo $this->_tpl_vars['kmoney']; ?>
<?php else: ?><?php echo $this->_tpl_vars['money']; ?>
<?php endif; ?>
            </div>
        </div>
        <div class="info_col">
            <div class="quota_font">
                全国彩额度
            </div>
            <div class="font_color1">
                0.0
            </div>
        </div>
        <div class="info_col">
            <div class="quota_font">
                香港彩额度
            </div>
            <div class="font_color1">
                0.0
            </div>
        </div>
    </div>
    <div class="sc-1x3n9hq-1 fCZmpJ">
        <div class="uq_icon">
        </div>
    </div>
    <div class="qhowzd-1 hSCNUx">
        <div class="qhowzd-2 hiSYfC">
            长龙连开期数
        </div>
        <div class="sc-2n9q9z-0 iEjSqT">
            <div class="sc-2n9q9z-1 krtGYb">
                <select>
                    <option value="6">6</option>
                    <option value="8">8</option>
                    <option value="10">10</option>
                    <option value="12">12</option>
                </select>
            </div>
        </div>
    </div>
    <div class="qhowzd-3 lmqiWT cls">
        
    </div>
    <div class="qhowzd-3 lmqiWT nocl" style="display:none;">
    <div class="qhowzd-4 ifVizG">
        <svg width="118.535" height="128.743"><defs>
        <style>
.prefix__a{fill:#e3e3e3}
        </style>
        </defs><path class="prefix__a" d="M112.747 44.709l-12.69-5.91a10 10 0 00-13.29 4.83l-5.91 12.72a10 10 0 004.83 13.29l12.69 5.92a10 10 0 0013.29-4.84l5.92-12.69a10 10 0 00-4.84-13.32zm-6 15.32l-3.48 1.12a2.18 2.18 0 00-1.32 1.2l-1.26 3.66c-.18.52-.57.56-.87.1l-2-3.07a2 2 0 00-1.55-.78h-3.65c-.55 0-.72-.35-.39-.78l2.36-3.07a2.25 2.25 0 00.33-1.75l-1-3.59c-.15-.52.15-.81.67-.62l3.38 1.2a2.09 2.09 0 001.75-.26l3.06-2.27c.44-.33.78-.15.75.4l-.21 3.83a2.1 2.1 0 00.75 1.6l2.87 2.16c.44.35.33.76-.16.92zM44.647 53.489a10 10 0 00-13.66-3.66l-26 15a10 10 0 00-3.64 13.66l15 26a10 10 0 0013.66 3.66l26-15a10 10 0 003.66-13.66zm-6.38 27.14l-3.64.29a2 2 0 00-1.46 1l-1.68 3.23c-.26.49-.65.48-.88 0l-1.59-3.53a2.23 2.23 0 00-1.39-1.12l-3.64-.78c-.54-.12-.64-.52-.24-.89l2.65-2.48a2.1 2.1 0 00.6-1.66l-.56-3.78c-.08-.54.24-.75.71-.47l3.26 2a2.09 2.09 0 001.76.09l3.26-1.51c.5-.23.82 0 .72.57l-.69 3.66a2.2 2.2 0 00.49 1.72l2.63 2.83c.37.41.23.78-.31.83zM105.807 93.819a10 10 0 00-11.54-8.11l-19.7 3.48a10 10 0 00-8.11 11.59l3.48 19.7a10 10 0 0011.59 8.11l19.7-3.48a10 10 0 008.04-11.59zm-11.2 9l-2 3.18a2.26 2.26 0 00-.15 1.78l1.48 3.57c.21.51-.06.8-.58.65l-3.51-1a2 2 0 00-1.7.38l-2.73 2.42c-.42.37-.78.22-.81-.33l-.23-3.86a2.23 2.23 0 00-.9-1.54l-3.12-2c-.46-.3-.42-.71.09-.91l3.35-1.3a2.13 2.13 0 001.15-1.34l.82-3.73c.12-.54.49-.62.83-.19l2.34 3a2.11 2.11 0 001.61.72l3.58-.25c.55-.04.77.28.48.78zM78.357 37.049l5.18-19.32a10 10 0 00-7.07-12.25L57.147.349a10 10 0 00-12.25 7l-5.18 19.34a10 10 0 007.07 12.25l19.32 5.18a10 10 0 0012.25-7.07zm-12.49-11.43l-.46 3.83c-.07.55-.44.68-.83.29l-2.58-2.58a2 2 0 00-1.68-.49l-3.56.81c-.53.12-.78-.18-.54-.68l1.65-3.45a2.23 2.23 0 000-1.79l-1.8-3.32c-.26-.49 0-.83.52-.76l3.56.47a2.12 2.12 0 001.65-.6l2.52-2.88c.36-.41.73-.3.81.24l.6 3.77a2.13 2.13 0 001.07 1.41l3.26 1.46c.5.23.52.65 0 .92l-3.24 1.84a2.21 2.21 0 00-.95 1.51z"></path></svg>
        <div class="qhowzd-2 hiSYfC">
            对不起，你现暂无任何长龙...
        </div>
    </div>
</div>
</div>

<!--长龙-->

<!--遗漏-->
<style type="text/css">

.bMzKjr {
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: auto;
    background: linear-gradient(rgb(235, 235, 235), 30%, white 100%);
}
.iYWXuL {
    width: 100%;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    flex-direction: column;
    overflow: auto;
}

.bfCaQY {
    width: 85%;
    height: 110px;
    margin-top: 8px;
    margin-bottom: 8px;
    border-width: 1px;
    border-style: solid;
    border-color: lightgrey;
    border-image: initial;
}

.eJDhF {
    color: white;
    position: relative;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
    margin-bottom: -22px;
}

.eJDhF > .title {
    margin-left: 10px;
    font-size: 14px;
    font-weight: bold;
}

.eJDhF > .tag-text {
    font-size: 10px;
    margin-right: 10px;
}

.cmGoYq {
    height: 27px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: end;
    justify-content: flex-end;
    background-color: rgb(62, 62, 62);
}

.gLqcai {
    width: 124px;
    height: 27px;
}

.jtVbVs {
    width: 100%;
    height: 80px;
    background-color: white;
    display: flex;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
}

.lgZVIB {
    width: 95%;
    padding-top: 5px;
    display: flex;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
}

.dnJhva {
    display: flex;
    flex-direction: row;
    -webkit-box-align: center;
    align-items: center;
}


svg:not(:root) {
    overflow: hidden;
}


:not(svg) {
    /*transform-origin: 0px 0px;*/
}

.lmYNBz {
    color: lightgrey;
    font-size: 9px;
    padding-left: 3px;
}

.bjLcqY {
    width: 95%;
    margin-top: 8px;
    display: flex;
    flex-direction: row;
    -webkit-box-pack: justify;
    justify-content: space-between;
}

.fKFcGo {
    width: 30%;
    height: 25px;
    display: flex;
    position: relative;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    border-width: 1px;
    border-style: solid;
    border-color: lightgrey;
    border-image: initial;
}
.duvsQC.green {
    color: rgb(0, 190, 109);
}

.duvsQC {
    color: grey;
    font-size: 12px;
}
.duvsQC.red {
    color: rgb(213, 16, 0);
}
.duvsQC.blue {
    color: rgb(0, 108, 216);
}
.emmWlu {
    width: 100%;
    height: inherit;
    display: flex;
    position: absolute;
    align-items: flex-end;
    -webkit-box-pack: end;
    justify-content: flex-end;
} 
.emmWlu.isSelected {
    border-width: 2px;
    border-style: solid;
    border-color: rgb(53, 168, 224);
    border-image: initial;
}
.emmWlu.isSelected::before {
    content: "";
    display: inline-block;
    width: 6px;
    height: 3px;
    transform: rotate(125deg);
    margin-right: -12px;
    margin-bottom: 3px;
    border-top: 1.5px solid rgb(255, 255, 255);
    border-right: 1.5px solid rgb(255, 255, 255);
}
.emmWlu.isSelected::after {
    content: "";
    right: 1px;
    bottom: 1px;
    border-bottom: 12px solid rgb(53, 168, 224);
    border-left: 12px solid transparent;
}   
</style>
<div class="sc-1yr5q98-0 bMzKjr ylmake" style="display: none;">
    <div class="bet-page-full slevm5-1 lastzd iJamhB" style="height: 100%;" data-show="false">
                    <div class="oc2pj9-1 ilDBjU">
                        <div class="pn_title">
                            <a>
                            <div class="lb_back">
                            </div>
                            </a>
                            <div style="flex:1 1 0%;">
                            </div>
                            <div style="flex:1 1 0%;">
                                注单列表
                            </div>
                            <div style="flex:1 1 0%;">
                            </div>
                        </div>
                    </div>
                    <div class="main-content">
                        <div class="sc-1fs8umj-0 ettwvL">
                            <div class="report-table table-header flex">
                                <div class="col col2">
                                    注单号
                                </div>
                                <div class="col col2">
                                    期数
                                </div>
                                <div class="col col2">
                                    玩法
                                </div>
                                <div class="col col2">
                                    金额
                                </div>
                            </div>
                            <div class="report-table table-content lastzdlist">
                           </div>

                        </div>
                    </div>

                    

                </div>
    <div class="sc-1x3n9hq-0 gSbRmf edus">
        <div class="info_col">
            <div class="quota_font">
                快开彩额度
            </div>
            <div class="font_color1 money">
                <?php echo $this->_tpl_vars['kmoney']; ?>

            </div>
        </div>
        <div class="info_col">
            <div class="quota_font">
                全国彩额度
            </div>
            <div class="font_color1">
                0.0
            </div>
        </div>
        <div class="info_col">
            <div class="quota_font">
                香港彩额度
            </div>
            <div class="font_color1">
                0.0
            </div>
        </div>
    </div>
    <div class="sc-1x3n9hq-1 fCZmpJ">
        <div class="uq_icon">
        </div>
    </div>
    <div class="sc-1yr5q98-3 iYWXuL yls">
       
    </div>
</div>
<!--遗漏-->


			<div class="tzbody">
				<div tabindex="1" class="i1h1lp-1 jhcQwh gamemenu">
					<div class="i1h1lp-2 epExtR">
                        <span class="text"><?php echo $this->_tpl_vars['gname']; ?>
</span>
						 <span class="arrow"></span>
					</div>
				</div>

				<div tabindex="1" class="sc-1d9hz4n-1 exdTne">
					<div class="sc-1d9hz4n-2 fRfaQM tongji" <?php if (( $this->_tpl_vars['fenlei'] != 101 && $this->_tpl_vars['fenlei'] != 107 && $this->_tpl_vars['fenlei'] != 121 && $this->_tpl_vars['fenlei'] != 103 )): ?>style='display:none;'<?php endif; ?>>
						统计
					</div>
				</div>
				<div class="sc-1x3n9hq-0 bfHvQP edu">
					<div class="info_col">
						<div class="quota_font">
							快开彩额度
						</div>
						<div class="font_color1 money">
							<?php if (( $this->_tpl_vars['gid'] != 100 || $this->_tpl_vars['fudong'] == 1 )): ?><?php echo $this->_tpl_vars['kmoney']; ?>
<?php else: ?><?php echo $this->_tpl_vars['money']; ?>
<?php endif; ?>
                        
						</div>
					</div>
					<div class="info_col">
						<div class="quota_font">
							<msreadoutspan class="msreadout-line-highlight msreadout-inactive-highlight">未结算<msreadoutspan class="msreadout-word-highlight">金额</msreadoutspan></msreadoutspan>
						</div>
						<div class="font_color2 wjs">
							<?php if (( $this->_tpl_vars['fenlei'] != 100 || $this->_tpl_vars['fudong'] == 1 )): ?><?php echo $this->_tpl_vars['kmoneyuse']; ?>
<?php else: ?><?php echo $this->_tpl_vars['moneyuse']; ?>
<?php endif; ?>
						</div>
					</div>
					<div class="info_col">
						<div class="quota_font">
							今日输赢
						</div>
						<div class="font_color2 synow">
							<?php echo $this->_tpl_vars['synow']; ?>

						</div>
					</div>
				</div>



<div class="sc-1sgo4n-0 dmUHEy tongjidiv" style="display: none;">
    <div class="sc-1sgo4n-1 bOxvrp" style="visibility: visible; opacity: 1;">
        <div class="backButton">
            <div class="sc-1sgo4n-5 bcFIVc closetongji">
            </div>
        </div>
        <div class="title">
            统计
        </div>
        <div class="sc-1sgo4n-2 fpPAXM tjmenu">
            <div class="active sc-1sgo4n-3 hCMaPW clctrl">
                <div>
                    两面长龙
                </div>
            </div>
            <div class="sc-1sgo4n-3 hCMaPW lzctrl">
                <div>
                    路珠
                </div>
            </div>
        </div>
    </div>
    <div class="sc-1sgo4n-4 kkjbvG cl">
        <div title="两面长龙">
            <div class="sc-13d06go-0 WQWtT">
                <ul class="cllist">
                    <li>
                    <div>
                        亚军
                    </div>
                    <div>
                        单 4期
                    </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<style type="text/css">
  .ftlutb {    border-collapse: collapse;    border-spacing: 0;}
  .ftlutb td.bai{color: #fff}
  .ftlutb td.red{color:red;}
  .ftlutb td.hei{color:#000;}
  .ftlutb td{border: 1px solid #bddcf5;text-align: center !important;min-height: 28px;padding: 0;}
</style>

    <div class="sc-1sgo4n-4 kkjbvG ftlu" style="display: none;">
        <div title="番路">
           <table class="ftlutb" style="width: 99%">
<tbody></tbody>
</table>

        </div>
    </div>

<div class="sc-1sgo4n-4 kkjbvG lz" style="display: none;">
    <div title="路珠">
        <div class="sc-9plaow-0 csoFvQ">
            <div class="sc-9plaow-1 jKrOPS">
                筛选名次
            </div>
            <div class="sc-9plaow-8 hEaWLh lzb">
                <ul>
                </ul>
            </div>
            <div class="sc-9plaow-3 hkLcFi lzp">
                <ul>
                </ul>
            </div>
            <div class="sc-9plaow-5 iabIyi lzr">
                <ul>
                </ul>
            </div>
            <br>
            <div class="sc-9plaow-1 jKrOPS">
                筛选路珠
            </div>
            <div class="sc-9plaow-9 egcjFr lzson">
                <ul>
                </ul>
            </div>
            <div class="sc-9plaow-6 kPKqVh lzlist">
                <ul>
                </ul>
            </div>
        </div>
    </div>
</div>


</div>

<style type="text/css">
    .qiu100{
        width: 24px;
        margin-top:2px;
    }
.result100 {
    height: 55px;
    display: flex;
    -webkit-box-pack: justify;
    justify-content: space-between;
    padding-left: 10px;
    padding-right: 10px;
    box-sizing: border-box;
    background-color: rgb(255, 255, 255);
    border-bottom: 1px solid rgb(234, 234, 234);
}

.result100 .draw {
    display: inline-block;
    line-height: 55px;
    color: #999;
    margin-right: 8px;
    max-width: 120px;
}
.result100 .draw span {
    font-size: 15px;
    color: #1378bd;
}
</style>
				<div class="sc-1x3n9hq-1 fCZmpJ">
					<div class="uq_icon">
					</div>
				</div>
				<div class="jv7r91-0 resultother">
					<div class="draw">
						<span class="upqishu"><?php echo $this->_tpl_vars['upqishu']; ?>
</span>
					</div>
					<div class="id4sei-1 hDSAeE kjresult">

					</div>
				</div>
				<div class="id4sei-0 jvJTfN">
					<div class="draw">
						<span class="thisqishu" v='<?php echo $this->_tpl_vars['thisqishu']; ?>
'><?php echo $this->_tpl_vars['thisqishu']; ?>
</span>
					</div>
					<div class="timer-info">
						<div class="close" s='<?php echo $this->_tpl_vars['panstatus']; ?>
' time0='<?php echo $this->_tpl_vars['pantime']; ?>
' time1='<?php echo $this->_tpl_vars['othertime']; ?>
' os='<?php echo $this->_tpl_vars['otherstatus']; ?>
'>
							<?php if ($this->_tpl_vars['panstatus'] == 1): ?>封盘:<?php else: ?>开盘:<?php endif; ?> <span>00:00</span>
						</div>
						<div class="open" timek='<?php echo $this->_tpl_vars['kjtime']; ?>
'>
							开奖: <span>00:00</span>
						</div>
						<div class="refresh">
							<div>
							</div>
							<span>10</span>
						</div>
					</div>
				</div>
				<div class="rough_lines">
				</div>
				<div class="sc-1v8csmt-0 kDGwcG">
					<div class="sc-1v8csmt-2 ifxNlC menuplay">
						<div class="height_overflow" style="height: 100%;">
                         <a class="lrm_two_sides clmenu" href="javascript:void(0)"><div><span>长龙</span></div></a>   
                         <div class="menu_lines"></div>
                         <a class="lrm_two_sides ylmenu" href="javascript:void(0)"><div><span>遗漏</span></div></a>  
                         <div class="menu_lines"></div>
                         <?php if ($this->_tpl_vars['fenlei'] != 100): ?>
							<a class="lrm_two_sides" href="javascript:void(0)" bid="">
							<div>
								<span><?php if ($this->_tpl_vars['fenlei'] == 151): ?>大小骰宝<?php else: ?>两面<?php endif; ?></span>
							</div>
							</a>
                            <div class="menu_lines"></div>
                          <?php endif; ?>
                         <?php if ($this->_tpl_vars['fenlei'] == 107): ?>
                            <a class="lrm_two_sides" href="javascript:void(0)" bid="15">
                            <div>
                                <span>1~10名</span>
                            </div>
                            </a>
                            <div class="menu_lines"></div>
                          <?php endif; ?>
                         <?php if ($this->_tpl_vars['fenlei'] == 121): ?>
                            <a class="lrm_two_sides" href="javascript:void(0)" bid="15">
                            <div>
                                <span>1~5球号</span>
                            </div>
                            </a>
                            <div class="menu_lines"></div>
                          <?php endif; ?>
                         <?php if ($this->_tpl_vars['fenlei'] == 103): ?>
                            <a class="lrm_two_sides" href="javascript:void(0)" bid="15">
                            <div>
                                <span>1~8球号</span>
                            </div>
                            </a>
                            <div class="menu_lines"></div>
                          <?php endif; ?>
                          <?php if (( $this->_tpl_vars['fenlei'] != 151 )): ?>
                              <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['b']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                  <?php if (( $this->_tpl_vars['fenlei'] == 107 )): ?>
                                     <?php if (( $this->_tpl_vars['b'][$this->_sections['i']['index']]['name'] == '冠亚军组合' || $this->_tpl_vars['b'][$this->_sections['i']['index']]['name'] == '番摊' )): ?>
                                        <a class="lrm_two_sides" href="javascript:void(0)" bid="<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
">
                                            <div>
                                                <span>冠亚和</span>
                                            </div>
                                        </a>
                                        <div class="menu_lines"></div>        
                                     <?php endif; ?>
                                  <?php elseif (( $this->_tpl_vars['fenlei'] == 121 )): ?>
                                        <a class="lrm_two_sides" href="javascript:void(0)" bid="<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
">
                                            <div>
                                                <span><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</span>
                                            </div>
                                        </a>
                                        <div class="menu_lines"></div> 
                                  <?php elseif (( $this->_tpl_vars['fenlei'] == 103 )): ?>
                                        <a class="lrm_two_sides" href="javascript:void(0)" bid="<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
">
                                            <div>
                                                <span><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</span>
                                            </div>
                                        </a>
                                        <div class="menu_lines"></div> 
                                  <?php elseif (( $this->_tpl_vars['fenlei'] == 100 )): ?>
                                      <?php if (( $this->_tpl_vars['b'][$this->_sections['i']['index']]['name'] != "過關" && $this->_tpl_vars['b'][$this->_sections['i']['index']]['name'] != "过关" )): ?>
                                        <a class="lrm_two_sides" href="javascript:void(0)" bid="<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['sid']; ?>
">
                                            <div>
                                                <span><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</span>
                                            </div>
                                        </a>
                                        <div class="menu_lines"></div> 
                                      <?php endif; ?>  
                                  <?php else: ?>
                                        <a class="lrm_two_sides" href="javascript:void(0)" bid="<?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['bid']; ?>
">
                                            <div>
                                                <span><?php echo $this->_tpl_vars['b'][$this->_sections['i']['index']]['name']; ?>
</span>
                                            </div>
                                        </a>
                                        <div class="menu_lines"></div> 
                                  <?php endif; ?>
                              <?php endfor; endif; ?>
                          <?php endif; ?>

						</div>
					</div>
                    

					<div class="sc-1v8csmt-1 btIRSJ make">
                        <div class="sc-jnlKLf gVBorT abcdab" style="display: none;">
                            <ul class="ab">
                               <li><span class="active" v="A">特码A</span></li>
                               <li><span class="" v="B">特码B</span></li>
                            </ul>
                            <input type="hidden" class="abcd" value="<?php echo $this->_tpl_vars['defaultpan']; ?>
">
                            <div class="mask-left hidden"></div>
                            <div class="mask-right hidden"></div>

                        </div>
                        <div class="rough_lines abcdabs" style="display: none;"></div>


                        <?php if ($this->_tpl_vars['fenlei'] == 107): ?>
                        <div class="sc-jnlKLf gVBorT abcdakj" style="display: none;height: 72px;">
                            <ul class="kj">
                               <li><span class="active" sname="冠军">冠军</span></li>
                               <li><span class="" sname="亚军">亚军</span></li>
                               <li><span class="" sname="第3名">第3名</span></li>
                               <li><span class="" sname="第4名">第4名</span></li>
                               <li><span class="" sname="第5名">第5名</span></li>
                               <li><span class="" sname="第6名">第6名</span></li>
                               <li><span class="" sname="第7名">第7名</span></li>
                               <li><span class="" sname="第8名">第8名</span></li>
                               <li><span class="" sname="第9名">第9名</span></li>
                               <li><span class="" sname="第10名">第10名</span></li>
                            </ul>
                            <input type="hidden" class="abcd" value="<?php echo $this->_tpl_vars['defaultpan']; ?>
">
                            <div class="mask-left hidden"></div>
                            <div class="mask-right hidden"></div>
                        </div>
                        <div class="rough_lines abcdakjs" style="display: none;"></div>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['fenlei'] == 101): ?>
                        <div class="sc-jnlKLf gVBorT abcdakj" style="display: none;height: 39px;">
                            <ul class="kj">
                               <li><span class="active" sname="第一球">第一球</span></li>
                               <li><span class="" sname="第二球">第二球</span></li>
                               <li><span class="" sname="第三球">第三球</span></li>
                               <li><span class="" sname="第四球">第四球</span></li>
                               <li><span class="" sname="第五球">第五球</span></li>
                            </ul>
                            <input type="hidden" class="abcd" value="<?php echo $this->_tpl_vars['defaultpan']; ?>
">
                            <div class="mask-left hidden"></div>
                            <div class="mask-right hidden"></div>
                        </div>
                        <div class="rough_lines abcdakjs" style="display: none;"></div>
                        <?php endif; ?>


					</div>


 


				</div>
				<div class="bet-page-full slevm5-1 lastzd iJamhB" style="height: 100%;" data-show="false">
					<div class="oc2pj9-1 ilDBjU">
						<div class="pn_title">
							<a>
							<div class="lb_back">
							</div>
							</a>
							<div style="flex:1 1 0%;">
							</div>
							<div style="flex:1 1 0%;">
								注单列表
							</div>
							<div style="flex:1 1 0%;">
							</div>
						</div>
					</div>
					<div class="main-content">
						<div class="sc-1fs8umj-0 ettwvL">
							<div class="report-table table-header flex">
								<div class="col col2">
									注单号
								</div>
								<div class="col col2">
									期数
								</div>
								<div class="col col2">
									玩法
								</div>
								<div class="col col2">
									金额
								</div>
							</div>
							<div class="report-table table-content lastzdlist">
                           </div>

						</div>
					</div>

                    

				</div>
			</div>
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
      </a><a class="mt_div"  target="_blank" type="" href="<?php echo $this->_tpl_vars['kfurl']; ?>
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
      </a><a class="mt_div" type="/creditmobile/rule" href="javascript:void(0)">
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
.ReactModal__Overlay.ReactModal__Overlay--after-open {
    background-color: rgba(55, 55, 55, 0.7) !important;
}
.ReactModal__Content {
    animation: 0.75s ease 0s 1 normal forwards running bounceIn;
}
.bet-amount-popup {
    width: 80%;
    background-color: rgb(255, 255, 255);
    text-align: center;
    padding-bottom: 1rem;
    box-shadow: rgba(100, 100, 100, 0.8) 0px 0px 30px;
    margin: 25% auto 0px;
    border-radius: 0.5rem;
    outline: none;
    border-width: 0px;
    border-style: initial;
    border-color: initial;
    border-image: initial;
    overflow: hidden;
}
.lobnrg {
    position: relative;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 45px;
    background: linear-gradient(to right, rgb(125, 202, 255) 0%, rgb(26, 81, 148) 100%);
}
.lobnrg .back-toggle {
    font-size: 0px;
    position: absolute;
    width: 25px;
    height: 25px;
    top: 10px;
    left: 10px;
    background: url(/css/mobi/img/ic_back.png)  center center / contain no-repeat;
}
.lobnrg .title {
    line-height: 45px;
    text-align: center;
    font-weight: bold;
    font-size: 0.875rem;
    color: rgb(255, 255, 255);
}
.fieldset:not(:last-child) {
    border-bottom: 1px solid rgb(234, 234, 234);
}
.fieldset {
    min-height: 40px;
    height: auto;
    line-height: 40px;
    background-color: rgb(255, 255, 255);
    box-sizing: border-box;
    position: relative;
    padding: 0px 20px;
}
.fieldset .round-input {
    display: inline-block;
    height: 90%;
    -webkit-appearance: none;
    width: 96px;
    padding: 7px 14px;
    border-radius: 24px;
    border-width: 1px;
    border-style: solid;
    border-color: rgb(118, 118, 118);
    border-image: initial;
}

.ReactModalPortal {
    position: fixed;
    /*background-color: rgba(55, 55, 55, 0.7);*/
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
.field-button {
    display: block;
    height: 40px;
    line-height: 40px;
    text-align: center;
    width: 100%;
    color: rgb(255, 255, 255);
    font-size: 0.875rem;
    border-width: 0px;
    border-style: initial;
    border-color: initial;
    border-image: initial;
    border-radius: 40px;
    background: linear-gradient(to right, rgb(125, 202, 255) 0%, rgb(26, 81, 148) 100%);
}
.persist-amount-modal {
    position: fixed;
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    background-color: rgba(255, 255, 255, 0.75);
    z-index: 100;
}

.ReactModal__Overlay {
    animation: 0.4s linear 0s 1 normal forwards running fadeIn;
}
</style>
<div class="ReactModalPortal fastje" style="display: none;">
    <div class="ReactModal__Overlay ReactModal__Overlay--after-open persist-amount-modal">
        <div class="ReactModal__Content ReactModal__Content--after-open bet-amount-remodal" tabindex="-1" role="dialog" aria-label="Preset">
            <div class="bet-amount-popup">
                <div class="oc2pj9-0 lobnrg">
                    <a class="back-toggle close">Back</a><span class="title">预设金额</span>
                </div>
                <div class="fieldset">
                    <input class="round-input" placeholder="设置金额" type="number" name="min" value="5" style="text-align: center;">
                </div>
                <div class="fieldset">
                    <input class="round-input" placeholder="设置金额" type="number" name="min" value="10" style="text-align: center;">
                </div>
                <div class="fieldset">
                    <input class="round-input" placeholder="设置金额" type="number" name="min" value="20" style="text-align: center;">
                </div>
                <div class="fieldset">
                    <input class="round-input" placeholder="设置金额" type="number" name="min" value="50" style="text-align: center;">
                </div>
                <div class="fieldset">
                    <input class="round-input" placeholder="设置金额" type="number" name="min" value="100" style="text-align: center;">
                </div>
                <div class="fieldset">
                    <button class="field-button setje">确认</button>
                </div>
            </div>
        </div>
    </div>
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
';
var ngid= <?php echo $this->_tpl_vars['gid']; ?>
;
var globalpath = '<?php echo $this->_tpl_vars['globalpath']; ?>
';
var fast=<?php echo $this->_tpl_vars['fast']; ?>
;
var fudong=<?php echo $this->_tpl_vars['fudong']; ?>
;
var kjurl='<?php echo $this->_tpl_vars['kjurl']; ?>
';
var fenlei='<?php echo $this->_tpl_vars['fenlei']; ?>
';
var pk10num = '<?php echo $this->_tpl_vars['pk10num']; ?>
';
var pk10ts = '<?php echo $this->_tpl_vars['pk10ts']; ?>
';
var app='<?php echo $this->_tpl_vars['app']; ?>
';
sma=[];
sma['紅'] = new Array(1,2,7,8,12,13,18,19,23,24,29,30,34,35,40,45,46); 
sma['藍'] = new Array(3,4,9,10,14,15,20,25,26,31,36,37,41,42,47,48); 
sma['綠'] = new Array(5,6,11,16,17,21,22,27,28,32,33,38,39,43,44,49); 
 </script>
</body>
</html>