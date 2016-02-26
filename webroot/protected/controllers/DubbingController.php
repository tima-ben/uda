<?php

class DubbingController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = self::LAYOUTS_COLUMN_2;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	public function init()
	{
		if(isset($_REQUEST['page']))
		{
			switch ($_REQUEST['page'])
			{
				case Controller::PAGE_ALL:
					Yii::app()->user->setState(Controller::PAGE_STATE,Controller::PAGE_ALL);
					break;
				case Controller::PAGE_20:
					Yii::app()->user->setState(Controller::PAGE_STATE,Controller::PAGE_20);
					break;
				case Controller::PAGE_25:
					Yii::app()->user->setState(Controller::PAGE_STATE,Controller::PAGE_25);
					break;
				case Controller::PAGE_30:
					Yii::app()->user->setState(Controller::PAGE_STATE,Controller::PAGE_30);
					break;
				default:
					Yii::app()->user->setState(Controller::PAGE_STATE,Controller::PAGE_DEFAULT);
					break;
			}
		}
		parent::init();

	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin','report'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Dubbing;
		$this->titleForAction = 'Saisie de données';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Dubbing']))
		{
			$model->attributes = $_POST['Dubbing'];
			if($model->save())
         {
            if(empty($_REQUEST['next']))
            {
               $this->redirect(array('view', 'id' => $model->id_dubbing));
            }
            else
            {
               $next = new Dubbing();
			   $next->tag_produser = $model->tag_produser;
               $next->id_category = $model->id_category;
               $next->date_dubbing = $model->date_dubbing;
               $model = $next;
            }
         }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->titleForAction = 'Modification des données';
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Dubbing']))
		{
			$model->attributes=$_POST['Dubbing'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id_dubbing));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Dubbing');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
      $this->titleForAction = 'Manage source data';
		$model=new Dubbing('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Dubbing']))
			$model->attributes=$_GET['Dubbing'];

		$this->render('admin',array(
			'model' => $model,
         'dataProvider' => $model->search(),
         'columns' => $model->columnForAdmin(),
		));
	}

   /**
    * Report all models.
    */
   public function actionReport()
   {
      $model = new Dubbing('search');
      $model->unsetAttributes();  // clear any default values
      if(isset($_GET['Dubbing']))
         $model->attributes=$_GET['Dubbing'];

      $columns = $model->columnForAdmin();
      $columns['id']['visible']=false;
      $columns['date_dubbing']['value'] = 'empty($data->date_dubbing) ? \'TOTAL\' : (date(\'Y-M\',strtotime($data->date_dubbing)))';
      $criteria = $model->getSearchCriteria();
      $criteria->select = $model::SELECT_FOR_REPORT;
      if(empty($_REQUEST['type']))
      {
         $this->titleForAction = 'Rapport (date, catégorie)';
         $criteria->group = $model::GROUP_DATE_CATEGORY_WITH_TOTALS;
         $tmp = $columns['date_dubbing'];
         $columns['date_dubbing'] = $columns['id_category'];
         $columns['id_category'] = $tmp;
      }
      else
      {
         $this->titleForAction = 'Rapport (catégorie, date)';
         $criteria->group = $model::GROUP_CATEGORY_DATA_WITH_TOTALS;
      }
	  if(Yii::app()->user->getState(Controller::PAGE_STATE,Controller::PAGE_DEFAULT) == Controller::PAGE_ALL )
	  {
		  $pagination = false;
	  }
	   else
	   {
		   $pagination = array('pageSize' => Yii::app()->user->getState(Controller::PAGE_STATE,Controller::PAGE_DEFAULT));
	   }
      unset($columns['buttons']);
      $this->render('admin',array(
         'model' => $model,
         'dataProvider' => new CActiveDataProvider(get_class($model),array('criteria'=>$criteria,'pagination' => $pagination)),
         'columns' => $columns,
      ));
   }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Dubbing the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Dubbing::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Dubbing $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='dubbing-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
