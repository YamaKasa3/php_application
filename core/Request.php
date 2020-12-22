<?php

class Request
{
    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }

        return false;
    }

    public function getGet($name, $default = null)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }

        return $default;
    }

    public function getPost($name, $default = null)
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }

        return $default;
    }

    // サーバのホスト名を取得する。HTTPリクエストヘッダにホストの値が含まれていない場合、
    // Apache側に設定されたホスト名が格納されている値を返す。
    public function getHost()
    {
        if (!empty($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_NAME'];
    }

    public function isSsl()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        }

        return false;
    }

    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /*
    ベースURLはこのフレームワーク上での名称
    ホスト部分より後ろからフロントコントローラまでの値がベースURLとなる
    HTML内にリンクを作成する際に利用し、リンクは絶対URLを指定する
    */ 
    public function getBaseUrl()
    {
        $script_name = $_SERVER['SCRIPT_NAME'];
        $request_uri = $this->getRequestUri();

        if (0 === strpos($request_uri, $script_name)) {
            // フロントコントローラがURLに含まれる場合
            return $script_name;
        } else if (0 == strpos($request_uri, dirname($script_name))) {
            // フロントコントローラが省略されている場合
            return rtrim(dirname($script_name), '/');
        }

        return '';
    }

    /*
    PATH_INFOは基本的にはREQUEST_URLからベースURLを取り除いた値となる
    */
    public function getPathInfo()
    {
        $base_url = $this->getBaseUrl();
        $request_uri = $this->getRequestUri();
        
        if (false !== ($pos = strpos($request_uri, '?'))) {
            $request_uri = substr($request_uri, 0, $pos);
        }
        
        $path_info = (string)substr($request_uri, strlen($base_url));

        return $path_info;
    }
}
