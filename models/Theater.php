<?php
require_once("Model.php");

class Theater extends Model
{
    private int $id;
    private int $standard_rows_count;
    private int $columns_count;
    private int $vip_rows_count;

    protected static string $table = "theaters";

    public function __construct(array $data)
    {
        $this->id = isset($data["id"]) ? (int)$data["id"] : 0;
        $this->standard_rows_count = isset($data["standard_rows_count"]) ? (int)$data["standard_rows_count"] : 0;
        $this->columns_count = isset($data["columns_count"]) ? (int)$data["columns_count"] : 0;
        $this->vip_rows_count = isset($data["vip_rows_count"]) ? (int)$data["vip_rows_count"] : 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRowsCount(): int
    {
        return $this->standard_rows_count;
    }

    public function getColumnsCount(): int
    {
        return $this->columns_count;
    }

    public function getVipRowsCount(): int
    {
        return $this->vip_rows_count;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setRowsCount(int $standard_rows_count)
    {
        $this->standard_rows_count = $standard_rows_count;
    }

    public function setColumnsCount(int $columns_count)
    {
        $this->columns_count = $columns_count;
    }

    public function setVipRowsCount(int $vip_rows_count)
    {
        $this->vip_rows_count = $vip_rows_count;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'standard_rows_count' => $this->standard_rows_count,
            'columns_count' => $this->columns_count,
            'vip_rows_count' => $this->vip_rows_count
        ];
    }
}
