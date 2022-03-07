<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use app\models\JevEntries;
use app\models\Customers;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else{
            $totalcashreceipt = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10101000,'type'=>'debit'])->sum('amount');
            $totalexpense = JevEntries::find()->joinWith('jev0')->joinWith('accountingCode')->where(['classification'=>'Expenses','type'=>'debit','month(jev_tracking.date_posted)'=>date('m'),'year(jev_tracking.date_posted)'=>date('Y')])->sum('amount');
            $totalcashdisbursement = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10101000,'type'=>'credit'])->sum('amount');
            $pettycashdebit = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10101020,'type'=>'debit'])->sum('amount');
            $pettycashcredit = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10101020,'type'=>'credit'])->sum('amount');
            $totalsales = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>40202160,'type'=>'credit','month(jev_tracking.date_posted)'=>date('m'),'year(jev_tracking.date_posted)'=>date('Y')])->sum('amount');
            $totalreceivable = JevEntries::find()->where(['accounting_code'=>10301010,'type'=>'debit'])->sum('amount')-JevEntries::find()->where(['accounting_code'=>10301010,'type'=>'credit'])->sum('amount');
            $totalpayable = JevEntries::find()->where(['accounting_code'=>20101010,'type'=>'credit'])->sum('amount')-JevEntries::find()->where(['accounting_code'=>20101010,'type'=>'debit'])->sum('amount');
            return $this->render('index',[
                'cashonhandpercentage'=>($totalcashreceipt>0?((($totalcashreceipt-$totalcashdisbursement)/$totalcashreceipt)*100):0),
                'pettycashpercentage'=>($pettycashdebit>0?((($pettycashdebit-$pettycashcredit)/$pettycashdebit)*100):0),
                'totalsales'=>$totalsales,
                'totalreceivable'=>$totalreceivable,
                'totalpayable'=>$totalpayable,
                'totalexpense'=>$totalexpense,
            ]);
        }
        

    }


    public function actionResetpassword1()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        $model = User::find()->where(['employee_id'=>Yii::$app->user->identity->employee_id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->save();
            Yii::$app->getSession()->setFlash('success4', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'Successfully saved new password!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  
            return $this->redirect(['index']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionTaccounts()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        return $this->render('taccounts');

    }

    public function actionFinancialStatement()
    {
        return $this->render('financialstatement');

    }

    public function actionIncomestatement()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        return $this->render('income_statement');

    }

    public function actionIncomestatementplain()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        return $this->renderAjax('income_statement_plain');

    }

    public function actionCre()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        return $this->render('cre');

    }

    public function actionCreplain()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        return $this->renderAjax('creplain');

    }

    public function actionBalanceSheet()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        return $this->render('balancesheet');

    }

    public function actionBalanceSheetPlain()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        return $this->renderPartial('balancesheetplain');

    }

    public function actionReceivables()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        return $this->render('receivables');

    }

    public function actionPayables()
    {
        if (Yii::$app->user->isGuest)
        return $this->redirect(['site/login']);
        else
        return $this->render('payables');

    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['site/index']);
        } else {
            $model->password = '';
            $this->layout = 'login';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
	
	public function actionReset($id){
        if($id=='houstonrockets'){
           Yii::$app->db->createCommand()->checkIntegrity(false)->execute();
           Yii::$app->db->createCommand()->truncateTable('accounts_payable_invoice')->execute();
           Yii::$app->db->createCommand()->truncateTable('accounts_payable_payment')->execute();
           Yii::$app->db->createCommand()->truncateTable('capitals')->execute();
           Yii::$app->db->createCommand()->truncateTable('cash_dec')->execute();
           Yii::$app->db->createCommand()->truncateTable('cash_dec_det')->execute();
           Yii::$app->db->createCommand()->truncateTable('customers')->execute();
           Yii::$app->db->createCommand()->truncateTable('dv_tracking')->execute();
           Yii::$app->db->createCommand()->truncateTable('employees')->execute();
           Yii::$app->db->createCommand()->truncateTable('inventory_loss')->execute();
           Yii::$app->db->createCommand()->truncateTable('jev_entries')->execute();
           Yii::$app->db->createCommand()->truncateTable('jev_tracking')->execute();
           Yii::$app->db->createCommand()->truncateTable('log')->execute();
           Yii::$app->db->createCommand()->truncateTable('members')->execute();
           Yii::$app->db->createCommand()->truncateTable('officers')->execute();
           Yii::$app->db->createCommand()->truncateTable('officers_positions')->execute();
           Yii::$app->db->createCommand()->truncateTable('or_ar_tracking')->execute();
           Yii::$app->db->createCommand()->truncateTable('payments_receivable')->execute();
           Yii::$app->db->createCommand()->truncateTable('payment_voucher')->execute();
           Yii::$app->db->createCommand()->truncateTable('payroll')->execute();
           Yii::$app->db->createCommand()->truncateTable('payroll_deductions')->execute();
           Yii::$app->db->createCommand()->truncateTable('payroll_deductions_honorarium')->execute();
           Yii::$app->db->createCommand()->truncateTable('payroll_dividends')->execute();
           Yii::$app->db->createCommand()->truncateTable('payroll_honorarium')->execute();
           Yii::$app->db->createCommand()->truncateTable('pcv_tracking')->execute();
           Yii::$app->db->createCommand()->truncateTable('petty_cash')->execute();
           Yii::$app->db->createCommand()->truncateTable('ppe')->execute();
           Yii::$app->db->createCommand()->truncateTable('ppe_depreciation')->execute();
           Yii::$app->db->createCommand()->truncateTable('pricelist')->execute();
           Yii::$app->db->createCommand()->truncateTable('products')->execute();
           Yii::$app->db->createCommand()->truncateTable('sales')->execute();
           Yii::$app->db->createCommand()->truncateTable('sales_details')->execute();
           Yii::$app->db->createCommand()->truncateTable('stock_card')->execute(); 
           Yii::$app->db->createCommand()->truncateTable('suppliers')->execute(); 
           $juan = new Customers();
           $juan->firstname = 'Various'; 
           $juan->lastname = 'Customer'; 
           $juan->save();
           Yii::$app->getSession()->setFlash('success4', [
                 'type' => 'success',
                 'duration' => 5000,
                 'icon' => 'fa fa-user',
                 'message' => 'All data has been deleted!',
                 'positonY' => 'top',
                 'positonX' => 'right'
             ]);  
            return $this->redirect(['index']);
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
