<?php

class DbManager
{
    protected $connections = array(); // PDOクラスのインスタンスを格納
    protected $repository_connection_map = array(); 
    //テーブルごとのRepositoryクラスと接続名の対応を格納

    // DBへ接続
    public function connect($name, $params)
    {
        $params = array_merge(array(
            'dsn' => null,
            'user' => '',
            'password' => '',
            'options' => array(),
        ), $params);

        $con = new PDO(
            $params['dsn'],
            $params['user'],
            $params['password'],
            $params['options']
        );

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connect[$name] = $con;
    }

    // connect()メソッドで接続したコネクションを取得
    public function getConnection($name = null)
    {
        if (is_null($name)) {
            // 現在指している配列要素の値を返す
            return current($this->connections);
        }

        return $this->connections[$name];
    }

    public function setRepositoryConnectionMap($repository_name, $name)
    {
        $this->repository_connection_map[$repository_name] = $name;
    }

    public function getConnectionForRepository($repository_name)
    {
        if (isset($this->repository_connection_map[$repository_name])) {
            $name = $this->repository_connection_map[$repository_name];
            $con = $this->getConnection($name);
        } else {
            $con = $this->getConnection();
        }

        return $con;
    }

    public function get($repository_name)
    {
        if (!isset($this->repositories['repository_name'])) {
            $repository_class = $repository_name . 'Repository';
            $con = $this->getConnectionForRepository($repository_name);
            $repository = new $repository_class($con); // 動的なクラスを生成している
            $this->repositories[$repository_name] = $repository;
        }

        return $this->repositories[$repository_name];
    }

    // インスタンスが破棄されたときに自動的に呼び出される
    public function __destruct()
    {
        foreach ($this->repositories as $repository) {
            unset($repository);
        }

        foreach ($this->connections as $con) {
            unset($con);
        }
    }
}
