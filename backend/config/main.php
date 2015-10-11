<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'authManager' => [
	    	 'class' => 'yii\rbac\PhpManager',
        	// 'class' => 'yii\rbac\DbManager',
	    ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => 0,//YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            	/* [
            		'class' => 'yii\mongodb\log\MongoDbTarget',
            		'levels' => ['info'],
            		'categories'=>['audit.*'],
            		'logVars' => [],
            	], */
            ],
        ],
	        'errorHandler' => [
	        'errorAction' => 'site/error',
        ],
        'urlManager' => [
	        'enablePrettyUrl' => true,
	        'showScriptName' => false,
	        'rules'=>array(
	        		'<controller:\w+>/<id:\d+>'=>'<controller>/view',
	        		'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
	        		'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
	        		'<controller:media>/<key:[\w.]+><flag:[!$@]*>'=>'<controller>/view',
	        		'<controller:document>/<key:[\w.]+><flag:[!$@]*>'=>'<controller>/view',
	        ),
        ],
        /* 'errorHandler' => [
            'errorAction' => 'site/error',
        ], */
    ],
    'language' => 'th',
    'params' => $params,
];
