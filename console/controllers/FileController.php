<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;

/**
 *
 */
class FileController extends Controller{
    public function actionGetStatusTable($yamlFile, $outFile, $trTpl = null , $tableTpl = null, $htmlTpl = null){
        $trFile = $trTpl ? $trTpl : Yii::getAlias('@app/tpl/tr_tpl.tpl');
        $tableFile = $tableTpl ? $tableTpl : Yii::getAlias('@app/tpl/table_tpl.tpl');
        $htmlFile = $htmlTpl ? $htmlTpl : Yii::getAlias('@app/tpl/html_tpl.tpl');
        $htmlContent = file_get_contents($htmlFile);
        $trContent = file_get_contents($trFile);
        $tableContent = file_get_contents($tableFile);
        $data = spyc_load_file($yamlFile);
        $content = "";
        foreach($data as $item){
            $trs = "";
            foreach($item['items'] as $def){
                // active|可用|无描述|无标志|无默认
                $def = $this->tr($def);
                $trs .= strtr($trContent, [
                    '_val_' => $def['value'],
                    '_symbol_' => $def['symbol'],
                    '_name_' => $def['name'],
                    '_des_' => $def['des'] ? $def['des'] : "",
                    '_isdefault_' => (array_key_exists('isdefault', $def) && $def['isdefault']) ? "是" : ""
                ]);
            }
            $content .= strtr($tableContent, ['_title_' => $item['name'], '_trs_' => $trs]);
        }
        $html = strtr($htmlContent, ['__content__' => $content]);
        file_put_contents($outFile, $html);
    }

    protected function tr($item){
        $def = explode('|', $item);
        $def['value'] = $def[0];
        $def['name'] = $def[1];
        $def['des'] = ArrayHelper::getValue($def, 2, '');
        $def['symbol'] = ArrayHelper::getValue($def, 3, '');
        $def['isdefault'] = ArrayHelper::getValue($def, 4, '');
        return $def;
    }
    
    public function actionEnumsStr($yamlFile, $file){
        $data = spyc_load_file($yamlFile);
        $result = [];
        foreach($data as $item){
            $enums = [];
            foreach($item['items'] as $value){
                $value = $this->tr($value);
                $enums[] = sprintf("%s:%s", $value['value'], $value['name']);
            }
            $result[$item['field']] = implode('|', $enums);
        }
        $content = sprintf("<?php\nreturn %s;", VarDumper::export($result));
        file_put_contents($file, $content);
        echo $file;
    }
}
