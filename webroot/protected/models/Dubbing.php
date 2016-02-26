<?php

/**
 * This is the model class for table "dubbing".
 *
 * The followings are the available columns in table 'dubbing':
 * @property integer $id_dubbing
 * @property integer $id_category
 * @property string $tag_produser
 * @property string $date_dubbing
 * @property string $money_art
 * @property string $money_direct
 * @property string $time_direct
 * @property string $time_direct_maison
 *
 * The followings are the available model relations:
 * @property Category $idCategory
 */
class Dubbing extends CActiveRecord
{
   const CONTROLLER_NAME = 'dubbing';
   const NAME_GRID_VIEW = 'dubbing-grid';

   const GROUP_CATEGORY_DATA = 'id_category, date_dubbing';
   const GROUP_CATEGORY_DATA_WITH_TOTALS = 'id_category, date_dubbing WITH ROLLUP';
   const GROUP_DATE_CATEGORY = 'date_dubbing, id_category';
   const GROUP_DATE_CATEGORY_WITH_TOTALS = 'date_dubbing, id_category WITH ROLLUP';
   const SELECT_FOR_REPORT = 'id_dubbing, id_category, date_dubbing, SUM(money_art) AS money_art, SUM(money_direct) AS money_direct, SUM(time_direct) AS time_direct, SUM(time_direct_maison) AS time_direct_maison';

   const BOTTOM_NAME_ADD_ACCOUNT = 'add_account';
   const BOTTOM_NAME_TRANSACTION = 'transaction';

   public function columnForAdmin()
   {
      $columns = array(
         'id' => array(
            'name' => 'id_dubbing',
         ),
         'id_category' => array(
            'name' => 'id_category',
            'header' => 'Catégorie',
            'value' => 'empty($data->id_category) ? \'TOTAL\' : $data->idCategory->name_category',
            'filter' => Category::listForDropDown(),
         ),
         'date_dubbing' => array(
            'name' => 'date_dubbing',
            'header' => 'Périod mensuelle',
            'value' => 'empty($data->date_dubbing) ? \'TOTAL\' : $data->date_dubbing',
         ),
         'money_art' => array(
            'name' => 'money_art',
            'header' => 'Cachets (artistes)',
         ),
         'money_direct' => array(
            'name' => 'money_direct',
            'header' => 'Cachets (dir. UDA)',
         ),
         'time_direct' =>array(
            'name' => 'time_direct',
            'header' => 'Hrs (dir. UDA)',
         ),
         'time_direct_maison' => array(
            'name' => 'time_direct_maison',
            'header' => 'Hrs (dir. maison)',
         ),
      );
      $buttons= array(
         'class' => 'CButtonColumn',
         'header' => 'Action',
         'headerHtmlOptions'=>array('width'=>'100px'),
         'updateButtonOptions'=>array('target'=>'blank'),
         'buttons' => array(
            'tax' => array(
               'label' => 'Tax recount',
               'url' => 'Yii::app()->controller->createUrl("/amount/tax",array("id"=>$data->amount_id))',
               'options'=>array('class'=>'bank_account'),
               'imageUrl' => '/images/currency_dollar.gif',
               'visible'=>'$data->amount_type_id==AmountType::GetIdByUid(AmountType::AMOUNT_TYPE_INVOICE_PRINTED)'
            ),
            'invoice' => array(
               'label' => 'Print Invoice',
               'url' => 'Yii::app()->controller->createUrl("/amount/invoice",array("id"=>$data->amount_id))',
               'options'=>array('target'=>'_blank'),
               'imageUrl' => '/images/printer.gif',
               'visible' => '$data->amount_type_id==AmountType::GetIdByUid("invoice_p")',
            ),
         ),
         'template' => '{view}{update}',
      );

      if(Yii::app()->user->name === 'admin')
      {
         $buttons['template'] = '{view}{update} {delete}';
      }
      if (Yii::app()->getController()->getId() !== self::CONTROLLER_NAME)
      {
         $buttons = $buttons + array(
               'deleteButtonUrl' => 'Yii::app()->controller->createUrl("/' . self::CONTROLLER_NAME . '/delete",array("id"=>$data->id_dubbing))',
               'viewButtonUrl' => 'Yii::app()->controller->createUrl("/' . self::CONTROLLER_NAME . '/view",array("id"=>$data->id_dubbing))',
               'updateButtonUrl' => 'Yii::app()->controller->createUrl("/' . self::CONTROLLER_NAME .  '/update",array("id"=>$data->id_dubbing))'
         );
      }

      $columns['buttons'] = $buttons;
      return $columns;
   }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dubbing';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
         array('id_category, tag_produser, date_dubbing', 'required'),
         array('id_category', 'numerical', 'integerOnly'=>true),
			array('money_art, money_direct, time_direct, time_direct_maison', 'length', 'max'=>12),
            array('tag_produser', 'length', 'max'=>10),
			array('date_dubbing', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_dubbing, id_category, tag_produser, date_dubbing, money_art, money_direct, time_direct, time_direct_maison', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idCategory' => array(self::BELONGS_TO, 'Category', 'id_category'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_dubbing' => 'Id Dubbing',
			'id_category' => 'Catégorie',
            'tag_produser' => 'Numéro UDA de producteur',
			'date_dubbing' => 'Period du: (aaaa-mm-jj)',
			'money_art' => 'Cachets (artistes)',
			'money_direct' => 'Cachets (direcreurs de plateau, membres UDA)',
			'time_direct' => 'Heures travailleés (direcreurs de plateau, membres UDA)',
			'time_direct_maison' => 'Heures travailleés (direcreurs de plateau, maison)',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		return new CActiveDataProvider($this, array(
			'criteria'=>$this->getSearchCriteria(),
		));
	}

   public function getSearchCriteria()
   {
      // @todo Please modify the following code to remove attributes that should not be searched.

      $criteria=new CDbCriteria;

      $criteria->compare('id_dubbing',$this->id_dubbing);
      $criteria->compare('id_category',$this->id_category);
      $criteria->compare('date_dubbing',$this->date_dubbing,true);
      $criteria->compare('money_art',$this->money_art,true);
      $criteria->compare('money_direct',$this->money_direct,true);
      $criteria->compare('time_direct',$this->time_direct,true);
      $criteria->compare('time_direct_maison',$this->time_direct_maison,true);
      return $criteria;
   }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dubbing the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
