<?php

namespace frontend\controllers;

use Yii;
use app\models\OrArTracking;
use app\models\OrArTrackingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * OrArTrackingController implements the CRUD actions for OrArTracking model.
 */
class OrArTrackingController extends Controller
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
     * Lists all OrArTracking models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('treasurer-only')){

            return $this->render('index');
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Displays a single OrArTracking model.
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
     * Creates a new OrArTracking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('treasurer-only')){
            $model = new OrArTracking();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('create', [
                'model' => $model,
            ]);

        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    public function actionCashjournal(){
        if(Yii::$app->user->can('treasurer-only')){
            return $this->render('cashjournal');
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
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

    public function actionEditor($id)
    {
        if(Yii::$app->user->can('cashier-only')){
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                if($model->save(false))
                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully saved OR number!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
                return $this->redirect(Yii::$app->request->referrer);
            }

            return $this->renderAjax('editor', [
                'model' => $model,
            ]);

        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    public function actionUpdatejevsales($id)
    {
        if(Yii::$app->user->can('bookkeeper-only')){
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                if($model->save())
                return $this->redirect(['sales/index','id'=>$model->jev]);
            }

            return $this->renderAjax('updatejev', [
                'model' => $model,
            ]);

        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Updates an existing OrArTracking model.
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
     * Deletes an existing OrArTracking model.
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
     * Finds the OrArTracking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrArTracking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrArTracking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
