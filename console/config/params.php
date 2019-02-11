<?php
use console\helpers\Console;
$desFormat = [Console::FG_RED];
return [
    'cmds' => [
        'save_self' => [
            sprintf("echo '%s'", Console::ansiFormat("保存php-console到github:%name% {commit}", $desFormat)),
            "cd %cmd_path%",
            "git pull origin master",
            "git add --all",
            'git commit -m "%0%"',
            'git push origin master'
        ],
        'pull_self' => [
            sprintf("echo '%s'", Console::ansiFormat("拉取php-console:%name%", $desFormat)),
            "cd %cmd_path%",
            'git pull origin master'
        ],
        'pwd' => [
            sprintf("echo '%s'", Console::ansiFormat("查看kz密码:%name%", $desFormat))
            ,'cat %kz_pwd_path%'
            ,"echo \n"
        ],
        '5to7' => [
            sprintf("echo '%s'", Console::ansiFormat("php5.6->php7.2:%name%", $desFormat))
            ,'update-alternatives --set php /usr/bin/php7.2'
            ,'service php7.2-fpm restart'
            ,'service nginx restart'
        ],
        '7to5' => [
            sprintf("echo '%s'", Console::ansiFormat("php7.2->php5.6:%name%", $desFormat))
            ,'update-alternatives --set php /usr/bin/php5.6'
            ,'service php5.6-fpm restart'
            ,'service nginx restart'
        ],
        'save_doc' => [
            sprintf("echo '%s'", Console::ansiFormat("保存doc到私有仓库:%name% {commit}", $desFormat)),
            "cd %doc_path%",
            "git add --all",
            'git commit -m "%0%"',
            'git push origin master'
        ],
        'cat_host' => [
            sprintf("echo '%s'", Console::ansiFormat("查看host:%name%", $desFormat))
            ,'cat /etc/hosts'
        ],
        'pull_doc' => [
            sprintf("echo '%s'", Console::ansiFormat("拉取doc:%name% {commit}", $desFormat)),
            "cd %doc_path%",
            'git pull origin master'
        ],
        'navicat' => [
            sprintf("echo '%s'", Console::ansiFormat("启动navicat", $desFormat))
            ,'cd %navicat_path%'
            ,'./start_navicat'
        ],
        'new_php5_serv' => [
            sprintf("echo '%s'", Console::ansiFormat("新建php5.6-http服务:%name% {name} {port} {root}", $desFormat)),
            'echo "%nginx_5.6_tpl%" > /etc/nginx/sites-enabled/%name%.conf',
            'nginx -t',
            'service nginx restart'
        ],
        'new_php7_serv' => [
            sprintf("echo '%s'", Console::ansiFormat("新建php7.2-http服务:%name% {name} {port} {root}", $desFormat)),
            'echo "%nginx_7.2_tpl%" > /etc/nginx/sites-enabled/%0%.conf',
            'nginx -t',
            'service nginx restart'
        ],
        'proxy' => [
            sprintf("echo '%s'", Console::ansiFormat("启动代理:%name%", $desFormat))
            ,'sslocal -c %proxy_conf_path%'
        ],
        'edit_ng_conf' => [
            sprintf("echo '%s'", Console::ansiFormat("编辑nginx配置文件:%name% {name} {port}", $desFormat))
            ,'gedit /etc/nginx/sites-enabled/%0%.conf',
        ],
        'ngrs' => [
            sprintf("echo '%s'", Console::ansiFormat("重启nginx:%name%", $desFormat))
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
