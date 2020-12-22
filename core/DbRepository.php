<?php
abstract class DbRepository
{
    protected $con;

    public function __construct($con)
    {
        $this->setConnection($con);
    }

    public function setConnection($con)
    {
        $this->con = $con;
    }

    // プリペアードステートメントに置き換える
    public function execute($sql, $params = array())
    {
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    // fetchは1行
    public function fetch($sql, $params = array())
    {
        // FETCH_ASSOC: 00結果を連想配列で受け取る
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    // fetchAllは全ての行
    public function fetchAll($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}
