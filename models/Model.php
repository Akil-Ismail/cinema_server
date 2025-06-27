<?php
abstract class Model
{

    protected static string $table;
    protected static string $primary_key = "id";

    public function insert(mysqli $mysqli, array $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", static::$table, $columns, $placeholders);
        $query = $mysqli->prepare($sql);
        $query->bind_param(str_repeat('s', count($values)), ...$values);
        return $query->execute();
    }
}
