<?php
namespace console\controllers;

use yii\console\Controller;
use common\models\AttachModel;

/**
 *
 */
class DocController extends Controller{
    public function actionSwg($type, $msg = ''){
        $path = [
            'service' => "/home/master/company/trainor/hsehome_develop_document/2.0/模块/服务模块/服务订单0.2/swagger.json"
        ];
        $commits = [
            'service' => 'service_swagger: 更新服务订单swagger.json'
        ];
        copy('/var/www/html/swagger.json', $path[$type]);
        system(sprintf("cd /home/master/company/trainor/hsehome_develop_document;svn commit -m \"%s\" %s;svn update;",
            $msg ? $msg : $commits[$type],
            $path[$type]
        ));
    }
    public function actionTable($type, $msg = ''){
        $path = [
            'service' => "/home/master/company/trainor/hsehome_develop_document/2.0/模块/服务模块/服务订单0.2/table.sql"
        ];
        $commits = [
            'service' => 'service_table: 更新服务数据表'
        ];
        copy('/home/master/doc/trs-doc/service/table.sql', $path[$type]);
        system(sprintf("cd /home/master/company/trainor/hsehome_develop_document;svn add %s;svn commit -m \"%s\" %s;svn update;",
                $path[$type],
                $msg ? $msg : $commits[$type],
                $path[$type]
            ));
    }
}
