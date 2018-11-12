# tp5工具类

## Redis并发锁
godtoy/helper/utils/RedisLocker

```php
$locker=new RedisLocker();
$locker->getLock($key,$wait,$timeout);



//free
$locker->freeLock($key);

```