<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Bannerstudio panel',

    'language' => 'ru',

    'preload' => array('bootstrap', 'log'),

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.yii-mail.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
    ),

    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            'ipFilters' => array('127.0.0.1', '::1'),
        ),

        'rights' => array(
            'userNameColumn' => 'login',
            'debug' => true,
        )
    ),

    'components' => array(

        'phpThumb' => array(
            'class' => 'ext.PhpThumb.EPhpThumb',
            'options' => array()
        ),

        'prettydate'=>array(
            'class'=>'PrettyDate'
        ),

        'user' => array(
            'class' => 'WebUser',
            'loginUrl' => array('/login'),
            'allowAutoLogin' => true,
        ),

        'mail' => array(
            'class' => 'application.extensions.yii-mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.email',
            'logging' => true,
            'dryRun' => false
        ),

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(

                'gii' => 'gii',
                'gii/<controller:\w+>' => 'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',

                'login' => 'auth/login',
                'logout' => 'auth/logout',
                'register' => 'auth/register',
                'forget_password' => 'auth/forget_password',

                'profile' => 'users/profile',

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),

        'bootstrap' => array(
            'class' => 'application.extensions.bootstrap.components.Bootstrap',
        ),

        'db' => require(dirname(__FILE__) . '/db.php'),

        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),

        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),

        'authManager' => array(
            'class' => 'RDbAuthManager',
        ),
    ),

    'params' => array(
        'upload_avatar' => '/uploads/avatars/',
        'email_admin' => 'admin@admin.ru',
        'admin_emails' => array('ozicoder@gmail.com'),
    ),
);