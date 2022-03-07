<?php

namespace frontend\controllers;

use Yii;
use app\models\Products;
use app\models\ProductsSearch;
use app\models\Log;
use app\models\StockCard;
use app\models\Pricelist;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest)
            $this->redirect(['site/login']);
        else return true;
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
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Products();
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('masterlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionMasterlist()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('masterlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
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

    public function actionStocksjournal()
    {
        return $this->render('stockjournal');
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();
        $errorcount = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $pricelist = new Pricelist();
            $pricelist->pId = $model->id;
            $pricelist->date_adjusted = date('Y-m-d');
            $pricelist->price = $model->price;
            $pricelist->save();

            /*if($existing = Products::find()->where(['sku'=>$model->sku])->one()){
                Log::addLog("Product (".$existing->id.")".$existing->product_name." was added ".$model->quantity_at_hand.", from ".$existing->quantity_at_hand." to ".($existing->quantity_at_hand+$model->quantity_at_hand));

                $stockcard = new StockCard();
                $stockcard->sku = $existing->sku;
                $stockcard->date = $model->date;
                $stockcard->finished = $model->finished;
                $stockcard->price = 0.00;
                $stockcard->existing = $existing->quantity_at_hand;
                $stockcard->added = $model->quantity_at_hand;
                $stockcard->total = ($model->quantity_at_hand+$existing->quantity_at_hand);
                $stockcard->remarks = $model->remarks;

                if($stockcard->save(false)){
                    Log::addLog("Stock Card added (".$existing->id.")".$existing->product_name." was added ".$model->quantity_at_hand.", from ".$existing->quantity_at_hand." to ".($existing->quantity_at_hand+$model->quantity_at_hand));
                }else{
                    $errorcount++;
                }

                $existing->quantity_at_hand = $existing->quantity_at_hand+$model->quantity_at_hand;
                $existing->buying_price = 0.00;
                $existing->price = $model->price;
                $existing->save(false);




                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully added '.$model->quantity_at_hand.' '.$model->product_name.'!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
            }else{
                if(!$model->save()){
                    $errorcount++;
                }
                Log::addLog("New product added - (".$model->id.")".$model->product_name.", quantity: ".$model->quantity_at_hand.", buying_price: 0.00, price: ".($model->price));

                $stockcard = new StockCard();
                $stockcard->sku = $model->sku;
                $stockcard->existing = 0;
                $stockcard->price = 0.00;
                $stockcard->date = $model->date;
                $stockcard->finished = $model->finished;
                $stockcard->added = $model->quantity_at_hand;
                $stockcard->total = $model->quantity_at_hand;
                $stockcard->remarks = $model->remarks;
                $stockcard->save(false);

                Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully added new item - '.$model->quantity_at_hand.' '.$model->product_name.'!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
            }
            if($errorcount==0){
                return $this->redirect(['products/index']);
            }*/
            Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully added new product!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 
            return $this->redirect(['index']);
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionProductionreport(){
        return $this->render('productionreport');
    }

    /**
     * Updates an existing Products model.
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

    public function actionSearchsku($id)
    {
        $model = Products::find()->where(['sku'=>$id])->one();
        if ($model) {
            $arr = ['code'=>'success','id'=>$model->id,'product_name'=>$model->product_name,'buying_price'=>$model->buying_price,'price'=>$model->price];
            return json_encode($arr);
        }else{
            $arr = ['code'=>'failed'];
            return json_encode($arr);
        }
    }

    /**
     * Deletes an existing Products model.
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
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
