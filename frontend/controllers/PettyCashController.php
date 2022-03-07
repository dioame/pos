<?php

namespace frontend\controllers;

use Yii;
use app\models\PettyCash;
use app\models\PettyCashSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\JevTracking;
use app\models\JevEntries;
use app\models\DvTracking;
use app\models\PcvTracking;

/**
 * PettyCashController implements the CRUD actions for PettyCash model.
 */
class PettyCashController extends Controller
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
     * Lists all PettyCash models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PettyCashSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PettyCash model.
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
     * Creates a new PettyCash model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PcvTracking();

        if ($model->load(Yii::$app->request->post())) {

            

            $model->save(false);

            $lastjev = JevTracking::find()->orderBy('id DESC')->one();
            $pieces = explode("-",$lastjev->jev);
            $lastjev = (int)$pieces[2]+1;

            $jevtracking = new JevTracking();
            $jevtracking->jev = date('Y-m-',strtotime($model->date)).$lastjev;
            $jevtracking->date_posted = $model->date;
            $jevtracking->remarks = $model->remarks;
            $jevtracking->source = 'expenses';
            $jevtracking->save();

            //debit
            $entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'debit';
            $entry->accounting_code = $model->type;
            $entry->amount= $model->amount;
            $entry->save();

            if($model->amount_paid>0){
                $entry = new JevEntries();
                $entry->jev = $jevtracking->jev;
                $entry->type= 'credit';
                $entry->accounting_code = 10101020;
                $entry->amount= $model->amount_paid;
                $entry->save();
            }

            if($model->balance>0){
                $entry = new JevEntries();
                $entry->jev = $jevtracking->jev;
                $entry->type= 'credit';
                $entry->accounting_code = 20101010;
                $entry->amount= $model->balance;
                $entry->save();
            }

            $model->jev = $jevtracking->jev;
            $model->dv = date('Y-m-',strtotime($model->date)).$model->id;;
            $model->save(false);

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully added new expense',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 

            return $this->redirect(['create']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEstablishment(){
        
        if (Yii::$app->request->post()) {

            $jev = JevTracking::createJev($_POST['date_posted'],$_POST['reference'],'others');
            JevEntries::insertAccount($jev,'debit',10101020,$_POST['amount']);
            JevEntries::insertAccount($jev,'credit',$_POST['fundsource'],$_POST['amount']);

            /*$lastjev = JevTracking::find()->orderBy('id DESC')->one();
            if($lastjev){
                $pieces = explode("-",$lastjev->jev);
                $lastjev = (int)$pieces[2]+1;
            }else{
                $lastjev = 1;
            }*/
            
            /*$jevtracking = new JevTracking();
            $jevtracking->jev = date('Y-m-',strtotime($_POST['date_posted'])).$lastjev;
            $jevtracking->date_posted = $_POST['date_posted'];
            $jevtracking->remarks = $_POST['reference'];
            $jevtracking->source = 'others';
            $jevtracking->save();

            $entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'debit';
            $entry->accounting_code = 10101020;
            $entry->amount= $_POST['amount'];
            $entry->save();

            $entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'credit';
            $entry->accounting_code = 10101000;
            $entry->amount= $_POST['amount'];
            $entry->save();*/

            Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully added new transaction!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 

            return $this->redirect(['petty-cash/establishment']);
        }

        return $this->render('establishment');
    }

    /**
     * Updates an existing PettyCash model.
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
     * Deletes an existing PettyCash model.
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
     * Finds the PettyCash model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PettyCash the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PettyCash::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
