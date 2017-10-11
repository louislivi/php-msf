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
    <script src="/static/js/jquery.min.js"></script>
    <style>
        .layui-form-item .layui-input-inline{
            width:360px;
        }
    </style>
</head>

<body>
<div style="margin: 15px;">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>微信分享设置</legend>
    </fieldset>

    <form class="layui-form" method="post" action="" enctype="multipart/form-data">
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
            <label class="layui-form-label">分享标题</label>
            <div class="layui-input-inline">
                <input type="text" name="title" autocomplete="off" placeholder="AppSecret" value="<?=$this->e($data['title'])?>" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">跳转域名</label>
            <div class="layui-input-inline">
                <input type="text" name="randhost" autocomplete="off"  value="<?=$this->e($data['randhost'])?>" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述信息</label>
            <div class="layui-input-inline">
                <input type="text" name="desc" autocomplete="off"  value="<?=$this->e($data['desc'])?>" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">统计代码</label>
            <div class="layui-input-inline">
                <input type="text" name="totalcode" autocomplete="off"  value="<?=$this->e($data['totalcode'])?>" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分享封面</label>
            <div class="layui-input-block">
                <div class="layui-upload" id="preview">
                    <img alt="点击上传" id="imghead" border="0" src="/static/upload<?=$this->e($data['imageurl'])?>" width="400" style="background: #eee;min-height: 200px;text-align: center;line-height: 200px;cursor: pointer;" onclick="$('#previewImg').click();">
                </div>
                <input type="file" name="imageurl" onchange="previewImage(this)" style="display: none;" id="previewImg">
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
    //图片上传预览    IE是用了滤镜。
    function previewImage(file)
    {
        var MAXWIDTH  = 500;
        var MAXHEIGHT = 300;
        var div = document.getElementById('preview');
        if (file.files && file.files[0])
        {
            div.innerHTML ='<img id=imghead onclick=$("#previewImg").click()>';
            var img = document.getElementById('imghead');
            img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
            }
            var reader = new FileReader();
            reader.onload = function(evt){img.src = evt.target.result;}
            reader.readAsDataURL(file.files[0]);
        }
        else //兼容IE
        {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead>';
            var img = document.getElementById('imghead');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
        }
    }
    function clacImgZoomParam( maxWidth, maxHeight, width, height ){
        var param = {top:0, left:0, width:width, height:height};
        if( width>maxWidth || height>maxHeight ){
            rateWidth = width / maxWidth;
            rateHeight = height / maxHeight;

            if( rateWidth > rateHeight ){
                param.width =  maxWidth;
                param.height = Math.round(height / rateWidth);
            }else{
                param.width = Math.round(width / rateHeight);
                param.height = maxHeight;
            }
        }
        param.left = Math.round((maxWidth - param.width) / 2);
        param.top = Math.round((maxHeight - param.height) / 2);
        return param;
    }
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