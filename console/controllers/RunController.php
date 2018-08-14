<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 *
 */
class RunController extends Controller{
    public function actionIndex($name){
        $others = array_splice($_SERVER['argv'], 3);
        if($others){
            system(
                sprintf(
                    Yii::$app->params['cmd_alias'][$name][0],
                    implode(' ', $others)
                )
            );
        }else{
            system(Yii::$app->params['cmd_alias'][$name][0]);
        }
    }
    public function actionList(){
        foreach(Yii::$app->params['cmd_alias'] as $name => $item){
            echo sprintf("%-35s %s \n", $name, $item[1]);
        }
    }
}
