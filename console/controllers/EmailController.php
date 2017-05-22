<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\AttachModel;

/**
 *
 */
class EmailController extends Controller{
    public function actionTest(){
        $transport  = new \Swift_SmtpTransport('smtp.qq.com', 465, 'ssl');
        $transport->setUsername('kitral.zhong@trainor.cn');
        $transport->setPassword('TDSZ2016kz');

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance('Wonderful Subject')
                    ->setFrom(array('kitral.zhong@trainor.cn' => 'kz'))
                    ->setTo(array('784248377@qq.com'));
        $message->setBody(  '<html>' .
                            ' <head></head>' .
                            ' <body>' .
                            '  Here is an image <img src="' . // Embed the file
                                 $message->embed(\Swift_Image::fromPath('/home/kitral/Pictures/Wallpapers/1.jpg')) .
                               '" alt="Image" />' .
                            '  Rest of message' .
                            ' </body>' .
                            '</html>', 'text/html');
        $mailer->send($message);
    }
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
            // Yii::$app->mailer->compose()
            // ->setFrom('kitral.zhong@trainor.cn')
            // ->setTo('hugh.yu@trainor.cn')
            // ->setSubject($title?$title.' '.$time:'kitral加班餐申请' . ' ' . $time)
            // ->setHtmlBody($content)
            // ->send();
        }
    }

}
