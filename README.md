PHP IB Framework
===========

      ╔------------------------------------------------╗
      ┆                 Power By @小笙_                ┆
      ┆         http://yueqian.sinaapp.com             ┆
      ┆  QQ：327719167  Email：hucsecurity@163.com     ┆
      ╚------------------------------------------------╝

注意：在SiteController里已有部分已开发完成的功能示例。      



框架简介：http://ibframework.sinaapp.com/

开发手册：http://ibframework.sinaapp.com/docs

部署安装

下载此源码包，只需要ib这个文件夹即可。使用ib目录下的ibc.php快速部署。

      cp -R ~/Download/ib /var/www/test
      cd /var/www/test
      php ib/ibc.php ./
      
即可在/var/www/test目录下生成index.php,protected等文件。通过浏览器访问 http://localhost/test。

部署后的文件目录树
      ├── config.php
      ├── ib
      │   ├── caching
      │   │   ├── Cache.php
      │   │   ├── IMemcache.php
      │   │   └── IRedis.php
      │   ├── cli
      │   │   ├── config.php
      │   │   ├── index.php
      │   │   └── SiteController.php
      │   ├── db
      │   │   ├── ActiveRecord.php
      │   │   ├── DbCommand.php
      │   │   ├── DbConnection.php
      │   │   └── Transaction.php
      │   ├── ibc.php
      │   ├── ib.php
      │   └── web
      │       ├── AppException.php
      │       ├── Application.php
      │       ├── Captcha.php
      │       ├── Controller.php
      │       ├── FileUpload.php
      │       ├── Page.php
      │       ├── Timer.php
      │       ├── UrlManager.php
      │       └── Validator.php
      ├── index.php
      ├── protected
      │   ├── controllers
      │   │   └── SiteController.php
      │   ├── models
      │   ├── runtime
      │   └── views
      └── public
          ├── css
          ├── images
          └── js



