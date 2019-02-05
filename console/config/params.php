<?php
use console\helpers\Console;
$desFormat = [Console::FG_RED];
return [
    'cmds' => [
        'save_self' => [
            sprintf("echo '%s'", Console::ansiFormat("保存php-console到github", $desFormat)),
            "cd %cmd_path%",
            "git add --all",
            'git commit -m "%0%"',
            'git push origin master'
        ],
        'save_doc' => [
            sprintf("echo '%s'", Console::ansiFormat("保存doc到私有仓库", $desFormat)),
            "cd %doc_path%",
            "git add --all",
            'git commit -m "%0%"',
            'git push origin master'
        ],
        'navicat' => [
            sprintf("echo '%s'", Console::ansiFormat("启动navicat", $desFormat))
            ,'cd %navicat_path%'
            ,'./start_navicat'

        ]
    ],
    'doc_path' => '',
    'cmd_path' => '',
    'navicat_path' => '',
];
