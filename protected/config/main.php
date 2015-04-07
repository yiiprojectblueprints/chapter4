<?php return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Issue Tracking',

	'import'=>array(
		'application.models.*',
	),

	'components'=>array(
		//CREATE USER 'ch4_issue'@'localhost' IDENTIFIED BY 'ch4_issue';
		//CREATE DATABASE IF NOT EXISTS  `ch4_issue` ;
		//GRANT ALL PRIVILEGES ON  `ch4\_issue` . * TO  'ch4_issue'@'localhost';
		'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=127.0.0.1;dbname=ch4_issue',
            'emulatePrepare' => true,
            'username' => 'ch4_issue',
            'password' => 'ch4_issue',
            'charset' => 'utf8',
            'schemaCachingDuration' => '3600',
            'enableProfiling' => true,
        ),

		'errorHandler'=>array(
            'errorAction'=>'site/error',
        ),
        
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'/' => '/issue/index',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		)
	),

	'params' => array(
		'sendgrid' => require __DIR__ . '/params.php'
	)
);