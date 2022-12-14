<?php
namespace Services;

use Psr\Container\ContainerInterface;


class DatabaseService extends BaseService
{
    protected $connection;
    protected $host;
    protected $port;
    protected $db_name;
    protected $user;
    protected $password;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->host = $_ENV['DB_HOST'];
        $this->port = $_ENV['DB_PORT'];
        $this->db_name = $_ENV['DB_DATABASE'];
        $this->user = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    public function connect()
    {
        if (!$this->connection) {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;port=%s;charset=utf8mb4",
                $this->host, $this->db_name, $this->port
            );
            $this->connection = new \PDO($dsn, $this->user, $this->password);
        }
        return $this->connection;
    }

    public function fetch($table, array $conditions, array $fields=['*'])
    {
        $stmt = $this->select($table, $conditions, $fields, 1);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function find($table, array $conditions, array $fields=['*'], array $order=[], $limit=null)
    {
        $stmt = $this->select($table, $conditions, $fields, $limit, $order);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function insert($table, array $data, $fetch=true)
    {
        $fields = array_keys($data);
        $values = array_map(fn($field): string => ":$field", $fields);
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)',
                        $table, implode(', ', $fields), implode(', ', $values)
        );
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($data);
        $id = $this->connect()->lastInsertId();
        if ($fetch)
            return $this->fetch($table, ['id' => $id]);
        return $id;
    }

    public function select($table, array $conditions = [], array $fields=['*'], $limit=null, array $order=[])
    {
        $sql = $this->buildConditions(
            sprintf('SELECT %s FROM %s ', implode(', ', $fields), $table),
            $conditions, $limit, $order
        );
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($conditions);
        return $stmt;
    }

    public function update($table, array $data, array $conditions = [], $limit=null)
    {
        $fields = array_map(fn($field): string => "$field = :{$field}_value", array_keys($data));
        $sql = $this->buildConditions(
            sprintf('UPDATE %s SET %s ', $table, implode(', ', $fields)),
            $conditions, $limit
        );
        $values = [];
        array_walk($data, function($item, $key) use (&$values) {
            $values[$key . "_value"] = $item;
        });
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(array_merge($values, $conditions));
    }

    public function call($name, $data=[])
    {
        if (func_num_args() != 2)
            return false;
        $fields = array_keys($data);
        $values = array_map(fn($field): string => ":$field", $fields);
        $sql = 'CALL ' . $name . '('.implode(', ', $values).')';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($data);
        return $stmt;
    }

    protected function buildConditions(string $sql, array $conditions = [], $limit=null, array $order=[])
    {
        if (count($conditions) > 0) {
            $where = array_map(fn($field): string => "$field = :$field", array_keys($conditions));
            $sql .= 'WHERE ' . implode(' AND', $where) . ' ';
        }
        if (isset($order[0]))
            $sql .= 'ORDER BY '. implode(',', $order) . ' ';
        if ($limit)
            $sql .= 'LIMIT '.$limit;
        return $sql;
    }
}
