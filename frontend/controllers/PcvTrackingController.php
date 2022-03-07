<?php

namespace frontend\controllers;

use Yii;
use app\models\PcvTracking;
use app\models\PcvTrackingSearch;
use app\models\JevTracking;
use app\models\JevEntries;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * PcvTrackingController implements the CRUD actions for PcvTracking model.
 */
class PcvTrackingController extends Controller
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
     * Lists all PcvTracking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PcvTrackingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PcvTracking model.
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
     * Creates a new PcvTracking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PcvTracking();

        if ($model->load(Yii::$app->request->post())) {
            $jev = JevTracking::createJev($model->date_posted,$model->particular,'PCV');
            JevEntries::insertAccount($jev,'debit',$model->debit,$model->amount);
            JevEntries::insertAccount($jev,'credit',10101020,$model->amount);
            $model->credit = 10101020;
            $model->pcv_number = PcvTracking::tempDv();
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
            return $this->redirect(['pcv-tracking/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PcvTracking model.
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
     * Deletes an existing PcvTracking model.
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
                 'message' => 'Successfully deleted PCV!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);
            return $this->redirect(['index']);
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Finds the PcvTracking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PcvTracking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PcvTracking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
