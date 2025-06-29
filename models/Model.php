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

    public function select(
        mysqli $mysqli,
        array $data,
        array $orKeys = [],
        array $orParams = [],
        array $andKeys = [],
        array $andParams = []
    ) {
        $columns = implode(", ", array_keys($data));

        // Build WHERE clause
        $whereParts = [];
        if (!empty($orKeys)) {
            $orConditions = [];
            foreach ($orKeys as $key) {
                $orConditions[] = "$key = ?";
            }
            $whereParts[] = '(' . implode(' OR ', $orConditions) . ')';
        }
        if (!empty($andKeys)) {
            $andConditions = [];
            foreach ($andKeys as $key) {
                $andConditions[] = "$key = ?";
            }
            $whereParts[] = '(' . implode(' AND ', $andConditions) . ')';
        }
        $conditions = !empty($whereParts) ? implode(' AND ', $whereParts) : '1';

        $sql = sprintf("SELECT %s FROM %s WHERE %s", $columns, static::$table, $conditions);

        $query = $mysqli->prepare($sql);

        // Merge params in the order of appearance in the WHERE clause
        $params = array_merge($orParams, $andParams);
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $query->bind_param($types, ...$params);
        }

        $query->execute();
        $result = $query->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $query->close();
        return $rows;
    }
    public function innerSelect(
        mysqli $mysqli,
        array $data,
        array $orKeys = [],
        array $orParams = [],
        array $andKeys = [],
        array $andParams = [],
        string $join = '' // Optional JOIN clause
    ) {
        $columns = implode(", ", array_keys($data));

        // Build WHERE clause
        $whereParts = [];
        if (!empty($orKeys)) {
            $orConditions = [];
            foreach ($orKeys as $key) {
                $orConditions[] = "$key = ?";
            }
            $whereParts[] = '(' . implode(' OR ', $orConditions) . ')';
        }
        if (!empty($andKeys)) {
            $andConditions = [];
            foreach ($andKeys as $key) {
                $andConditions[] = "$key = ?";
            }
            $whereParts[] = '(' . implode(' AND ', $andConditions) . ')';
        }
        $conditions = !empty($whereParts) ? implode(' AND ', $whereParts) : '1';

        // Add JOIN clause if provided
        $joinClause = $join ? " $join " : "";

        $sql = sprintf(
            "SELECT %s FROM %s %s WHERE %s",
            $columns,
            static::$table,
            $joinClause,
            $conditions
        );

        $query = $mysqli->prepare($sql);

        // Merge params in the order of appearance in the WHERE clause
        $params = array_merge($orParams, $andParams);
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $query->bind_param($types, ...$params);
        }

        $query->execute();
        $result = $query->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $query->close();
        return $rows;
    }
    public function update(
        mysqli $mysqli,
        array $data,
        string $id
    ) {
        // Build SET clause
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "$key = ?";
        }
        $setClause = implode(", ", $setParts);

        // Combine all params (first set values, then where values)
        $params = array_merge(array_values($data));

        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = %s",
            static::$table,
            $setClause,
            $id
        );

        $query = $mysqli->prepare($sql);
        if (!$query) {
            throw new Exception("Prepare failed: " . $mysqli->error);
        }

        $types = str_repeat('s', count($params));
        $query->bind_param($types, ...$params);

        $result = $query->execute();
        $query->close();
        return $result;
    }

    public function delete(
        mysqli $mysqli,
        string $id
    ) {
        $sql = sprintf("DELETE FROM %s WHERE id = ?", static::$table);
        $query = $mysqli->prepare($sql);
        if (!$query) {
            throw new Exception("Prepare failed: " . $mysqli->error);
        }
        $query->bind_param('i', $id);
        $result = $query->execute();
        $query->close();
        return $result;
    }
}
