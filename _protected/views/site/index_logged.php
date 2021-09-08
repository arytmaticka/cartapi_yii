<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', Yii::$app->name);
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Cart API</h1>

        <p class="lead"><?= Yii::t('app', 'Welcome to Cart Api dashboard.') ?></p>
        <h2><?= Yii::t('app', 'Your token is:') ?></h2>
        <div class="alert alert-success" role="alert">
            <?= $token ?>
        </div>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <h3>Running docker image</h3>

                <p>Running docker image with docker-compose in project folder</p>
                <pre><code>docker-compose up</code></pre>
                <p>Run in browser on <a href="http://127.0.0.1">http://127.0.0.1</a></p>
                <p>Log in using default login and password. There is exposed mysql database <i>cartapi</i> on port 3306 and testing database <i>cartapi_test</i> on port 3307</p>
                <p>Api is exposed under two addresses:</p>
                <ul>
                    <li>Cart api: <a href="http://127.0.0.1/api/cart">http://127.0.0.1/api/cart</a></li>
                    <li>Products api: <a href="http://127.0.0.1/api/products">http://127.0.0.1/api/products</a>. Paggination is made using page GET <i>page</i> attribute.
                        <br/>
                        For example command:
                        <pre>curl -i -H "Accept:application/json" "http://<?= $token ?>@127.0.0.1/api/products?page=2"</pre>
                        Will show second page of products list. 
                    </li>
                </ul>
                <p>Token is needed for authentication and it's used as the username in HTTP basic authentication.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h3>Testing using codeception in doceker</h3>

                <p>To run cedeception tests after containers are started use command:</p>
                <pre>docker-compose exec php sh -c "cd /app/web/_protected/tests && ../vendor/codeception/codeception/codecept run api"</pre>

            </div>
        </div>

    </div>

</div>
</div>

