<?php
use kartik\select2\Select2;
use app\models\JevEntries;
use app\models\Uacs;
use app\models\Members;
use app\models\StockCard;
use app\models\Capitals;
use app\models\MonthlyDues;
use dosamigos\chartjs\ChartJs;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */

$this->title = 'L&E | Business Enterprise';
?>
<head>
	<script>
	  function resizeIframe(obj) {
	    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
	  }
	</script>
</head>
<style type="text/css">
	.wrap > .container {
	    background-color: #f0f3f3;
	}
	th{
		text-align: center;
	}
</style>
<div class="site-index">
	<section id="main-content" class="animated fadeInUp">
	    <div class="row">
	        <div class="col-md-12 col-lg-5">
	            <div class="row">
	                <div class="col-md-6">
	                    <div class="panel panel-solid-success widget-mini">
	                        <div class="panel-body">
	                            <i class="icon-bar-chart"></i>
	                            <span class="total text-center">&#8369;<?= number_format($totalsales,2) ?></span>
	                            <span class="title text-center">Monthly Sales</span>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-6">
	                    <div class="panel widget-mini">
	                        <div class="panel-body">
	                            <i class="icon-support"></i>
	                            <span class="total text-center">&#8369;<?= number_format($totalreceivable,2) ?></span>
	                            <span class="title text-center">Total Receivables</span>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-6">
	                    <div class="panel widget-mini">
	                        <div class="panel-body">
	                            <i class="icon-envelope-open"></i>
	                            <span class="total text-center">&#8369;<?= number_format($totalpayable,2) ?></span>
	                            <span class="title text-center">Total Payables</span>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-6">
	                    <div class="panel panel-solid-info widget-mini">
	                        <div class="panel-body">
	                            <i class="icon-user"></i>
	                            <span class="total text-center">&#8369;<?= number_format($totalexpense,2) ?></span>
	                            <span class="title text-center">Monthly Expenses</span>
	                        </div>
	                    </div>
	                </div>
	            	<div class="col-md-12">
	            	    <div class="panel panel-solid-success widget-mini">
	            	        <div class="panel-body" style="padding: 0px;">
	            	            
	            	        </div>
	            	    </div>
	            	</div>
	            </div>
	        </div>
	        <div class="col-md-3">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                    <h3 class="panel-title">ASSET STATS</h3>
	                </div>
	                <div class="panel-body">
	                    <div class="row">
	                    	<div class="col-md-12">
	                    		<div class="chart-container" style="position: relative; height:auto; width:100%">
	                    	<?php
	                    		$assetsquery = Uacs::find()->where(['Classification'=>'Asset','isEnabled'=>1])->all();
	                    		$allasset = [];
	                    		foreach ($assetsquery as $key) {
	                    			$allasset['uacs'][] = $key->object_code;
	                    			$allasset['code'][] = $key->uacs;
	                    			$allasset['value'][] =JevEntries::getAssetBalance($key->uacs);
	                    		}
	                    		$colors = ['#33cc99','#666699','#ffcc99','#cccccc','#3366cc','#ee4840','#737373'];
	                    		//var_dump($allasset);
	                    	?>
	                        <?= ChartJs::widget([
	                            'type' => 'doughnut',
	                            'options' => [
	                                'responsive'=> true,
	                            ],
	                            'clientOptions' => [
	                                    'legend' => [
	                                        'display' => false,
	                                        'position' => 'left',
	                                        'labels' => [
	                                            'fontSize' => 14,
	                                            'fontColor' => "#425062",
	                                        ]
	                                    ],
	                                    'tooltips' => [
	                                        'enabled' => true,
	                                        'intersect' => true
	                                    ],
	                                    'hover' => [
	                                        'mode' => false
	                                    ],
	                                    'maintainAspectRatio' => false,

	                                ],
	                            'data' => [
	                                'labels' => $allasset['uacs'],
	                                'datasets' => [
	                                    [
	                                        'label' => "My First dataset",
	                                        'backgroundColor' => $colors,
	                                        'pointBackgroundColor' => "rgba(179,181,198,1)",
	                                        'pointBorderColor' => "#fff",
	                                        'pointHoverBackgroundColor' => "#fff",
	                                        'pointHoverBorderColor' => "rgba(179,181,198,1)",
	                                        'data' => $allasset['value']
	                                    ]
	                                ]
	                            ]
	                        ]);
	                        ?>
	                    		</div>
	                    	</div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Flags</h3>
                        <div class="actions pull-right">
                            <i class="fa fa-expand"></i>
                        </div>
                    </div>
                    <div class="panel-body widget-gauge" style="height: 180px;overflow-y: scroll;">
                    	<small>
                    		<?php
                    			$pettycash = (JevEntries::find()->where(['type'=>'debit','accounting_code'=>10101020])->sum('amount')-JevEntries::find()->where(['type'=>'credit','accounting_code'=>10101020])->sum('amount'));
                    			if($pettycash<4000){
                    				if($pettycash<=0){
                    					$type = 'danger';
                    				}else{
                    					$type = 'warning';
                    				}
                    				echo '<a href="/pos/frontend/web/petty-cash/establishment?date='.date('Y-m-d').'"><div class="alert alert-'.$type.'" role="alert" style="padding: 0px;margin: 0px;">
						                	  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						                	  <span class="sr-only">Error:</span>
						                	  Petty Cash Fund is already below P 4,000.00
						                	</div></a>';
                    			}
                    			$balances = Yii::$app->getDb()->createCommand("SELECT *,id,amount,due_date,CONCAT('AP#',invoice_number,' to ',(SELECT supplier_name FROM suppliers WHERE id=accounts_payable_invoice.supplier),' with a remaining balance of ',(amount - ifnull((SELECT sum(b.amount) FROM accounts_payable_payment A JOIN dv_tracking B ON A.dv_number=B.dv_number WHERE a.ap_id=accounts_payable_invoice.id GROUP BY a.ap_id),0)-ifnull((SELECT sum(b.amount) FROM accounts_payable_payment A JOIN pcv_tracking B ON A.pcv_number=B.pcv_number WHERE a.ap_id=accounts_payable_invoice.id GROUP BY a.ap_id),0))) AS 'remarks' FROM accounts_payable_invoice having (amount - ifnull((SELECT sum(b.amount) FROM accounts_payable_payment A JOIN dv_tracking B ON A.dv_number=B.dv_number WHERE a.ap_id=accounts_payable_invoice.id GROUP BY a.ap_id),0)-ifnull((SELECT sum(b.amount) FROM accounts_payable_payment A JOIN pcv_tracking B ON A.pcv_number=B.pcv_number WHERE a.ap_id=accounts_payable_invoice.id GROUP BY a.ap_id),0))>0")->queryAll();
                    			foreach ($balances as $key) {
                    				if($key['due_date']<=date('Y-m-d')){
                    					$type = 'danger';
                    				}else{
                    					$type = 'info';
                    				}
                    				echo '<a href="/pos/frontend/web/accounts-payable-payment/petty-cash"><div class="alert alert-'.$type.'" role="alert" style="padding: 0px;margin: 0px;">
						                	  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						                	  <span class="sr-only">Error:</span>
						                	  '.$key['remarks'].'
						                	</div></a>';
                    			}
                    		?>
                    	</small>
                    </div>
                </div>
            </div>
	    </div>
	    <div class="row">
	        <div class="col-md-12 col-lg-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                    <h3 class="panel-title">FINANCIAL STATS</h3>
	                </div>
	                <div class="panel-body server-chart">
	                    <div class="row">
	                        <div class="col-md-12 col-lg-6">
	                            <ul>
	                                <li>
	                                    <span class="text-left">Cash on Hand (<?= round($cashonhandpercentage,2) ?>%)</span>
	                                    <div class="progress progress-xs">
	                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: <?= $cashonhandpercentage ?>%">
	                                        </div>
	                                    </div>
	                                </li>
	                                <li>
	                                    <span class="text-left">Petty Cash Fund (<?= round($pettycashpercentage,2) ?>%)</span>
	                                    <div class="progress progress-xs">
	                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?= round($pettycashpercentage,2) ?>%">
	                                        </div>
	                                    </div>
	                                </li>
	                            </ul>
	                            <hr>
	                            <div class="col-md-12 col-lg-12">
	                            	<center><h3>Gross Income vs Net Income</h3></center>
	                                <div class="bar-chart">
	                                	<?php
	                                		$allsales = [];
	                                		$allnet = [];
	                                		$datenow = date('Y-m-d',strtotime('-11 month',strtotime('now')));
	                                		for($i=1;$i<=12;$i++){
	                                			$year = date('Y',strtotime($datenow));
	                                			$month = date('m',strtotime($datenow));
	                                			$sales = JevEntries::find()->joinWith('accountingCode')->joinWith('jev0')->where(['Classification'=>'Income','month(date_posted)'=>$month,'year(date_posted)'=>$year,'type'=>'credit'])->sum('amount');
	                                			$expense = JevEntries::find()->joinWith('accountingCode')->joinWith('jev0')->where(['Classification'=>'Expenses','month(date_posted)'=>$month,'year(date_posted)'=>$year,'type'=>'debit'])->sum('amount');
	                                			$allsales['label'][] = date('M y',strtotime($datenow));
	                                			$allsales['value'][] = $sales;
	                                			$allnet['value'][] = $sales-$expense;
	                                			$datenow = date('Y-m-d',strtotime('+1 month',strtotime($datenow)));
	                                		}
	                                		/*foreach ($sales as $key) {
	                                			$allsales['uacs'][] = $key->object_code;
	                                			$allsales['code'][] = $key->uacs;
	                                			$allsales['value'][] =JevEntries::getAssetBalance($key->uacs);
	                                		}*/
	                                		$colors = ['#33cc99','#666699','#ffcc99','#cccccc','#3366cc','#ee4840','#737373'];

	                                	?>	   
	                                    <?= ChartJs::widget([
	                                        'type' => 'bar',
	                                        'clientOptions' => [
	                                                'legend' => [
	                                                    'display' => false,
	                                                    'position' => 'left',
	                                                    'labels' => [
	                                                        'fontSize' => 14,
	                                                        'fontColor' => "#425062",
	                                                    ]
	                                                ],
	                                                'tooltips' => [
	                                                    'enabled' => true,
	                                                    'intersect' => true
	                                                ],
	                                                'hover' => [
	                                                    'mode' => false
	                                                ],
	                                                'maintainAspectRatio' => false,
	                                                'responsive' => true,

	                                            ],
	                                        'data' => [
	                                            'labels' => $allsales['label'],
	                                            'datasets' => [
	                                                [
	                                                    'label' => "Gross Income",
	                                                    'backgroundColor' => "rgba(140,221,205,0.5)",
	                                                    'borderColor' => "rgba(179,181,198,1)",
	                                                    'pointBackgroundColor' => "rgba(179,181,198,1)",
	                                                    'pointBorderColor' => "#fff",
	                                                    'pointHoverBackgroundColor' => "#fff",
	                                                    'pointHoverBorderColor' => "rgba(179,181,198,1)",
	                                                    'data' => $allsales['value']
	                                                ],
	                                                [
	                                                    'label' => "Net Income",
	                                                    'backgroundColor' => "rgba(102,153,204,0.5)",
	                                                    'borderColor' => "rgba(255,99,132,1)",
	                                                    'pointBackgroundColor' => "rgba(255,99,132,1)",
	                                                    'pointBorderColor' => "#fff",
	                                                    'pointHoverBackgroundColor' => "#fff",
	                                                    'pointHoverBorderColor' => "rgba(255,99,132,1)",
	                                                    'data' => $allnet['value']
	                                                ]
	                                            ]
	                                        ]
	                                    ]);
	                                    ?>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-md-12 col-lg-6">
	                            	<center><h3>Sales vs. Expenses</h3></center>
	                            <div class="row">
	                                    <div class="line-chart">
	                                    	<?php
	                                    		$allsales = [];
	                                    		$allexpenses = [];
	                                    		$datenow = date('Y-m-d',strtotime('-11 month',strtotime('now')));
	                                    		for($i=1;$i<=12;$i++){
	                                    			$year = date('Y',strtotime($datenow));
	                                    			$month = date('m',strtotime($datenow));
	                                    			$sales = JevEntries::find()->joinWith('accountingCode')->joinWith('jev0')->where(['Classification'=>'Income','month(date_posted)'=>$month,'year(date_posted)'=>$year,'type'=>'credit'])->sum('amount');
	                                    			$expense = JevEntries::find()->joinWith('accountingCode')->joinWith('jev0')->where(['Classification'=>'Expenses','month(date_posted)'=>$month,'year(date_posted)'=>$year,'type'=>'debit'])->sum('amount');
	                                    			$allsales['label'][] = date('M y',strtotime($datenow));
	                                    			$allsales['value'][] = $sales;
	                                    			$allexpenses['value'][] = $expense;
	                                    			$datenow = date('Y-m-d',strtotime('+1 month',strtotime($datenow)));
	                                    		}
	                                    		/*foreach ($sales as $key) {
	                                    			$allsales['uacs'][] = $key->object_code;
	                                    			$allsales['code'][] = $key->uacs;
	                                    			$allsales['value'][] =JevEntries::getAssetBalance($key->uacs);
	                                    		}*/
	                                    		$colors = ['#33cc99','#666699','#ffcc99','#cccccc','#3366cc','#ee4840','#737373'];

	                                    	?>	   
	                                        <?= ChartJs::widget([
	                                            'type' => 'line',
	                                            'clientOptions' => [
	                                                    'legend' => [
	                                                        'display' => false,
	                                                        'position' => 'left',
	                                                        'labels' => [
	                                                            'fontSize' => 14,
	                                                            'fontColor' => "#425062",
	                                                        ]
	                                                    ],
	                                                    'tooltips' => [
	                                                        'enabled' => true,
	                                                        'intersect' => true
	                                                    ],
	                                                    'hover' => [
	                                                        'mode' => false
	                                                    ],
	                                                    'maintainAspectRatio' => true,

	                                                ],
	                                            'data' => [
	                                                'labels' => $allsales['label'],
	                                                'datasets' => [
	                                                    [
	                                                        'label' => "Sales",
	                                                        'backgroundColor' => "rgba(140,221,205,0.5)",
	                                                        'borderColor' => "rgba(26,187,155,1)",
	                                                        'pointBackgroundColor' => "rgba(26,187,155,1)",
	                                                        'pointBorderColor' => "#97bbcd",
	                                                        'data' => $allsales['value']
	                                                    ],
	                                                    [
	                                                        'label' => "Expenses",
	                                                        'backgroundColor' => "rgba(102,153,204,0.5)",
	                                                        'borderColor' => "rgba(30,123,182,1)",
	                                                        'pointBackgroundColor' => "rgba(30,123,182,1)",
	                                                        'pointBorderColor' => "#97bbcd",
	                                                        'data' => $allexpenses['value']
	                                                    ]
	                                                ]
	                                            ]
	                                        ]);
	                                        ?>
	                                    </div>
	                                
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div class="row">
	        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
	        	<div class="panel panel-default chat-widget">
	        	    <div class="panel-heading">
	        	        <h3 class="panel-title">Changes in Retained Earnings</h3>
	        	        <div class="actions pull-right">
	        	            <i class="fa fa-expand"></i>
	        	            <i class="fa fa-chevron-down"></i>
	        	            <i class="fa fa-times"></i>
	        	        </div>
	        	    </div>
	        	    <div class="panel-body">
	        	    	<iframe src="creplain?date_from=<?=date('Y-m-01')?>&date_to=<?=date('Y-m-t')?>" style="width:100%;font-family: 'Open Sans', Helvetica, Arial, 'sans-serif';" frameborder="0" scrolling="no" onload="resizeIframe(this)" ></iframe> 
	        	    </div>
	        	</div>
	            <div class="panel panel-default chat-widget">
	                <div class="panel-heading">
	                    <h3 class="panel-title">Members</h3>
	                    <div class="actions pull-right">
	                        <i class="fa fa-expand"></i>
	                        <i class="fa fa-chevron-down"></i>
	                        <i class="fa fa-times"></i>
	                    </div>
	                </div>
	                <div class="panel-body">
	                	<div class="col-md-12" style="height:220px;overflow-y: auto;padding: 5px;">
	                		<table width="100%" style="font-size: 0.8em;">
	                			<tr>
	                				<th>Name</th>
	                				<th width="30%">Total Contribution</th>
	                			</tr>
	                	<?php
	                		$members = Members::find()->all();
	                		$allmembers = [];
	                		foreach ($members as $key) {
	                			echo '<tr>';
	                			echo '<td>'.$key->firstname.' '.$key->lastname.'</td>';
	                			echo '<td class="rightni" style="padding:5px;">P '.number_format(Capitals::find()->where(['membersId'=>$key->id,'type'=>'cash'])->sum('amount'),2).'</td>';
	                			//$allcustomers[''][] = $key->object_code;
	                			//$allcustomers['value'][] =JevEntries::getAssetBalance($key->uacs);
	                			echo '</tr>';
	                		}
	                	?>
	                		</table>
	                	</div>
	                </div>
	            </div>
	        	<div class="panel panel-default chat-widget">
	        	    <div class="panel-heading">
	        	        <h3 class="panel-title">About L&E FMS</h3>
	        	        <div class="actions pull-right">
	        	            <i class="fa fa-expand"></i>
	        	            <i class="fa fa-chevron-down"></i>
	        	            <i class="fa fa-times"></i>
	        	        </div>
	        	    </div>
	        	    <div class="panel-body">
	        	    	<div class="col-md-7" style="padding: 0px;">
	        	    		<p style="font-size: 0.7em">This system was developed to provide chuchu</p>
	        	    	</div>
	        	    	<div class="col-md-5" style="padding: 0px;">
	        	    		<img src="/pos/frontend/web/img/pointofsale.png" />
	        	    	</div>
	        	    </div>
	        	</div>
	        </div>

	        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8">
	            <div class="panel panel panel-default">
	                <div class="panel-heading">
	                    <h3 class="panel-title">Income Statement</h3>
	                    <div class="actions pull-right">
	                        <i class="fa fa-expand"></i>
	                        <i class="fa fa-chevron-down"></i>
	                        <i class="fa fa-times"></i>
	                    </div>
	                </div>
	                <div class="panel-body">
	                    <div class="col-md-12">
	                    	<iframe src="incomestatementplain?date_from=<?=date('Y-m-01')?>&date_to=<?=date('Y-m-t')?>" style="width:100%;font-family: 'Open Sans', Helvetica, Arial, 'sans-serif';" frameborder="0" scrolling="no" onload="resizeIframe(this)" ></iframe> 
	          			</div>
	                </div>
	            </div>
	            <div class="panel panel panel-default">
	                <div class="panel-heading">
	                    <h3 class="panel-title">Balance Sheet</h3>
	                    <div class="actions pull-right">
	                        <i class="fa fa-expand"></i>
	                        <i class="fa fa-chevron-down"></i>
	                        <i class="fa fa-times"></i>
	                    </div>
	                </div>
	                <div class="panel-body">
	                    <div class="col-md-12">
	                        <iframe src="balance-sheet-plain?date_from=<?=date('Y-m-01')?>&date_to=<?=date('Y-m-t')?>" style="width:100%;font-family: 'Open Sans', Helvetica, Arial, 'sans-serif';" frameborder="0" scrolling="no" onload="resizeIframe(this)" ></iframe> 
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div class="row">
	        <div class="col-md-12 col-lg-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                    <h3 class="panel-title">PRODUCTION LINE</h3>
	                </div>
	                <div class="panel-body server-chart">
	                    <div class="row">
	                        <div class="col-md-12 col-lg-12">
	                            <div class="col-md-12 col-lg-12">
	                            	<center><h3>Daily Production for the last 60 Days</h3></center>
	                                <div class="bar-chart">
	                                	<?php
	                                		$allproduction = [];
	                                		$date_raw = date('Y-m-d', strtotime('-60 day'));
	                                		for($i=1;$i<=60;$i++){
	                                			$date_raw = date('Y-m-d', strtotime('+1 day', strtotime($date_raw)));
	                                			$production = StockCard::find()->select('sum(added) as added')->where(['date(finished)'=>date('Y-m-d',strtotime($date_raw))])->groupBy('date(finished)')->one();
	                                			$allproduction['label'][]=date('M d',strtotime($date_raw));
	                                			$allproduction['value'][]=$production['added'];
	                                			/*$allsales['label'][] = date('F',strtotime('2018-'.$i.'-1'));
	                                			$allsales['value'][] = $sales;
	                                			$allnet['value'][] = $sales-$expense;*/
	                                		}
	                                		/*foreach ($sales as $key) {
	                                			$allsales['uacs'][] = $key->object_code;
	                                			$allsales['code'][] = $key->uacs;
	                                			$allsales['value'][] =JevEntries::getAssetBalance($key->uacs);
	                                		}*/
	                                		$colors = ['#33cc99','#666699','#ffcc99','#cccccc','#3366cc','#ee4840','#737373'];

	                                	?>	   
	                                    <?= ChartJs::widget([
	                                        'type' => 'bar',
	                                        'clientOptions' => [
	                                                'legend' => [
	                                                    'display' => false,
	                                                    'position' => 'left',
	                                                    'labels' => [
	                                                        'fontSize' => 14,
	                                                        'fontColor' => "#425062",
	                                                    ]
	                                                ],
	                                                'tooltips' => [
	                                                    'enabled' => true,
	                                                    'intersect' => true
	                                                ],
	                                                'hover' => [
	                                                    'mode' => false
	                                                ],
	                                                'maintainAspectRatio' => false,
	                                                'responsive' => true,

	                                            ],
	                                        'data' => [
	                                            'labels' => $allproduction['label'],
	                                            'datasets' => [
	                                                [
	                                                    'label' => "Production Added",
	                                                    'backgroundColor' => "rgba(140,221,205,0.5)",
	                                                    'borderColor' => "rgba(179,181,198,1)",
	                                                    'pointBackgroundColor' => "rgba(179,181,198,1)",
	                                                    'pointBorderColor' => "#fff",
	                                                    'pointHoverBackgroundColor' => "#fff",
	                                                    'pointHoverBorderColor' => "rgba(179,181,198,1)",
	                                                    'data' => $allproduction['value']
	                                                ]
	                                            ]
	                                        ]
	                                    ]);
	                                    ?>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div class="row">
	        <div class="col-md-12 col-lg-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                    <h3 class="panel-title">MONTHLY DUES CONTRIBUTION</h3>
	                </div>
	                <div class="panel-body server-chart">
	                    <div class="row">
	                        <div class="col-md-12 col-lg-12">
	                        	<table width="100%" style="font-size:0.8em;">
	                        		<tr>
	                        			<td style="padding: 20px;">Members</td>
	                        			<?php
	                        				$datenow = date('Y-m-d');
	                        				for($i=1;$i<=12;$i++){
	                        					$month = date('m',strtotime($datenow));
	                        					$year = date('Y',strtotime($datenow));
	                        					echo '<td style="padding: 20px;">'.date('M y',strtotime($year.'-'.$month.'-1')).'</td>';
	                        					$datenow = date('Y-m-d',strtotime('-1 month',strtotime($year.'-'.$month.'-1')));
	                        				}
	                        			?>
	                        		</tr>
	                        		<?php
	                        			$members = Members::find()->all();
	                        			foreach ($members as $key) {
	                        				echo '<tr>';
	                        				echo '<td style="padding: 5px;">'.$key->fulllist.'</td>';
	                        				$datenow = date('Y-m-d');
	                        				for($i=1;$i<=12;$i++){
	                        					$month = date('m',strtotime($datenow));
	                        					$year = date('Y',strtotime($datenow));
	                        					$date = date('Y-m-t',strtotime($year.'-'.$month.'-1'));
	                        					if(strtotime($date)>strtotime($key->date_started)){
	                        						$model = MonthlyDues::find()->where(['month'=>intval($month),'year'=>$year,'mID'=>$key->id])->one();
	                        						if($model){
	                        							if($model->jev){
	                        								echo '<td style="padding: 20px;"><span class="glyphicon glyphicon-check" aria-hidden="true" style="color:green;"></span></td>';
	                        							}else{
	                        								echo '<td style="padding: 20px;"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true" style="color:orange;"></span></td>';
	                        							}
	                        						}else{
	                        							echo '<td style="padding: 20px;"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red;"></span></td>';
	                        						}
	                        						
	                        					}else{
	                        						echo '<td style="padding: 20px;"><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:#d6d6d6;"></span></td>';
	                        					}
	                        					/*if(date('Y',strtotime($key->date_started))>$year&&date('m',strtotime($key->date_started))>$month){
	                        						
	                        					}*/
	                        					/*echo '<td style="padding: 20px;"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span></td>';*/
	                        					//echo '<td style="padding: 20px;">'.$date.' < '.$key->date_started.'</td>';
	                        					
	                        					$datenow = date('Y-m-d',strtotime('-1 month',strtotime($year.'-'.$month.'-1')));
	                        				}
	                        				echo '</tr>';
	                        			}
	                        		?>
	                        	</table>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>
</div>
