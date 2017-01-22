<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\AttachModel;

/**
 *
 */
class EmailController extends Controller{

    public function actionJiabancan($to = '', $title = null, $time = null){
        if('hugh' == $to){
            $time = $time ? $time : date('Y/m/d', time());
            $content = <<<eot
            申请时间：$time<br/>
            申请人：kitral
eot;
            Yii::$app->mailer->compose()
            ->setFrom('kitral.zhong@trainor.cn')
            ->setTo('784248377@qq.com')
            ->setSubject($title?$title.' '.$time:'kitral加班餐申请' . ' ' . $time)
            ->setHtmlBody($content)
            ->send();
            Yii::$app->mailer->compose()
            ->setFrom('kitral.zhong@trainor.cn')
            ->setTo('hugh.yu@trainor.cn')
            ->setSubject($title?$title.' '.$time:'kitral加班餐申请' . ' ' . $time)
            ->setHtmlBody($content)
            ->send();
        }
    }

}
