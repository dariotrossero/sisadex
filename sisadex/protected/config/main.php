<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'language'=>'es',
	'name'=>'Sis.Ad.Ex',
 	'theme'=>'bootstrap', // requires you to copy the theme under your themes directory
	// preloading 'log' component
	'preload'=>array('log',
		 'bootstrap'// preload the bootstrap component
		 ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.opt.*',
		
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'admin',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),*/
	'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'admin',
                         'generatorPaths' => array(
                                                   'ext.ajaxgii', 
                                                 ),
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
        ),

		
	),

	// application components
	'components'=>array(

		
 		'coreMessages'=>array(
            'basePath'=>null,
        ),
		'errorHandler'=>array(
            'errorAction'=>'site/error',
        ),
       'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		'user'=>array(
 // enable cookie-based authentication
     'allowAutoLogin'=>true,
     'loginUrl' => array('/site/login'),
      'class'=>'WebUser',
      ),
		'config'=>array
                (
                        'class'=>'ext.FileConfig',
                        'configFile'=>'appConfig/config.values',
                ),
		   //
			//  IMPORTANTE:  asegurate de que la entrada 'user' (y format) que por defecto trae Yii
			//               sea sustituida por estas a continuación:
			//
			
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			  
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/examenes.db',
			 'initSQLs'=>array(
                                'PRAGMA foreign_keys = ON',
                        ),
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
					
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
				),
				
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'dariotrossero@gmail.com',
	),
);
