<?php
return [
	'adminEmail' => 'kitral.zhong@trainor.cn',
	'y2log_alias' => [
		'@rsfr' => '/home/master/pro/php/hsehome2.0/roadsafety/frontend',
	],
    'cmd_alias' => [
        'kill-qq' => [
            "ps -ef | grep 'QQ' | grep -v grep | awk '{ print $2 }' | xargs kill -9",
            '杀死QQ后台进程'
        ],
    ]
];
