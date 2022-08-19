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
}
