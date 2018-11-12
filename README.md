# tp5工具类


install dependencies 
``` bash
composer require godtoy/tp5-helper-mixed
```



## Redis并发锁



```php
use godtoy/helper/utils/RedisLocker;

$locker=new RedisLocker();
$locker->getLock($key,$wait,$timeout);

//free
$locker->freeLock($key);
```