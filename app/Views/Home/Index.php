<?php include __DIR__.'/../Common/header.php'?>
			<div class="layui-side layui-bg-black" id="admin-side">
				<div class="layui-side-scroll" id="admin-navbar-side" lay-filter="side">

                </div>
			</div>
			<div class="layui-body" style="bottom: 0;border-left: solid 2px #1AA094;" id="admin-body">
				<div class="layui-tab admin-nav-card layui-tab-brief" lay-filter="admin-tab">
					<ul class="layui-tab-title">
						<li class="layui-this">
							<i class="fa fa-dashboard" aria-hidden="true"></i>
							<cite>控制面板</cite>
						</li>
					</ul>
					<div class="layui-tab-content" style="min-height: 150px; padding: 5px 0 0 0;">
						<div class="layui-tab-item layui-show">
							<iframe src="/home/main"></iframe>
						</div>
					</div>
				</div>
			</div>
<?php include __DIR__.'/../Common/footer.php'?>
			<div class="site-tree-mobile layui-hide">
				<i class="layui-icon">&#xe602;</i>
			</div>
			<div class="site-mobile-shade"></div>

			<!--锁屏模板 start-->
			<script type="text/template" id="lock-temp">
				<div class="admin-header-lock" id="lock-box">
					<div class="admin-header-lock-img">
						<img src="/static/images/0.jpg"/>
					</div>
					<div class="admin-header-lock-name" id="lockUserName">beginner</div>
					<input type="text" class="admin-header-lock-input" value="输入密码解锁.." name="lockPwd" id="lockPwd" />
					<button class="layui-btn layui-btn-small" id="unlock">解锁</button>
				</div>
			</script>
			<!--锁屏模板 end -->

			<script type="text/javascript" src="/static/plugins/layui/layui.js"></script>
			<script type="text/javascript" src="/static/datas/nav.js"></script>
			<script src="/static/js/index.js"></script>
			<script>
				layui.config({
                    base:'/static/js/'
                }).use('layer', function() {
					var $ = layui.jquery,
						layer = layui.layer;

					$('#video1').on('click', function() {
						layer.open({
							title: 'YouTube',
							maxmin: true,
							type: 2,
							content: 'video.html',
							area: ['800px', '500px']
						});
                    });
                    $('#pay').on('click', function () {
                        layer.open({
                            title: false,
                            type: 1,
                            content: '<img src="images/xx.png" />',
                            area: ['500px', '250px'],
                            shadeClose: true
                        });
                    });


				});
			</script>
		</div>
	</body>

</html>