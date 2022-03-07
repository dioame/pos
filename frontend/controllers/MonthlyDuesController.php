<?php

namespace frontend\controllers;

use Yii;
use app\models\MonthlyDues;
use app\models\MonthlyDuesSearch;
use app\models\JevTracking;
use app\models\JevEntries;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MonthlyDuesController implements the CRUD actions for MonthlyDues model.
 */
class MonthlyDuesController extends Controller
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
     * Lists all MonthlyDues models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MonthlyDuesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MonthlyDues model.
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
     * Creates a new MonthlyDues model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new MonthlyDues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }*/

    public function actionCreate()
    {
        $model = new MonthlyDues();

        if ($model->load(Yii::$app->request->post())) {
            $array=$model->mID;
            $totalamount = 0;

            $jev = JevTracking::createJev(date('Y-m-d'),'To record monthly dues collected','others');
            
            foreach ($array as $value) {

                $tempModel = new MonthlyDues(); 
                $tempModel->load(Yii::$app->request->post());
                $totalamount += $tempModel->amount;
                $tempModel->mID = $value;
                $tempModel->jev = $jev;
                $tempModel->save(false);

            }

            JevEntries::insertAccount($jev,'debit',10101000,$totalamount);
            JevEntries::insertAccount($jev,'credit',30101030,$totalamount);

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully recorded monthly due collection!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /*public function actionCreatejev()
    {
        $error=0;
        if(isset($_POST['selection'])){
            $selection = $_POST['selection'];
            $transaction_date = $_POST['transaction_date'];
            $totalamount = 0;

            $jev = JevTracking::createJev($transaction_date,'To record monthly dues collected','others');

            foreach ($selection as $key) {
                $model = MonthlyDues::findOne($key);
                $totalamount += $model->amount;
                $model->jev = $jev;
                $model->save();
            }
            
            JevEntries::insertAccount($jev,'debit',10101000,$totalamount);
            JevEntries::insertAccount($jev,'credit',30101030,$totalamount);
           

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => ($error==0?'success':'danger'),
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => ($error==0?'Successfully created new Journal Entry':'Ooops! Something went wrong! The JEV# might already been taken.'),
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            }

            return $this->redirect(['index']);
    }*/

    /**
     * Updates an existing MonthlyDues model.
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
     * Deletes an existing MonthlyDues model.
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
     * Finds the MonthlyDues model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MonthlyDues the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonthlyDues::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
