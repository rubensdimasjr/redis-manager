<?php

namespace Rubensdimas\RedisManager;

class Redis {

  /**
   * Conexão com o Redis
   * @var RedisConnect
   */
  private $connection;
  
  public function __construct()
  {
    $this->setConnection(RedisConnect::getRedisConnection());
  }

  /**
   * Método responsável por setar uma conexão com o Redis
   * @param RedisConnect
   */
  public function setConnection($connection){
    $this->connection = $connection;
  }

  /**
   * Método responsável por retornar uma conexão com o redis
   * @return \Predis\Client
   */
  public function getConnection(){
    return $this->connection;
  }

  /**
   * Método responsável por deletar uma linha do cache
   * @param string $key
   * @param string $field
   * @return bool
   */
  public function delete($key, $field){
    try{
      $this->getConnection()->hdel($key,$field);
    }catch(\Predis\Client $e){
      echo "Error: ".$e->getMessage();
    }

    return true;
  }

  /**
   * Método responsável por editar uma linha do cache 
   * @param string $key
   * @param string $field
   * @param array $values
   * @return bool
   */
  public function edit($key, $field, $values){
    try{
      $this->getConnection()->hdel($key,[$field]);
      $this->getConnection()->hset($key, $field, json_encode($values, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }catch(\Predis\Client $e){
      echo "Error: ".$e->getMessage();
    }
  
    return true;
  }

  /**
   * Método responsável por retornar dados do redis baseado na key e no campo 
   * @param string $key
   * @param string $field
   * @return array|null
   */
  public function getByKeyAndField($key, $field){
    $cachedEntry = $this->getConnection()->hget($key, $field);

    if($cachedEntry){
      return json_decode($cachedEntry, true);
    }

    return $cachedEntry;
  }

  /**
   * Método responsável por retornar todos os dados do cache baseados em sua key
   * @param string $key
   * @return array|null
   */
  public function getAll($key){
    $cachedEntry = $this->getConnection()->hgetall($key);
    $temp = [];

    if($cachedEntry){
      foreach($cachedEntry as $hash => $field){
        $temp[] = json_decode($cachedEntry[$hash],true);
      }

      return $temp;
    }

    return null;
  }

  /**
   * Método responsável por inserir dados no cache baseados por uma chave, um campo, os dados e um tempo de expiração
   * @param string $key
   * @param string $field
   * @param array $values
   * @param int $time_expire
   * @return void
   */
  public function insert($key, $field, $values, $time_expire){
    try{
      $this->getConnection()->hset($key, $field, json_encode($values, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
      $this->getConnection()->expire($key, $time_expire);
    }catch(\Predis\Client $e){
      echo "Error: ".$e->getMessage();
    }
  }
  
}
