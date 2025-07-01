<?php
require_once("Model.php");

class Pricing extends Model
{
    private int $id;
    private float $regular;
    private float $vip;
    private int $type_id;
    private string $type;
    protected static string $table = "pricing";

    public function __construct(array $data)
    {
        $this->regular = isset($data["regular"]) ? (float)$data["regular"] : 0.0;
        $this->vip = isset($data["vip"]) ? (float)$data["vip"] : 0.0;
        $this->type_id = isset($data["type_id"]) ? (int)$data["type_id"] : 0;
        $this->type = isset($data["type"]) ? (string)$data["type"] : "";
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'regular' => $this->regular,
            'vip' => $this->vip,
            'type_id' => $this->type_id
        ];
    }
}
