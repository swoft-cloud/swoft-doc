# 环境搭建

## PHP安装

版本至少大于等于7.0

## Swoole 安装
开启协程和异步redis

## composer安装

## hiredis 安装

## nginx 配合swoft使用
```php
server {
  listen 80;
  server_name swoft.xxx.link;
  access_log /data/wwwlogs/swoft.puwei.link_nginx.log combined;
  index index.html index.htm index.php;
  root /mnt/www/swoft/public;#这个指定到public下面就OK
  
  include /usr/local/nginx/conf/rewrite/none.conf;
  #error_page 404 /404.html;
  #error_page 502 /502.html;
 location / {
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header Host $host;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header Connection "keep-alive";
    proxy_pass http://127.0.0.1:83;
  } 
  location ~ [^/]\.php(/|$) {

   
    proxy_pass http://127.0.0.1:83;#这个端口你是多少写多少
    #fastcgi_pass 127.0.0.1:83;
    #fastcgi_pass unix:/dev/shm/php-cgi.sock;
    #fastcgi_index index.php;
    #include fastcgi.conf;
  }

  location ~ .*\.(gif|jpg|jpeg|png|bmp|swf|flv|mp4|ico)$ {
    expires 30d;
    access_log off;
  }
  location ~ .*\.(js|css)?$ {
    expires 7d;
    access_log off;
  }
  location ~ /\.ht {
    deny all;
  }
}


```
