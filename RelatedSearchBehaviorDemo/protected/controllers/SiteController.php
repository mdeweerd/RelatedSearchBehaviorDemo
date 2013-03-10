<?php

Yii::import('application.extensions.ireport.*');

class SiteController extends Controller {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionInvoices() {
        $this->render('invoices');
    }

    public function actionTestForum40699() {
        $invoiceLine = Invoiceline::model()->findByPk(1);
        CVarDumper::dump($invoiceLine->siblings,10,true);
    }

}