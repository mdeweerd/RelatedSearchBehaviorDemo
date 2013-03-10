<?php

/**
 * This is the model class for table "album".
 *
 * The followings are the available columns in table 'album':
 * @property integer $AlbumId
 * @property string $Title
 * @property integer $ArtistId
 */
class Album extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Album the static model class
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
		return 'album';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Title, ArtistId', 'required'),
			array('ArtistId', 'numerical', 'integerOnly'=>true),
			array('Title', 'length', 'max'=>160),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('AlbumId, Title, ArtistId', 'safe', 'on'=>'search'),
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
		    'artist'=>array(self::HAS_ONE,'Artist',array('ArtistId'=>'ArtistId')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'AlbumId' => 'Album',
			'Title' => 'Title',
			'ArtistId' => 'Artist',
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

		$criteria->compare('AlbumId',$this->AlbumId);
		$criteria->compare('Title',$this->Title,true);
		$criteria->compare('ArtistId',$this->ArtistId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}