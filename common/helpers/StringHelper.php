<?php
namespace common\helpers;

use yii\helpers\BaseFileHelper;
/**
 *
 */
class StringHelper extends BaseFileHelper
{
    public static function camleToUnder($string){
        $stack = explode(' ', preg_replace('/([A-Z])/', ' $1', $string));
        return strtolower(implode('_', $stack));
    }
    public static function underToCamel($string){
        $string = preg_replace('/_([a-z])/', ' $1', $string);
        $string = preg_replace('/\s/', '' ,ucwords($string));
        return lcfirst($string);
    }
}
