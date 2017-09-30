<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title>后台管理模板</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    <link rel="stylesheet" href="/static/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/static/css/global.css" media="all">
    <link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css">

</head>

<body>
<div class="layui-layout layui-layout-admin" style="border-bottom: solid 5px #1aa094;">
    <div class="layui-header header header-demo">
        <div class="layui-main">
            <div class="admin-login-box">
                <a class="logo" style="left: 0;" href="">
                    <span style="font-size: 22px;">管理系统</span>
                </a>
                <div class="admin-side-toggle">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                <div class="admin-side-full">
                    <i class="fa fa-life-bouy" aria-hidden="true"></i>
                </div>
            </div>
            <ul class="layui-nav admin-header-item">
<!--                <li class="layui-nav-item">-->
<!--                    <a href="javascript:;">清除缓存</a>-->
<!--                </li>-->
<!--                <li class="layui-nav-item" id="pay">-->
<!--                    <a href="javascript:;">捐赠我</a>-->
<!--                </li>-->
<!--                <li class="layui-nav-item">-->
<!--                    <a href="javascript:;">浏览网站</a>-->
<!--                </li>-->
<!--                <li class="layui-nav-item" id="video1">-->
<!--                    <a href="javascript:;">视频</a>-->
<!--                </li>-->
                <li class="layui-nav-item">
                    <a href="javascript:;" class="admin-header-user">
                        <img src="/static/images/0.jpg" />
                        <span>Admin</span>
                    </a>
                    <dl class="layui-nav-child">
<!--                        <dd>-->
<!--                            <a href="javascript:;"><i class="fa fa-user-circle" aria-hidden="true"></i> 个人信息</a>-->
<!--                        </dd>-->
<!--                        <dd>-->
<!--                            <a href="javascript:;"><i class="fa fa-gear" aria-hidden="true"></i> 设置</a>-->
<!--                        </dd>-->
<!--                        <dd id="lock">-->
<!--                            <a href="javascript:;">-->
<!--                                <i class="fa fa-lock" aria-hidden="true" style="padding-right: 3px;padding-left: 1px;"></i> 锁屏 (Alt+L)-->
<!--                            </a>-->
<!--                        </dd>-->
                        <dd>
                            <a href="/admin/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a>
                        </dd>
                    </dl>
                </li>
            </ul>
            <ul class="layui-nav admin-header-item-mobile">
                <li class="layui-nav-item">
                    <a href="login.html"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a>
                </li>
            </ul>
        </div>
    </div>