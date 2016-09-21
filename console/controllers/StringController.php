<?php
namespace console\controllers;

use yii\console\Controller;
use common\helpers\StringHelper;

/**
 * 字符串工具
 */
class StringController extends Controller{
    public function actionUnderToCamel($string){
        echo StringHelper::underToCamel($string) . "\n";
    }
    public function actionCamelToUnderFromFile($file){
        if(file_exists($file)){
            $handle = fopen($file, "r");
            if ($handle) {
                while (($buffer = fgets($handle, 4096)) !== false) {
                    echo trim(StringHelper::camleToUnder($buffer), ",\'\"\n") . "\n";
                }
                if (!feof($handle)) {
                    echo "Error: unexpected fgets() fail\n";
                }
                fclose($handle);
            }
        }else{
            echo "Error:the file you specfied is not exists, {$file} ";
        }
    }
}
