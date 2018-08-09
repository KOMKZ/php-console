<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;


class DictController extends Controller{
    public function actionIndex(){
        $p = Yii::$app->request->getParams();
        array_shift($p);
        $v = implode(" ", $p);
        file_put_contents(
            Yii::getAlias("@common/data/newword.text"),
            $v . "\n",
            FILE_APPEND
        );
    }
}
