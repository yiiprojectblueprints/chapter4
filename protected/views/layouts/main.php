<!DOCTYPE html>
<html>
	<head>
        <title><?php echo CHtml::encode(Yii::app()->name); ?></title>

        <?php Yii::app()->clientScript
        				->registerMetaTag('text/html; charset=UTF-8', 'Content-Type')
        				->registerCssFile('//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css')
        				->registerCssFile('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css')
        				->registerCssFile('//fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700')
        				->registerCssFile(Yii::app()->baseUrl . '/css/dashboard.css')
        				->registerScriptFile('//code.jquery.com/jquery.js')
        				->registerScriptFile('//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js');
        ?>
	</head>
	<body>
		<div class="row">
			<div class="container">
				<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
					<div class="navbar-header">
					    <a class="navbar-brand" href="/"><?php echo CHtml::encode(Yii::app()->name); ?></a>
					</div>
				</nav>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row" style="margin-top: 100px;">
				<div class="col-sm-3 col-md-2 sidebar">
	            	<?php $this->widget('zii.widgets.CMenu', array(
	            		'htmlOptions' => array(
	            			'class' => 'nav nav-sidebar'
	            		),
	            		'items' => array(
	            			array('label' => 'My Issues', 'url' => $this->createAbsoluteUrl('/')),
	            			array('label' => 'Create New Issue', 'url' => $this->createUrl('issue/create')),
	            			array('label' => 'Search for Issues', 'url' => $this->createUrl('issue/search')),
	            			array('label' => 'Manage Users', 'url' => $this->createUrl('user/index')),
	            			array('label' => 'Create New User', 'url' => $this->createUrl('user/save')),
	            			array('label' => 'Logout', 'url' => $this->createUrl('site/logout'))
	            		)
	            	)); ?>
		        </div>
	        	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<?php echo $content; ?>
				</div>
			</div>
		</div>
	</body>
</html>