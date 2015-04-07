<?php

class IssueController extends CController
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
				'actions' => array('index', 'create', 'update'),
				'users'=>array('@'),
			),
			array('allow',
				'actions' => array( 'search'),
				'users'=>array('@'),
				'expression' => 'Yii::app()->user->role>=2'
			),
			array('allow',
				'actions' => array('emailUpdate'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays issues belonging to the current user and issues belonging to all users if they are a supporter
	 */
	public function actionIndex()
	{
		// Retrieve the issues belonging to the currently logged in user
		$issues = new Issue('search');
        $issues->unsetAttributes();

        if(isset($_GET['Issue']))
            $issues->attributes=$_GET['Issue'];

        // Don't search resolved issues
        $issues->status_id = '<5';

        $issues->customer_id = Yii::app()->user->id;

        // Render the View
		$this->render('index', array(
			'issues' => $issues
		));
	}

	/**
	 * Allows the supporter or admin to search for issues
	 */
	public function actionSearch()
	{
		$issues = new Issue('search');
		$issues->status_id = '<5';

		if (isset($_GET['issue']))
		{
			if (is_numeric($_GET['issue']))
			{
				$issue = Issue::model()->findByPk($_GET['issue']);
				if ($issue != NULL)
					$this->redirect($this->createUrl('issue/update', array('id' => $issue->id)));
			}

			$issues->title = $_GET['issue'];
			$issues->description = $_GET['issue'];
		}
		
		$this->render('search', array(
			'issues' => $issues
		));
	}


	/**
	 * Handles the creation of new issues by the customer
	 */
	public function actionCreate()
	{
		$issue = new Issue;

		// If the POST attributes are set
		if (isset($_POST['Issue']))
		{
			$issue->attributes = $_POST['Issue'];

			if ($issue->save())
			{
				Yii::app()->user->setFlash('success', "Issue #{$issue->id} has successfully been created");
				$this->redirect($this->createUrl('issue/update', array('id' => $issue->id)));
			}
		}

		$this->render('create', array(
			'model' => $issue
		));
	}

	/**
	 * Handles the updating of an issue
	 * @param  int $id
	 */
	public function actionUpdate($id=NULL)
	{
		// Load the necessary models
		$issue = $this->loadModel($id);
		$update = new Update;
		$update->update = NULL;
		$customer_id = $issue->customer_id;

		// If the user is a customer, verify that they own this issue before allowing them to view it
		// Supporters and administrators can view all issues with impunity
		if (Yii::app()->user->role == 1)
		{
			if (Yii::app()->user->id != $customer_id)
				throw new CHttpException(403, 'You do not have permission to view this issue');
		}

		// Allow supporters and administrators to update the issue
		if (Yii::app()->user->role >= 2)
		{
			if (isset($_POST['Issue']))
			{
				$issue->attributes = $_POST['Issue'];
				if ($issue->save())
					Yii::app()->user->setFlash('success', "Issue #{$issue->id} has successfully been updated");
			}
		}

		// Allow everyone who can see the issue to create a new update
		if (isset($_POST['Update']))
		{
			$update->issue_id = $issue->id;
			$update->update = $_POST['Update']['update'];
			if ($update->save())
			{
				Yii::app()->user->setFlash('success', "Issue #{$issue->id} has successfully been updated");
				$this->redirect($this->createUrl('issue/update', array('id' => $issue->id)));
			}
		}

		$this->render('update', array(
			'issue' => $issue,
			'update' => $update,
			'md' => new CMarkdownParser
		));
	}

	/**
	 * This is the SendGrid Parse API response endpoint
	 * Incoming emails will be directed to here
	 */
	public function actionEmailUpdate()
	{
		// This is recieving emails currently
		$from = $this->_parseEmailAddress($_POST['from']);
		$subject = $_POST['subject'];

		// Get the ID from the subject
		$idString = NULL;
		preg_match('/\[Issue #.*?\]/', $subject, $idString);
		$id = str_replace(']', '', str_replace('[Issue #', '', (isset($idString[0]) ? $idString[0] : 0)));

		// Find a user with that email
		$user = User::model()->findByAttributes(array('email' => $from['email']));

		// If we don't recognize this user, create one
		if ($user == NULL)
		{
			$user = new User;
			$user->attributes = array(
				'name' => $from['name'],
				'email' => $from['email'],
				'password' => 'changeme9',
				'role_id' => 1
			);

			// Make sure that we can save the user before continuing.
			if (!$user->save())
				return true;
		}

		// Find the issue with that ID
		$issue = Issue::model()->findByPk($id);

		// If the user or ID are NULL, or that email address doesn't belong to that customer, Create a new issue
		if ($issue == NULL || $id == NULL || $issue->customer_id != $user->id)
		{
			// create the issue, save it, then return - no further work needs to be done.
			$issue = new Issue;
			$issue->_isEmailCreate = true;

			$issue->attributes = array(
				'title' => $subject,
				'description' => $_POST['text'],
				'customer_id' => $user->id
			);			

			$issue->save();
			return true;
		}

		// Split the body along the do not edit line
		$body = explode('--------------- DO NOT EDIT BELOW THIS LINE ---------------', $_POST['text']);
		$body = $body[0];

		// Set the update
		$update = new Update;
		$update->author_id = $user->id;
		$update->issue_id = $issue->id;
		$update->update = $body;
		$update->isEmailUpdate = true;

		$update->save();
		return true;
 	}

 	/**
 	 * Parses the SendGrid email address into an array we can use
 	 * @param string $raw 	The raw email data
 	 */
	private function _parseEmailAddress($raw)
	{
		$name = "";
		$email = trim($raw, " '\"");

		if (preg_match("/^(.*)<(.*)>.*$/", $raw, $matches))
		{
			array_shift($matches);
			$name = trim($matches[0], " '\"");
			$email = trim($matches[1], " '\"");
		}

		return array(
			"name" => $name,
			"email" => $email,
			"full" => $name . " <" . $email . ">"
		); 
	}

	/**
	 * Loads the issue model
	 * @param  int $id
	 * @return Issue model
	 */
	private function loadModel($id=NULL)
	{
		if ($id == NULL)
			throw new CHttpException(400, 'Missing ID');

		$model = Issue::model()->findByPk($id);

		if ($model == NULL)
			throw new CHttpException(404, 'No issue with that ID was found');

		return $model;
	}
}