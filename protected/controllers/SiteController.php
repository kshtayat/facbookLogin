<?php

class SiteController extends Controller
{
	public function filters()
	{
		return array( 'accessControl' ); // perform access control for CRUD operations
	}

	public function accessRules()
	{
		return array(
			array('deny', // deny logged in users to access
					'users'=>array('@'),
					'actions' => array('login'),
					'deniedCallback'=>array($this,'redirectToHomePage'),
			),
			array('allow',
				'users'=>array('*'),
				'actions'=> array('index', 'login', 'location','logout')
			),
				array('allow',
						'users'=>array('@'),
						'actions'=> array( 'location')
			),
			array('deny'),
			);
	}

	public static function redirectToHomePage()
	{
		Yii::app()->request->redirect(Yii::app()->homeUrl);
	}
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$FbAppId = Yii::app()->params['FbAppId'];
		$fbUserId = Yii::app()->request->getPost('fbId');
		if (!empty($fbUserId))
		{
			$hometown = Yii::app()->request->getPost('hometown');
			$email    = Yii::app()->request->getPost('email');
			$firstName =Yii::app()->request->getPost('firstName');
			$lastName =Yii::app()->request->getPost('lastName');
		
			$userLogin= UserLogin::model()->findByAttributes(array('fb_user_id'=>$fbUserId));
			if (empty($userLogin))
				$userLogin = new UserLogin();

			$userLogin->fb_user_id    = $fbUserId;
			$userLogin->first_name    = $firstName;
			$userLogin->user_email    = $email;
			$userLogin->user_hometown = $hometown;
			$userLogin->last_name    = $lastName;
			$userLogin->login_time   = new CDbExpression('now()');
			// log login action


			if ($userLogin->save())
			{
				// Create Identity for validation 
				$model = new LoginForm();
				$model->username = $userLogin->user_email;
				$model->password = $userLogin->password;
				$model->rememberMe=true;
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login())
					$this->redirect($this->createUrl('location'));

			}
		}
		$this->render('login',array(
			'FbAppId'=>$FbAppId,
		));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionLocation()
	{

		$userLogin= UserLogin::model()->findByPk(Yii::app()->user->getId());
		## For Enhansment we can pass this value in sessien
		$userHometown='';
		$firstName='';
		$lastName='';
		if ( !empty($userLogin ))
		{
			$userHometown = $userLogin->user_hometown;
			$firstName=$userLogin->first_name;
			$lastName=$userLogin->last_name;
		}
		$this->render('location', array('hometown' => $userHometown,'firstName'=>$firstName,'lastName'=>$lastName));
	}
}