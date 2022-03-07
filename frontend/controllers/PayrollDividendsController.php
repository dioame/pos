<?php

namespace frontend\controllers;

use Yii;
use app\models\PayrollDividends;
use app\models\PayrollDividendsSearch;
use app\models\JevTracking;
use app\models\JevEntries;
use app\models\DvTracking;
use app\models\PcvTracking;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PayrollDividendsController implements the CRUD actions for PayrollDividends model.
 */
class PayrollDividendsController extends Controller
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
     * Lists all PayrollDividends models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PayrollDividendsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PayrollDividends model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PayrollDividends model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PayrollDividends();

        if ($model->load(Yii::$app->request->post())) {
           // $model->save(false);
            $array=$model->emp_id;
            foreach ($array as $value) {
                $tempModel = new PayrollDividends(); 
                $tempModel->load(Yii::$app->request->post());
                $tempModel->emp_id = $value;
                $tempModel->date_to = $tempModel->date_from;
                $tempModel->number_of_hours = 1;
                $tempModel->save(false);
            }
            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully created payroll!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreatepayable()
    {
        $error=0;
        if(isset($_POST['selection'])){
            $selection = $_POST['selection'];
            $transaction_date = $_POST['transaction_date'];
            $totalamount = 0;

            $jev = JevTracking::createJev($transaction_date,'Dividends Declare','others');
            $count=0;
            foreach ($selection as $key) {
                $model = PayrollDividends::findOne(['id'=>$key,'dividends_payable_jev'=>null]);
                if($model){
                    $count++;
                    $totalamount += $model->hourly_rate;
                    $model->dividends_payable_jev = $jev;
                    $model->save();
                }
                
            }
            
            JevEntries::insertAccount($jev,'debit',30000001,$totalamount);
            JevEntries::insertAccount($jev,'credit',20000001,$totalamount);     

            if($count<1){
                $message = 'Oooops! Seems like some records has already a dividends payable JEV!';
                $jevmodel = JevTracking::find()->where(['jev'=>$jev])->one()->delete();
                $error = 'danger';
            }else{
                $message = 'Successfully created new Journal Entry!';
                $error = 'success';
            }      

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => $error,
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => $message,
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            }

            return $this->redirect(['index']);
    }

    public function actionCreatejev()
    {
        $error=0;
        if(isset($_POST['selection'])){
            $selection = $_POST['selection'];
            $transaction_date = $_POST['transaction_date'];
            $totalamount = 0;

            $jev = JevTracking::createJev($transaction_date,'Payment of Dividends','dividends-payment');
            $dv = new DvTracking();
            $dv->dv_number = DvTracking::getNewdvnumber();
            $dv->type = 'dividends-payment';
            $dv->save(false);
            $count=0;
            foreach ($selection as $key) {
                $model = PayrollDividends::findOne(['id'=>$key,'dv'=>null,'pcv'=>null]);
                if($model){
                    $count++;
                    $totalamount += $model->hourly_rate;
                    $model->jev = $jev;
                    $model->dv = $dv->dv_number;
                    $model->save();
                }
            }

            JevEntries::insertAccount($jev,'debit',20000001,$totalamount);
            JevEntries::insertAccount($jev,'credit',10101000,$totalamount);     

            if($count<1){
                $message = 'Oooops! Seems like some records has already a DV!';
                $jevmodel = JevTracking::find()->where(['jev'=>$jev])->one()->delete();
                $dv->delete();
                $error = 'danger';
            }else{
                $message = 'Successfully created new Journal Entry!';
                $error = 'success';
                $dv->date_posted = $transaction_date;
                $dv->amount = $totalamount;
                $dv->particular = 'Payment of Dividends';
                $dv->debit = 20000001;
                $dv->credit = 10101000;
                $dv->jev = $jev;
                $dv->save(false);
            }      

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => $error,
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => $message,
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            }

            return $this->redirect(['index']);
    }

    public function actionCreatepcv()
    {
        $error=0;
        if(isset($_POST['selection'])){
            $selection = $_POST['selection'];
            $transaction_date = $_POST['transaction_date'];
            $totalamount = 0;

            $jev = JevTracking::createJev($transaction_date,'Payment of Dividends','dividends-payment');
            $dv = new PcvTracking();
            $dv->pcv_number = PcvTracking::tempDv();
            $dv->type = 'dividends-payment';
            $dv->save(false);
            $count=0;
            foreach ($selection as $key) {
                $model = PayrollDividends::findOne(['id'=>$key,'dv'=>null,'pcv'=>null]);
                if($model){
                    $count++;
                    $totalamount += $model->hourly_rate;
                    $model->jev = $jev;
                    $model->pcv = $dv->pcv_number;
                    $model->save();
                }
            }

            JevEntries::insertAccount($jev,'debit',20000001,$totalamount);
            JevEntries::insertAccount($jev,'credit',10101020,$totalamount);     

            if($count<1){
                $message = 'Oooops! Seems like some records has already a PCV!';
                $jevmodel = JevTracking::find()->where(['jev'=>$jev])->one()->delete();
                $dv->delete();
                $error = 'danger';
            }else{
                $message = 'Successfully created new Journal Entry!';
                $error = 'success';
                $dv->date_posted = $transaction_date;
                $dv->amount = $totalamount;
                $dv->particular = 'Payment of Dividends';
                $dv->debit = 20000001;
                $dv->credit = 10101020;
                $dv->jev = $jev;
                $dv->save(false);
            }      

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => $error,
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => $message,
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            }

            return $this->redirect(['index']);
    }

    /**
     * Updates an existing PayrollDividends model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully updated payroll!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PayrollDividends model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PayrollDividends model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PayrollDividends the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PayrollDividends::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
