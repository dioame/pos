<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Expenses;
use app\models\ExpensesSearch;
use app\models\ExpenseTypesSearch;
use app\models\ExpenseTypes;
use app\models\ExpensesPayments;
use app\models\Log;
use app\models\JevTracking;
use app\models\JevEntries;
use app\models\DvTracking;
use app\models\DvTrackingSearch;
use app\models\PaymentVoucher;
use app\models\AccountsPayableInvoice;

/**
 * ExpensesController implements the CRUD actions for Expenses model.
 */
class ExpensesController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
     * Lists all Expenses models.
     * @return mixed
     */
    public function actionIndex()
    {
        /*$searchModel = new ExpensesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);*/

        $searchModel = new DvTrackingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPettycash()
    {
        $searchModel = new ExpensesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->joinWith('expensesPayments');
        $dataProvider->query->where(['expenses_payments.type'=>'petty cash fund']);

        return $this->render('pettycash', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Expenses model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => DvTracking::findOne($id),
        ]);
    }

    public function actionPaymentVoucher()
    {
        $dvmodel = new DvTracking();
        $particulars = new PaymentVoucher();

        if ($dvmodel->load(Yii::$app->request->post()) && $particulars->load(Yii::$app->request->post())) {
            var_dump($particulars);
        }

        return $this->render('payment-voucher',[
            'dvmodel'=>$dvmodel,
            'particulars'=>$particulars
        ]);
    }

    /**
     * Creates a new Expenses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DvTracking();

        if ($model->load(Yii::$app->request->post())) {

            $jev = JevTracking::createJev($model->date_posted,$model->particular,'expenses');
            JevEntries::insertAccount($jev,'debit',$model->debit,$model->amount);
            JevEntries::insertAccount($jev,'credit','10101000',$model->amount);
            $model->credit = 10101000;

            /*if($model->balance>0){
                JevEntries::insertAccount($jev,'credit','20101010',$model->balance);
                $apmodel = new AccountsPayableInvoice();
                $apmodel->supplier = $model->payee;
                $apmodel->invoice_date = $model->date_posted;
                $apmodel->type_of_expense = $model->debit;
                $apmodel->amount = $model->amount;
                $apmodel->jev = $jev;
                $apmodel->save(false);
            }*/

            $model->jev = $jev;
            $model->save(false);

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully added new expense',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 

            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionNew()
    {
        if(Yii::$app->user->can('treasurer-only')){
            $model = new Expenses();
            $searchModel = new ExpenseTypesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('new', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
        
    }

    public function actionAdd()
    {
        $id=$_POST['id'];
        $arr = [];
        $model = ExpenseTypes::find()->where(['id'=>$id])->one();
        $arr = ['id'=>$model->id,'product_name'=>$model->name,'price'=>$model->amount];
        return json_encode($arr);
    }

    public function actionNewtransaction()
    {
        $ret = 0;
        $success = 1;
        $model = new Expenses();
        $expenseid=$_POST['expenseid'];

        $model->type=$expenseid;
        $model->payee=$_POST['payee'];
        $model->remarks=$_POST['remarks'];
        $model->amount=$_POST['totalhere'];
        $model->date=$_POST['transaction_date'];
        $model->amount_paid=$_POST['amount_paid'];
        $model->balance=$_POST['totalhere']-$_POST['amount_paid'];

        if($model->save(false)){
            Log::addLog("Created new expense. ID: ".$model->id." Total: ".$model->amount." Amount Paid: ".$model->amount_paid." ");

            $payment = new ExpensesPayments();
            $payment->expense_id = $model->id;
            $payment->type = $_POST['customerid'];
            $payment->amount_paid = $model->amount_paid;
            $payment->date_recorded = $_POST['transaction_date'];
            $payment->save();

            if($model->balance>0&&$payment->type!='accounts payable'){
                $payment2 = new ExpensesPayments();
                $payment2->expense_id = $model->id;
                $payment2->type = 'accounts payable';
                $payment2->amount_paid = $model->balance;
                $payment2->date_recorded = $_POST['transaction_date'];
                $payment2->save();
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
            $this->redirect(['expenses/new']);
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

    public function actionUpdatejev($id)
    {
        if(Yii::$app->user->can('bookkeeper-only')){
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                if($model->save())
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->renderAjax('updatejev', [
                'model' => $model,
            ]);

        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Updates an existing Expenses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = DvTracking::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'DV updated successfully!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  
            $jeventries = JevEntries::find()->where(['jev'=>$model->jev])->all();
            foreach ($jeventries as $key) {
                $temp = JevEntries::findOne($key->id);
                $temp->amount = $model->amount;
                $temp->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('_form2', [
            'model' => $model,
        ]);
    }
    /**
     * Deletes an existing Expenses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $jev = DvTracking::findOne($id)->jev;

        if($jevmodel = JevTracking::find()->where(['jev'=>$jev])->one()){
            $jevmodel->delete();
        }
        //$this->findModel($id)->delete();

        Yii::$app->getSession()->setFlash('success1', [
             'type' => 'success',
             'duration' => 5000,
             'icon' => 'fa fa-user',
             'message' => 'Record successfully deleted!',
             'positonY' => 'top',
             'positonX' => 'right'
         ]);  

        return $this->redirect(['index']);
    }

    /**
     * Finds the Expenses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Expenses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expenses::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
