<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="description" content="">
    <meta name="author" content="Vital Ozierski, ozicoder@gmail.com">

    <title></title>

    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/smoothness/jquery-ui-1.10.1.custom.min.css">

    <link rel="stylesheet/less" href="/css/base.less"/>

    <? if (Yii::app()->user->isGuest): ?>
        <link rel="stylesheet/less" href="/css/login.less"/>
    <? else: ?>
        <link rel="stylesheet/less" href="/css/styles.less"/>
        <link rel="stylesheet/less" href="/css/responsive/media.less"/>
    <? endif; ?>

    <script src="/js/modernizr.2.6.2.min.js"></script>
    <script src="/js/less-1.3.3.min.js"></script>
    <script src="/js/jquery-ui-1.10.1.custom.min.js"></script>

    <? if (!Yii::app()->user->isGuest): ?>
        <script src="/js/jquery.expandable.js"></script>
        <script src="/js/scripts.js"></script>
        <script src="/js/ajaxfileupload.js"></script>

        <link rel="stylesheet" href="/css/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen"/>
        <script type="text/javascript" src="/js/jquery.fancybox.js?v=2.1.4"></script>

    <? endif; ?>

</head>

<body>

<ul class="flashes">
    <? foreach (Yii::app()->user->getFlashes() as $key => $message): ?>
        <li class="alert alert-<?=$key?> flash-<?= $key; ?>"><?=$message;?></li>
    <? endforeach; ?>
</ul>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">

            <a href="/" class="brand"><img src="/img/logo.png"></a>

            <div class="nav-collapse" id="collapse_0">
                <? if (!Yii::app()->user->isGuest): ?>

                    <ul class="nav">
                        <li><a href="/">Главная</a></li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Проекты <span
                                    class="caret"></span></a>
                            <ul id="yw1" class="dropdown-menu">
                                <? if (Yii::app()->user->checkAccess('Projects.Create')): ?>
                                    <li class="strong"><a tabindex="-1" href="/projects/create">Добавить проект</a></li>
                                <? endif; ?>

                                    <? foreach (Project::FindLastProjects(30) as $project): ?>
                                        <li class="project"><a tabindex="-1"
                                                               href="/projects/<?= $project->id ?>"><?=$project->name?></a>
                                        </li>
                                    <? endforeach; ?>

                                <li class="strong"><a tabindex="-1" href="/">Все проекты</a></li>
                            </ul>
                        </li>

                        <? if (Yii::app()->user->checkAccess('Users.Index') || Yii::app()->user->checkAccess('Users.Create')): ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Администрирование<span
                                        class="caret"></span></a>
                                <ul id="yw3" class="dropdown-menu">

                                    <? if (Yii::app()->user->checkAccess('Users.Index')): ?>
                                        <li><a tabindex="-1" href="/users">Список пользователей</a></li>
                                    <? endif; ?>

                                    <? if (Yii::app()->user->checkAccess('Users.Create')): ?>
                                        <li><a tabindex="-1" href="/users/create">Добавить пользователя</a></li>
                                    <? endif; ?>

                                </ul>
                            </li>
                        <? endif; ?>

                    </ul>
                <? endif; ?>
            </div>

            <? if (!Yii::app()->user->isGuest): ?>


                <div class="profile-block dropdown">

                    <a href="#" class="dropdown-toggle">
                        <div class="user-photo">
                            <img src="<?= Yii::app()->user->getModel()->avatar_url ?>"/>
                        </div>
                        <span class="username"><?=Yii::app()->user->getModel()->login?></span>

                        <div style="clear: both"></div>
                    </a>

                    <ul id="yw4" class="dropdown-menu">
                        <li><a href="/profile">Личные данные</a></li>
                        <li><a href="/logout">Выйти</a></li>
                    </ul>

                </div>

                <div class="message-status-block">
                    <a href="#" class="toggle-messages-list-container"></a>
                    <span></span>

                    <div class="messages-list">
                        <div class="messages-list-container">
                            <? $this->widget('bootstrap.widgets.TbListView', array(
                                'dataProvider' => $this->getCommentsDataProvider(),
                                'itemView' => '/projects/_header_comments_item',
                                'template' => "{items}",
                                'enablePagination' => false,
                            ));
                            ?>
                        </div>
                    </div>
                </div>


            <? endif; ?>
        </div>
    </div>
</div>
<div class="container">
    <? /*
    <div class="row-fluid">

        <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
            'homeLink' => CHtml::link('Главная', '/'),
            'links' => $this->breadcrumbs,
        )); ?>
    </div>
*/?>

    <div class="page-container">
        <? if (isset($page_header)): ?>
            <h1><?=$page_header?></h1>
        <? endif; ?>
        <?=$content?>
    </div>
</div>

</body>