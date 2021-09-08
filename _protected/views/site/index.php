<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = Yii::t('app', Yii::$app->name);
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Cart API</h1>

        <p class="lead"><?= Yii::t('app', 'Welcome to Cart Api dashboard.') ?></p>

        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['site/login']) ?>"><?= Yii::t('app', 'Login to get token') ?></a></p>

        <div class="body-content">

            <div class="row">
                <div class="col-lg-12">
                    <p></p>
                    <p class="lead">Default admin login: <span class="alert alert-info">cartapi</span> , password: <span class="alert alert-info">cartapi</span> </p>
                        <p class="lead">Default user login: <span class="alert alert-info">test</span> , password: <span class="alert alert-info">testtest</span></p>
                    
                </div>
            </div>
        </div>
    </div>

</div>

