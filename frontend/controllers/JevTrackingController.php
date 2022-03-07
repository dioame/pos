<?php

namespace frontend\controllers;

use Yii;
use app\models\JevTracking;
use app\models\JevTrackingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * JevTrackingController implements the CRUD actions for JevTracking model.
 */
class JevTrackingController extends Controller
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
     * Lists all JevTracking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JevTrackingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGeneraljournal(){
        if(Yii::$app->user->can('bookkeeper-only')){
            return $this->render('generaljournal');
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    public function actionEditjournal(){
        if(Yii::$app->user->can('bookkeeper-only')){
            return $this->render('editjournal');
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    public function actionBankjournal(){
        if(Yii::$app->user->can('bookkeeper-only')){
            return $this->render('bankjournal');
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    public function actionJournalGenerator(){
        return $this->render('journalgen');
    }

    /**
     * Displays a single JevTracking model.
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
     * Creates a new JevTracking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('bookkeeper-only')){
            $model = new JevTracking();

            if ($model->load(Yii::$app->request->post())) {
                $model->jev = JevTracking::tempJev();
                $model->save();
                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully saved journal entry',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
                return $this->redirect(['update', 'id' => $model->id]);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    public function actionClosingEntry()
    {
        if(Yii::$app->user->can('bookkeeper-only')){
            $model = new JevTracking();

            if ($model->load(Yii::$app->request->post())) {
                $model->jev = JevTracking::tempJev();
                $model->source='closing entry';
                $model->isClosingEntry='yes';
                if($model->save()){
                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully made changes to the journal entry',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
                return $this->redirect(['update', 'id' => $model->id]);
            }else{
                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'danger',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Sorry, something went wrong! Please check the JEV#!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
                return $this->redirect(['closing-entry']);
            }
                
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Updates an existing JevTracking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully made changes to the journal entry',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
            }else{
                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'danger',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Sorry, something went wrong! Please check the JEV#!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
            }
            
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing JevTracking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        Yii::$app->getSession()->setFlash('success1', [
             'type' => 'success',
             'duration' => 5000,
             'icon' => 'fa fa-user',
             'message' => 'Successfully deleted journal entry',
             'positonY' => 'top',
             'positonX' => 'right'
         ]); 
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the JevTracking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JevTracking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JevTracking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
