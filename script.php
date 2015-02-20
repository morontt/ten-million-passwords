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
        $arr = [];
        while (($buffer = fscanf($f, "%s %s")) !== false) {
            // detect non-ASCII
            if (!preg_match('/[^\x20-\x7f]/', $buffer[1])) {
                $arr[] = $buffer[1];
            }
            $i++;

            if ($i == 500) {
                $this->save($arr);
                $i = 0;
                $arr = [];
            }
        }
        fclose($f);

        $this->save($arr);
    }

    protected function save(array $data)
    {
        $sql = "INSERT IGNORE INTO `pass` (`password`, `hash_md5`) VALUES ";

        $values = [];
        foreach ($data as $item) {
            $values[] = "('{$item}', MD5('{$item}'))";
        }
        $sql .= implode(',', $values);

        $sth = $this->pdo->prepare($sql);
        $sth->execute();
    }
}

$app = new App();
$app->start();
