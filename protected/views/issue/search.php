<h3>Search for Issues</h3>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-form',
	'method' => 'get',
	'htmlOptions' => array(
		'class' => 'form-horizontal',
		'role' => 'form',
	)
)); ?>
	<p>Search for issues...</p>
	<div class="form-group">
		<?php echo CHtml::textField('issue', isset($_GET['issue']) ? $_GET['issue'] : NULL, array('class' => 'form-control', 'placeholder' => 'Search for Issues by ID, Title, or Description...')); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary pull-right col-md-offset-1')); ?>
	</div>
<?php $this->endWidget(); ?>

<?php if ($issues != NULL): ?>
	<?php $this->renderPartial('issues', array('model' => $issues)); ?>
<?php endif; ?>