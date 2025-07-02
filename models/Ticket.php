<?php
require_once("Model.php");

class Ticket extends Model
{
    private int $user_id;
    private int $theater_id;
    private int $showtime_id;
    private string $seat;
    private string $row;

    protected static string $table = "tickets";

    public function __construct(array $data)
    {
        $this->user_id = $data['user_id'] ?? 0;
        $this->theater_id = $data['theater_id'] ?? 0;
        $this->showtime_id = $data['showtime_id'] ?? 0;
        $this->seat = $data['seat'] ?? 0;
        $this->row = $data['row'] ?? 0;
    }

    public function toArray()
    {
        return [
            'user_id' => $this->user_id,
            'theater_id' => $this->theater_id,
            'showtime_id' => $this->showtime_id,
            'seat' => $this->seat,
            'row' => $this->row
        ];
    }
}
