#!/usr/bin/env php
<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.02.15
 * Time: 0:47
 */

Class App
{
    protected $pdo;

    public function start()
    {
        $this->connect();
        $this->readAndSave();
    }

    protected function connect()
    {
        require_once('config.php');

        $dsn = sprintf('mysql:dbname=%s;host=%s', $config['dbname'], $config['host']);

        try {
            $this->pdo = new \PDO($dsn, $config['user'], $config['password']);
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        }
    }

    protected function readAndSave()
    {
        $file = realpath(__DIR__) . DIRECTORY_SEPARATOR . '10-million-combos.txt';

        if (!file_exists($file)) {
            echo 'Error: file 10-million-combos.txt not exists' . PHP_EOL;
            exit(1);
        }

        $f = fopen($file, 'r');
        $i = 0;
        while (($buffer = fgets($f)) !== false && $i < 10) {
            echo $buffer;
            $i++;
        }
        fclose($f);
    }
}

$app = new App();
$app->start();
