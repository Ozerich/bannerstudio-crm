<div class="page <?= Yii::app()->user->role == 'admin' ? 'mode-admin' : '' ?>" id="page_project">
    <input type="hidden" id="project_id" value="<?= $model->id ?>">
    <input type="hidden" id="active_mode" value="<?= $mode ?>"/>

    <div class="header">
        <h1><?=$model->name?> (ID <?=$model->id?>):</h1>

        <? if (Yii::app()->user->role != 'worker'): ?>
            <span style="display: <?= $mode == 'customer' ? 'inline-block' : 'none' ?>" class="price"
                  mode="customer"><?=$model->customer_price?></span>
        <? endif; ?>
        <? if (Yii::app()->user->role != 'customer'): ?>
            <span style="display: <?= $mode == 'worker' ? 'inline-block' : 'none' ?>" class="price"
                  mode="worker"><?=$model->worker_price?></span>
        <? endif; ?>

        <span class="status"><?=$model->closed ? 'ЗАКРЫТ' : Project::$statuses[$model->status]?></span>
        <button class="btn btn-small" id="btn_show_description"><i class="icon icon-chevron-down"></i>Развернуть
            описание
        </button>

        <? if (Yii::app()->user->role == 'admin'): ?>
            <a href="/projects/edit/<?= $model->id ?>" class="btn btn-small" id="btn_edit_project"
               style="display: none"><i
                    class="icon icon-pencil"></i>Редактировать</a>
        <? endif; ?>

        <div class="additional-info" style="display: none">

            <? if (Yii::app()->user->role == 'admin'): ?>

                <div class="persons">
                    <div class="persons-list">
                        <h2>Сотрудники</h2>
                        <ul>
                            <? foreach ($model->workers as $person): ?>
                                <li><a href="/users/<?= $person->id ?>"><?=$person->display_name?></a></li>
                            <? endforeach; ?>
                        </ul>
                    </div>

                    <div class="persons-list">
                        <h2>Клиенты</h2>
                        <ul>
                            <? foreach ($model->customers as $person): ?>
                                <li><a href="/users/<?= $person->id ?>"><?=$person->display_name?></a></li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </div>

            <? endif; ?>

            <div class="project-description">
                <? if (Yii::app()->user->role != 'customer'): ?>
                    <p mode="worker"
                       style="display: <?= $mode == 'worker' ? 'block' : 'none' ?>"><?=$model->prepareText($model->worker_text);?></p>
                <? endif; ?>
                <? if (Yii::app()->user->role != 'worker'): ?>
                    <p mode="customer"
                       style="display: <?= $mode == 'customer' ? 'block' : 'none' ?>"><?=$model->prepareText($model->customer_text);?></p>
                <? endif; ?>
            </div>

            <button class="btn btn-small" id="btn_hide_description"><i class="icon icon-chevron-up"></i>Свернуть
                описание
            </button>

        </div>
    </div>

    <? if (Yii::app()->user->role != 'worker'): ?>
        <div id="slider" mode="customer"
             style="display: <?= count($model->slider_pages) > 0 && $mode == 'customer' ? 'block' : 'none' ?>">
            <div class="loader" style="display: none"></div>
            <div class="slider-container">
            </div>
        </div>
    <? endif; ?>

    <div class="comments-form"><div class="disabled" style="display: none"></div>
        <form method="post" id="new_comment_form" enctype="multipart/form-data" action="/projects/add_comment">

            <input type="hidden" name="project_id" value="<?=$model->id?>">
            <input type="hidden" name="mode" value="<?=$mode;?>">
            <textarea name="message" placeholder="Добавить комментарий"></textarea>

            <button class="btn btn-success" id="btn_add_comment">Написать</button>
            <div class="files">
                <div class="file-item example" style="display: none">

                    <div class="file">
                        <input type="file" name="file[]">
                        <span class="btn-upload">Прикрепить файл</span>
                    </div>

                    <div class="filename-block" style="display: none">
                        <span class="filename"></span>
                        <a href="#" class="delete-file">Удалить</a>
                        <span class="status"></span>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <button class="btn btn-mini btn-info mark_all_read-btn" style="display: none">Все сообщения прочитаны</button>


    <? if (Yii::app()->user->role == 'admin'): ?>

        <div class="buttons-row">
            <a href="#" class="btn" id="btn_to_slider" mode="customer"
               style="display: <?= $mode == 'customer' ? 'inline' : 'none' ?>">В слайдер</a>
            <a href="#" class="btn" id="btn_to_customer" mode="worker"
               style="display: <?= $mode == 'worker' ? 'inline' : 'none' ?>">Заказчику</a>
            <a href="#" class="btn" id="btn_to_worker" mode="customer"
               style="display: <?= $mode == 'customer' ? 'inline' : 'none' ?>">Сотруднику</a>
            <a href="#" class="btn" id="btn_delete_files">Удалить</a>
        </div>

    <? endif; ?>

    <div class="comments-block">
        <div class="loader"></div>
        <div class="comments-list">
            <?=$this->renderPartial('_comments_list', array('model' => $model, 'mode' => $mode));?>
        </div>
    </div>

    <? if (Yii::app()->user->role == 'admin'): ?>

        <div class="project-footer <?= $mode ?>">


            <div class="switches">

                <div class="switch customer <?= $mode == 'customer' ? 'active' : '' ?>">
                    <? $customers = $model->getUsers('customer'); $customer = null; if ($customers) $customer = $customers[0]; ?>

                    <div class="avatar">
                        <? if ($customer): ?>
                            <img src="<?= $customer->avatar_url ?>">
                        <? endif; ?>
                    </div>

                    <div class="text">
                        <span class="switch-name">Клиенты (<?=count($customers);?>)</span>
                        <span class="user-name"><?=$customer ? $customer->display_name : ''?></span>

                    </div>
                </div>

                <div class="switch worker <?= $mode == 'worker' ? 'active' : '' ?>">
                    <? $workers = $model->getUsers('worker'); $worker = null; if ($workers) $worker = $workers[0]; ?>

                    <div class="avatar">
                        <? if ($worker): ?>
                            <img src="<?= $worker->avatar_url ?>">
                        <? endif; ?>
                    </div>

                    <div class="text">
                        <span class="switch-name">Сотрудники (<?=count($workers);?>)</span>

                        <span class="user-name"><?=$worker ? $worker->display_name : '&nbsp;'?></span>

                    </div>
                </div>

            </div>
            <span class="summary"><?=$model->name?> (ID <?=$model->id?>) - <span
                    mode="worker"
                    style="display: <?= $mode == 'worker' ? 'inline' : '' ?>"><?=$model->worker_price?></span><span
                    mode="customer"
                    style="display: <?= $mode == 'customer' ? 'inline' : '' ?>"><?=$model->customer_price?></span></span>
        </div>

    <? endif; ?>

</div>

<div id="popup_form" class="popup-form" style="display: none">
    <input type="hidden" class="direction" value="">
    <textarea placeholder="Добавить комментарий" spellcheck="false"></textarea>
    <ul class="files">
    </ul>
    <div class="footer">
        <button class="btn btn-success submit-popup">Написать</button>
    </div>
</div>

<div id="popup_slider" class="popup-form" style="display:none">
    <label>Страница:</label>
    <select>
        <option value="0">Новая страница</option>
    </select>
    <button class="btn btn-success">Добавить</button>
</div>

<div id="popup_html_slider" class="popup-form" style="display: none">
    <input type="hidden" class="page-id"/>
    <label>HTML код:</label>
    <textarea></textarea>
    <button class="btn btn-success">Добавить</button>
</div>