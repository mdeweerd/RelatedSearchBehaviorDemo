<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $CustomerId
 * @property string $FirstName
 * @property string $LastName
 * @property string $Company
 * @property string $Address
 * @property string $City
 * @property string $State
 * @property string $Country
 * @property string $PostalCode
 * @property string $Phone
 * @property string $Fax
 * @property string $Email
 * @property integer $SupportRepId
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('FirstName, LastName, Email', 'required'),
			array('SupportRepId', 'numerical', 'integerOnly'=>true),
			array('FirstName, City, State, Country', 'length', 'max'=>40),
			array('LastName', 'length', 'max'=>20),
			array('Company', 'length', 'max'=>80),
			array('Address', 'length', 'max'=>70),
			array('PostalCode', 'length', 'max'=>10),
			array('Phone, Fax', 'length', 'max'=>24),
			array('Email', 'length', 'max'=>60),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('CustomerId, FirstName, LastName, Company, Address, City, State, Country, PostalCode, Phone, Fax, Email, SupportRepId', 'safe', 'on'=>'search'),
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
		        'support'=>array(self::HAS_ONE,'Employee',array('EmployeeId'=>'SupportRepId')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'CustomerId' => 'Customer',
			'FirstName' => 'First Name',
			'LastName' => 'Last Name',
			'Company' => 'Company',
			'Address' => 'Address',
			'City' => 'City',
			'State' => 'State',
			'Country' => 'Country',
			'PostalCode' => 'Postal Code',
			'Phone' => 'Phone',
			'Fax' => 'Fax',
			'Email' => 'Email',
			'SupportRepId' => 'Support Rep',
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

		$criteria->compare('CustomerId',$this->CustomerId);
		$criteria->compare('FirstName',$this->FirstName,true);
		$criteria->compare('LastName',$this->LastName,true);
		$criteria->compare('Company',$this->Company,true);
		$criteria->compare('Address',$this->Address,true);
		$criteria->compare('City',$this->City,true);
		$criteria->compare('State',$this->State,true);
		$criteria->compare('Country',$this->Country,true);
		$criteria->compare('PostalCode',$this->PostalCode,true);
		$criteria->compare('Phone',$this->Phone,true);
		$criteria->compare('Fax',$this->Fax,true);
		$criteria->compare('Email',$this->Email,true);
		$criteria->compare('SupportRepId',$this->SupportRepId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}