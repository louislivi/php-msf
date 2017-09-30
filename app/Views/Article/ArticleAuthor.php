<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>作者管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="css/global.css" media="all">
</head>

<body>
<div style="margin: 15px;">
    <fieldset class="layui-elem-field site-demo-button" style="padding: 10px;">
        <legend>作者列表</legend>
        <div>
            <?php if($result):?>
                <?php foreach ($result as $v):?>
                    <a href="javascript:if(confirm('是否删除该作者？')) location='/article/authorDelete?id=<?=$v['id']?>&article_id=<?=$article_id?>';"
                       class="layui-btn layui-btn-danger" >
                        <?=$v['aname']?><i class="layui-icon"></i>
                    </a>
                <?php endforeach;?>
            <?php else:?>
                <a href="javascript:void()" data-opt="edit"
                   class="layui-btn layui-btn-mini">
                    暂无作者
                </a>
            <?php endif;?>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field site-demo-button" style="padding: 10px;">
        <legend>添加作者</legend>
        <form class="layui-form" method="post" action="/article/authorAdd">
            <input name="__RequestVerificationToken" type="hidden" value="<?=$this->e($token)?>" />
            <input type="hidden" name="article_id" value="<?=$article_id?>">
            <div class="layui-form-item">
                <label class="layui-form-label">账号名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="aname" autocomplete="off" placeholder="账号名称" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">关注链接URL</label>
                <div class="layui-input-block">
                    <input type="url" name="url" lay-verify="url" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="demo1">添加作者</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </fieldset>
</div>
</body>
<script type="text/javascript" src="/static/plugins/layui/layui.js"></script>
<script>
    layui.use(['form'], function() {
        var form = layui.form(),
            $ = layer.jquery;
        form.on('submit(demo1)', function(data) {
            $('form').submit();
            return false;
        });
    })
</script>

</html>