# nginx 配置

这里列出了一些常用的nginx配置

## http 应用

```nginx
server {
    listen      80;
    server_name www.site.dev site.dev;
    root        /path/to/your-project/public;
    index       index.html index.htm;

    error_log logs/site.dev.error.log;
    access_log logs/site.dev.access.log;

    ##### 第一个必选规则: 匹配首页
    location = / {
        proxy_pass http://127.0.0.1:9501;
    }

    ##### 第二个必选规则: 处理静态文件请求，这是nginx作为http服务器的强项
    # 有两种配置模式，目录匹配或后缀匹配,任选其一或搭配使用
    # location ^~ /static/ {
    #     root /path/to/your-project/static/;
    # }

    location ~* \.(js|css|map|png|jpg|jpeg|gif|ico|ttf|woff2|woff)$ {
        expires       max;
        # root  /path/to/your-project/static/;
        # log_not_found off;
        access_log    off;
    }

    ##### 通用规则: 上面的都不匹配
    location / {
        # try_files $uri $uri/;

        # proxy_redirect  off;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_http_version 1.1;
        # proxy_set_header Upgrade $http_upgrade;
        # proxy_set_header Connection "upgrade";
        proxy_set_header Connection "keep-alive";

        # 没有找到文件就转发到 swoole server
        # 也可去掉 if. 全部转发到后端server
        if (!-e $request_filename){
            proxy_pass http://127.0.0.1:9501;
        }
    }
}
```

## websocket 应用

```conf
server {
  listen 80;
  server_name io.yourhost.com;

  location / {
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header Host $host;
    proxy_http_version 1.1;
    proxy_pass http://127.0.0.1:18307;
  }
}
```

## 启用 https 版本

```nginx
server {
  listen       443 ssl;
  listen       80;
  server_name swoft.org www.swoft.org;
  root        /path/to/your-project/public;
  index       index.html index.htm;

  ssl on;
  ssl_certificate /my-certs/2235215_www.swoft.org.pem;
  ssl_certificate_key /my-certs/2235215_www.swoft.org.key;

  ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
  ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:ECDHE:ECDH:AES:HIGH:!NULL:!aNULL:!MD5:!ADH:!RC4;
  ssl_session_cache shared:SSL:20m;
  ssl_session_timeout 60m;
  ssl_prefer_server_ciphers on;

  add_header Strict-Transport-Security max-age=16000000;
  # error_page 497  https://$host$request_uri;

  error_log /var/log/nginx/your-project.error.log;
  access_log /var/log/nginx/your-project.access.log;

  location / {
    # proxy_redirect  off;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header Host $host;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_http_version 1.1;
    # proxy_set_header Upgrade $http_upgrade;
    # proxy_set_header Connection "upgrade";
    proxy_set_header Connection "keep-alive";

    proxy_pass http://127.0.0.1:18306;
  }

  location ~* \.(js|map|css|png|jpg|jpeg|gif|ico|ttf|woff2|woff)$ {
    expires       max;
    #log_not_found off;
    access_log    off;
  }
}
```