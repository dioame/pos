<?php

namespace frontend\controllers;

use Yii;
use app\models\Expenses;
use app\models\ExpensesPaymentPayable;
use app\models\ExpensesPaymentPayableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExpensesPaymentPayableController implements the CRUD actions for ExpensesPaymentPayable model.
 */
class ExpensesPaymentPayableController extends Controller
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
     * Lists all ExpensesPaymentPayable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExpensesPaymentPayableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExpensesPaymentPayable model.
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
     * Creates a new ExpensesPaymentPayable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExpensesPaymentPayable();
        //$expensesModel = Expenses::findOne($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->save(false);

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully added payment!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('create', [
            //'expensesModel' => $expensesModel,
            'model' => $model,
            //'id' => $id,
        ]);
       
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

    /**
     * Updates an existing ExpensesPaymentPayable model.
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

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExpensesPaymentPayable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the ExpensesPaymentPayable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExpensesPaymentPayable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExpensesPaymentPayable::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
