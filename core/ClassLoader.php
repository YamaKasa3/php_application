<?php

class ClassLoader
{
    protected $dirs;

    public function regster()
    {
        /*
        SPL The Standard PHP Library
        ファイルを読み込んだときに、そのファイルがないときのエラーに対処するために、
        自分自身のloadClass()メソッドを登録する
        */
        spl_autoload_register(array($this, 'loadClass'));
    }

    public function registerDir($dir)
    {
        $this->dirs[] = $dir; // array_push
    }

    public function loadClass($class)
    {
        foreach ($this->dirs as $dir) {
            $file = $dir . '/' . $class . '.php';
            if (is_readable($file)) {
                require $file;
                return;
            }
        }
    }
}
