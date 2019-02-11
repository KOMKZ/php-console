<?php
use console\helpers\Console;
$desFormat = [Console::FG_RED];
return [
    'cmds' => [
        'save_self' => [
            sprintf("echo '%s'", Console::ansiFormat("save php-console to github:%name% {commit}", $desFormat)),
            "cd %cmd_path%",
            "git add --all",
            'git commit -m "%0%"',
            'git push origin master'
        ],
        'pull_self' => [
            sprintf("echo '%s'", Console::ansiFormat("pull php-console:%name%", $desFormat)),
            "cd %cmd_path%",
            'git pull origin master'
        ],
        'pwd' => [
            sprintf("echo '%s'", Console::ansiFormat("cat kz pwd:%name%", $desFormat))
            ,'cat %kz_pwd_path%'
            ,"echo \n"
        ],
        '5to7' => [
            sprintf("echo '%s'", Console::ansiFormat("php5.6->php7.2:%name%", $desFormat))
            ,'update-alternatives --set php /usr/bin/php5.6'
            ,'service php5.6-fpm restart'
            ,'service nginx restart'
        ],
        '7to5' => [
            sprintf("echo '%s'", Console::ansiFormat("php7.2->php5.6:%name%", $desFormat))
            ,'update-alternatives --set php /usr/bin/php7.2'
            ,'service php7.2-fpm restart'
            ,'service nginx restart'
        ],
        'save_doc' => [
            sprintf("echo '%s'", Console::ansiFormat("save doc in private hub:%name% {commit}", $desFormat)),
            "cd %doc_path%",
            "git add --all",
            'git commit -m "%0%"',
            'git push origin master'
        ],
        'cat_host' => [
            sprintf("echo '%s'", Console::ansiFormat("check host:%name%", $desFormat))
            ,'cat /etc/hosts'
        ],
        'pull_doc' => [
            sprintf("echo '%s'", Console::ansiFormat("pull doc:%name% {commit}", $desFormat)),
            "cd %doc_path%",
            'git pull origin master'
        ],
        'navicat' => [
            sprintf("echo '%s'", Console::ansiFormat("start navicat", $desFormat))
            ,'cd %navicat_path%'
            ,'./start_navicat'
        ],
        'new_php5_serv' => [
            sprintf("echo '%s'", Console::ansiFormat("new php5.6-http server:%name% {name} {port} {root}", $desFormat)),
            'echo "%nginx_5.6_tpl%" > /etc/nginx/sites-enabled/%name%.conf',
            'nginx -t',
            'service nginx restart'
        ],
        'new_php7_serv' => [
            sprintf("echo '%s'", Console::ansiFormat("new php7.2-http server:%name% {name} {port} {root}", $desFormat)),
            'echo "%nginx_7.2_tpl%" > /etc/nginx/sites-enabled/%0%.conf',
            'nginx -t',
            'service nginx restart'
        ],
        'proxy' => [
            sprintf("echo '%s'", Console::ansiFormat("start proxy:%name%", $desFormat))
            ,'sslocal -c %proxy_conf_path%'
        ],
        'edit_ng_conf' => [
            sprintf("echo '%s'", Console::ansiFormat("edit nginx conf file:%name% {name} {port}", $desFormat))
            ,'gedit /etc/nginx/sites-enabled/%0%.conf',
        ],
        'ngrs' => [
            sprintf("echo '%s'", Console::ansiFormat("restart nginx:%name%", $desFormat))
            ,'service nginx restart'
        ]
    ],
    'nginx_5.6_tpl' => '
server {
    # for %0%
	listen %1% default_server;
	listen [::]:%1% default_server ipv6only=on;
	root %2%;
	index index.php index.html index.htm;
	server_name localhost;
	location / {
		try_files $uri $uri/ =404;
	}
	location ~ ^(?!/(assets|index.php|favicon.ico|index-test.php))(.*) {
        rewrite ^(?!/index.php)(/.*) /index.php$2 last;
    }
	location ~ \.php$ {
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/run/php/php5.6-fpm.sock;
		fastcgi_index index.php;
		include fastcgi.conf;
	}
}',
    'nginx_7.2_tpl' => '
server {
    # for %0%
	listen %1% default_server;
	listen [::]:%1% default_server ipv6only=on;
	root %2%;
	index index.php index.html index.htm;
	server_name localhost;
	location / {
		try_files $uri $uri/ =404;
	}
	location ~ ^(?!/(assets|index.php|favicon.ico|index-test.php))(.*) {
                rewrite ^(?!/index.php)(/.*) /index.php$2 last;
    }
	location ~ \.php$ {
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		fastcgi_pass unix:/run/php/php7.2-fpm.sock;
		fastcgi_index index.php;
		include fastcgi.conf;
	}
}',
    //个人文档仓库本地路径
    'doc_path' => '',
    //php-console项目本地地址
    'cmd_path' => '',
    //todo项目本地地址
    'todo_path' => '',
    //navicate bin路径地址
    'navicat_path' => '',
    // 密码文件地址
    'kz_pwd_path' => '',
    // 代理软件项目文件地址
    'proxy_conf_path' => '',
];
