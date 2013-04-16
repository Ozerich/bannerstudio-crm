<div class="page <?= Yii::app()->user->role == 'admin' ? 'mode-admin' : '' ?>" id="page_project">
    <input type="hidden" id="project_id" value="<?= $model->id ?>">
    <input type="hidden" id="active_mode" value="worker"/>

    <div class="header">
        <h1><?=$model->name?> (ID <?=$model->id?>):</h1>
        <? if(Yii::app()->user->role != 'worker'): ?>
            <span class="price" mode="customer"><?=$model->customer_price?> руб.</span>
        <? endif; ?>
        <? if(Yii::app()->user->role != 'customer'): ?>
            <span class="price" mode="worker"><?=$model->worker_price?> руб.</span>
        <? endif; ?>
        <span class="status"><?=Project::$statuses[$model->status]?></span>
        <button class="btn btn-small" id="btn_show_description"><i class="icon icon-chevron-down"></i>Развернуть
            описание
        </button>
        <? if(Yii::app()->user->role == 'admin'): ?>
        <a href="/projects/edit/<?= $model->id ?>" class="btn btn-small" id="btn_edit_project" style="display: none"><i
                class="icon icon-pencil"></i>Редактировать</a>
        <? endif; ?>

        <div class="additional-info" style="display: none">

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

            <div class="project-description">
                <? if(Yii::app()->user->role != 'customer'): ?>
                    <p mode="worker"><?=$model->worker_text?></p>
                <? endif; ?>
                <? if(Yii::app()->user->role != 'worker'): ?>
                    <p mode="customer"><?=$model->customer_text?></p>
                <? endif; ?>
            </div>


            <button class="btn btn-small" id="btn_hide_description"><i class="icon icon-chevron-up"></i>Свернуть
                описание
            </button>

        </div>
    </div>


    <div class="comments-form">
        <textarea placeholder="Добавить комментарий"></textarea>
        <button class="btn btn-success" id="btn_add_comment">Написать</button>
        <div class="files">
            <div class="file-item example" style="display: none">

                <div class="file">
                    <a href="#" class="btn-upload">Прикрепить файл</a>
                    <input type="file" name="file">
                </div>

                <div class="filename-block" style="display: none">
                    <span class="filename"></span>
                    <a href="#" class="delete-file">Удалить</a>
                    <span class="status"></span>
                </div>
            </div>
        </div>
    </div>

    <? if(Yii::app()->user->role == 'admin'): ?>

    <div class="buttons-row">
        <a href="#" class="btn" id="btn_to_slider" mode="customer">В слайдер</a>
        <a href="#" class="btn" id="btn_to_customer" mode="worker">Заказчику</a>
        <a href="#" class="btn" id="btn_to_worker" mode="customer">Сотруднику</a>
        <a href="#" class="btn" id="btn_delete">Удалить</a>
    </div>

    <? endif; ?>

    <div class="comments-block">
        <div class="loader"></div>
        <div class="comments-list">
            <?=$this->renderPartial('_comments_list', array('model' => $model));?>
        </div>
    </div>

    <? if(Yii::app()->user->role == 'admin'): ?>

    <div class="project-footer worker">
        <div class="switches">

            <div class="switch customer">
                <span class="switch-name">Клиенты</span>
            </div>

            <div class="switch worker active">
                <span class="switch-name">Сотрудники</span>
            </div>

        </div>
        <span class="summary"><?=$model->name?> (ID <?=$model->id?>) - <span mode="worker"><?=$model->worker_price?></span><span mode="customer"><?=$model->customer_price?></span> руб.</span>
    </div>

    <? endif; ?>

</div>

