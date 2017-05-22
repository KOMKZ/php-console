<?php
namespace console\helpers;

use yii\helpers\BaseConsole;
use yii\helpers\ArrayHelper;

/**
 *
 */
class Console extends BaseConsole
{
    public static function inputMuliti($prompt = null){
        if (isset($prompt)) {
            static::stdout($prompt);
        }
        $s = '';
        while($l = fgets(\STDIN)){
            if($l == "\n"){
                echo $s;
                break;
            }else{
                $s .= $l;
            }
        }
        return $s;
    }
    public static function promptMulitiLine($text, $options = [])
    {
        $options = ArrayHelper::merge(
            [
                'required'  => false,
                'default'   => null,
                'pattern'   => null,
                'validator' => null,
                'error'     => 'Invalid input.',
            ],
            $options
        );
        $error   = null;

        top:
        $input = $options['default']
            ? static::inputMuliti("$text [" . $options['default'] . '] ')
            : static::inputMuliti("$text ");

        if ($input === '') {
            if (isset($options['default'])) {
                $input = $options['default'];
            } elseif ($options['required']) {
                static::output($options['error']);
                goto top;
            }
        } elseif ($options['pattern'] && !preg_match($options['pattern'], $input)) {
            static::output($options['error']);
            goto top;
        } elseif ($options['validator'] &&
            !call_user_func_array($options['validator'], [$input, &$error])
        ) {
            static::output(isset($error) ? $error : $options['error']);
            goto top;
        }

        return $input;
    }
}
