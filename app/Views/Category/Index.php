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
        <a href="/category/categoryAdd" class="layui-btn layui-btn-small" id="add">
            <i class="layui-icon">&#xe608;</i> 添加栏目
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
<!--        <a href="javascript:;" class="layui-btn layui-btn-small" id="search">-->
<!--            <i class="layui-icon">&#xe615;</i> 搜索-->
<!--        </a>-->
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend>栏目列表</legend>
        <div class="layui-field-box layui-form">
            <table class="layui-table admin-table">
                <tbody id="content">
               <?php foreach ($result as $value):?>
                   <?php if(!$value['pid']):?>
                        <tr style="">
                            <td><span style="font-weight: bold">ID:</span>&nbsp;<?= $value['id']?></td>
                            <td><span style="font-weight: bold">栏目名称:</span>&nbsp;<?= $value['title']?></td>
                            <td><span style="font-weight: bold">父级栏目:</span>&nbsp;<?= $value['p_title']?:'无'?></td>
                            <td><span style="font-weight: bold">排序:</span>&nbsp;<?= $value['front_sort']?:0?></td>
                            <td><span style="font-weight: bold">操作;</span>&nbsp;
                                <a href="/category/categoryEdit?id=<?= $value['id']?>" data-opt="edit" class="layui-btn layui-btn-mini">编辑</a>
                                <a href="javascript:if(confirm('是否需要删除该文章?')) location='/category/categoryDelete?id=<?=$value['id']?>';" data-id="1" data-opt="del" class="layui-btn layui-btn-danger layui-btn-mini">删除</a>
                            </td>
                        </tr>
                       <?php foreach ($result as $child_value):?>
                           <?php if($value['id'] == $child_value['pid']):?>
                               <tr>
                                   <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                   <td><span style="font-weight: bold">ID:</span>&nbsp;<?= $child_value['id']?></td>
                                   <td><span style="font-weight: bold">栏目名称:</span>&nbsp;<?= $child_value['title']?></td>
                                   <td><span style="font-weight: bold">父级栏目:</span>&nbsp;<?= $child_value['p_title']?:'无'?></td>
                                   <td><span style="font-weight: bold">排序:</span>&nbsp;<?= $child_value['front_sort']?:0?></td>
                                   <td><span style="font-weight: bold">操作;</span>&nbsp;
                                       <a href="/category/categoryEdit?id=<?= $child_value['id']?>" data-opt="edit" class="layui-btn layui-btn-mini">编辑</a>
                                       <a href="javascript:if(confirm('是否需要删除该文章?')) location='/category/categoryDelete?id=<?=$child_value['id']?>';" data-id="1" data-opt="del" class="layui-btn layui-btn-danger layui-btn-mini">删除</a>
                                   </td>
                               </tr>
                               <?php endif;?>
                       <?php endforeach;?>
                   <?php endif;?>
               <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </fieldset>
    <div class="admin-table-page">
        <div id="paged" class="page">
            <div class="layui-box layui-laypage layui-laypage-default" id="layui-laypage-0">
            </div>
        </div>
    </div>
</div>
</body>

</html>
