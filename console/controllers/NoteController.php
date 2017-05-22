<?php
namespace console\controllers;


use Yii;
use yii\console\Controller;
use console\helpers\Console;

class NoteController extends Controller{
    public function actionAdd(){
        // $title = Console::prompt("输入title:", ['required' => true]);
        // $tags = Console::prompt("输入tags(空格隔开):", ['default' => '']);

        $contents = Console::promptMulitiLine("输入笔记内容(以空行结束):\n");
    }


}
