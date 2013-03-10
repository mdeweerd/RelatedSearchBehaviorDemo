<?php

/**
 * This is the model class for table "invoiceline".
 *
 * The followings are the available columns in table 'invoiceline':
 * @property integer $InvoiceLineId
 * @property integer $InvoiceId
 * @property integer $TrackId
 * @property string $UnitPrice
 * @property integer $Quantity
 */
class Invoiceline extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invoiceline the static model class
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
		return 'invoiceline';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('InvoiceId, TrackId, UnitPrice, Quantity', 'required'),
			array('InvoiceId, TrackId, Quantity', 'numerical', 'integerOnly'=>true),
			array('UnitPrice', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('InvoiceLineId, InvoiceId, TrackId, UnitPrice, Quantity', 'safe', 'on'=>'search'),
		    // Related field aliases allowed during search.
			array('TrackName,Bytes,Composer,Milliseconds,UnitPrice,AlbumTitle,Artist,MediaType,LastName,FirstName,InvoiceDate', 'safe', 'on'=>'search'),
			array('Genre,BillingCountry,SupportLastName,SupportFirstName,SupportPhone,SupportEmail', 'safe', 'on'=>'search'),
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
	                            'TrackName'=>'track.Name',
	                            'Bytes'=>'track.Bytes',
	                            'Composer'=>'track.Composer',
	                            'Milliseconds'=>'track.Milliseconds',
	                            'UnitPrice'=>'track.UnitPrice',
	                            'AlbumTitle'=>'track.album.Title',
	                            'Artist'=>'track.album.artist.Name',
	                            'MediaType'=>'track.mediatype.Name',
	                            'Genre'=>'track.genre.Name',
	                            'LastName'=>'invoice.customer.LastName',
	                            'FirstName'=>'invoice.customer.FirstName',
	                            'InvoiceDate'=>'invoice.InvoiceDate',
	                            'BillingCountry'=>'invoice.BillingCountry',
	                            'SupportLastName'=>'invoice.customer.support.LastName',
	                            'SupportFirstName'=>'invoice.customer.support.FirstName',
	                            'SupportPhone'=>'invoice.customer.support.Phone',
	                            'SupportEmail'=>'invoice.customer.support.Email',
	                    ),
	            ),
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
		        'track'=>array(self::HAS_ONE,'Track',array('TrackId'=>'TrackId')),
		        'invoice'=>array(self::HAS_ONE,'Invoice',array('InvoiceId'=>'InvoiceId')),
		        // Test for http://www.yiiframework.com/forum/index.php/topic/40699-how-to-refer-main-table-column-in-relational-condition/
		        'siblings'=>array(self::HAS_MANY,'Invoiceline',array('InvoiceId'=>'InvoiceId'),'condition'=>'t.InvoiceLineId!=siblings.InvoiceLineId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'InvoiceLineId' => 'Invoice Line',
			'InvoiceId' => 'Invoice',
			'TrackId' => 'Track',
			'UnitPrice' => 'Unit Price',
			'Quantity' => 'Quantity',
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

	    $alias=$this->getTableAlias(true,false).".";
	    $criteria=new CDbCriteria;

	    $criteria->compare($alias.'InvoiceLineId',$this->InvoiceLineId);
	    $criteria->compare($alias.'InvoiceId',$this->InvoiceId);
	    $criteria->compare($alias.'TrackId',$this->TrackId);
	    $criteria->compare($alias.'UnitPrice',$this->UnitPrice,true);
	    $criteria->compare($alias.'Quantity',$this->Quantity);

	    /** Next line added/changed to used related search behavior */
	    return $this->relatedSearch(
	            $criteria,
	            array(
	                    'pagination'=>array('pageSize'=>10),
	            )
	            //	,array('sort'=>array('defaultOrder'=>$this->getTableAlias(true,false).'.date DESC'))
	    );
	}
}