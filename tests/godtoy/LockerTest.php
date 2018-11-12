<?php
/**
 * Created by PhpStorm.
 * User: godtoy
 * Date: 2018/11/12
 * Time: 10:29
 */

declare(strict_types=1);

namespace godtoy;

use godtoy\helper\utils\RedisLocker;
use PHPUnit\Framework\TestCase;

class LockerTest extends TestCase
{

    public function testGetLock()
    {
        $locker = new RedisLocker();
        $locker->getLock("asd");
    }
}