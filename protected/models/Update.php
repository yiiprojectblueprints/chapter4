<?php

/**
 * This is the model class for table "issue_updates".
 *
 * The followings are the available columns in table 'issue_updates':
 * @property integer $id
 * @property integer $issue_id
 * @property integer $author_id
 * @property string $update
 * @property integer $created
 * @property integer $updated
 *
 * The followings are the available model relations:
 * @property Issues $issue
 */
class Update extends CActiveRecord
{
	/**
	 * Whether or not this is an email update
	 * @var boolean $isEmailUpdate
	 */
	public $isEmailUpdate = false;

	/**
	 * Adds the CTimestampBehavior to this class
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' 			=> 'zii.behaviors.CTimestampBehavior',
				'createAttribute' 	=> 'created',
				'updateAttribute' 	=> 'updated',
				'setUpdateOnCreate' => true
			)
		);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'issue_updates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('issue_id, author_id, created, updated', 'numerical', 'integerOnly'=>true),
			array('update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, issue_id, update, created, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'issue' => array(self::BELONGS_TO, 'Issue', 'issue_id'),
			'user' => array(self::BELONGS_TO, 'User', 'author_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'issue_id' => 'Issue',
			'author_id' => 'Author',
			'update' => 'Update',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}

	/**
	 * Sets the author to the currently logged in user
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		// Allow the author_id to be set, but reset it to the logged in user->id if it isn't set
		if ($this->author_id == NULL)
			$this->author_id = Yii::app()->user->id;

		if ($this->update == '')
			return false;

		return parent::beforeSave();
	}

	/**
	 * After save event, handles the sending of emails
	 */
	public function afterSave()
	{
		// If the issue was created by the currently logged in user, or this is an email update, don't send an email
		$issue = Issue::model()->findByPk($this->issue_id);

		// Don't send an email if the customer provides an update, if this came from email, or the status of the issue is resolved
		if ($issue->customer_id == Yii::app()->user->id || $this->isEmailUpdate || $issue->status_id == 5)
			return parent::afterSave();

		// If this is a NEW issue, send the user an email with the detais
		$user = User::model()->findByPk($issue->customer_id);

		// Init the SendGrid object and the Email Object
		$sendgrid = new SendGrid(Yii::app()->params['sendgrid']['username'], Yii::app()->params['sendgrid']['password']);
		$email    = new SendGrid\Email();

		$email->setFrom(Yii::app()->params['sendgrid']['from'])
			  ->addTo($user->email)
			  ->setSubject("[Issue #$issue->id] $this->subject | Issue has been updated")
			  ->setText('Issue has been updated')
			  ->setHtml(Yii::app()->controller->renderPartial('//email/updated', array('issue' => $issue, 'update' => $this, 'user' => $user), true));

		$sendgrid->send($email);

		return parent::afterSave();
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('issue_id',$this->issue_id);
		$criteria->compare('author_id',$this->author_id);
		$criteria->compare('update',$this->update,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'order' => 'created DESC'
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Update the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
