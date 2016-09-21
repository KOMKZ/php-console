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
/**
 * 数据库辅助命令
 */
class DbController extends Controller
{
    private $_generator = null;
    public $template = '@tests/unit/templates/fixtures';
    private $_db;

    public function options($actionID){
        return array_merge(parent::options($actionID) , [
            'template', 'db'
        ]);
    }
    public function getDb(){
        return $this->_db;
    }
    public function setDb($value){
        $this->_db = $value;
    }




    /**
     * 获取指定表插入多条数据的sql语句
     * @param  string  $tableName  插入的数据表名
     * @param  integer  $count     插入的假数据的数量
     * @param  integer $perMax    每条sql插入数据行的记录
     * insert table_name [fields] values (), (), 多少个括号
     */
    public function actionInsertMulti($tableName, $count, $perMax = 5){
        if($perMax < 0) $this->error("每次插入的数量应该是一个正数\n");
        if($count < 0) $this->error("插入的数量应该是一个正数\n");
        // i检测模板定义文件是否存在
        if(file_exists($this->template) && !is_dir($this->template)){
            $templateFilePath = $this->template;
        }else{
            $templatePath = Yii::getAlias($this->template);
            if(is_dir($templatePath)){
                $templateFilePath = $templatePath . '/' . $tableName . '.php';
            }else{
                $templateFilePath = "";
            }
        }

        if(!file_exists($templateFilePath))$this->error("没有找到fixture模板文件 {$templateFilePath}\n");

        // 检测指定的表是否存在
        $db = $this->getDbObject();
        if($db->getTableSchema($tableName) === null) $this->error("指定的数据表不存在\n");
        // 检查fixture中用户定义的字段是否合法
        $validColumns = $db->getTableSchema($tableName)->columnNames;
        $userColumns = $this->getFixtrueFields($templateFilePath);
        if(empty($userColumns)){
            $this->error("您的fixture文件没有指定字段名或者根本没有指定数据，请检查{$templateFilePath}\n");
        }
        $diff = array_diff($userColumns, $validColumns);
        if(!empty($diff)){
            $diffReport = VarDumper::dumpAsString($diff);
            $this->error("以下的字段是不合法的，请检查{$templateFilePath}中的定义\n", [Console::FG_RED], false);
            $this->error($diffReport . "\n");
        }


        // 准备初始化
        $generator = $this->getGenerator();
        $command = $db->createCommand();
        $columns = [];

        // 开始执行
        $this->info("程序处理开始.....\n", true);
        $this->startTime();
        for($i = 1; $i <= $count; $i += $perMax){
            $values = [];
            for($j = $i; $j < ($i + $perMax) && $j <= $count; $j++){
                $this->info("第{$j}条假数据已经生成\n");
                // echo Console::ansiFormat("第{$j}条假数据已经生成\n");
                $values[] = $this->generateFixture($templateFilePath, $j);
            }
            $end = $j - 1;
            if(empty($columns))$columns = array_keys($values[0]);
            if(!$this->test)$command->batchInsert($tableName, $columns, $values)->execute();
            // file_put_contents($sqlFilePath, strtr('/*第 start ～ end 条数据*/'."\n",['start'=>$i, 'end' => $end]) , FILE_APPEND);
            // file_put_contents($sqlFilePath, $sql . ";\n\n", FILE_APPEND);
            $this->info("写入{$i} - {$end}条数据\n", null, [Console::FG_GREEN]);
        }
        $this->info("共插入{$count}条数据，每次sql插入{$perMax}\n", true);
        $this->info($this->getRunTime(), true);
        exit(0);
    }
    /**
     * 获取指定表的字段名
     * @param  string $tableName 数据表名
     * @param  boolean $wrapLine  是否换行显示
     * @param  string $dbName    数据库名，不指定的时候使用配置数据库
     * @param  string $host      服务器地址
     * @return string            字段名
     */
    public function actionGetTableFields($tableName, $wrapLine = false, $dbName = '', $host = 'localhost'){
        $fields = $this->getTableFields($tableName, $dbName = '', $host = 'localhost');
        $result = '';
        $implode = $wrapLine ? "\n" : " ";
        foreach($fields as $value){
            $result .= "'{$value}',{$implode}";
        }
        echo substr($result, 0, -2) . "\n";
    }
    /**
     * 获取指定数据库表的字段comments,用于生成attributeLabels
     * @param  string $tableName 表名
     * @param  string $dbName    数据库名
     * @param  string $host      服务器名
     * @return string            字段名称地图
     */
    public function actionGetTableComments($tableName, $dbName = '', $host = 'localhost'){
        $comments = $this->getTableComments($tableName, $dbName);
        $result = '';
        $template = "'%name%' => '%label%',\n";
        foreach($comments as $name => $label){
            $result .= strtr($template, ['%name%'=>$name, '%label%' => $label]);
        }
        echo mb_substr($result,0,-2) . "\n";
    }



    private function getTableFields($tableName, $dbName = '', $host = 'localhost'){
        $db = $this->getDbObject($dbName, $host);
        $tableObj = $db->getTableSchema($tableName);
        $columns = $tableObj->columns;
        return ArrayHelper::getColumn($columns,'name');
    }
    private function getTableComments($tableName, $dbName = '', $host = 'localhost'){
        $db = $this->getDbObject($dbName, $host);
        $tableObj = $db->getTableSchema($tableName);
        if($tableObj){
			$columns = $tableObj->columns;
			$labels = ArrayHelper::map($columns,'name','comment');
			foreach($labels as $name=>$value){
				$labels[$name] = Yii::t('app',$value);
			}
            return $labels;
		}else{
			throw new InvalidConfigException("The table does not exist: " . $tableName);
		}
    }

    private function getDbObject($dbName = '', $host = 'localhost'){
        $dbConfig = Yii::$app->params['db'];
        if($dbName || $this->getDb()){
            $dbName = $this->getDb() ? $this->getDb() : $dbName;
            $dsn = [
                "host={$host}",
                "dbname={$dbName}"
            ];
            $dsn = "mysql:" . implode(';', $dsn);
            $dbConfig['dsn'] = $dsn;
        }
        return Yii::createObject($dbConfig);
    }

    private function getGenerator()
    {
        if ($this->_generator === null) {
            $language = Yii::$app->language;
            $this->_generator = \Faker\Factory::create(str_replace('-', '_', $language));
        }
        return $this->_generator;
    }

    private function generateFixture($template, $index){
        $faker = $this->getGenerator();
        return require($template);
    }

    private function getFixtrueFields($template){
        if(file_exists($template)){
            $faker = \Faker\Factory::create(str_replace('-', '_', Yii::$app->language));
            $index = 0;
            return array_keys(require($template));
        }
        return [];
    }






}
