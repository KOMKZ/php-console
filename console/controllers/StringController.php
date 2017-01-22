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
    public function actionNumMd($file, $out){
        $string = file_get_contents($file);
        if(preg_match_all("/(#+)\s+([0-9.]*)\s?(.*?)\n/", $string, $matches)){
            $output = [];
            $this->build($this->classarray($matches[1], 1, ''), '', $output);
            global $map;
            $map = [];
            foreach($matches[3] as $k => $value){
                // if(isset($output[$k])){
                    $map[] = $output[$k];
                // }
            }
            $content = preg_replace_callback("/(#+)\s+([0-9.]*)\s?(.*?)\n/", function($m) use($map){
                global $map;
                $one = array_shift($map);
                return sprintf("%s %s %s\n", $m[1], $one, $m[3]);
            }, $string);
            file_put_contents($out, $content);
        }else{
            echo "wrong\n";
        }
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
    protected function array_search_values($m_needle, $a_haystack, $b_strict = true){
        return array_intersect_key( $a_haystack, array_flip( array_keys( $a_haystack, $m_needle, $b_strict)));
    }
    protected function get_slice_array($levals, $max){
        $slice = [];
        foreach ($levals as $key =>$value) {
            $nextKey = $key + 1;
            if(isset($levals[$nextKey])){
                $slice[] = [$value + 1, $levals[$nextKey] - $value - 1];
            }else{
                $slice[] = [$value, $max];
            }
        }
        return $slice;
    }
    protected function classarray($a, $i, $prefix = ''){

        $result = [];
        $levals = array_keys($this->array_search_values(str_repeat('#', $i), $a));

        $max = count($a);
        if(!empty($levals)){
            $slice = [];
            $slice = $this->get_slice_array($levals, $max);

            for($n = 0, $all=count($levals); $n < $all; $n++){
                $result[$n+1] = array_slice($a, $slice[$n][0], $slice[$n][1]);
            }
            foreach($result as $key => $items){
                $result[$key] = $this->classarray($items, $i+1);
            }
            return $result;
        }else{
            return null;
        }
        return $result;
    }
    protected function build($a, $prefix = '', &$output){
        if(is_array($a)){
            foreach($a as $name => $items){
                $num = $prefix . $name . '.';
                array_push($output, $num);
                $this->build($items, $num, $output);
            }
        }
    }
}
