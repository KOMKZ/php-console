<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 *
 */
class RunController extends Controller{
    public function actionIndex($name){
        system(Yii::$app->params['cmd_alias'][$name]);
    }
    public function actionList(){
        foreach(Yii::$app->params['cmd_alias'] as $name => $item){
            echo $name . "\n";
        }
    }
}
