<?php

namespace frontend\controllers;

use Yii;
use app\models\Purchases;
use app\models\PurchasesSearch;
use app\models\PurchaseDetails;
use app\models\JevTracking;
use app\models\JevEntries;
use app\models\DvTracking;
use app\models\DvDebits;
use app\models\AccountsPayableInvoice;
use app\models\ApInvoiceDetails;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\base\Model;
use yii\web\Response;
use yii\db\Expression;

/**
 * PurchasesController implements the CRUD actions for Purchases model.
 */
class PurchasesController extends Controller
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
     * Lists all Purchases models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchasesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchases model.
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
     * Creates a new Purchases model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /*$model = new Purchases();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);*/
        $modelPurchases = new Purchases;
        $modelDetails = [new PurchaseDetails];
        if ($modelPurchases->load(Yii::$app->request->post())) {

            $modelDetails = Model::createMultiple(PurchaseDetails::classname());
            Model::loadMultiple($modelDetails, Yii::$app->request->post());

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelDetails),
                    ActiveForm::validate($modelPurchases)
                );
            }

            // validate all models
            $valid = $modelPurchases->validate();
            $valid = Model::validateMultiple($modelDetails) && $valid;
            
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelPurchases->save(false)) {

                        foreach ($modelDetails as $modelAddress) {
                            $modelAddress->purchase_id = $modelPurchases->id;
                            if (! ($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        $categories = PurchaseDetails::find()
                        ->joinWith('product.category0')
                        ->where(['purchase_id'=>$modelPurchases->id])
                        ->groupBy('purchaseAccount')
                        ->all();

                        $jev = JevTracking::createJev($modelPurchases->date_posted,'To record purchases','Purchases');

                        if($modelPurchases->amount_paid>0){
                            $dvmodel = new DvTracking();
                            $dv = DvTracking::getNewdvnumber();

                            $dvmodel->dv_number = $dv;
                            $dvmodel->date_posted = $modelPurchases->date_posted;
                            $dvmodel->amount = $modelPurchases->amount_paid;
                            $dvmodel->payee = $modelPurchases->payee;
                            $dvmodel->particular = 'Payment of purchases';
                            $dvmodel->type ='Purchases Payment';
                            $dvmodel->credit = 10101000;
                            $dvmodel->jev = $jev;
                            $dvmodel->save();

                            JevEntries::insertAccount($jev,'credit',10101000,$dvmodel->amount);

                            $modelPurchases->dv_number = $dvmodel->dv_number;

                        }

                        if($modelPurchases->accounts_payable>0){
                            JevEntries::insertAccount($jev,'credit',20101010,$modelPurchases->accounts_payable);
                            $apmodel = new AccountsPayableInvoice();
                            $apmodel->supplier = $modelPurchases->payee;
                            $apmodel->invoice_date = $modelPurchases->date_posted;
                            $apmodel->amount = $modelPurchases->accounts_payable;
                            $apmodel->jev = $jev;
                            $apmodel->save(false);

                            $modelPurchases->ap_invoice = $apmodel->id;
                        }

                        foreach ($categories as $category) {
                            $sum = PurchaseDetails::find()
                                ->joinWith('product.category0')
                                ->where(['purchase_id'=>$modelPurchases->id,'purchaseAccount'=>$category->product->category0->purchaseAccount])
                                ->sum('total');
                            if($modelPurchases->amount_paid>0){
                                $catmodel = new DvDebits();
                                $catmodel->dv_id = $dvmodel->id;
                                $catmodel->account_code = $category->product->category0->purchaseAccount;
                                $catmodel->amount = $sum;
                                $catmodel->save(false);
                            }
                            if($modelPurchases->accounts_payable>0){
                                $apdetailsmodel = new ApInvoiceDetails();
                                $apdetailsmodel->ap_id = $apmodel->id;
                                $apdetailsmodel->account_code = $category->product->category0->purchaseAccount;
                                $apdetailsmodel->amount = $sum;
                                $apdetailsmodel->save(false);
                            }
                                JevEntries::insertAccount($jev,'debit',$category->product->category0->purchaseAccount,$sum);
                        }

                        $modelPurchases->jev = $jev;
                        $modelPurchases->save();

                    }


                    if ($flag) {
                        $transaction->commit();

                        //Creating DV & JEV


                        Yii::$app->getSession()->setFlash('success1', [
                             'type' => 'success',
                             'duration' => 5000,
                             'icon' => 'fa fa-user',
                             'message' => 'Transaction recorded successfully!',
                             'positonY' => 'top',
                             'positonX' => 'right'
                         ]);


                        return $this->redirect(['view', 'id' => $modelPurchases->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }else{
                return var_dump($valid->errors);
            }
        }

        return $this->render('create', [
            'modelPurchases' => $modelPurchases,
            'modelDetails' => (empty($modelDetails)) ? [new PurchaseDetails] : $modelDetails
        ]);
    }

    /**
     * Updates an existing Purchases model.
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
     * Deletes an existing Purchases model.
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
     * Finds the Purchases model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchases the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchases::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
