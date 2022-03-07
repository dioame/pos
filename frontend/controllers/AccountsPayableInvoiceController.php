<?php

namespace frontend\controllers;

use Yii;
use app\models\AccountsPayableInvoice;
use app\models\AccountsPayableInvoiceSearch;
use app\models\AccountsPayablePaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\JevTracking;
use app\models\JevEntries;

/**
 * AccountsPayableInvoiceController implements the CRUD actions for AccountsPayableInvoice model.
 */
class AccountsPayableInvoiceController extends Controller
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
     * Lists all AccountsPayableInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountsPayableInvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccountsPayableInvoice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new AccountsPayablePaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['ap_id'=>$id]);
        $dataProvider->query->orderBy(['id'=>SORT_DESC]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new AccountsPayableInvoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccountsPayableInvoice();

        if ($model->load(Yii::$app->request->post())) {
            $jev = JevTracking::createJev($model->invoice_date,'To record '.$model->typeOfExpense->object_code.' payable to '.$model->supplier0->supplier_name.' amounting '.number_format($model->amount,2),'API');
            JevEntries::insertAccount($jev,'debit',$model->type_of_expense,$model->amount);
            JevEntries::insertAccount($jev,'credit','20101010',$model->amount);
            $model->jev = $jev;
            $model->save(false);
            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Transaction recorded successfully!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AccountsPayableInvoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $jeventries = JevEntries::find()->where(['jev'=>$model->jev])->all();
            foreach ($jeventries as $key) {
                $tempmodel = JevEntries::findOne($key->id);
                $tempmodel->amount = $model->amount;
                if($key->type=='debit'){
                    $tempmodel->accounting_code = $model->type_of_expense;
                }
                $tempmodel->save();
            }
            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully updated accounts payable - '.$model->id.'!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AccountsPayableInvoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        //JevTracking::find()->where(['jev'=>$model->jev])->one()->delete();
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AccountsPayableInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccountsPayableInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccountsPayableInvoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
