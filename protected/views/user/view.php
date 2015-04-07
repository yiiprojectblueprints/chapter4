<div clas="row buttons">
	<h3 class="pull-left">About <?php echo CHtml::encode($user->name); ?></h3>
	<?php echo CHtml::link('Update User', $this->createUrl('user/save', array('id' => $user->id)), array('class' => 'btn btn-primary pull-right col-md-offset-1')); ?>
</div>
<div class="clearfix"></div>
<dl class="dl-horizontal">
  <dt>Name</dt>
  <dd><?php echo CHtml::encode($user->name); ?></dd>

  <dt>Email Address</dt>
  <dd><?php echo CHtml::encode($user->email); ?></dd>

  <dt>Created On</dt>
  <dd><?php echo gmdate("F m, Y @ H:i", $user->created); ?> UTC</dd>

  <dt>Last Updated</dt>
  <dd><?php echo gmdate("F m, Y @ H:i", $user->updated); ?> UTC</dd>

  <dt></dt>
  <dd></dd>
</dl>

<hr />

<h3>Issues Belonging to <?php echo CHtml::encode($user->name); ?> (<?php echo CHtml::encode($user->email); ?>)</h3>
<?php $this->renderPartial('//issue/issues', array('model' => $issues)); ?>