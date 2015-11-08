<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'aliases' => [
				'@app' => '@common/libs/app',
	],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
