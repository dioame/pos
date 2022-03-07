<?php

namespace frontend\controllers;

use Yii;
use app\models\PaymentsReceivable;
use app\models\PaymentsReceivableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\models\JevEntries;
use app\models\JevTracking;
use app\models\OrArTracking;
use app\models\Sales;

/**
 * PaymentsReceivableController implements the CRUD actions for PaymentsReceivable model.
 */
class PaymentsReceivableController extends Controller
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
     * Lists all PaymentsReceivable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentsReceivableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaymentsReceivable model.
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
     * Creates a new PaymentsReceivable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaymentsReceivable();

        if ($model->load(Yii::$app->request->post())) {

            $salesmodel = Sales::findOne($model->sales_id);

            $jev = JevTracking::createJev($model->transaction_date,'Collection of AR from '.$salesmodel->customerfullname.' - SID#'.$salesmodel->id,'collection of ar');

            JevEntries::insertAccount($jev,'debit',10101000,$model->amount_paid);
            JevEntries::insertAccount($jev,'credit',10301010,$model->amount_paid);

            $newor = new OrArTracking();
            $newor->tracking = OrArTracking::getNewor();
            $newor->save(false);


            $model->jev = $jev;
            $model->orNo = $newor->id;
            $model->save(false);

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully added new collection',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 

            return $this->redirect(['create']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PaymentsReceivable model.
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
     * Deletes an existing PaymentsReceivable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('treasurer-only')){
            $model = $this->findModel($id);
            $jev = JevTracking::find()->where(['jev'=>$model->jev])->one();
            
            if($jev){
                $jev->delete();
            }

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully deleted collection!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);
            return $this->redirect(['index']);
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Finds the PaymentsReceivable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentsReceivable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentsReceivable::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
