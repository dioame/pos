<?php

namespace frontend\controllers;

use Yii;
use app\models\Ppe;
use app\models\PpeSearch;
use app\models\JevTracking;
use app\models\JevEntries;
use app\models\DvTracking;
use app\models\PcvTracking;
use app\models\AccountsPayableInvoice;
use app\models\PpeDepreciationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PpeController implements the CRUD actions for Ppe model.
 */
class PpeController extends Controller
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
     * Lists all Ppe models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PpeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ppe model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new PpeDepreciationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Ppe model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ppe();
        $error=0;
        if ($model->load(Yii::$app->request->post())) {

            if(!$model->save()){$error=1;}
            
            $jev = JevTracking::createJev($model->date_acquired,'To record  acquisition of '.$model->particular,'ppe');
            JevEntries::insertAccount($jev,'debit',$model->uacs,$model->quantity*$model->unit_cost);
            JevEntries::insertAccount($jev,'credit',$model->fund_source,$model->quantity*$model->unit_cost);

            if($model->fund_source==10101000||$model->fund_source==10102010){
                $dv = new DvTracking();
                $dv->dv_number = DvTracking::getNewdvnumber();
                $dv->date_posted = $model->date_acquired;
                $dv->amount =$model->quantity*$model->unit_cost;
                $dv->particular = 'To record  acquisition of '.$model->particular;
                $dv->type = 'ppe';
                $dv->debit = $model->uacs;
                $dv->credit = $model->fund_source;
                $dv->jev = $jev;
                if(!$dv->save(false)){$error=1;}
            }elseif($model->fund_source==20101010){
                $apmodel = new AccountsPayableInvoice();
                $apmodel->invoice_number = $_POST['accountspayableinvoice-invoice_date'];
                $apmodel->supplier = $_POST['accountspayableinvoice-supplier'];
                $apmodel->invoice_date = $_POST['accountspayableinvoice-invoice_date'];
                $apmodel->due_date = $_POST['accountspayableinvoice-due_date'];
                $apmodel->po_number = $_POST['accountspayableinvoice-po_number'];
                $apmodel->type_of_expense = $model->uacs;
                $apmodel->amount = $model->quantity*$model->unit_cost;
                $apmodel->jev = $jev;
                if(!$apmodel->save(false)){$error=1;}
            }else{
                $dv = new PcvTracking();
                $dv->pcv_number = PcvTracking::tempDv();
                $dv->date_posted = $model->date_acquired;
                $dv->amount =$model->quantity*$model->unit_cost;
                $dv->particular = 'To record  acquisition of '.$model->particular;
                $dv->type = 'ppe';
                $dv->debit = $model->uacs;
                $dv->credit = $model->fund_source;
                $dv->jev = $jev;
                if(!$dv->save(false)){$error=1;}
            }


            $model->jev = $jev;
            if(!$model->save(false)){$error=1;}

            Yii::$app->getSession()->setFlash('success1', [
                 'type' => ($error==0?'success':'danger'),
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => ($error==0?'Successfully created new Journal Entry':'Ooops! Something went wrong! The JEV# might already been taken.'),
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);
             return $this->redirect(['index']); 
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ppe model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            Yii::$app->getSession()->setFlash('success1', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Record successfully updated!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]); 
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ppe model.
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
     * Finds the Ppe model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ppe the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ppe::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
