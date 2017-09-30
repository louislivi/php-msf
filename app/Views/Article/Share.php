<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>微信分享设置</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    <link rel="stylesheet" href="/static/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css">
</head>

<body>
<div style="margin: 15px;">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>微信分享设置</legend>
    </fieldset>

    <form class="layui-form" method="post" action="">
        <input name="__RequestVerificationToken" type="hidden" value="<?=$this->e($token)?>" />
        <div class="layui-form-item">
            <label class="layui-form-label">AppID</label>
            <div class="layui-input-inline">
                <input type="text" name="appid" autocomplete="off" placeholder="AppID" value="<?=$this->e($data['appid'])?>" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">AppSecret</label>
            <div class="layui-input-inline">
                <input type="text" name="secret" autocomplete="off" placeholder="AppSecret" value="<?=$this->e($data['secret'])?>" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="share">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="/static/plugins/layui/layui.js"></script>
<script>
    layui.use(['layer', 'form'], function() {
        var layer = layui.layer,
            $ = layui.jquery,
            form = layui.form();

        form.on('submit(share)',function(data){
            $('form').submit();
            return false;
        });
    });
</script>
</body>

</html>