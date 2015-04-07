<?php if (Yii::app()->user->hasFlash('success')): ?>
	<div class="alert alert-success">
		<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
<?php endif; ?>
<h3><?php echo $model->isNewRecord ? 'Create New User' : 'Update User ' . CHtml::encode($model->name) . " (#{$model->id})"; ?></h3>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-form',
	'htmlOptions' => array(
		'class' => 'form-horizontal',
		'role' => 'form'
	)
)); ?>
	
	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'email', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->textField($model,'email', array('class' => 'form-control', 'placeholder' => 'Set the email address')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'name', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->textField($model,'name', array('class' => 'form-control', 'placeholder' => 'Set the name')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'role', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->dropDownList($model, 'role_id', CHtml::listData(Role::model()->findAll(), 'id', 'name'), array('empty'=>'Select Role', 'class' => 'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'password', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->passwordField($model,'password', array('class' => 'form-control', 'placeholder' => 'Set the password. Leave blank to leave unchanged', 'value' => '')); ?>
		</div>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary pull-right col-md-offset-1')); ?>
	</div>
<?php $this->endWidget(); ?>
