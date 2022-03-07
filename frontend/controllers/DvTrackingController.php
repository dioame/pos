<?php

namespace frontend\controllers;

use Yii;
use app\models\DvTracking;
use app\models\DvTrackingSearch;
use app\models\AccountsPayableInvoice;
use app\models\AccountsPayablePayment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\JevTracking;
use app\models\JevEntries;

/**
 * DvTrackingController implements the CRUD actions for DvTracking model.
 */
class DvTrackingController extends Controller
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
     * Lists all DvTracking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DvTrackingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DvTracking model.
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
     * Creates a new DvTracking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type,$apid)
    {
        $model = new DvTracking();

        if ($model->load(Yii::$app->request->post())) {
            $jev = JevTracking::createJev($model->date_posted,'To record payment of AP'.AccountsPayableInvoice::findOne($apid)->invoice_number,'DV');
            JevEntries::insertAccount($jev,'debit','20101010',$model->amount);
            JevEntries::insertAccount($jev,'credit',$model->credit,$model->amount);
            $model->jev = $jev;
            $model->save();

            $payments = new AccountsPayablePayment();
            $payments->ap_id = $apid;
            $payments->dv_number = $model->dv_number;
            $payments->save(false);

            return $this->redirect(['accounts-payable-invoice/view', 'id' => $apid]);
        }

        $vouchertype="";
        $creditaccount = 0;

        switch ($type) {
            case 'cash':
                $vouchertype = 'Cash';
                $creditaccount = 10101000;
                break;

            case 'cashinbank':
                $vouchertype = 'Check';
                $creditaccount = 10102010;
                break;
            
            case 'petty-cash':
                $vouchertype = 'Petty Cash';
                $creditaccount = 10101020;
                break;

            default:
                $vouchertype = 'Unknown';
                $creditaccount = 0;
                break;
        }

        return $this->render('create', [
            'model' => $model,
            'vouchertype' => $vouchertype,
            'creditaccount' => $creditaccount,
            'apmodel'=>AccountsPayableInvoice::findOne($apid)
        ]);
    }

    /**
     * Updates an existing DvTracking model.
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
     * Deletes an existing DvTracking model.
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
     * Finds the DvTracking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DvTracking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DvTracking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
