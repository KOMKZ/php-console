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
        preg_match_all('/(%[a-zA-Z_\-\.0-9]+%)/', $cmd, $params);
        $vars = $this->getVars($params[1]);
        $cmd = strtr($cmd, $vars);
        system($cmd);
    }
    public function actionHelp(){
        foreach(Yii::$app->params['cmds'] as $name => $def){
            $des = strtr($def[0], ['%name%' => $name]);
            echo sprintf("%-15s%s\n", $name, $des);
        }
    }
    public function actionAlias(){
        $binCansFile = Yii::getAlias('@app/runtime/kz_bin_cans.txt');
        $binCans = [];
        if(file_exists($binCansFile)){
            $binCans = unserialize(file_get_contents($binCansFile));
            $binCans = $binCans ? $binCans : [];
        }else{
            file_put_contents($binCansFile, '');
        }
        foreach($binCans as $binPath){
            $name = basename($binPath);
            if(strpos($name, 'kz_') === 0){
                unlink($binPath);
            }
        }
        file_put_contents($binCansFile, '');
        $binCans = [];


        $binTpl = <<<js
#!/bin/bash
cd %path;
./yii %action $*;
js;
        $root = dirname(dirname(__DIR__));
        $tos = [
            'kz_help' => 'r/help',
            'kz_alias' => 'r/alias'
        ];
        foreach(Yii::$app->params['cmds'] as $name => $def){
            $binName = 'kz_' . $name;
            $tos[$binName] = 'r ' . $name;
        }

        foreach($tos as $name => $action){
            echo sprintf("%s %s\n", $name, $action);
            $content = strtr($binTpl, [
                '%path' => $root,
                '%action' => $action
            ]);
            $binPath = '/usr/local/bin/' . $name;
            $binCans[] = $binPath;
            file_put_contents($binPath, $content);
            chmod($binPath, 0777);
        }
        file_put_contents($binCansFile, serialize($binCans));
        echo "ok\n";
    }
    protected function getVars($need = []){
        $to = [];
        foreach(array_slice($_SERVER['argv'], 3) as $key => $val){
            $to["%{$key}%"] = $val;
        }
        $to['%name%'] = $_SERVER['argv'][2];
        foreach ($need as $val) {
            $key = trim($val, '%');
            if(isset(Yii::$app->params[$key])){
                $to[$val] = Yii::$app->params[$key];
                preg_match_all('/(%[a-zA-Z_\-\.0-9]+%)/', $to[$val], $params);
                $to[$val] = strtr($to[$val], $this->getVars($params[1]));
            }
        }
        return $to;
    }
    protected function renderString($content, $vars){
    }
}