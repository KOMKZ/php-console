<?php
Yii::setAlias('@common', dirname(dirname(__DIR__)) . '/common');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
require Yii::getAlias('@console/helpers/spyc.php');
