<?php
namespace console\controllers;


use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\db\Command;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\base\InvalidConfigException;

class RController extends Controller{
    public function actionIndex($name){
        $cmd = Yii::$app->params['cmds'][$name];
        $cmd = implode(';', $cmd);
        preg_match_all('/(%[a-zA-Z_\-0-9]+%)/', $cmd, $params);
        $to = [];
        foreach(array_splice($_SERVER['argv'], 3) as $key => $val){
            $to["%{$key}%"] = $val;
        }
        foreach ($params[1] as $val) {
            $key = trim($val, '%');
            if(isset(Yii::$app->params[$key])){
                $to[$val] = Yii::$app->params[$key];
            }
        }
        $cmd = strtr($cmd, $to);
        system($cmd);

    }

}