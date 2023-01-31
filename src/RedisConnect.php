<?php

namespace Rubensdimas\RedisManager;

use Predis\Client as EntityRedis;

class RedisConnect {

  private static $redis_instance;
  private static $redis_conn;
  
  private final function __construct()
  {
    self::$redis_conn = new EntityRedis();
  }

  public static function getRedisConnection(){
    if (!isset(self::$redis_instance)) {
      self::$redis_instance = new RedisConnect;
    } /* else {
      echo "Redis já está conectado";
    } */

    return self::$redis_conn;
  }

}

// Primeira conexão

/* $conn = RedisConnect::getRedisConnection();

// Segunda conexão
$conn = RedisConnect::getRedisConnection();
 */