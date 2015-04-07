--------------- DO NOT EDIT BELOW THIS LINE ---------------
<div class="email">
	Hello <?php echo $user->name; ?>,<br /><br />

	This is a notification that a new issue (#<?php echo $issue->id; ?>) has been updated with the following message:<br /><br />

	<blockquote>
		<?php echo $update->update; ?>
	</blockquote>

	<hr />

	As a reminder, here is the description of the issue you provided:<br /><br />

	<strong><?php echo $issue->title; ?></strong>
	<blockquote>
		<?php echo $issue->description; ?>
	</blockquote>

	<br /><br />

	To reply to this issue you may either reply to this email, or login <?php echo CHtml::link('here', $this->createAbsoluteUrl('issue/update', array('id' => $issue->id))); ?>.
	<br /><br />

	Thank you,<br />
	Issue Tracking System
</div>