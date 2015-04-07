<?php

class m140315_211132_issues extends CDbMigration
{
	public function safeUp()
	{
		// Create the statuses table
		$this->createTable('statuses', array(
			'id' 		=> 'pk',
			'name' 		=> 'string',
			'created' 	=> 'integer',
			'updated' 	=> 'integer'
		));

		// Create the attachments table
		$this->createTable('attachments', array(
			'id' 			=> 'pk',
			'issue_id' 		=> 'integer',
			'url'			=> 'string',
			'created' 		=> 'integer',
			'updated' 		=> 'integer'
		));

		// Create the issues table
		$this->createTable('issues', array(
			'id' 			=> 'pk',
			'customer_id' 	=> 'integer',
			'title' 		=> 'string',
			'description'	=> 'text',
			'status_id'		=> 'integer',
			'created' 		=> 'integer',
			'updated' 		=> 'integer'
		));

		$this->createTable('issue_updates', array(
			'id' 			=> 'pk',
			'issue_id' 		=> 'integer',
			'author_id' 	=> 'integer',
			'update' 		=> 'text',
			'created' 		=> 'integer',
			'updated' 		=> 'integer'
		));

		// Create a Foreign key on issues::status_id -> statuses::id
		$this->addForeignKey('issue_status', 'issues', 'status_id', 'statuses', 'id', NULL, 'CASCADE', 'CASCADE');

		// Create a Foreign key on attachments::issue_id -> issues::id
		$this->addForeignKey('issue_attachments', 'attachments', 'issue_id', 'issues', 'id', NULL, 'CASCADE', 'CASCADE');

		// Create a Foreign key on users::role_id -> roles::id
		$this->addForeignKey('issue_customer', 'issues', 'customer_id', 'users', 'id', NULL, 'CASCADE', 'CASCADE');

		// Create a Foreign Key on issue_updates::issue_id -> issues::id
		$this->addForeignKey('issue_updates', 'issue_updates', 'issue_id', 'issues', 'id', NULL, 'CASCADE', 'CASCADE');
		$this->addForeignKey('update_author', 'issue_updates', 'author_id', 'users', 'id', NULL, 'CASCADE', 'CASCADE');
	}

	public function safeDown()
	{
		echo "m140315_211132_issues does not support migration down.\n";
		return false;
	}
}