<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>添加广告</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no">

		<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" media="all" />
		<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css">
        <script src="/static/js/jquery.min.js"></script>
	</head>

	<body>
		<div style="margin: 15px;">
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				<legend>添加广告</legend>
			</fieldset>

			<form class="layui-form" method="post" action="" enctype="multipart/form-data">
                <input name="__RequestVerificationToken" type="hidden" value="<?=$this->e($token)?>" />
				<div class="layui-form-item">
					<label class="layui-form-label">广告名称</label>
					<div class="layui-input-inline">
						<input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入名称" class="layui-input">
					</div>
				</div>
                <div class="layui-form-item">
                    <label class="layui-form-label">广告排版</label>
                    <div class="layui-input-inline">
                        <select name="list_typeset" lay-filter="aihao">
                            <option value="1" >左右图文</option>
                            <option value="2" >上下大图</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">封面图</label>
                    <div class="layui-input-block">
                        <div class="layui-upload" id="preview">
                            <img id="imghead" border="0" src="" width="400" alt="点击上传" onclick="$('#previewImg').click();" style="background: #eee;min-height: 200px;text-align: center;line-height: 200px;cursor: pointer;">
                        </div>
                        <input type="file" name="cover_src" onchange="previewImage(this)" style="display: none;" id="previewImg">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">广告链接</label>
                    <div class="layui-input-block">
                        <input type="url" name="link" lay-verify="url" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-inline">
                        <input type="text" name="remarks" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">添加日期</label>
                    <div class="layui-input-inline">
                        <input type="text" name="create_time" id="date" placeholder="yyyy-mm-dd" autocomplete="off" value="<?=date('Y-m-d H:i:s')?>" class="layui-input" readonly>
                    </div>
                </div>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
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
			layui.use(['form'], function() {
				var form = layui.form();
				//自定义验证规则
				form.verify({
					title: function(value) {
						if(value.length < 3) {
							return '标题至少得3个字符啊';
						}
					}
				});
				//监听提交
				form.on('submit(demo1)', function(data) {
					$('form').submit();
					return false;
				});
			});
		</script>
	</body>

</html>