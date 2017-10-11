<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>添加栏目</title>
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
				<legend>添加栏目</legend>
			</fieldset>

			<form class="layui-form" method="post" action="">
                <input name="__RequestVerificationToken" type="hidden" value="<?=$this->e($token)?>" />
				<div class="layui-form-item">
					<label class="layui-form-label">栏目名称</label>
					<div class="layui-input-inline">
						<input type="text" value="<?=$result[0]['title']?>" name="title" lay-verify="title" autocomplete="off" placeholder="请输入名称" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">父栏目</label>
					<div class="layui-input-inline">
						<select name="pid" lay-filter="aihao">
							<option value="0">顶级</option>
                            <?php foreach ($list as $value):?>
                                <?php if($value['id'] == $result[0]['pid']):?>
							        <option selected="selected" value="<?=$value['id']?>"><?=$value['title']?></option>
                                <?php else:?>
                                    <option value="<?=$value['id']?>"><?=$value['title']?></option>
                                <?php endif;?>
                            <?php endforeach;?>
						</select>
					</div>
				</div>
                <div class="layui-form-item">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" name="front_sort" autocomplete="off" value="<?=$result[0]['front_sort']?>" class="layui-input">
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