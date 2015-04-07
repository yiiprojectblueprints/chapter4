<?php

class SiteController extends CController
{
	public $layout = 'signin';

	public function actionLogin()
	{
		$model = new LoginForm;

		if (isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];

			if ($model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}

		$this->render('login', array('model' => $model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect($this->createUrl('/site/login'));
	}

	public function actionError()
	{
		$this->layout = 'main';
		if($error=Yii::app()->errorHandler->error)
			$this->render('error', array('error' => $error));
	}
}