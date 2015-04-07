<?php

class UserController extends CController
{
	/**
	 * AccessControl filter
	 * @return array
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * AccessRules, only authenticated users can access this page
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('search', 'view'),
				'users'=>array('@'),
				'expression' => 'Yii::app()->user->role>=2'
			),
			array('allow',
				'actions' => array('index', 'save', 'delete'),
				'users'=>array('@'),
				'expression' => 'Yii::app()->user->role==3'
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a list of all the available users
	 */
	public function actionIndex()
	{
		$users = new User('search');
		$users->unsetAttributes();

		if (isset($_GET['User']))
			$users->attributes = $_GET['Users'];

		$this->render('index', array(
			'model' => $users
		));
	}

	/**
	 * Creates a new user or updates an existing user
	* @param  int $id
	 */
	public function actionSave($id=NULL)
	{
		if ($id == NULL)
			$user = new User;
		else
			$user = $this->loadModel($id);

		if (isset($_POST['User']))
		{
			$user->attributes = $_POST['User'];

			try
			{
				if ($user->save())
				{
					Yii::app()->user->setFlash('success', 'The user has sucessfully been updated');
					$this->redirect($this->createUrl('user/save', array('id' => $user->id)));
				}
			} catch (Exception $e) {
				$user->addError('email', 'A user with that email address already exists');
			}
		}

		$this->render('save', array(
			'model' => $user
		));
	}

	/**
	 * Shows the users currently assigned issues and some information relating to that issue
	 * @param  int $id
	 */
	public function actionView($id=NULL)
	{
		// Retrieve the requested user
		$user = $this->loadModel($id);

		// Retrieve issues assigned to this user that are NOT resolved
		$issues = new Issue('search');
        $issues->unsetAttributes();

        if(isset($_GET['Issue']))
            $issues->attributes=$_GET['Issue'];

        // Don't search resolved issues
        $issues->status_id = '<5';

        $issues->customer_id = $user->id;

        $this->render('view', array(
        	'user' 	 => $user,
			'issues' => $issues
		));
	}

	/**
	 * Delets a particular user
	 * @param  int $id
	 */
	public function actionDelete($id=NULL)
	{
		if ($id == Yii::app()->user->id)
			throw new CHttpException(403, 'You cannot delete yourself');

		$user = $this->loadModel($id);

		if ($user->delete())
			$this->redirect($this->createUrl('user/index'));

		throw new CHttpException(400, 'Bad Request');
	}

	/**
	 * Loads a model with a given ID, and throws the appropriate error
	 * @param  int $id
	 * @return User::model
	 */
	private function loadModel($id=NULL)
	{
		if ($id == NULL)
			throw new CHttpException(400, 'Missing ID');

		$model = User::model()->findByPk($id);

		if ($model == NULL)
			throw new CHttpException(404, 'No user with that ID could be found');

		return $model;
	}
}