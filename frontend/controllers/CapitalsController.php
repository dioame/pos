<?php

namespace frontend\controllers;

use Yii;
use app\models\Capitals;
use app\models\CapitalsSearch;
use app\models\JevEntriesSearch;
use app\models\OrArTracking;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use app\models\JevTracking;
use app\models\JevEntries;

/**
 * CapitalsController implements the CRUD actions for Capitals model.
 */
class CapitalsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest)
            $this->redirect(['site/login']);
        else
            return true;
    }
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
     * Lists all Capitals models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('treasurer-only')||Yii::$app->user->can('bookkeeper-only')){

            $searchModel = new JevEntriesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->where(['accounting_code'=>30101030,'type'=>'credit']);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
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
                if($model->save(false)){
                    Yii::$app->getSession()->setFlash('success1', [
                         'type' => 'success',
                         'duration' => 5000,
                         'icon' => 'fa fa-user',
                         'message' => 'Successfully saved OR number!',
                         'positonY' => 'top',
                         'positonX' => 'right'
                     ]); 
                    return $this->redirect(Yii::$app->request->referrer);
                }else{
                    Yii::$app->getSession()->setFlash('error', [
                         'type' => 'danger',
                         'duration' => 5000,
                         'icon' => 'fa fa-user',
                         'message' => 'Error saving OR number!',
                         'positonY' => 'top',
                         'positonX' => 'right'
                     ]); 
                    return $this->redirect(Yii::$app->request->referrer);
                }
                
            }

            return $this->renderAjax('editor', [
                'model' => $model,
            ]);

        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Displays a single Capitals model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * Creates a new Capitals model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('treasurer-only')){
            $model = new Capitals();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                if($model->type=='cash'){
                    $jev = JevTracking::createJev($model->date_posted,'Capital Contribution - '.$model->members->lastname.', '.$model->members->firstname,'capitals');
                    JevEntries::insertAccount($jev,'debit',10101000,$model->amount);
                    JevEntries::insertAccount($jev,'credit',30101030,$model->amount);

                    $newor = new OrArTracking();
                    $newor->tracking = $model->arNo;
                    $newor->save(false);

                    $model->jev = $jev;
                    $model->save(false);
                }                

                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully added capital!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
                return $this->redirect(['index']);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Updates an existing Capitals model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('treasurer-only')){
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully updated capital!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
                return $this->redirect(['index']);
            }

            return $this->render('create', [
                'model' => $model,
            ]);

        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Deletes an existing Capitals model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->can('treasurer-only')){
            $jev = JevEntries::findOne($id)->jev;
            //$ornumber = Sales::find()->where(['jev'=>$jev])->one()->orNo;
            $jevmodel = JevTracking::find()->where(['jev'=>$jev])->one();
            if($jevmodel){
                $jevmodel->delete();
            }
            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully deleted capital!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);
            return $this->redirect(['index']);
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    /**
     * Finds the Capitals model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Capitals the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Capitals::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
