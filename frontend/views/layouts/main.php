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
  a{
      cursor: pointer;
  }
  .grid-view td {
    white-space:pre-line; // or just 'normal'
  }
  .rightni{
    text-align: right;
  }
  .navbar-brand {
      float: left;
      height: 50px;
      padding: 0px;
      font-size: 18px;
      line-height: 20px;
  }
  .wrap > .container {
      background-color: #f0f3f3;
  }
  .breadcrumb {
      background-color: #f9f9f9;
  }
  .navbar-nav {
      height: 50px;
  }
  .select2-container--krajee .select2-selection {
      -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
      box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 0px;
      color: #555555;
      font-size: 14px;
      outline: 0;
  }
  .select2-container--krajee .select2-selection--single .select2-selection__arrow {
      border-top-width: initial;
      border-right-width: initial;
      border-bottom-width: initial;
      border-top-color: initial;
      border-right-color: initial;
      border-bottom-color: initial;
      border-top-right-radius: 4px;
      border-bottom-right-radius: 4px;
      position: absolute;
      height: 100%;
      top: 1px;
      right: 1px;
      width: 20px;
      border-style: none none none solid;
      border-image: initial;
      border-left: 0px solid rgb(170, 170, 170);
  }
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
  .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover {
      background: none;
      color: #e0e0e0;
      cursor: default;
  }
  .btn-primary, a.btn-primary:link, a.btn-primary:visited {
      color: #fff;
      background-color: #0daed0;
  }
  .dropdown-header {
      background-color: #e5f1f0;
      display: block;
      padding: 3px 20px;
      font-size: 12px;
      line-height: 1.42857143;
      color: #777;
      white-space: nowrap;
  }
  .center{
    text-align: center;
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
        'brandLabel' => '<img src="/pos/frontend/web/img/kclogosmall.png" alt="Home" style="height:100%;">',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index'],'visible' => !Yii::$app->user->isGuest],
        //['label' => 'Sales', 'url' => ['/sales/create'],'visible' => !Yii::$app->user->isGuest],
        [
            'label' => 'Transactions', 
            'items' => [
                 ['label' => 'Production', 'options' => ['class' => 'header']],
                ['label' => 'New Production', 'url' => ['/stock-card/create'],'visible' => Yii::$app->user->can('production-only')],
                ['label' => 'Inventory Loss', 'url' => ['/inventory-loss/create'],'visible' => Yii::$app->user->can('production-only')],
                
                ['label' => 'Sales', 'options' => ['class' => 'header']],
                ['label' => 'Sales', 'url' => ['/sales/create'],'visible' => Yii::$app->user->can('cashier-only')],                

                ['label' => 'Disbursements', 'options' => ['class' => 'header']],

                ['label' => 'Cash Disbursement', 'url' => ['/expenses/create'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Petty Cash Disbursement', 'url' => ['/pcv-tracking/create','date'=>date('Y-m-d')],'visible' => Yii::$app->user->can('treasurer-only')],

                ['label' => 'Purchases', 'options' => ['class' => 'header']],
                ['label' => 'Record New Purchases', 'url' => ['/ppe/create','date'=>date('Y-m-d')],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Purchase Journal', 'url' => ['/ppe/index','date'=>date('Y-m-d')],'visible' => Yii::$app->user->can('bookkeeper-only')],

                /*['label' => 'Production', 'options' => ['class' => 'header']],
                ['label' => 'New Production', 'url' => ['/stock-card/create'],'visible' => Yii::$app->user->can('production-only')],
                ['label' => 'Inventory Loss', 'url' => ['/inventory-loss/create'],'visible' => Yii::$app->user->can('production-only')],*/

                ['label' => 'Property, Plant, and Equipment', 'options' => ['class' => 'header']],

                ['label' => 'Create New PPE', 'url' => ['/ppe/create','date'=>date('Y-m-d')],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'PPE Depreciation', 'url' => ['/ppe/index','date'=>date('Y-m-d')],'visible' => Yii::$app->user->can('bookkeeper-only')],

                ['label' => 'Accounts Payable', 'options' => ['class' => 'header']],
                ['label' => 'Accounts Payable Recording', 'url' => ['/accounts-payable-invoice/create'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Accounts Payable Payment (Cash/Bank)', 'url' => ['/accounts-payable-payment/create'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Accounts Payable Payment (Petty Cash)', 'url' => ['/accounts-payable-payment/petty-cash'],'visible' => Yii::$app->user->can('treasurer-only')],

                ['label' => 'Bank Transactions', 'options' => ['class' => 'header']],
                
                ['label' => 'Bank Deposit', 'url' => ['/transactions/bank-deposit'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Bank Withdrawal', 'url' => ['/transactions/bank-withdrawal'],'visible' => Yii::$app->user->can('treasurer-only')],

                ['label' => 'Other Transactions', 'options' => ['class' => 'header']],

                ['label' => 'Capital Contributions', 'url' => ['/capitals/create'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Monthly Dues Collection', 'url' => ['/monthly-dues/index'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Collection (Receivable)', 'url' => ['payments-receivable/create'],'visible' => Yii::$app->user->can('cashier-only')],
                ['label' => 'Petty Cash Establishment', 'url' => ['/petty-cash/establishment','date'=>date('Y-m-d')],'visible' => Yii::$app->user->can('treasurer-only')],
                //['label' => 'Other Income', 'url' => ['/index'],'visible' => Yii::$app->user->can('cashier-only')],
                
                //['label' => 'Remittances', 'url' => ['/payroll/remittances'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Closing Entries', 'url' => ['/jev-tracking/closing-entry'],'visible' => Yii::$app->user->can('bookkeeper-only')],
            ],
            'visible' => !Yii::$app->user->isGuest
        ],
        //['label' => 'Sales Report', 'url' => ['/sales/index'],'visible' => !Yii::$app->user->isGuest],
        //['label' => 'Sales Detailed', 'url' => ['/sales-details/index'],'visible' => !Yii::$app->user->isGuest],
        //['label' => 'Add New Expense', 'url' => ['/expenses/new'],'visible' => Yii::$app->user->can('treasurer-only')],      
        [
            'label' => 'Reports', 
            'items' => [
                ['label' => 'Inventory Loss', 'url' => ['/inventory-loss/index'],'visible' => Yii::$app->user->can('production-only')],
                //['label' => 'DV Tracking', 'url' => ['/dv-tracking/index'],'visible' => Yii::$app->user->can('cashier-only')],
                ['label' => 'End of Day Report', 'url' => ['/sales/eodreport','date'=>date('Y-m-d')],'visible' => Yii::$app->user->can('cashier-only')],
                ['label' => 'T-accounts', 'url' => ['/site/taccounts'],'visible' => Yii::$app->user->can('bookkeeper-only')],
                ['label' => 'Income Statement', 'url' => ['/site/incomestatement'],'visible' => !Yii::$app->user->isGuest],
                ['label' => 'Changes in Retained Earnings', 'url' => ['/site/cre'],'visible' => !Yii::$app->user->isGuest],
                ['label' => 'Balance Sheet', 'url' => ['/site/balance-sheet'],'visible' => !Yii::$app->user->isGuest],
            ],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => 'Journals', 
            'items' => [

                ['label' => 'Machine Operator', 'options' => ['class' => 'header']],
                ['label' => 'Production Journal', 'url' => ['/stock-card/index'],'visible' => Yii::$app->user->can('production-only')],

                ['label' => 'Manager', 'options' => ['class' => 'header']],
                ['label' => 'Stocks Journal', 'url' => ['/products/stocksjournal'],'visible' => Yii::$app->user->can('manager-only')],

                ['label' => 'Treasurer', 'options' => ['class' => 'header']],
                ['label' => 'OR/AR Summary', 'url' => ['/or-ar-tracking/index'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Capital Contributions Journal', 'url' => ['/capitals/index'],'visible' => Yii::$app->user->can('treasurer-only')||Yii::$app->user->can('bookkeeper-only')],
                ['label' => 'Disbursement Journal (Cash/Cash in Bank)', 'url' => ['/expenses/index'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Disbursement Journal (Petty Cash)', 'url' => ['/pcv-tracking/index'],'visible' => Yii::$app->user->can('treasurer-only')],



                ['label' => 'Cash on Hand Journal', 'url' => ['/or-ar-tracking/cashjournal'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Cash in Bank Journal', 'url' => ['/jev-tracking/bankjournal'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Petty Cash Journal', 'url' => ['/expenses/pettycash'],'visible' => Yii::$app->user->can('treasurer-only')],

                ['label' => 'Bookkeeper', 'options' => ['class' => 'header']],
                ['label' => 'General Journal', 'url' => ['/jev-tracking/generaljournal'],'visible' => Yii::$app->user->can('bookkeeper-only')],  
                ['label' => 'Sales Journal', 'url' => ['/sales/index'],'visible' => Yii::$app->user->can('bookkeeper-only')],
                ['label' => 'Accounts Receivable Journal', 'url' => ['/sales/receivables'],'visible' => Yii::$app->user->can('bookkeeper-only')],
                ['label' => 'Accounts Payable Journal', 'url' => ['/accounts-payable-invoice/index'],'visible' => Yii::$app->user->can('bookkeeper-only')],
                ['label' => 'Property, Plant and Equipment Journal', 'url' => ['/ppe/index'],'visible' => Yii::$app->user->can('bookkeeper-only')],
                ['label' => 'Subsidiary Journal (per Account)', 'url' => ['/jev-tracking/journal-generator'],'visible' => Yii::$app->user->can('manager-only')],
                ['label' => 'Journal Entry Settings', 'url' => ['/jev-tracking/index'],'visible' => Yii::$app->user->can('bookkeeper-only')],
            ],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => 'Masterlist', 
            'items' => [
                ['label' => 'Members', 'url' => ['/members/index'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Officers', 'url' => ['/officers/index'],'visible' => Yii::$app->user->can('manager-only')],
                ['label' => 'Employees', 'url' => ['/employees/index'],'visible' => Yii::$app->user->can('manager-only')],
                ['label' => 'Products Categories', 'url' => ['/product-categories/index'],'visible' => Yii::$app->user->can('manager-only')],
                ['label' => 'Products Masterlist', 'url' => ['/products/masterlist'],'visible' => Yii::$app->user->can('manager-only')],
                ['label' => 'Price List', 'url' => ['/pricelist/index'],'visible' => Yii::$app->user->can('manager-only')],
                ['label' => 'Customers', 'url' => ['/customers/index'],'visible' => Yii::$app->user->can('cashier-only')],
                ['label' => 'Suppliers', 'url' => ['/suppliers/index'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Positions', 'url' => ['/officers-positions/index'],'visible' => Yii::$app->user->can('manager-only')],
                ['label' => 'Chart of Accounts', 'url' => ['/uacs/index'],'visible' => Yii::$app->user->can('bookkeeper-only')],
                ['label' => 'Change Password', 'url' => ['/site/resetpassword1']],
            ],
            'visible' => !Yii::$app->user->isGuest
        ],
        [
            'label' => 'Payroll', 
            'items' => [
                ['label' => 'Payroll of Employees Salary', 'url' => ['/payroll/index'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Payroll of Officers Honorarium', 'url' => ['/payroll-honorarium/index'],'visible' => Yii::$app->user->can('treasurer-only')],
                ['label' => 'Payroll of Dividends', 'url' => ['/payroll-dividends/index'],'visible' => Yii::$app->user->can('treasurer-only')],
            ],
            'visible' => !Yii::$app->user->isGuest
        ],
        
    ];
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
