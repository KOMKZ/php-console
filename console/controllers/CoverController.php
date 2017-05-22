<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use yii\helpers\Inflector;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;


class CoverController extends Controller{
    public function actionXmlToPhp(){
        $xml = new \XMLReader();
        $xml->open('/home/kitral/Documents/tmp/file_id.xml');
        $assoc = $this->xml2assoc($xml);
        $xml->close();
        file_put_contents('1.txt', "<?php\nreturn [\n");
        foreach($assoc[0]['value'] as $item){
            $r = [];
            foreach($item['value'] as $sub){
                $r[$sub['tag']] = $sub['value'];
            }
            file_put_contents('1.txt', VarDumper::export($r) . ",\n", FILE_APPEND);
        }
        file_put_contents('1.txt', '];', FILE_APPEND);

    }
    function xml2assoc($xml) {
        $tree = null;
        while($xml->read())
            switch ($xml->nodeType) {
                case \XMLReader::END_ELEMENT: return $tree;
                case \XMLReader::ELEMENT:
                    $node = array('tag' => $xml->name, 'value' => $xml->isEmptyElement ? '' : $this->xml2assoc($xml));
                    if($xml->hasAttributes)
                        while($xml->moveToNextAttribute())
                            $node['attributes'][$xml->name] = $xml->value;
                    $tree[] = $node;
                break;
                case \XMLReader::TEXT:
                case \XMLReader::CDATA:
                    $tree .= $xml->value;
            }
        return $tree;
    }
}
