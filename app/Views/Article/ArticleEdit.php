<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>文章编辑</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    <link rel="stylesheet" href="/static/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css">
    <script src="/static/js/jquery.min.js"></script>

    <link rel="stylesheet" href="/kind_editor_XikjO21/themes/default/default.css" />
    <link rel="stylesheet" href="/kind_editor_XikjO21/plugins/code/prettify.css" />
    <script charset="utf-8" src="/kind_editor_XikjO21/kindeditor-all-min.js"></script>
    <script charset="utf-8" src="/kind_editor_XikjO21/lang/zh-CN.js"></script>
    <script charset="utf-8" src="/kind_editor_XikjO21/plugins/code/prettify.js"></script>
</head>

<body>
<div style="margin: 15px;">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>文章编辑</legend>
    </fieldset>

    <form class="layui-form" method="post" action="/article/articleEdit?id=<?=$article['result'][0]['id']?>"
          enctype="multipart/form-data">
        <input name="__RequestVerificationToken" type="hidden" value="<?=$this->e($token)?>" />
        <div class="layui-form-item">
            <label class="layui-form-label">文章标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="title" autocomplete="off" maxlength="200" placeholder="请输入标题" value="<?=$article['result'][0]['title']?>" class="layui-input" style="width: 38%;">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">文章栏目</label>
            <div class="layui-input-inline">
                <select name="category_id" lay-filter="category">
                    <option value="请选择栏目" disabled>请选择栏目</option>
                    <?php foreach ($category['result'] as $v):?>
                        <option <?php if($article['result'][0]['category_id'] == $v['id']) echo 'selected=\'selected\'';?>
                                value="<?=$v['id']?>"><?=$v['title']?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">封面图</label>
            <div class="layui-input-block">
                <div class="layui-upload" id="preview">
                    <img alt="点击上传" id="imghead" border="0" src="/static/upload<?=$article['result'][0]['cover_src']?>" width="400" onclick="$('#previewImg').click();" style="background: #eee;min-height: 200px;text-align: center;line-height: 200px;cursor: pointer;">
                </div>
                <input type="file" name="cover_src" onchange="previewImage(this)" style="display: none;" id="previewImg">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序值</label>
            <div class="layui-input-inline">
                <input type="number" value="<?=$article['result'][0]['backend_sort']?>" name="backend_sort" lay-verify="number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">广告绑定</label>
            <div class="layui-input-inline">
                <select name="bind_ad" lay-filter="aihao">
                    <option value="0">随机</option>
                    <?php foreach ($advertisement['result'] as $v):?>
                    <option <?php if($article['result'][0]['bind_ad'] == $v['id']) echo 'selected=\'selected\'';?>
                            value="<?=$v['id']?>"><?=$v['title']?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">关联文章</label>
            <div class="layui-input-block">
                <input type="text" name="relation_ids" autocomplete="off" placeholder="填写关联文章id，多个以|符号分隔，例如1|2|3|4"  class="layui-input" style="width: 38%;" value="<?=$article['result'][0]['relation_ids']?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否置顶</label>
            <div class="layui-input-block">
                <input type="checkbox" <?php if ($article['result'][0]['ding'])echo 'checked=""'; else echo ''; ?>  lay-skin="switch" name="ding" title="开关">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布时间</label>
            <div class="layui-input-inline">
                <input type="text" readonly name="create_time" autocomplete="off"  placeholder="" value="<?=$article['result'][0]['create_time']?>" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">文章内容</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea layui-hide" name="contents" lay-verify="content" id="LAY_demo_editor"><?=$article['result'][0]['contents']?></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">文章作者</label>
            <div class="layui-input-block">
                <?php if($account['result']):?>
                    <?php foreach ($account['result'] as $v):?>
                        <a href="<?=$v['url']?:'javascript:void()'?>" data-opt="edit"
                           class="layui-btn layui-btn-mini">
                            <?=$v['aname']?>
                        </a>
                    <?php endforeach;?>
                <?php else:?>
                    <a href="javascript:void()" data-opt="edit"
                       class="layui-btn layui-btn-mini">
                        暂无作者
                    </a>
                <?php endif;?>
            </div>
            <a href="/article/articleAuthor?id=<?=$article['result'][0]['id']?>" class="layui-btn layui-btn-small layui-btn-normal">管理文章作者</a>
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
    layui.use(['form', 'laydate'], function() {
        var form = layui.form(),
            layer = layui.layer,
            laydate = layui.laydate;

        //自定义验证规则
        form.verify({
            title: function(value) {
                value = $.trim(value);
                if( value.length === 0 ) {
                    return '标题必填';
                }
            },
            category:function( value ) {
                if( isNaN(value) || value <0 ) {
                    return '栏目必须并且只能是正整数';
                }
            },
            number:function ( value ) {
                if( isNaN(value) || value <0 ) {
                    return '只能是正整数';
                }
            },
            content:function( value ) {
                value = $.trim( value );
                if( value.length === 0 ) {
                    return '内容必填';
                }
            },
        });

        //监听提交
        form.on('submit(demo1)', function(data) {
            $('form').submit();
            return false;
        });
    });



    //编辑器配置
    var editor1,inum = 0;
    KindEditor.ready(function(K) {
        editor1 = K.create('textarea[name="contents"]', {
            cssPath : '/kind_editor_XikjO21/plugins/code/prettify.css',
            //指定上传图片的服务器端程序
            uploadJson : '/Article/EditUpImageApi',
            //true时显示浏览服务器图片功能
            allowFileManager : true,
            width:'100%',
            height:'500px',
            items:[//配置编辑器的工具栏
                'source', '|', 'undo', 'redo', '|', 'preview', 'template', 'code', 'cut', 'copy', 'paste','plainpaste', '|', 'justifyleft', 'justifycenter', 'justifyright','justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript','superscript', 'clearhtml', 'quickformat','/', 'selectall', '|', 'fullscreen','formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold','italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'multiimage', 'table', 'hr', 'emoticons', 'pagebreak','anchor', 'link', 'unlink',
            ],
            //设置编辑器创建后执行的回调函数
            afterCreate : function() {
                var self = this;
                K.ctrl(document, 13, function() {
                    self.sync();
                    K('form[name=example]')[0].submit();
                });
                K.ctrl(self.edit.doc, 13, function() {
                    self.sync();
                    K('form[name=example]')[0].submit();
                });
            },
            extraFileUploadParams:{'__RequestVerificationToken':'<?php isset($token)&&print_r($token);?>'},
            filePostName:'file',
            afterChange:function(){
                if( inum === 0 ) {
                    inum++;
                    return null;
                }
                //var html = editor1.html();
                //console.log(html);
                editor1.sync();
            },
        });
        prettyPrint();
    });

</script>
</body>

</html>