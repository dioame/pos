<?php

namespace frontend\controllers;

use Yii;
use app\models\StockCard;
use app\models\StockCardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockCardController implements the CRUD actions for StockCard model.
 */
class StockCardController extends Controller
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
     * Lists all StockCard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockCardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StockCard model.
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
     * Creates a new StockCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StockCard();

        if ($model->load(Yii::$app->request->post())) {
            $hoursfrom = $_POST['hoursfrom'];
            if($_POST['medianfrom']=='pm'){
                $hoursfrom += 12;
            }
            $datefrom = date("c",strtotime($model->date.' '.$hoursfrom.':'.$_POST['minutesfrom'].':00'));

            $hoursto = $_POST['hoursto'];
            if($_POST['medianto']=='pm'){
                $hoursto += 12;
            }
            $dateto = date("c",strtotime($model->finished.' '.$hoursto.':'.$_POST['minutesto'].':00'));

            $model->date = $datefrom;
            $model->finished = $dateto;
            $model->save();


            //return date("c",strtotime($model->date.' '.$hoursfrom.':'.$_POST['minutesfrom'].':00'));
            //$model->save();
            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully added production!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StockCard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $hoursfrom = $_POST['hoursfrom'];
            if($_POST['medianfrom']=='pm'){
                $hoursfrom += 12;
            }
            $datefrom = date("c",strtotime($model->date.' '.$hoursfrom.':'.$_POST['minutesfrom'].':00'));

            $hoursto = $_POST['hoursto'];
            if($_POST['medianto']=='pm'){
                $hoursto += 12;
            }
            $dateto = date("c",strtotime($model->finished.' '.$hoursto.':'.$_POST['minutesto'].':00'));

            $model->date = $datefrom;
            $model->finished = $dateto;
            $model->save();

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully updated production!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            return $this->redirect('index');
        }

        //$model->date = date('Y-m-d',strtotime($model->date));
        //$model->finished = date('Y-m-d',strtotime($model->finished));

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StockCard model.
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
     * Finds the StockCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockCard::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
