<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;


/**
 * 加密解密控制器，暂时用于加密解密github的需要保密的内容
 */
class SecurityController extends Controller
{
    /**
     * 递归加密指定目录下的所有文件
     * @param  string $file     目录路径
     * @param  string $password 加密密码
     * @param  boolean $encrypt  是否加密目录，不支持
     */
    public function actionEncrypt($file, $password, $encrypt = true)
    {
        if (is_dir($file)) {
            $files = $this->getDirFiles($file);
            foreach ($files as $fileName) {
                echo "正在加密文件：{$fileName}\n";
                $this->encryptFile($fileName, $password);
                //暂时不支持相对路径
                // if ($encrypt) {
                //     $pos = mb_strrpos($fileName, '/');
                //     $rootName = mb_substr($fileName, 0, $pos);
                //     $name = mb_substr($fileName, $pos + 1);
                //     $newName = Yii::$app->security->encryptByPassword($name,$password);
                //     echo "正在加密文件名：{$name} >> {$newName} \n";
                //     rename($fileName, $rootName.'/'.$newName);
                // }
            }
            // 记录操作

        } else {
            echo "正在加密文件：{$file}\n";
        }
    }
    /**
     * 递归加密指定目录下的所有文件
     * @param  string $file     目录路径
     * @param  string $password 解密密码
     * @param  boolean $encrypt  是否加密目录，不支持
     */
    public function actionDecrypt($file, $password, $encrypt = true)
    {
        if (is_dir($file)) {
            $files = $this->getDirFiles($file);
            foreach ($files as $fileName) {
                echo "正在解密文件：{$fileName}\n";
                $this->decryptFile($fileName, $password);
                //暂时不支持相对路径
                // if ($encrypt) {
                //     $pos = mb_strrpos($fileName, '/');
                //     $rootName = mb_substr($fileName, 0, $pos);
                //     $name = mb_substr($fileName, $pos + 1);
                //     $newName = Yii::$app->security->decryptByPassword($name,$password);
                //     echo "正在解密文件名：{$name} >> {$newName}\n";
                //     rename($fileName, $rootName.'/'.$newName);
                // }
            }
        } else {
            echo "正在解密文件：{$file}\n";
        }
    }
    public function decryptFile($file, $password)
    {
        $contents = file_get_contents($file);
        $contents = Yii::$app->security->decryptByPassword($contents, $password);
        file_put_contents($file, $contents);
    }
    public function encryptFile($file, $password)
    {
        $contents = file_get_contents($file);
        $contents = Yii::$app->security->encryptByPassword($contents, $password);
        file_put_contents($file, $contents);
    }
    public function getDirFiles($path)
    {
        $result = scandir($path);
        $file = [];
        array_shift($result);
        array_shift($result);
        foreach ($result as $item) {
            $name = $path.'/'.$item;
            if (is_dir($name)) {
                $file = array_merge($file, $this->getDirFiles($name));
            } else {
                $file[] = $name;
            }
        }
        return $file;
    }
}
