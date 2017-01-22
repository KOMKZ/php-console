<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use yii\helpers\Inflector;
use yii\helpers\Console;
use yii\helpers\FileHelper;
/**
 * 对yii2-console的命令行模式生产快捷方式
 * yii2 command/generate $binDir $password
 * 对应的快捷命令成为
 * ycogenerate(y+co+generate)
 * @see generateCode方法
 */
class CommandController extends Controller
{
    public $consoleName = 'yii2';
    /**
     * 拉取文件然后提示是否解密私密文件
     * @param  array  $privateDir 需要解密的文件夹目录
     */
    public function actionGitPull($privateDir = array('private-note')){
        $password = '';
        foreach($privateDir as $index => $dir){
            shell_exec('git pull origin master');
            if(!is_dir($dir)){
                echo "{$dir}目录不存在请检查\n";
                exit();
            }else{
                $result = FileHelper::findFiles($dir);
                list($securityController,$actionId) = Yii::$app->createController('/security');
                if(!empty($result)){
                    $file = array_pop($result);
                    $contents = mb_substr(file_get_contents($file),1,100);
                    echo '----------------------------------' . "\n";
                    echo $contents;
                    echo '----------------------------------' . "\n";
                    echo "您的私密文件夹的某个文件的内容如上：\n";
                    echo "该文件在{$dir}目录中\n";
                    $select = Console::confirm('是否对该目录的所有文件进行解密');
                    if($select){
                        $password = $password ? $password : Console::prompt('请输入您的解密密码');
                        $securityController->actionDecrypt($dir, $password);
                    }
                }
            }
        }
    }
    /**
     * 推送数据提示是否需要加密数据
     * @param  array  $privateDir 需要加密的文件夹
     */
    public function actionGitPush($privateDir = array('private-note')){
        $password = '';
        $commitString = "";
        foreach($privateDir as $index => $dir){
            if(!is_dir($dir)){
                echo "{$dir}目录不存在请检查\n";
                exit();
            }else{
                $result = FileHelper::findFiles($dir);
                list($securityController,$actionId) = Yii::$app->createController('/security');
                if(!empty($result)){
                    $file = array_pop($result);
                    $contents = mb_substr(file_get_contents($file),1,100);
                    echo '----------------------------------' . "\n";
                    echo $contents;
                    echo '----------------------------------' . "\n";
                    echo "您的私密文件夹的某个文件的内容如上：\n";
                    echo "该文件在{$dir}目录中\n";
                    $select = Console::confirm('是否对该目录的所有文件进行加密');
                    if($select){
                        $password = $password ? $password : Console::prompt('请输入您的加密密码');
                        $commitString .= "{$index}、对{$dir}文件进行加密操作\n";
                        $securityController->actionEncrypt($dir, $password);
                    }
                }
            }
            if($commitString == ""){
                shell_exec('git push origin master');
            }else{
                shell_exec("git add --all;
                            git commit -m '{$commitString}';
                            git push origin master;");
            }
        }
    }
    /**
     * 对所有的yii2命令生产快捷命令
     * @param  string $binDir   命令将存放的文件夹，最好使用sudo
     * @param  string $password 用于标识命令是否是本程序产生，必须设定，删除产生的命令同时需要输入密码
     */
    public function actionGenerate($binDir,$password){
        if(!is_dir($binDir)){
            echo "指定目录不存在或者不是一个目录\n";
            return ;
        }
        $commands = $this->getCommands();
        $commandsCode = $this->generateCode($commands);
        $this->generateBinFile($commandsCode, $binDir,$password);
    }
    /**
     * 删除用本程序产生的快捷命令
     * @param  string $binDir   命令将存放的文件夹，最好使用sudo
     * @param  string $password 用于检验命令是否是本程序产生，必须设定，产生的命令同时需要输入密码
     * @return [type]           [description]
     */
    public function actionDeleteAll($binDir,$password){
        if(!is_dir($binDir)){
            echo "指定目录不存在或者不是一个目录\n";
            return ;
        }
        $commands = $this->getCommands();
        $commandsCode = $this->generateCode($commands);
        $this->deleteAllBinFile($commandsCode, $binDir, $password);
    }

    public function deleteAllBinFile($commandsCode, $binDir, $password){
        $files = [];
        foreach($commandsCode as $binName => $code){
            $file = $binDir . '/' . $binName;
            if(is_file($file) && $this->checkFile($file, $password)){
                $files['delete'][] = $file;
            }else{
                $files['ignore'][] = $file;
            }
        }
        echo "将要删除以下文件：\n";
        foreach($files['delete'] as $file){
            echo $file . "\n";
        }
        if(!empty($files['ignore'])){
            echo "以下文件将被无视：\n";
            foreach($files['ignore'] as $file){
                echo $file . "\n";
            }
        }
        $select = Console::confirm('您确定要执行这个操作吗？');
        if($select){
            foreach($files['delete'] as $file){
                if(unlink($file)){
                    echo "删除成功：{$file}\n";
                }else{
                    //颜色处理
                    echo "删除失败：{$file}\n";
                }
            }
        }
    }
    public function checkFile($file, $password){
        $handle = @fopen($file, "r");
        $line = 2;$i = 1;
        $buffer = "";
        if ($handle) {
            while (!feof($handle)) {
                if($i > $line){
                    fclose($handle);
                    $buffer = strtr($buffer, '# ',' ');
                    $hash = trim(strtr($buffer,"\n",' '));
                    echo "正在计算文件是否合法{$file}\n";
                    return Yii::$app->security->validatePassword($password, $hash);
                }
                $buffer = fgets($handle, 4096);
                $i++;
            }
            fclose($handle);
        }
        return false;
    }
    public function generateBinFile($commandsCode, $binDir, $password){
        $declare = "#!/bin/bash\n";
        $key = "# " . Yii::$app->security-> generatePasswordHash($password) . "\n";
        $comments = "# description:auto generate by {$this->consoleName}\n" .
                    "# author:kz\n";
        foreach($commandsCode as $binName => $code){
            $contents = $declare . $key . $comments . $code . "\n";
            $filePath = Yii::getAlias('@runtime') . '/tmp/' . $binName;
            echo "正在生成快捷命令：{$binName}\n";
            file_put_contents($filePath, $contents);
            chmod($filePath,0755);
            $newPath = $binDir . '/' . $binName;
            shell_exec("mv {$filePath} {$newPath}");
        }
    }
    public function generateCode($commands){
        $commandsCode = [];
        foreach($commands as $controllerName => $actions){
            foreach($actions as $actionName => $args){
                $argsStr = "";
                for($i = 1, $max = count($args); $i <= $max; $i++){
                    $argsStr .= '$'.$i.' ';
                }
                //应该要检查一下 yii2是否可用的，或者这里开发成为参数
                $code = "yii2 %controller%/%action% "  . $argsStr;
                $code = strtr($code,[
                    '%controller%' => $controllerName,
                    '%action%' => $actionName
                ]);
                //命令的名字
                $name = substr($this->consoleName, 0, 1) .
                        substr($controllerName, 0, 2) .
                        $actionName;
                $commandsCode[$name] = $code;
            }
        }
        return $commandsCode;
    }
    public function getCommands(){
        $commands = [];
        $controllers = $this->getControllers();
        foreach($controllers as $controllerName){
            list($controller, $actionId) = Yii::$app->createController($controllerName);
            $actions = $this->getActions($controller);
            foreach($actions as $actionName){
                $action = $controller->createAction($actionName);
                $args = $controller->getActionArgsHelp($action);
                $commands[substr($controllerName, 1)][$actionName] = $args;
            }
        }

        return $commands;
    }
    public function getControllers(){
        $module = Yii::$app;
        $prefix = $module instanceof Application ? '' : $module->getUniqueID() . '/';
        $commands = [];
        foreach (array_keys($module->controllerMap) as $id) {
            $commands[] = $prefix . $id;
        }
        $controllerPath = $module->getControllerPath();
        if (is_dir($controllerPath)) {
            $files = scandir($controllerPath);
            foreach ($files as $file) {
                if (!empty($file) && substr_compare($file, 'Controller.php', -14, 14) === 0) {
                    $controllerClass = $module->controllerNamespace . '\\' . substr(basename($file), 0, -4);
                    if ($this->validateControllerClass($controllerClass)) {
                        $commands[] = $prefix . Inflector::camel2id(substr(basename($file), 0, -14));
                    }
                }
            }
        }
        return $commands;
    }
    public function getActions($controller)
    {
        $actions = array_keys($controller->actions());
        $class = new \ReflectionClass($controller);
        foreach ($class->getMethods() as $method) {
            $name = $method->getName();
            if ($name !== 'actions' && $method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0) {
                $actions[] = Inflector::camel2id(substr($name, 6), '-', true);
            }
        }
        sort($actions);

        return array_unique($actions);
    }

    protected function validateControllerClass($controllerClass)
    {
        if (class_exists($controllerClass)) {
            $class = new \ReflectionClass($controllerClass);
            return !$class->isAbstract() && $class->isSubclassOf('yii\console\Controller');
        } else {
            return false;
        }
    }
}
