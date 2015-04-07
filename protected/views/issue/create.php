<h3>Create a New Issue</h3>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-form',
	'htmlOptions' => array(
		'class' => 'form-horizontal',
		'role' => 'form'
	)
)); ?>
	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'title', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->textField($model,'title', array('class' => 'form-control', 'placeholder' => 'Set the Subject of your issue...')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'description', array('class' => 'col-sm-2 control-label')); ?>
		<div class="col-sm-10">
			<?php echo $form->textArea($model,'description', array('class' => 'form-control', 'placeholder' => 'Set the description of your issue...')); ?>
		</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary pull-right col-md-offset-1')); ?>
	</div>
<?php $this->endWidget(); ?>
