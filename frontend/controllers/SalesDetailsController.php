<?php

namespace frontend\controllers;

use Yii;
use app\models\Sales;
use app\models\SalesDetails;
use app\models\SalesDetailsSearch;
use app\models\ProductsSearch;
use app\models\Products;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * SalesDetailsController implements the CRUD actions for SalesDetails model.
 */
class SalesDetailsController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all SalesDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->can('cashier-only')){
            $searchModel = new SalesDetailsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }

    public function actionNew()
    {
        if(Yii::$app->user->can('cashier-only')){
            $model = new SalesDetails();
            $searchModel = new ProductsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('new', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        }else{
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
        
    }

    public function actionAdd()
    {
        $id=$_POST['id'];
        $arr = [];
        $model = Products::find()->where(['id'=>$id])->one();
        $arr = ['id'=>$model->id,'product_name'=>$model->product_name,'price'=>$model->price];
        return json_encode($arr);
    }

    /**
     * Displays a single SalesDetails model.
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
     * Creates a new SalesDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SalesDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SalesDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $sales = Sales::findOne($model->sales_id);
            $salesdetailssum = SalesDetails::find()->where(['sales_id'=>$model->sales_id])->sum('sub_total');
            $sales->total = $salesdetailssum;
            $sales->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SalesDetails model.
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
     * Finds the SalesDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SalesDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SalesDetails::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
