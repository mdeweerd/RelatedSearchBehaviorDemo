<?php

/**
 * This is the model class for table "track".
 *
 * The followings are the available columns in table 'track':
 * @property integer $TrackId
 * @property string $Name
 * @property integer $AlbumId
 * @property integer $MediaTypeId
 * @property integer $GenreId
 * @property string $Composer
 * @property integer $Milliseconds
 * @property integer $Bytes
 * @property string $UnitPrice
 */
class Track extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Track the static model class
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
		return 'track';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Name, MediaTypeId, Milliseconds, UnitPrice', 'required'),
			array('AlbumId, MediaTypeId, GenreId, Milliseconds, Bytes', 'numerical', 'integerOnly'=>true),
			array('Name', 'length', 'max'=>200),
			array('Composer', 'length', 'max'=>220),
			array('UnitPrice', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('TrackId, Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice', 'safe', 'on'=>'search'),
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
		        'album'=>array(self::HAS_ONE,'Album',array('AlbumId'=>'AlbumId')),
		        'genre'=>array(self::HAS_ONE,'Genre',array('GenreId'=>'GenreId')),
		        'mediatype'=>array(self::HAS_ONE,'Mediatype',array('MediaTypeId'=>'MediaTypeId')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'TrackId' => 'Track',
			'Name' => 'Name',
			'AlbumId' => 'Album',
			'MediaTypeId' => 'Media Type',
			'GenreId' => 'Genre',
			'Composer' => 'Composer',
			'Milliseconds' => 'Milliseconds',
			'Bytes' => 'Bytes',
			'UnitPrice' => 'Unit Price',
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

		$criteria->compare('TrackId',$this->TrackId);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('AlbumId',$this->AlbumId);
		$criteria->compare('MediaTypeId',$this->MediaTypeId);
		$criteria->compare('GenreId',$this->GenreId);
		$criteria->compare('Composer',$this->Composer,true);
		$criteria->compare('Milliseconds',$this->Milliseconds);
		$criteria->compare('Bytes',$this->Bytes);
		$criteria->compare('UnitPrice',$this->UnitPrice,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}