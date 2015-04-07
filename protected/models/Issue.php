<?php

/**
 * This is the model class for table "issues".
 *
 * The followings are the available columns in table 'issues':
 * @property integer $id
 * @property integer $customer_id
 * @property string $title
 * @property string $description
 * @property integer $status_id
 * @property integer $created
 * @property integer $updated
 *
 * The followings are the available model relations:
 * @property Attachments[] $attachments
 * @property Users $supporter
 * @property Users $customer
 * @property Statuses $status
 */
class Issue extends CActiveRecord
{
	/**
	 * Attributes from afterFind()
	 * @var array
	 */
	private $_oldAttributes = array();

	/**
	 * Hard set of IsNewRecord
	 * @var boolean
	 */
	private $_isNewRecord = false;

	/**
	 * If this issue was created via an email
	 * @var boolean
	 */
	public $_isEmailCreate = false;

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
		return 'issues';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, status_id, created, updated', 'numerical', 'integerOnly'=>true),
			array('title, description', 'required'),
			array('title', 'length', 'max'=>255),
			array('description', 'safe'),
			array('id, customer_id, supporter_id, title, description, status_id, created, updated', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'User', 'customer_id'),
			'updates' => array(self::HAS_MANY, 'Update', 'issue_id'),
			'status' => array(self::BELONGS_TO, 'Status', 'status_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customer',
			'title' => 'Title',
			'description' => 'Description',
			'status_id' => 'Status',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}

	/**
	 * After finding a user and getting a valid result
	 * store the old attributes in $this->_oldAttributes
	 * @return parent::afterFind();
	 */
	public function afterFind()
	{
		if ($this !== NULL)
			$this->_oldAttributes = $this->attributes;
		return parent::afterFind();
	}

	/**
	 * BeforeSave method to set the customer_id and the the status id
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave()
	{
		if ($this->isNewRecord)
		{
			// If this is a new issue, set the customer_id to be the currently logged in user
			if (!$this->_isEmailCreate)
				$this->customer_id = Yii::app()->user->id;

			// And set the status to 'New'
			$this->status_id = 1;

			// Set IsNewRecord so that afterSave can pick this up
			$this->_isNewRecord = true;
		}
		else // Otherwise reset the customer_id back to what it previously was (prevent it from being changed)
			$this->customer_id = $this->_oldAttributes['customer_id'];

		return parent::beforeSave();
	}

	/**
	 * After save event for sending emails
	 */
	public function afterSave()
	{
		// If this is a NEW issue, send the user an email with the details
		$user = User::model()->findByPk($this->customer_id);

		// Init the SendGrid object and the Email Object
		$sendgrid = new SendGrid(Yii::app()->params['sendgrid']['username'], Yii::app()->params['sendgrid']['password']);
		$email    = new SendGrid\Email();
		$email->setFrom(Yii::app()->params['sendgrid']['from'])
			  ->addTo($user->email);

		if ($this->_isNewRecord)
		{
			$email->setSubject("[Issue #$this->id] $this->subject | A New Issue Has Been Created For You")
				  ->setText('Issue has been created')
				  ->setHtml(Yii::app()->controller->renderPartial('//email/created', array('issue' => $this, 'user' => $user), true));

			// Send the SendGrid email
			$sendgrid->send($email);
		}
		else
		{
			// Send the user an email if the issue has been resolved
			if ($this->status_id == 5 && $this->_oldAttributes['status'] != 5)
			{
				$email->addTo($user->email)
				  ->setSubject("[Issue #$this->id] Issue has been resolved")
				  ->setText('Issue has been resolved')
				  ->setHtml(Yii::app()->controller->renderPartial('//email/resolved', array('issue' => $this, 'user' => $user), true));

				// Send the SendGrid email
				$sendgrid->send($email);
			}
		}
		
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->addSearchCondition('title',$this->title,true, 'OR');
		$criteria->addSearchCondition('description',$this->title,true, 'OR');
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return issue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
