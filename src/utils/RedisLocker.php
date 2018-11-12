<?php
/**
 * Created by PhpStorm.
 * User: godtoy
 * Date: 2018/11/12
 * Time: 9:50
 */

namespace godtoy\helper\utils;


use think\facade\Cache;

class RedisLocker
{

    const LOCKER_REDIS_PREFIX = '_locker_:';
    const TIME_OUT = 10;//s

    /**
     * @var \Redis $handler
     */
    private $handler = null;

    /**
     * Locker constructor.
     */
    public function __construct()
    {
        $cache = Cache::store('redis');
        // 获取缓存对象句柄
        $this->handler = $cache->handler();
    }

    /**
     * @param $key
     * @param int $wait 等待时间
     * @param int $timeout 锁超时时间
     * @return bool
     */
    public function getLock($key, $wait = 3, $timeout = 10)
    {
        $key = $this->getKey($key);
        $count = 1;
        //1.尝试进行设置锁，值标识了这个锁应当过期的时间
        while (false === $this->handler->setnx($key, time() + $timeout)) {
            //2.如果不能获取锁的话,我需要尝试去查看一下是否是锁过期了，或者是没有设置一个超时时间
            $val = (int)$this->handler->get($key);
            if ($val <= time()) {
                //手动释放锁,
                $this->freeLock($key);
                continue;
            }
            //3.如果这个锁没有超时就继续尝试,直到超时
            sleep(1);
            $count++;
            if ($count >= $wait) {
                return false;
            }
        }
        //获取到了并发锁，设置超时时间
        $this->handler->expire($key, $timeout);
        return true;
    }

    /**
     * 释放锁
     * @param $key
     * @return int
     */
    public function freeLock($key)
    {
        $key = $this->getKey($key);
        return $this->handler->del($key);
    }

    private function getKey($key)
    {
        return $key = sprintf(self::LOCKER_REDIS_PREFIX . "%s", $key);
    }
}
