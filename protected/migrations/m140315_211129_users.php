<?php

class m140315_211129_users extends CDbMigration
{
	// Abstract Column types
	// http://www.yiiframework.com/doc/api/1.1/CDbSchema#getColumnType-detail
	public function safeUp()
	{
		// Create the Roles table
		$this->createTable('roles', array(
			'id' 		=> 'pk',
			'name' 		=> 'string',
			'created' 	=> 'integer',
			'updated' 	=> 'integer'
		));

		// Create the users table
		$this->createTable('users', array(
			'id' 		=> 'pk',
			'email'	 	=> 'string',
			'password' 	=> 'string',
			'name' 		=> 'string',
			'role_id'	=> 'integer',
			'created' 	=> 'integer',
			'updated' 	=> 'integer'
		));

		// Create a unique index on the email column
		$this->createIndex('email_index', 'users', 'email', true);

		// Create a Foreign key on users::role_id -> roles::id
		$this->addForeignKey('user_roles', 'users', 'role_id', 'roles', 'id', NULL, 'CASCADE', 'CASCADE');
	}

	public function safeDown()
	{
		echo "m140315_211129_users does not support migration down.\n";
		return false;
	}

}