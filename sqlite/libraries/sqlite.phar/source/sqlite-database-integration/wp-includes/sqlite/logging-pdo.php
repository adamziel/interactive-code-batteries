<?php

class LoggingPDO extends PDO
{
    private $loggedQueries = [];

    public function query(string $query, ?int $fetchMode = null, mixed...$fetchModeArgs): PDOStatement|false
    {
        $this->loggedQueries[] = [$query, null];
        return parent::query($query, $fetchMode, ...$fetchModeArgs);
    }

    public function prepare(string $query, array $options = [])
    {
        $stmt = new LoggingStatement(parent::prepare($query, $options));
        $this->loggedQueries[] = [$query, $stmt];
        return $stmt;
    }

    public function reset()
    {
        $this->loggedQueries = [];
    }

    public function getLoggedQueries()
    {
        $result = [];
        foreach ($this->loggedQueries as list($query, $stmt)) {
            $result[] = [
                'query' => $query,
                'params' => $stmt ? $stmt->getParams() : null,
            ];
        }
        return $result;
    }
}

class LoggingStatement
{

    private $stmt;
    private $params = [];

    public function __construct($stmt)
    {
        $this->stmt = $stmt;
    }

    public function execute($params = null)
    {
        if ($params) {
            $this->params = $params;
        }
        return $this->stmt->execute($params);
    }

    public function bindParam($param, &$var, $type = PDO::PARAM_STR, $maxLength = 0, $driverOptions = null)
    {
        $this->params[$param] = $var;
        return $this->stmt->bindParam($param, $var, $type, $maxLength, $driverOptions);
    }

    public function getParams()
    {
        return $this->params;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->stmt, $method], $args);
    }

    public function __get($name)
    {
        return $this->stmt->$name;
    }

    public function __set($name, $value)
    {
        $this->stmt->$name = $value;
    }
}