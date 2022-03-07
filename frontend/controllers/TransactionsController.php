<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use app\models\JevTracking;
use app\models\JevEntries;

/**
 * CapitalsController implements the CRUD actions for Capitals model.
 */
class TransactionsController extends Controller
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

    public function actionBankDeposit(){
        
        if (Yii::$app->request->post()) {

            $jev = JevTracking::createJev($_POST['date_posted'],'Bank Deposit - Ref: '.$_POST['reference'],'others');
            JevEntries::insertAccount($jev,'debit',10102010,$_POST['amount']);
            JevEntries::insertAccount($jev,'credit',10101000,$_POST['amount']);



            /*$lastjev = JevTracking::find()->orderBy('id DESC')->one();
            if($lastjev){
                $pieces = explode("-",$lastjev->jev);
                $lastjev = (int)$pieces[2]+1;
            }else{
                $lastjev = 1;
            }
            
            $jevtracking = new JevTracking();
            $jevtracking->jev = date('Y-m-',strtotime($_POST['date_posted'])).$lastjev;
            $jevtracking->date_posted = $_POST['date_posted'];
            $jevtracking->remarks = 'Bank Deposit - Ref: '.$_POST['reference'];
            $jevtracking->source = 'others';
            $jevtracking->save();*/

            /*$entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'debit';
            $entry->accounting_code = 10102010;
            $entry->amount= $_POST['amount'];
            $entry->save();

            $entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'credit';
            $entry->accounting_code = 10101000;
            $entry->amount= $_POST['amount'];
            $entry->save();
*/
            Yii::$app->getSession()->setFlash('success1', [
                     'type' => 'success',
                     'duration' => 5000,
                     'icon' => 'fa fa-user',
                     'message' => 'Successfully added new transaction!',
                     'positonY' => 'top',
                     'positonX' => 'right'
                 ]); 

            return $this->redirect(['transactions/bank-deposit','title'=>'Bank Deposit']);
        }

        return $this->render('deposit',['title'=>'Bank Deposit']);
    }

    public function actionBankWithdrawal(){
        
        if (Yii::$app->request->post()) {

            $jev = JevTracking::createJev($_POST['date_posted'],'Bank Withdrawal - Ref: '.$_POST['reference'],'others');
            JevEntries::insertAccount($jev,'debit',10101000,$_POST['amount']);
            JevEntries::insertAccount($jev,'credit',10102010,$_POST['amount']);

            /*$lastjev = JevTracking::find()->orderBy('id DESC')->one();
            if($lastjev){
                $pieces = explode("-",$lastjev->jev);
                $lastjev = (int)$pieces[2]+1;
            }else{
                $lastjev = 1;
            }
            
            $jevtracking = new JevTracking();
            $jevtracking->jev = date('Y-m-',strtotime($_POST['date_posted'])).$lastjev;
            $jevtracking->date_posted = $_POST['date_posted'];
            $jevtracking->remarks = 'Bank Withdrawal - Ref: '.$_POST['reference'];
            $jevtracking->source = 'others';
            $jevtracking->save();*/

            /*$entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'credit';
            $entry->accounting_code = 10102010;
            $entry->amount= $_POST['amount'];
            $entry->save();

            $entry = new JevEntries();
            $entry->jev = $jevtracking->jev;
            $entry->type= 'debit';
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

            return $this->redirect(['transactions/bank-withdrawal','title'=>'Bank Withdrawal']);
        }

        return $this->render('deposit',['title'=>'Bank Withdrawal']);
    }

}
