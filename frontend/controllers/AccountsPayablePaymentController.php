<?php

namespace frontend\controllers;

use Yii;
use app\models\AccountsPayableInvoice;
use app\models\AccountsPayablePayment;
use app\models\AccountsPayablePaymentSearch;
use app\models\DvTracking;
use app\models\PcvTracking;
use app\models\JevTracking;
use app\models\JevEntries;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccountsPayablePaymentController implements the CRUD actions for AccountsPayablePayment model.
 */
class AccountsPayablePaymentController extends Controller
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
     * Lists all AccountsPayablePayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountsPayablePaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccountsPayablePayment model.
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
     * Creates a new AccountsPayablePayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccountsPayablePayment();
        $dvmodel = new DvTracking();

        if ($model->load(Yii::$app->request->post()) && $dvmodel->load(Yii::$app->request->post())) {

            //generate DV number
            $dv = DvTracking::getNewdvnumber();

            //search for AP model
            $apmodel = AccountsPayableInvoice::findOne($model->ap_id);

            //create jev
            $jev = JevTracking::createJev($dvmodel->date_posted,$dvmodel->particular,'AP Payment');

            $dvmodel->payee = $apmodel->supplier;
            $dvmodel->dv_number = $dv;
            $dvmodel->type ='AP Payment';
            $dvmodel->debit = 20101010;
            $dvmodel->jev = $jev;
            $dvmodel->save();           

            $model->dv_number = $dvmodel->dv_number;
            $model->save();

            JevEntries::insertAccount($jev,'debit',20101010,$dvmodel->amount);
            JevEntries::insertAccount($jev,'credit',$dvmodel->credit,$dvmodel->amount);

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Transaction recorded successfully!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  

            return $this->redirect(['accounts-payable-invoice/view', 'id' => $model->ap_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'dvmodel' => $dvmodel,
        ]);
    }

    public function actionPettyCash()
    {
        $model = new AccountsPayablePayment();
        $dvmodel = new PcvTracking();

        if ($model->load(Yii::$app->request->post()) && $dvmodel->load(Yii::$app->request->post())) {

            //generate DV number
            $dv = PcvTracking::tempDv();

            //search for AP model
            $apmodel = AccountsPayableInvoice::findOne($model->ap_id);

            //create jev
            $jev = JevTracking::createJev($dvmodel->date_posted,$dvmodel->particular,'AP Payment');

            $dvmodel->payee = $apmodel->supplier;
            $dvmodel->pcv_number = $dv;
            $dvmodel->type ='AP Payment';
            $dvmodel->debit = 20101010;
            $dvmodel->credit = 10101020;
            $dvmodel->jev = $jev;
            $dvmodel->save();           

            $model->pcv_number = $dvmodel->pcv_number;
            $model->save();

            JevEntries::insertAccount($jev,'debit',20101010,$dvmodel->amount);
            JevEntries::insertAccount($jev,'credit',10101020,$dvmodel->amount);

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Transaction recorded successfully!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  

            return $this->redirect(['accounts-payable-invoice/view', 'id' => $model->ap_id]);
        }

        return $this->render('createpettycash', [
            'model' => $model,
            'dvmodel' => $dvmodel,
        ]);
    }

    /**
     * Updates an existing AccountsPayablePayment model.
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
     * Deletes an existing AccountsPayablePayment model.
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
     * Finds the AccountsPayablePayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccountsPayablePayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccountsPayablePayment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
