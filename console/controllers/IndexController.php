<?php
namespace console\controllers;

use yii\console\Controller;
use common\models\AttachModel;

/**
 *
 */
class IndexController extends Controller{
    public function actionIndex(){
        $attachModel = new AttachModel();
        $postId = 999;
        $sourceId = 'oss:post_thumb.jpg';
        $others = [
            'relate_user_id' => 1,
            'used_for' => 'post_thumb',
        ];
        // return attach_id or false
        $result = $attachModel->attach($postId, $sourceId, $others);
        // the record save in table look like following
        /**
         * $record = [
         * 		'aid' => 1,
         * 		'type' => 'post_thumb',
         * 		'object_id' => 999,
         * 		'source_id' => 'oss:post_thumb.jpg',
         * 		'source_path' => null,
         * 		'source_name' => '',
         * 		'source_des' => '',
         * 		'relate_user_id' => 1,
         * 		'create_user_id' => 1, // equal to `relate_user_id` by default
         * 		'status' => 1,
         * 		'created_at' => 1477272469,
         * ];
         */
         // the result is an array with attach records elements
         $result = $attachModel->retrieve([
             'object_id' => $postId,
             'type' => 'post_thumb',
         ]);
         // you can pass a callback function as second argument to retrieve
         $result = $attachModel->retrieve([
             'object_id' => $postId,
             'type' => 'post_thumb',
         ], function($index, $record){
             return [$index => $record];
         });
         // return true or false
         // you can pass a callback fucntion as second argument to filter record
         $result = $attachModel->detach([
             'object_id' => $postId,
             'type' => 'post_thumb',
             'relate_user_id' => 1,
         ], function($index, $record){
             return true;
         });



    }
}
