<?php

/**
 * @class UserCommand
 * Provides functionality for creating, updating, and deleting users
 */
class UserCommand extends CConsoleCommand 
{
	/**
	 * Creates a user with the provided email and password
	 * @param  email $email      The email address
	 * @param  string $password  The password
	 */
	public function actionCreate($email, $password)
	{
		$model = new User;
		$model->attributes = array(
			'email' => $email,
			'password' => $password
		);

		if (!$model->validate())
			echo "Missing Required Attribute\n";
		else
		{
			try {
				if ($model->save())
					echo "User Created\n";
				else
					print_r($model->getErrors);
				return;
			} catch (Exception $e) {
				print_r($e->getMessage());
			}
		}
	}

	/**
	 * Action to delete a user
	 * @param  email $email      The email address
	 */
	public function actionDelete($email)
	{
		// Retrieve the user and verify that they exist
		$model = User::model()->findByAttributes(array('email' => $email));
		if ($model == NULL)
		{
			echo "No user with that email was found.\n";
			return 0;
		}

		// Delete the user or return an error
		if ($model->delete())
			echo "User has been deleted.\n";
		else
			echo "User could not be deleted.\n";
	}

	/**
	 * Provides functionality to change the user's password
	 * @param  email $email         The email address
	 * @param  string $oldPassword  The old password
	 * @param  string $newPassword  The New Password
	 */
	public function actionChangePassword($email, $oldPassword, $newPassword)
	{
		$model = User::model()->findByAttributes(array('email' => $email));

		if ($model == NULL)
		{
			echo "No user with that email was found.\n";
			return 0;
		}

		if (password_verify($oldPassword, $model->password))
		{
			$model->password = password_hash($newPassword, PASSWORD_BCRYPT, array('cost' => 13));

			if ($model->save())
				echo "Password has been changed.\n";
			else
				echo "Password could not be changed.\n";
		}
		else
			echo "Unable to Verify Old Password.\n";
	}
}