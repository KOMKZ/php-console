<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 *
 */
class LogController extends Controller{

    /**
     * %time%-100,100的单位
     * d:day
     * h:hour
     * m:minute
     * s:second
     * @var string %time%-100,100的单位
     */
    public $format = 's';

    public function options($actionID)
    {
        return array_merge(
            parent::options($actionID),
            ['format']
        );
    }
    /**
     * 查询日志文件
     * 查询5分钟前的日志记录:
     * ./yii log/search-log %time%-5 %time% {$file} --format=m
     * 查询一天前的日志记录:
     * ./yii log/search-log %time%-1 %time% {$file} --format=d
     * 查询指定时间段的日志记录:
     * ./yii log/search-log 2017-01-12\ 12:00:00 2017-01-13\ 13:00:00
     * ./yii log/search-log 2017-01-12 2017-01-13
     * example:
     * kzcmd log/search-log %time%-25 %time% /var/www/html/hsehome2/app/console/runtime/logs/fortune-cron.log --format=m
     * @param  string $begin 开始时间
     * @param  string $end   结束时间
     * @param  string $file  日志文件路径
     * @return string
     */
    public function actionSearchLog($begin, $end, $file){
        if(!file_exists($file)){
            echo "$file 不存在\n";
            exit();
        }
        // $file = '/var/www/html/hsehome2/app/console/runtime/logs/fortune-cron.log';
        $time = $this->getTimes($file);
        if(preg_match('/%time%([+\-]*[0-9]*)/', $begin, $matches)){
            $begin = time() + $this->getOffset($this->format, $matches[1]);
        }else{
            $begin = strtotime($begin);
        }
        if(preg_match('/%time%([+\-]*[0-9]*)/', $end, $matches)){
            $end = time() + $this->getOffset($this->format, $matches[1]);
        }else{
            $end = strtotime($end);
        }
        list($beginPos, $endPos) = $this->searchByTime($time, $begin, $end);
        if($beginPos > 0){
            $content = '';
            foreach($time as $lineNum => $item){
                if($beginPos <= $lineNum && $lineNum <= $endPos){
                    $content .= $this->getTextFromFromFile($file, $item['line'], $item['line'] + $item['range'] + 1);
                }
            }
            echo $content;
        }
    }
    private function getOffset($format, $value){
        switch ($format) {
            case 'd':
                return 3600*24*$value;
            case 'h':
                return 3600*$value;
            case 'm':
                return 60*$value;
            case 's':
                return $value;
            default:
                throw new \ErrorException('unsupported format'. $format);
                break;
        }
    }
    private function getTimes($file){
        $h = fopen($file, 'rb');
        $lineNum = 0;
        $lastPos = -1;
        $time = [];
        $range = -1;
        while(false !== ($line = fgets($h))){
            $lineNum++;
            if(-1 != $range){
                $range++;
            }
            if(preg_match('/^([0-9\-]+\s[0-9:]+)/', $line, $matches)){
                if(-1 != $lastPos){
                    $time[$lastPos]['range'] = $range - 1;
                }
                $lastPos = $lineNum;
                $range = 0;
                $time[$lineNum] = [
                    'time' => strtotime($matches[1]),
                    'line' => $lineNum
                ];
            }
        }
        $time[$lastPos]['range'] = $range;
        fclose($h);
        return $time;
    }

    private function searchByTime($data, $begin, $end){
        // 寻找begin
        $beginPos = -1;
        foreach ($data as $key => $item) {
            if($item['time'] >= $begin){
                $beginPos = $key;
                break;
            }
        }

        // 寻找end
        $endPos = -1;
        $lastPos = -1;
        foreach($data as $key => $item){
            if($item['time'] > $end){
                $endPos = $lastPos;
                break;
            }else{
                $lastPos = $key;
            }
        }
        if(-1 == $endPos){
            $endPos = $lastPos;
        }
        return [$beginPos, $endPos];
    }
    private function getTextFromFromFile($file, $startLine, $endLine){
        $h = fopen($file, 'rb');
        $string = "";
        $lineNum = 0;
        while(false !== ($line = fgets($h))){
            $lineNum++;
            if(($lineNum >= $startLine) && ($lineNum < $endLine)){
                $string .= $line;
            }elseif($lineNum >= $endLine){
                break;
            }
        }
        fclose($h);
        return $string;
    }
}
