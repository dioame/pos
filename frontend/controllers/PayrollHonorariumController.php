<?php

namespace frontend\controllers;

use Yii;
use app\models\PayrollHonorarium;
use app\models\PayrollDeductionsHonorarium;
use app\models\PayrollHonorariumSearch;
use app\models\Officers;
use app\models\PcvTracking;
use app\models\JevTracking;
use app\models\JevEntries;
use app\models\DvTracking;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PayrollController implements the CRUD actions for Payroll model.
 */
class PayrollHonorariumController extends Controller
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
     * Lists all Payroll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PayrollHonorariumSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreatejev()
    {
        $error=0;
        if(isset($_POST['selection'])){
            $selection = $_POST['selection'];
            $transaction_date = $_POST['transaction_date'];
            $totalamount = 0;

            $jev = JevTracking::createJev($transaction_date,'Payment of Honoraria Expense from ','payroll-honorarium');
            $dv = new DvTracking();
            $dv->dv_number = DvTracking::getNewdvnumber();
            $dv->type = 'payroll-honorarium';
            $dv->save(false);

        //if($jev = JevTracking::find()->where(['jev'=>date('Y-m-',strtotime($transaction_date)).$lastjev])){

            $first= strtotime(PayrollHonorarium::findOne($selection[0])->date_from);
            $last= strtotime(PayrollHonorarium::findOne($selection[0])->date_to);
            foreach ($selection as $key) {
                $model = PayrollHonorarium::findOne($key);
                $curFirst = strtotime($model->date_from);
                if ($curFirst < $first) {
                    $first = $curFirst;
                }
                $curLast = strtotime($model->date_to);
                if ($curLast > $last) {
                    $last = $curLast;
                }
                $totaldeduction = PayrollDeductionsHonorarium::find()->where(['pID'=>$model->id])->sum('amount');
                $totalamount += ($model->number_of_hours*$model->hourly_rate)-$totaldeduction;

                $model->dv = $dv->dv_number;
                $model->jev = $jev;
                $model->save();
                //$model->jev = date('Y-m-',strtotime($transaction_date)).$lastjev;
                //$model->save();
            }

            $jevmodel = JevTracking::find()->where(['jev'=>$jev])->one();
            $jevmodel->remarks = 'Payment of Honoraria Expense from '.date('F j, Y',$first).' to '.date('F j, Y',$last);
            $jevmodel->save();
            
            JevEntries::insertAccount($jev,'debit','50102100',$totalamount);
            JevEntries::insertAccount($jev,'credit','10101000',$totalamount);

            //$dv = new DvTracking();
            //$dv->dv_number = DvTracking::getNewdvnumber();
            $dv->date_posted = $transaction_date;
            $dv->amount = $totalamount;
            $dv->particular = 'Payment of Honoraria Expense from '.date('F j, Y',$first).' to '.date('F j, Y',$last);
            $dv->debit = '50102100';
            $dv->credit = '10101000';
            $dv->jev = $jev;
            $dv->save(false);

            //$model->jev = $jev;
            //$model->dv = $dv->dv_number;


    /*
        $jevtracking = new JevTracking();
        $jevtracking->jev = date('Y-m-',strtotime($transaction_date)).$lastjev;
        $jevtracking->date_posted = $transaction_date;
        $jevtracking->remarks = 'Payment of Honoraria Expense from '.date('F j, Y',$first).' to '.date('F j, Y',$last);
        $jevtracking->source = 'payroll';
        ($jevtracking->save()?$error=0:$error=1);

        $entry = new JevEntries();
        $entry->jev = $jevtracking->jev;
        $entry->type= 'debit';
        $entry->accounting_code = 50102100;
        $entry->amount= $totalamount;
        ($entry->save()?$error=0:$error=1);

        $entry = new JevEntries();
        $entry->jev = $jevtracking->jev;
        $entry->type= 'credit';
        $entry->accounting_code = 10101000;
        $entry->amount= $totalamount;
        ($entry->save()?$error=0:$error=1);*/

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => ($error==0?'success':'danger'),
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => ($error==0?'Successfully created new Journal Entry':'Ooops! Something went wrong! The JEV# might already been taken.'),
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            /*}else{
                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'danger',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Ooops! Something went wrong! The JEV# might already been taken.',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
            }*/
            }

            return $this->redirect(['expenses/view','id'=>$dv->id]);


            //return $error;
        
      
    }

    public function actionCreatepcv()
    {
        $error=0;
        if(isset($_POST['selection'])){
            $selection = $_POST['selection'];
            $transaction_date = $_POST['transaction_date'];
            $totalamount = 0;

            $jev = JevTracking::createJev($transaction_date,'Payment of Honoraria Expense from ','payroll');
            $dv = new PcvTracking();
            $dv->pcv_number = PcvTracking::tempDv();
            $dv->type = 'payroll-honorarium';
            $dv->save(false);

            $first= strtotime(PayrollHonorarium::findOne($selection[0])->date_from);
            $last= strtotime(PayrollHonorarium::findOne($selection[0])->date_to);
            foreach ($selection as $key) {
                $model = PayrollHonorarium::findOne($key);
                $curFirst = strtotime($model->date_from);
                if ($curFirst < $first) {
                    $first = $curFirst;
                }
                $curLast = strtotime($model->date_to);
                if ($curLast > $last) {
                    $last = $curLast;
                }
                $totaldeduction = PayrollDeductionsHonorarium::find()->where(['pID'=>$model->id])->sum('amount');
                $totalamount += ($model->number_of_hours*$model->hourly_rate)-$totaldeduction;

                $model->pcv = $dv->pcv_number;
                $model->jev = $jev;
                $model->save();
            }

            $jevmodel = JevTracking::find()->where(['jev'=>$jev])->one();
            $jevmodel->remarks = 'Payment of Honoraria Expense from '.date('F j, Y',$first).' to '.date('F j, Y',$last);
            $jevmodel->save();
            
            JevEntries::insertAccount($jev,'debit',50102100,$totalamount);
            JevEntries::insertAccount($jev,'credit',10101020,$totalamount);

            //$dv = new DvTracking();
            //$dv->dv_number = DvTracking::getNewdvnumber();
            $dv->date_posted = $transaction_date;
            $dv->amount = $totalamount;
            $dv->particular = 'Payment of Honoraria Expense from '.date('F j, Y',$first).' to '.date('F j, Y',$last);
            $dv->debit = 50102100;
            $dv->credit = 10101020;
            $dv->jev = $jev;
            $dv->save(false);

           

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => ($error==0?'success':'danger'),
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => ($error==0?'Successfully created new Journal Entry':'Ooops! Something went wrong! The JEV# might already been taken.'),
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            }

            return $this->redirect(['pcv-tracking/view','id'=>$dv->id]);
    }

    /**
     * Displays a single Payroll model.
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

    public function actionRemittances()
    {
        return $this->render('remittances');
    }

    /**
     * Creates a new Payroll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PayrollHonorarium();
        $deductionModel = new PayrollDeductionsHonorarium();

        if ($model->load(Yii::$app->request->post())) {
            $payrolldeductions = $_POST['PayrollDeductions'];
           // $model->save(false);
            $array=$model->emp_id;
            foreach ($array as $value) {
                $tempModel = new PayrollHonorarium(); 
                $tempModel->load(Yii::$app->request->post());
                $tempModel->number_of_hours=1;
                $tempModel->hourly_rate=Officers::findOne($value)->p->honorarium;
                $tempModel->emp_id = $value;
                $tempModel->save(false);

                foreach ($payrolldeductions as $key => $value) {
                    $deductionModel = new PayrollDeductionsHonorarium();
                    $deductionModel->pID = $tempModel->id;
                    $deductionModel->dID = $key;
                    $deductionModel->amount = $value;
                    $deductionModel->save(false);
                }
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
            'deductionModel' => $deductionModel,
        ]);
    }

    /**
     * Updates an existing Payroll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Payroll model.
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
     * Finds the Payroll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payroll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PayrollHonorarium::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
