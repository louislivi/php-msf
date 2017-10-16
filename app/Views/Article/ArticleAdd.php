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
    <script src="http://open.web.meitu.com/sources/xiuxiu.js" type="text/javascript"></script>
    <!-- 提示:: 如果你的网站使用https, 将xiuxiu.js地址的请求协议改成https即可 -->
    <script type="text/javascript">
        window.onload=function(){
            host = 'http://'+window.location.host;
            /*第1个参数是加载编辑器div容器，第2个参数是编辑器类型，第3个参数是div容器宽，第4个参数是div容器高*/
            //xiuxiu.setLaunchVars("customMenu", ["decorate"]);
            xiuxiu.setLaunchVars ("nav", "edit");
            xiuxiu.setLaunchVars("sizeTipVisible", 1);
            //xiuxiu.setLaunchVars("cropPresets", "100x100");
            xiuxiu.embedSWF("altContent",1,"100%",600);
            //修改为您自己的图片上传接口
            xiuxiu.setUploadURL(host+"/article/EditUpImageApi");
            xiuxiu.setUploadArgs ({'__RequestVerificationToken':"<?=$this->e($token)?>"});
            xiuxiu.setUploadDataFieldName("file");
            xiuxiu.setUploadType(2);
            xiuxiu.onInit = function ()
            {
                xiuxiu.loadPhoto("");//修改为要处理的图片url
            }
            xiuxiu.onUploadResponse = function (data)
            {
                alert('上传成功！');
                data = JSON.parse(data);
                xiuxiu.loadPhoto(host+data.url);
                document.getElementById('cover_src').value = '/article/'+data.title;
            }

        }
    </script>
</head>

<body>
<div style="margin: 15px;">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>文章编辑</legend>
    </fieldset>

    <form class="layui-form" method="post" action="/article/articleAdd?>"
          enctype="multipart/form-data">
        <input name="__RequestVerificationToken" type="hidden" value="<?=$this->e($token)?>" />
        <div class="layui-form-item">
            <label class="layui-form-label">文章标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="title" autocomplete="off" maxlength="200" placeholder="请输入标题"  class="layui-input" style="width: 38%;">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">文章栏目</label>
            <div class="layui-input-inline">
                <select name="category_id" lay-filter="aihao">
                    <option value="请选择栏目" disabled>请选择栏目</option>
                    <?php foreach ($category['result'] as $v):?>
                        <option
                                value="<?=$v['id']?>"><?=$v['title']?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">文章排版</label>
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
                <div id="altContent" class="altContent"></div>
                <input lay-verify="image" id="cover_src" type="hidden" name="cover_src" value="">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">排序值</label>
            <div class="layui-input-inline">
                <input type="number" min="0" max="1000" value="0" name="backend_sort" lay-verify="number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">虚拟阅读数</label>
            <div class="layui-input-inline">
                <input type="number" value="0" name="views_offset" lay-verify="number" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">广告绑定</label>
            <div class="layui-input-block">
                <span style="color: #ccc">填写关联广告id，多个以|符号分隔，例如1|2|3|4,默认0为随机</span>
                <input lay-verify="ids" type="text" name="bind_ad" autocomplete="off" placeholder="填写广告id，多个以|符号分隔，例如1|2|3|4,默认0为随机" value="0"  class="layui-input" style="width: 38%;">
<!--                <select name="bind_ad" lay-filter="aihao">-->
<!--                    <option value="0">随机</option>-->
<!--                    --><?php //foreach ($advertisement['result'] as $v):?>
<!--                        <option-->
<!--                                value="--><?//=$v['id']?><!--">--><?//=$v['title']?><!--</option>-->
<!--                    --><?php //endforeach;?>
<!--                </select>-->
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">关联文章</label>
            <div class="layui-input-block">
                <input lay-verify="ids" type="text" name="relation_ids" autocomplete="off" placeholder="填写关联文章id，多个以|符号分隔，例如1|2|3|4"  class="layui-input" style="width: 38%;">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否置顶</label>
            <div class="layui-input-block">
                <input type="checkbox" lay-skin="switch" name="ding" title="开关">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">发布时间</label>
            <div class="layui-input-inline">
                <input type="text" readonly name="create_time" autocomplete="off"  placeholder="" value="<?=date('Y-m-d H:i:s')?>" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">文章内容</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea layui-hide" name="contents" lay-verify="content" id="LAY_demo_editor"></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">文章作者</label>
            <div class="layui-input-block">
                <a href="javascript:void()" data-opt="edit"
                   class="layui-btn layui-btn-mini">
                    暂无作者
                </a>
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
            number:function ( value ) {
                if( isNaN(value) || value <0 ) {
                    return '只能是正整数';
                }
                if(value > 1000 ) {
                    return '请输入1-1000范围值';
                }
            },
            content:function( value ) {
                value = $.trim( value );
                if( value.length === 0 ) {
                    return '内容必填';
                }
            },
            image:function( value ) {
                value = $.trim( value );
                if( value.length === 0 ) {
                    return '请上传封面图，打开文件后点击右下角确定按钮！';
                }
            },
            ids:function ( value ) {
                if(value && value != 0){
                    rgExp = /^(?:\d{1,}[\|$]?)+$/;
                    verify = value.match(rgExp);
                    if(!verify){
                        return '请以|分割！';
                    }
                }
            }
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