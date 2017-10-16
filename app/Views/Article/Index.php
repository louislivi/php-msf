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
        <form class="layui-form" style="float:right;">
            <div class="layui-form-item" style="margin:0;">
                <label class="layui-form-label">标题/内容</label>
                <div class="layui-input-inline">
                    <input type="text" name="search" placeholder="请输入关键字.." autocomplete="off" value="<?=isset($search)?$search:''?>" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux" style="padding:0;">
                    <button lay-filter="search" class="layui-btn" lay-submit><i class="fa fa-search" aria-hidden="true"></i> 查询</button>
                </div>
            </div>
        </form>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend>文章列表</legend>
        <div class="layui-field-box layui-form">
            <table class="layui-table admin-table">
                <thead>
                <tr>
<!--                    <th style="width: 30px;"><input type="checkbox" lay-filter="allselector" lay-skin="primary"></th>-->
                    <th>ID</th>
                    <th width="42%">文章标题</th>
                    <th>虚拟阅读数</th>
                    <th><a title="点击排序" href="javascript:sort_location('zan')">被赞数</a></th>
                    <th><a title="点击排序" href="javascript:sort_location('yue')">阅读数</a></th>
                    <th><a title="点击排序" href="javascript:sort_location('fen')">分享数</a></th>
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
                    <td><?= $value['views_offset']??0?></td>
                    <td><?= $value['zan']??0?></td>
                    <td><?= $value['views']??0?></td>
                    <td><?= $value['share_num']??0?></td>
                    <td><?= $value['create_time']?></td>
                    <td>
                        <a href="/content.html?id=<?= $value['id']?>" target="_blank" class="layui-btn layui-btn-normal layui-btn-mini">预览</a>
                        <a href="/article/articleEdit?id=<?= $value['id']?>" data-opt="edit" class="layui-btn layui-btn-mini">编辑</a>
                        <a href="javascript:if(confirm('是否需要删除该文章?')) location='/article/articleDelete?id=<?=$value['id']?>';" data-id="1" data-opt="del" class="layui-btn layui-btn-danger layui-btn-mini">删除</a>
                        <?php if (in_array($value['id'],$collection)):?>
                            <a href="javascript:if(confirm('是否取消收藏该文章?')) location='/article/articleCollectionDel?id=<?=$value['id']?>';" class="layui-btn layui-btn-normal layui-btn-small"><i class="layui-icon"></i></a>
                        <?php else:?>
                            <a href="javascript:if(confirm('是否需要收藏该文章?')) location='/article/articleCollectionAdd?id=<?=$value['id']?>';" class="layui-btn layui-btn-primary layui-btn-small"><i class="layui-icon"></i></a>
                        <?php endif;?>
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
<script>
    function sort_location(type) {
        if(location.href.indexOf('?') > 0){
            location=location.href+'&sort='+type
        }else{
            location=location.href+'?sort='+type
        }
    }
</script>

</html>