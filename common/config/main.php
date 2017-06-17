<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            //'class' => 'yii\rbac\DbManager',//两种方式增加主键
            'class' => yii\rbac\DbManager::className(),
        ],
    ],
];
