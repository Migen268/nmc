<?php

class ORM
{
    private $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function insert($table, $data)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->db->query($sql, $data);
        return $this->db->lastInsertId();
    }

    public function update($table, $data, $where)
    {
        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $conditions = implode(' AND ', array_map(fn($key) => "$key = :where_$key", array_keys($where)));

        $sql = "UPDATE $table SET $set WHERE $conditions";

        $params = array_merge($data, array_combine(array_map(fn($key) => "where_$key", array_keys($where)), $where));
        $this->db->query($sql, $params);
    }

    public function delete($table, $where)
    {
        $conditions = implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($where)));
        $sql = "DELETE FROM $table WHERE $conditions";
        $this->db->query($sql, $where);
    }

    public function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }
}
?>
