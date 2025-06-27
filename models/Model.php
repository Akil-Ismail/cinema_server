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

    public function select(mysqli $mysqli, array $data, array $orConditions = [], array $andConditions = [])
    {
        $columns = implode(", ", array_keys($data));

        // Build WHERE clause
        $whereParts = [];
        if (!empty($andConditions)) {
            $whereParts[] = '(' . implode(' AND ', $andConditions) . ')';
        }
        if (!empty($orConditions)) {
            $whereParts[] = '(' . implode(' OR ', $orConditions) . ')';
        }
        $conditions = !empty($whereParts) ? implode(' AND ', $whereParts) : '1';

        if (empty($conditions)) {
            $conditions = '1'; // Default condition to select all
        }

        $sql = sprintf("SELECT %s FROM %s WHERE %s", $columns, static::$table, $conditions);

        $query = $mysqli->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $query->close();
        return $rows;
    }
}
