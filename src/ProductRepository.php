<?php

namespace App;

class ProductRepository
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare('select * from products where id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('delete from products where id = ?');
        return $stmt->execute([$id]);
    }

    public function all()
    {
        return $this->pdo->query('select * from products')->fetchAll();
    }

    public function insert($params)
    {
        $pdo = $this->pdo;

        $fields = implode(', ', array_keys($params));
        $values = implode(', ', array_map(function ($v) use ($pdo) {
            return $pdo->quote($v);
        }, array_values($params)));
        return $pdo->exec("insert into products ($fields) values ($values)");
    }
}
