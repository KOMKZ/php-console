<?php
namespace console\base;

use yii\console\Application as BaseApplication;

/**
 *
 */
class Application extends BaseApplication
{
    public function coreCommands()
    {
        return [
            'help' => 'yii\console\controllers\HelpController',
            // 'migrate' => 'yii\console\controllers\MigrateController',
        ];
    }
}
