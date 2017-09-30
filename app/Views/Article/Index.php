<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Table</title>
    <link rel="stylesheet" href="/static/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/static/css/global.css" media="all">
    <link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/static/css/table.css" />
</head>

<body>
<div class="admin-main">

    <blockquote class="layui-elem-quote">
        <a href="/article/articleAdd" class="layui-btn layui-btn-small" id="add">
            <i class="layui-icon">&#xe608;</i> 添加文章
        </a>
<!--        <a href="#" class="layui-btn layui-btn-small" id="import">-->
<!--            <i class="layui-icon">&#xe608;</i> 导入信息-->
<!--        </a>-->
<!--        <a href="#" class="layui-btn layui-btn-small">-->
<!--            <i class="fa fa-shopping-cart" aria-hidden="true"></i> 导出信息-->
<!--        </a>-->
<!--        <a href="#" class="layui-btn layui-btn-small" id="getSelected">-->
<!--            <i class="fa fa-shopping-cart" aria-hidden="true"></i> 获取全选信息-->
<!--        </a>-->
        <a href="javascript:;" class="layui-btn layui-btn-small" id="search">
            <i class="layui-icon">&#xe615;</i> 搜索
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend>文章列表</legend>
        <div class="layui-field-box layui-form">
            <table class="layui-table admin-table">
                <thead>
                <tr>
<!--                    <th style="width: 30px;"><input type="checkbox" lay-filter="allselector" lay-skin="primary"></th>-->
                    <th>ID</th>
                    <th>文章标题</th>
                    <th><a title="点击排序" href="?sort=zan">被赞数</a></th>
                    <th><a title="点击排序" href="?sort=yue">阅读数</a></th>
                    <th><a title="点击排序" href="?sort=fen">分享数</a></th>
                    <th><a title="点击排序" href="">创建时间</a></th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="content">
               <?php foreach ($result as $value):?>
                <tr>
<!--                    <td><input type="checkbox" lay-skin="primary"></td>-->
                    <td><?= $value['id']?></td>
                    <td><?= $value['title']?></td>
                    <td><?= $value['zan']?:0?></td>
                    <td><?= $value['views']?:0?></td>
                    <td><?= $value['share_num']?:0?></td>
                    <td><?= $value['create_time']?></td>
                    <td>
                        <a href="/article/articleDetails?id=<?= $value['id']?>" target="_blank" class="layui-btn layui-btn-normal layui-btn-mini">预览</a>
                        <a href="/article/articleEdit?id=<?= $value['id']?>" data-opt="edit" class="layui-btn layui-btn-mini">编辑</a>
                        <a href="javascript:if(confirm('是否需要删除该文章?')) location='/article/articleDelete?id=<?=$value['id']?>';" data-id="1" data-opt="del" class="layui-btn layui-btn-danger layui-btn-mini">删除</a>
                    </td>
                </tr>
               <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </fieldset>
    <div class="admin-table-page">
        <div id="paged" class="page">
            <div class="layui-box layui-laypage layui-laypage-default" id="layui-laypage-0">
                <?=$pageList?>
            </div>
        </div>
    </div>
</div>
</body>

</html>