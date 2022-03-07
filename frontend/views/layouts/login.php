<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use kartik\growl\Growl;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/pos/frontend/web/css/AdminLTE.min.css">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="/pos/frontend/web/favicon.ico" type="image/x-icon">
    <?php $this->head() ?>
    <script src="/pos/frontend/web/js/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).keydown(function(){
            if(event.which == 120) { //F2
                window.location="/pos/frontend/web/sales-details/new";
                return false;
            }
        });
    </script>
</head>
<style type="text/css">
  .well {
      min-height: 20px;
      padding: 19px;
      margin-bottom: 20px;
      background-color: #f9f9f9;
      border: 1px solid #e3e3e3;
      border-radius: 4px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
      box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
  }
</style>
<body>
<?php $this->beginBody() ?>
<div id="genericmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header modal-default">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="modal-title"></h4>
      </div>
        <div class="col-md-12">
            <br>
            <div id="modal-body">
            </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-success" data-dismiss="modal" onclick="btnsendto()">Send</button> -->
      </div>
    </div>

  </div>
</div>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    if (Yii::$app->user->isGuest) {
        //$menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php $plustime=2000;$delay=0;foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
        <?php
        echo Growl::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Hey User!',
            'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
            'showSeparator' => true,
            'delay' => $delay, //This delay is how long before the message shows
            'pluginOptions' => [
                'showProgressbar' => false,
                'delay' => $plustime, //This delay is how long the message shows for
                'placement' => [
                    'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                    'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                ]
            ]
        ]);
        $plustime+=500;
        $delay+=500;
        ?>
        <?php endforeach; ?>
        <!-- <?= Alert::widget() ?> -->
        <div class="well">
          <?= $content ?>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><a>kcDevTeam</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
