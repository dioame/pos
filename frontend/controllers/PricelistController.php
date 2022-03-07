<?php

namespace frontend\controllers;

use Yii;
use app\models\Pricelist;
use app\models\PricelistSearch;
use app\models\Products;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PricelistController implements the CRUD actions for Pricelist model.
 */
class PricelistController extends Controller
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
     * Lists all Pricelist models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PricelistSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pricelist model.
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
     * Creates a new Pricelist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($pID)
    {
        $model = new Pricelist();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $product = Products::findOne($pID);
            $product->price = $model->price;
            $product->save();
            Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully adjsuted new price!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
            return $this->redirect(['products/masterlist']);
        }

        return $this->render('create', [
            'model' => $model,
            'pID' => $pID,
        ]);
    }

    /**
     * Updates an existing Pricelist model.
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
     * Deletes an existing Pricelist model.
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

    public function actionGetprice($pid){
        if($pricemodel=Pricelist::find()->where(['pId'=>$pid])->orderBy(['date_adjusted'=>SORT_DESC,'id'=>SORT_DESC])->one()){
            return $pricemodel->price;
        }else{
            return 0;
        }
        
    }

    /**
     * Finds the Pricelist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pricelist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pricelist::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
