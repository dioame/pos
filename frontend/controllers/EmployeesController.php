<?php

namespace frontend\controllers;

use Yii;
use app\models\Employees;
use app\models\EmployeesSearch;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmployeesController implements the CRUD actions for Employees model.
 */
class EmployeesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest)
            $this->redirect(['site/login']);
        else{
            if(!Yii::$app->user->can('manager-only')){
                throw new ForbiddenHttpException('The requested page is forbidden.');
            }else{
                return true;
            }
        }
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
     * Lists all Employees models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('manager-only'))
            throw new NotFoundHttpException('The requested page does not exist or access denied.');
        else
        $searchModel = new EmployeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employees model.
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
     * Creates a new Employees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('manager-only'))
            throw new NotFoundHttpException('The requested page does not exist or access denied.');
        else
        $model = new Employees();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->email!=null||$model->email!=''){
                $user = new User();
                $user->username = strtolower($model->email);
                $user->email = $model->email;
                $user->employee_id = $model->id;
                $user->setPassword('password');
                $user->generateAuthKey();
                $user->save();
            }
            
            Yii::$app->getSession()->setFlash('success4', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully saved new employee!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  
            return $this->redirect(['index']);
            
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    public function actionResetpasswordadmin($id){
        if (!Yii::$app->user->can('manager-only'))
            throw new NotFoundHttpException('The requested page does not exist or access denied.');
        else
        $user = User::find()->where(['employee_id'=>$id])->one();
        $user->setPassword('password');
        $user->generateAuthKey();
        if($user->save()){
            Yii::$app->getSession()->setFlash('success4', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully set the default password',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  
        }else{
            $model->delete();
            Yii::$app->getSession()->setFlash('error1', [
                 'type' => 'danger',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Failed to reset password',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  
        }
        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Employees model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('manager-only'))
            throw new NotFoundHttpException('The requested page does not exist or access denied.');
        else
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Employees model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('manager-only'))
            throw new NotFoundHttpException('The requested page does not exist or access denied.');
        else
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Employees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employees::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
