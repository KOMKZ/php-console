<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 *
 */
class FileController extends Controller{
    public function actionParsePhp($fileName = ''){

    }
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
                $trs .= strtr($trContent, [
                    '_val_' => $def['value'],
                    '_symbol_' => $def['symbol'],
                    '_name_' => $def['name'],
                    '_des_' => $def['des'] ? implode("<br/>", $def['des']) : "",
                    '_isdefault_' => (array_key_exists('isdefault', $def) && $def['isdefault']) ? "是" : ""
                ]);
            }
            $content .= strtr($tableContent, ['_title_' => $item['name'], '_trs_' => $trs]);
        }
        $html = strtr($htmlContent, ['__content__' => $content]);
        file_put_contents($outFile, $html);
    }
}
