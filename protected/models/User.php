<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property integer $role_id
 * @property integer $created
 * @property integer $updated
 *
 * The followings are the available model relations:
 * @property Incidents[] $incidents
 * @property Incidents[] $incidents1
 * @property Roles $role
 */
class User extends CActiveRecord
{
	/**
	 * Attributes from afterFind()
	 * @var array
	 */
	private $_oldAttributes = array();
	
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_id, created, updated', 'numerical', 'integerOnly'=>true),
			array('email, role_id, name, password', 'required'),
			array('email', 'email'),
			array('email, password', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, email, password, role_id, created, updated', 'safe', 'on'=>'search'),
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
			'issues' => array(self::HAS_MANY, 'Issue', 'customer_id'),
			'role' => array(self::HAS_ONE, 'Role', 'id'),
			'updates' => array(self::HAS_MANY, 'Update', 'author_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'password' => 'Password',
			'role_id' => 'Role',
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
	 * Before saving a user's password, password_hash it
	 * @return parent::beforeSave()
	 */
	public function beforeValidate()
	{
		if ($this->password == NULL)
		{
			if (!$this->isNewRecord)
				$this->password = $this->_oldAttributes['password'];
		}
		else
			$this->password = password_hash($this->password, PASSWORD_BCRYPT, array('cost' => 13));

		return parent::beforeSave();
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
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('role_id',$this->role_id);
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
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
