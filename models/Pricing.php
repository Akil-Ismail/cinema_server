<?php
require_once("Model.php");

class Pricing extends Model
{
    private int $id;
    private float $standard;
    private float $vip;
    private int $type_id;

    protected static string $table = "pricing";

    public function __construct(array $data)
    {
        $this->standard = isset($data["standard"]) ? (float)$data["standard"] : 0.0;
        $this->vip = isset($data["vip"]) ? (float)$data["vip"] : 0.0;
        $this->type_id = isset($data["type_id"]) ? (int)$data["type_id"] : 0;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'standard' => $this->standard,
            'vip' => $this->vip,
            'type_id' => $this->type_id
        ];
    }
}
