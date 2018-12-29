#!/bin/bash

set -x

# 保存环境变量，使容器内正常显示中文
env >> /etc/default/locale

# 开启ssh服务 方便远程登录
/etc/init.d/ssh start

# 运行 php-fpm
php-fpm