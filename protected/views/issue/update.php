<?php if (Yii::app()->user->hasFlash('success')): ?>
		<div class="alert alert-success">
			<?php echo Yii::app()->user->getFlash('success'); ?>
		</div>
	<?php endif; ?>
<h3><?php echo $issue->title; ?> (#<?php echo $issue->id; ?>)</h3>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'project-form',
	'htmlOptions' => array(
		'class' => 'form-horizontal',
		'role' => 'form'
	)
)); ?>
	<?php echo $form->errorSummary($issue); ?>

	<?php if (Yii::app()->user->role >= 2): ?>
		<div>
			<h4>Supporter Options</h4>
			<div class="form-group">
				<?php echo $form->labelEx($issue,'status_id', array('class' => 'col-sm-2 control-label')); ?>
				<div class="col-sm-10">
					<?php echo $form->dropDownList($issue, 'status_id', CHtml::listData(Status::model()->findAll('id>=2'), 'id', 'name'), array('empty'=>'Select Status', 'options' => array(2 => array('selected' => 'selected')), 'class' => 'form-control')); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<hr />
	<div>
		<h4>Description</h4>
		<p><?php echo $md->safeTransform($issue->description); ?></p>
	</div>
	<hr />
	<div>
		<h4>Updates</h4>
		<?php foreach ($issue->updates as $update): ?>
			<div class="update">
				<strong>By: <?php echo CHtml::link(CHtml::encode($update->user->name), $this->createUrl('user/view', array('id' => $update->user->id))); ?></strong>  <em><?php echo gmdate('F m, Y @ H:i'); ?> UTC</em>
				<div class="update-body">
					<?php echo $md->safeTransform($update->update); ?>
				</div>
			</div>
		<?php endforeach; ?>

		<div class="form-group">
			<?php echo $form->labelEx($update,'update', array('class' => 'col-sm-2 control-label')); ?>
			<div class="col-sm-10">
				<?php echo $form->textArea($update, 'update', array('value' => '', 'class' => 'form-control', 'placeholder' => 'Add your update here')); ?>
			</div>
		</div>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($issue->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary pull-right col-md-offset-1')); ?>
	</div>
<?php $this->endWidget(); ?>
