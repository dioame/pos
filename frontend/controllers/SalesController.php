<?php

namespace frontend\controllers;

use Yii;
use app\models\Sales;
use app\models\SalesDetails;
use app\models\Products;
use app\models\SalesSearch;
use app\models\Log;
use app\models\OrArTracking;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\JevTracking;
use app\models\JevEntries;
use app\models\CashDec;
use app\models\CashDecDet;
use app\models\JevEntriesSearch;
use app\models\PaymentsReceivableSearch;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class SalesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest)
            $this->redirect(['site/login']);
        else return true;
    }
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sales models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JevEntriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['accounting_code'=>40202160,'type'=>'credit']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReceivables()
    {
        $searchModel = new JevEntriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['accounting_code'=>10301010,'type'=>'debit']);

        return $this->render('receivable', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single Sales model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new PaymentsReceivableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['sales_id'=>$id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEodreport($date)
    {
        $date = $date;
        $model = Sales::find()->where(['date(transaction_date)'=>$date])->all();
        return $this->render('eodreport', [
            'model' => $model,
            'date' => $date,
        ]);
    }

    public function actionEodsave()
    {
        if(count($_POST)>0){

            if($eodmodel = CashDec::find()->where(['date_posted'=>$_POST['date']])->one()){
                foreach ($_POST as $name =>$val) {
                    $cashdecdetails = CashDecDet::find()->where(['cash_dec'=>$eodmodel->id,'type'=>$name])->one();
                    $cashdecdetails->count = $val;
                    $cashdecdetails->save(false);
                }
            }else{
                $cashdec = new CashDec();
                $cashdec->date_posted = $_POST['date'];
                $cashdec->save();

                foreach ($_POST as $name =>$val) {
                    $cashdecdetails = new CashDecDet();
                    $cashdecdetails->cash_dec = $cashdec->id;
                    $cashdecdetails->type = $name;
                    $cashdecdetails->count = $val;
                    $cashdecdetails->save(false);
                }
            }

            Yii::$app->getSession()->setFlash('success1', [
             'type' => 'success',
             'duration' => 5000,
             'icon' => 'fa fa-user',
             'message' => 'Successfully saved cashier declaration!',
             'positonY' => 'top',
             'positonX' => 'right'
            ]); 
            
            $date = $_POST['date'];
        }else{
            $date = date('Y-m-d');
        }
        return $this->redirect(['sales/eodreport',
            'date' => $date,
        ]);
    }


    /**
     * Creates a new Sales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sales();
        $salesmodel = new SalesDetails();

        if ($model->load(Yii::$app->request->post()) && $salesmodel->load(Yii::$app->request->post())) {


            $jev = JevTracking::createJev($model->transaction_date,'Sold '.$salesmodel->quantity.' '.$salesmodel->unit.' of '.$salesmodel->product->product_name.' @ P'.$salesmodel->product_price.'/'.$salesmodel->unit.' '.($model->sales_on_credit>0?'(P '.number_format($model->sales_on_credit,2).' on account)':''),'sales');

            JevEntries::insertAccount($jev,'credit','40202160',$salesmodel->quantity*$salesmodel->product_price);

            $model->jev = $jev;
            $model->total = $salesmodel->quantity*$salesmodel->product_price;
            $model->save(false);

            $salesmodel->sales_id = $model->id;
            $salesmodel->sub_total = $model->total;
            $salesmodel->save(false);

            $newor = new OrArTracking();
            $newor->tracking = $model->tempor;
            $newor->save(false);

            if($model->amount_paid!=0){
                JevEntries::insertAccount($jev,'debit','10101000',$model->amount_paid);
            }

            if($model->sales_on_credit>0){
                JevEntries::insertAccount($jev,'debit','10301010',$model->sales_on_credit);
            }

            $model->jev = $jev;
            $model->orNo = $newor->id;
            $model->save(false);

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully added new sales',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 

            return $this->redirect(['create']);
        }

        return $this->render('create', [
            'model' => $model,
            'salesmodel' => $salesmodel,
        ]);
    }

    

    public function actionNewtransaction()
    {
        $ret = 0;
        $success = 1;
        $model = new Sales();

        $model->transaction_date=$_POST['entry_date'];
        $model->customer_id=$_POST['customerid'];
        $model->total=$_POST['totalhere'];
        $model->amount_paid=$_POST['amount_paid'];
        $model->paid=$_POST['paidval'];
        $model->sales_on_credit=$_POST['totalhere']-$_POST['amount_paid'];

        if($model->save(false)){
            Log::addLog("Created new transaction. ID: ".$model->id." Total: ".$model->total." Amount Paid: ".$model->amount_paid." ");
            $items=$_POST['items'];

            $armodel = new OrArTracking();
            $armodel->type = 'OR';
            $armodel->save();
            $armodel->number = date('Y').'-'.date('m').'-'.$armodel->id;
            $armodel->save();

            $model->orNo = $armodel->id;
            $model->save();

            foreach ($items as $key) {
                $ret = Products::find()->where(['id'=>$key['id']])->one();
                $detail = new SalesDetails();
                $detail->sales_id = $model->id;
                $detail->product_id = $key['id'];
                $detail->quantity = $key['quantity'];
                $detail->product_price = $key['product_price'];
                $detail->buying_price = $ret->buying_price;
                $detail->sub_total = $key['sub_total'];
                
                if($detail->save(false)){
                    Log::addLog("Product (".$ret->id.")".$ret->product_name." was substracted ".$detail->quantity.", from ".$ret->quantity_at_hand." to ".($ret->quantity_at_hand-$detail->quantity)." tID: ".$model->id);
                    $ret->quantity_at_hand = $ret->quantity_at_hand-$detail->quantity;
                    if(!$ret->save(false)){
                        $success=0;
                    }
                }else{
                    $success=0;
                }
            }
        }else{
            $success = 0;
            $model->delete();
        }

        if($success=1){
            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Transaction recorded successfully!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  
            $this->redirect(['sales-details/new']);
        }else{
            Yii::$app->getSession()->setFlash('danger', [
                 'type' => 'danger',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Ooops! Something went wrong! Please contact administrator.',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  
        }
        
    }

    /**
     * Updates an existing Sales model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sales model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {   
        $jev = JevEntries::findOne($id)->jev;
        $ornumber = Sales::find()->where(['jev'=>$jev])->one()->orNo;
        $jevmodel = JevTracking::find()->where(['jev'=>$jev])->one();
        if($jevmodel){
            $jevmodel->delete();
        }    

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sales::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
