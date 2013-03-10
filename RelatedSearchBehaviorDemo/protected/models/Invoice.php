<?php

/**
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $InvoiceId
 * @property integer $CustomerId
 * @property string $InvoiceDate
 * @property string $BillingAddress
 * @property string $BillingCity
 * @property string $BillingState
 * @property string $BillingCountry
 * @property string $BillingPostalCode
 * @property string $Total
 */
class Invoice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CustomerId, InvoiceDate, Total', 'required'),
			array('CustomerId', 'numerical', 'integerOnly'=>true),
			array('BillingAddress', 'length', 'max'=>70),
			array('BillingCity, BillingState, BillingCountry', 'length', 'max'=>40),
			array('BillingPostalCode, Total', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('InvoiceId, CustomerId, InvoiceDate, BillingAddress, BillingCity, BillingState, BillingCountry, BillingPostalCode, Total', 'safe', 'on'=>'search'),
		        array('LastName,FirstName', 'safe', 'on'=>'search'),
		        array('SupportLastName,SupportFirstName,SupportPhone,SupportEmail', 'safe', 'on'=>'search'),
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
		        'customer'=>array(self::HAS_ONE,'Customer',array('CustomerId'=>'CustomerId')),
		        'invoicelines'=>array(self::HAS_MANY,'Invoiceline',array('InvoiceId'=>'InvoiceId')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'InvoiceId' => 'Invoice',
			'CustomerId' => 'Customer',
			'InvoiceDate' => 'Invoice Date',
			'BillingAddress' => 'Billing Address',
			'BillingCity' => 'Billing City',
			'BillingState' => 'Billing State',
			'BillingCountry' => 'Billing Country',
			'BillingPostalCode' => 'Billing Postal Code',
			'Total' => 'Total',
		);
	}


	/**
	 * (non-PHPdoc)
	 * @see CModel::behaviors()
	 */
	public function behaviors() {
	    return array(
	            // Add RelatedSearchBehavior
	            'relatedsearch'=>array(
	                    'class'=>'RelatedSearchBehavior',
	                    'relations'=>array(
	                            'LastName'=>'customer.LastName',
	                            'FirstName'=>'customer.FirstName',
	                            'SupportLastName'=>'customer.support.LastName',
	                            'SupportFirstName'=>'customer.support.FirstName',
	                            'SupportPhone'=>'customer.support.Phone',
	                            'SupportEmail'=>'customer.support.Email',
	                    ),
	            ),
	    );
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('InvoiceId',$this->InvoiceId);
		$criteria->compare('CustomerId',$this->CustomerId);
		$criteria->compare('InvoiceDate',$this->InvoiceDate,true);
		$criteria->compare('BillingAddress',$this->BillingAddress,true);
		$criteria->compare('BillingCity',$this->BillingCity,true);
		$criteria->compare('BillingState',$this->BillingState,true);
		$criteria->compare('BillingCountry',$this->BillingCountry,true);
		$criteria->compare('BillingPostalCode',$this->BillingPostalCode,true);
		$criteria->compare('Total',$this->Total,true);

		return $this->relatedsearch(
		        $criteria,
		        array()
		);
	}
}