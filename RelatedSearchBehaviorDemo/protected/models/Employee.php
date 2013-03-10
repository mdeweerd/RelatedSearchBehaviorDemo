<?php

/**
 * This is the model class for table "employee".
 *
 * The followings are the available columns in table 'employee':
 * @property integer $EmployeeId
 * @property string $LastName
 * @property string $FirstName
 * @property string $Title
 * @property integer $ReportsTo
 * @property string $BirthDate
 * @property string $HireDate
 * @property string $Address
 * @property string $City
 * @property string $State
 * @property string $Country
 * @property string $PostalCode
 * @property string $Phone
 * @property string $Fax
 * @property string $Email
 */
class Employee extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Employee the static model class
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
		return 'employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('LastName, FirstName', 'required'),
			array('ReportsTo', 'numerical', 'integerOnly'=>true),
			array('LastName, FirstName', 'length', 'max'=>20),
			array('Title', 'length', 'max'=>30),
			array('Address', 'length', 'max'=>70),
			array('City, State, Country', 'length', 'max'=>40),
			array('PostalCode', 'length', 'max'=>10),
			array('Phone, Fax', 'length', 'max'=>24),
			array('Email', 'length', 'max'=>60),
			array('BirthDate, HireDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('EmployeeId, LastName, FirstName, Title, ReportsTo, BirthDate, HireDate, Address, City, State, Country, PostalCode, Phone, Fax, Email', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'EmployeeId' => 'Employee',
			'LastName' => 'Last Name',
			'FirstName' => 'First Name',
			'Title' => 'Title',
			'ReportsTo' => 'Reports To',
			'BirthDate' => 'Birth Date',
			'HireDate' => 'Hire Date',
			'Address' => 'Address',
			'City' => 'City',
			'State' => 'State',
			'Country' => 'Country',
			'PostalCode' => 'Postal Code',
			'Phone' => 'Phone',
			'Fax' => 'Fax',
			'Email' => 'Email',
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

		$criteria->compare('EmployeeId',$this->EmployeeId);
		$criteria->compare('LastName',$this->LastName,true);
		$criteria->compare('FirstName',$this->FirstName,true);
		$criteria->compare('Title',$this->Title,true);
		$criteria->compare('ReportsTo',$this->ReportsTo);
		$criteria->compare('BirthDate',$this->BirthDate,true);
		$criteria->compare('HireDate',$this->HireDate,true);
		$criteria->compare('Address',$this->Address,true);
		$criteria->compare('City',$this->City,true);
		$criteria->compare('State',$this->State,true);
		$criteria->compare('Country',$this->Country,true);
		$criteria->compare('PostalCode',$this->PostalCode,true);
		$criteria->compare('Phone',$this->Phone,true);
		$criteria->compare('Fax',$this->Fax,true);
		$criteria->compare('Email',$this->Email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}