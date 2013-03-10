<?php
$this->pageTitle = Yii::app()->name;

echo CHtml::tag('p',array(),'The Grid View below lists all invoice lines of the database. All fields are searcheable, and can be sorted.');
echo CHtml::tag('p',array(),'The implementation is very easy with '
        .CHtml::link('RelatedSearchBehavior','http://www.yiiframework.com/extension/relatedsearchbehavior/')
        .'.  Check out the \'Invoice\' model to see how it is done.');
echo CHtml::tag('p',array(),'The database is open source from '.CHtml::link('http://chinookdatabase.codeplex.com','http://chinookdatabase.codeplex.com')." The schema is shown further below.    ");

$model=new Invoice('search');
if(isset($_GET['Invoice'])) {
    $model->attributes=$_GET['Invoice'];
}

$dataProvider=$model->search();
if($dataProvider instanceof KeenActiveDataProvider) {
    /* @var $dataProvider KeenActiveDataProvider */
    $dataProvider->withKeenLoading=array(
            'customer',
            'customer.support',
            'invoicelines.track.genre',
    );
}

$dataProvider->pagination=array('pageSize'=>4,);

$this->widget('zii.widgets.grid.CGridView',array(
        'dataProvider'=>$dataProvider,
        'filter'=>$model,
        'columns'=>array(
                'InvoiceId',
                'LastName',
                'FirstName',
                //'InvoiceDate',
                //'BillingCountry',
                'SupportLastName',
                'SupportFirstName',
                'SupportPhone',
                'SupportEmail',
        )
));

echo CHtml::image('images/ChinookDatabaseSchema1.1.png');



