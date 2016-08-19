<?php

/* @var $this yii\web\View */

$this->title = 'Перевод денег со счета на счет';

use yii\helpers\Html;

?>

<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
        <h1 class="text-center">Перевод со счёта на счёт</h1>



        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo \yii\helpers\Url::to(['site/payment'])?>" method="POST" class="form-horizontal">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" /> <!-- <- Защита от CSRF -->
                    <div class="form-group">
                        <div class="col-sm-5">
                            <b>
                                От кого перечислять будем?
                            </b>
                        </div>
                        <div class="col-sm-7">
                            <select name="from_user" class="form-control">
                                <?php echo $users;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-5">
                            <b>
                                Кому перечислять будем?
                            </b>
                        </div>
                        <div class="col-sm-7">
                            <select name="to_user" class="form-control">
                                <?php echo $users;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-5">
                            <b>
                                Сколько?
                            </b>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="amount" value="3.04">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 text-center">
                            <button class="btn btn-success" type="submit">Перечислить!</button>
                        </div>
                    </div>
                    <?php if (isset($error) && $error):?>
                        <div class="form-group">
                            <div class="col-xs-12 text-center">
                                <div class="alert alert-danger">
                                    <?php echo Html::encode($error);?>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                </form>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>
                               ID
                            </th>
                            <th>
                                Имя пользователя
                            </th>
                            <th>
                                Баланс
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $usersTableArray;?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Информация
            </div>
            <div class="panel-body">
                Вся логика переводов находится в файле <code>basic/models/Payments.php</code>
            </div>
        </div>
    </div>
</div>