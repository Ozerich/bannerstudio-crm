<? $workers = $model->workers_list ? explode(',', $model->workers_list) : array();
$customers = $model->customers_list ? explode(',', $model->customers_list) : array(); ?>
<? $all_workers = User::GetWorkers();
$all_customers = User::GetCustomers(); ?>

<div class="form admin-form" id="form_project">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'project-form',
    )); ?>


    <div class="header-block">
        <div class="input">
            <?=$form->textField($model, 'name', array('placeholder' => 'Название проекта'));?>
            <?=$form->error($model, 'name');?>
        </div>
        <div class="status-block">
            <label class="closed"
                   for="closed"><?=$form->checkbox($model, 'closed', array('id' => 'closed'));?> <?=$model->getAttributeLabel('closed');?></label>
            <?=$form->dropDownList($model, 'status', Project::$statuses, $model->closed ? array('class' => 'status-select', 'disabled' => 'disabled') : array('class' => 'status-select'));?>
        </div>
        <br clear="all"/>
    </div>

    <div class="widgets">

        <div class="widget" id="widget_customer">
            <div class="widget-header">
                <i class="icon-list-alt"></i>

                <h3>Проект у заказчика</h3>
            </div>

            <div class="widget-content">

                <div class="price-param param">
                    <?=$form->labelEx($model, 'customer_price');?>
                    <?=$form->textField($model, 'customer_price');?>
                    <?=$form->error($model, 'customer_price');?>
                </div>


                <div class="users-list">
                    <label>Заказчики, допущенные в проект:</label>

                    <div class="lists">

                        <select multiple="multiple" class="source-select">
                            <? foreach ($all_customers as $customer) if (!in_array($customer->id, $customers)): ?>
                            <option value="<?=$customer->id?>"><?=$customer->login?></option>
                            <? endif; ?>
                        </select>

                        <div class="buttons">
                            <a class="btn btn-mini btn-select-right" href="#"><i class="icon-arrow-right"></i></a>
                            <a class="btn btn-mini btn-select-left" href="#"><i class="icon-arrow-left"></i></a>
                        </div>

                        <select multiple="multiple" for="customers_list" class="destination-select">
                            <? foreach ($all_customers as $customer) if (in_array($customer->id, $customers)): ?>
                            <option value="<?=$customer->id?>"><?=$customer->login?></option>
                            <? endif; ?>
                        </select>
                        <?=$form->error($model, 'customers_list');?>


                        <br clear="all"/>
                    </div>


                </div>

                <div class="description-block">
                    <?=$form->labelEx($model, 'customer_text');?>
                    <button class="btn btn-mini" id="insert_from_worker">Вставить от сотрудника</button>
                    <?=$form->textarea($model, 'customer_text', array('class' => 'text'));?>
                    <?=$form->error($model, 'customer_text');?>
                </div>


                <label class="email-remind-block checkbox" for="customer_email_remind">
                    <input type="checkbox" id="customer_email_remind" name="send_customer_email" checked> Написать новым письмо на e-mail
                </label>



            </div>
        </div>

        <div class="widget" id="widget_worker">
            <div class="widget-header">
                <i class="icon-list-alt"></i>

                <h3>Проект у сотрудника</h3>
            </div>

            <div class="widget-content">

                <div class="price-param param">
                    <?=$form->labelEx($model, 'worker_price');?>
                    <?=$form->textField($model, 'worker_price');?>
                    <?=$form->error($model, 'worker_price');?>
                </div>


                <div class="users-list">
                    <label>Сотрудники, допущенные в проект:</label>

                    <div class="lists">

                        <select multiple="multiple" class="source-select">
                            <? foreach ($all_workers as $worker) if (!in_array($worker->id, $workers)): ?>
                            <option value="<?=$worker->id?>"><?=$worker->login?></option>
                            <? endif; ?>
                        </select>

                        <div class="buttons">
                            <a class="btn btn-mini btn-select-right" href="#"><i class="icon-arrow-right"></i></a>
                            <a class="btn btn-mini btn-select-left" href="#"><i class="icon-arrow-left"></i></a>
                        </div>

                        <select multiple="multiple" for="workers_list" class="destination-select">
                            <? foreach ($all_workers as $worker) if (in_array($worker->id, $workers)): ?>
                            <option value="<?=$worker->id?>"><?=$worker->login?></option>
                            <? endif; ?>
                        </select>
                        <?=$form->error($model, 'workers_list');?>

                        <br clear="all"/>
                    </div>
                </div>

                <div class="description-block">
                    <?=$form->labelEx($model, 'worker_text');?>
                    <button class="btn btn-mini" id="insert_from_customer">Вставить от заказчика</button>
                    <?=$form->textarea($model, 'worker_text', array('class' => 'text'));?>
                    <?=$form->error($model, 'worker_text');?>
                </div>


                <label class="email-remind-block checkbox" for="worker_email_remind">
                    <input type="checkbox" id="worker_email_remind" name="send_worker_email" checked> Написать новым письмо на e-mail
                </label>

            </div>
        </div>

        <br clear="all"/>
    </div>


    <div class="submit-buttons">
        <?=CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'btn btn-primary'));?>
    </div>

    <?=$form->hiddenField($model, 'workers_list', array('id' => 'hid_workers_list'));?>
    <?=$form->hiddenField($model, 'customers_list', array('id' => 'hid_customers_list'));?>

    <? $this->endWidget(); ?>
</div>