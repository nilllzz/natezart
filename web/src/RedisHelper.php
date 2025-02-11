<?php

namespace Natezart;

final class RedisHelper
{
    private const string REDIS_HOST = "redis";
    private const int REDIS_PORT = 0;

    /**
     * Creates a new single-mode Redis connection.
     */
    public static function getInstance(): \Redis
    {
        $redis = new \Redis();
        $redis->connect(self::REDIS_HOST, self::REDIS_PORT);
        return $redis;
    }
}
