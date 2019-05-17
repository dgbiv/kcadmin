# kcadmin
#### 这是一套基于Yii2的后台框架，包括管理员管理和rabc角色管理。
环境要求：
- php >7.0
- nginx
- mysql
- mongodb

clone 代码后执行以下操作
```shell
composer install
php init
```
初始化后需配置数据库参数,文件路径/common/config/main-local.php
```php
 'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=数据库名',
            'username' => '用户名',
            'password' => '密码',
            'charset' => 'utf8',
        ],
```
执行脚本初始化数据库
```shell
./yii init
```