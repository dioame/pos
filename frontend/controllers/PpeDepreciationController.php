<?php

namespace frontend\controllers;

use Yii;
use app\models\PpeDepreciation;
use app\models\PpeDepreciationSearch;
use app\models\JevTracking;
use app\models\JevEntries;
use app\models\Ppe;
use app\models\Uacs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PpeDepreciationController implements the CRUD actions for PpeDepreciation model.
 */
class PpeDepreciationController extends Controller
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
     * Lists all PpeDepreciation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PpeDepreciationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PpeDepreciation model.
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
     * Creates a new PpeDepreciation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PpeDepreciation();

        if ($model->load(Yii::$app->request->post())) {

            $accumulatedmodel = Uacs::find()->where(['object_code'=>'Accumulated Depreciation - '.$model->ppe->uacs0->object_code,'classification'=>'Asset'])->one();
            $depreciationmodel = Uacs::find()->where(['object_code'=>'Depreciation - '.$model->ppe->uacs0->grouping,'classification'=>'Expenses'])->one();

            $error=0;

            $jev = JevTracking::createJev($model->date_posted,'To record depreciation expense of '.$model->ppe->particular.' - '.$model->ppeID,'ppe_depreciation');
            JevEntries::insertAccount($jev,'debit',$depreciationmodel->uacs,$model->amount);
            JevEntries::insertAccount($jev,'credit',$accumulatedmodel->uacs,$model->amount);


            /*$lastjev = JevTracking::find()->orderBy('id DESC')->one();
            if($lastjev){
                $pieces = explode("-",$lastjev->jev);
                $lastjev = (int)$pieces[2]+1;
            }else{
                $lastjev = 1;
            }

            $jevtracking = new JevTracking();
            $jevtracking->jev = date('Y-m-',strtotime($model->date_posted)).$lastjev;
            $jevtracking->date_posted = $model->date_posted;
            $jevtracking->remarks = 'To record depreciation expense of '.$model->ppe->particular.' - '.$model->ppeID;
            $jevtracking->source = 'ppe_depreciation';
            ($jevtracking->save()?$error=0:$error=1);*/

            /*$entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'debit';
            $entry->accounting_code = $depreciationmodel->uacs;
            $entry->amount= $model->amount;
            ($entry->save()?$error=0:$error=1);

            $entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'credit';
            $entry->accounting_code = $accumulatedmodel->uacs;
            $entry->amount= $model->amount;
            ($entry->save()?$error=0:$error=1);*/

            $model->jev1 = $jev;
            $model->save();


            //second entry
            /*$error=0;
            $lastjev = JevTracking::find()->orderBy('id DESC')->one();
            if($lastjev){
                $pieces = explode("-",$lastjev->jev);
                $lastjev = (int)$pieces[2]+1;
            }else{
                $lastjev = 1;
            }

            $jevtracking = new JevTracking();
            $jevtracking->jev = date('Y-m-',strtotime($model->date_posted)).$lastjev;
            $jevtracking->date_posted = $model->date_posted;
            $jevtracking->remarks = 'To record accumulated depreciation of '.$model->ppe->particular.' - '.$model->ppeID;
            $jevtracking->source = 'ppe_depreciation';
            ($jevtracking->save()?$error=0:$error=1);

            $entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'debit';
            $entry->accounting_code = $accumulatedmodel->uacs;
            $entry->amount= $model->amount;
            ($entry->save()?$error=0:$error=1);

            $entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'credit';
            $entry->accounting_code = $model->ppe->uacs;
            $entry->amount= $model->amount;
            ($entry->save()?$error=0:$error=1);*/

            //$model->jev2 = $jevtracking->jev;
            //$model->save();

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => ($error==0?'success':'danger'),
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => ($error==0?'Successfully created new Journal Entry':'Ooops! Something went wrong! The JEV# might already been taken.'),
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);
            return $this->redirect(['ppe/view', 'id' => $model->ppeID]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PpeDepreciation model.
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
     * Deletes an existing PpeDepreciation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $jev = PpeDepreciation::findOne($id)->jev1;
        $ppeid = PpeDepreciation::findOne($id)->ppeID;

        if($jevmodel = JevTracking::find()->where(['jev'=>$jev])->one()){
            $jevmodel->delete();
        }
        //$this->findModel($id)->delete();

        Yii::$app->getSession()->setFlash('success1', [
             'type' => 'success',
             'duration' => 5000,
             'icon' => 'fa fa-user',
             'message' => 'Record successfully deleted!',
             'positonY' => 'top',
             'positonX' => 'right'
         ]);  

        return $this->redirect(['ppe/view','id'=>$ppeid]);
    }

    /**
     * Finds the PpeDepreciation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PpeDepreciation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PpeDepreciation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
