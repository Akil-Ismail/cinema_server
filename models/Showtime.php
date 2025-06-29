<?php
require_once("Model.php");

class Showtime extends Model
{
    private int $id;
    private string $time;
    private string $date;
    private int $movie_id;
    private int $type_id;
    private string $type;
    private int $theater_id;

    protected static string $table = "showtimes";

    public function __construct(array $data)
    {
        $this->time = $data["time"] ?? "";
        $this->date = $data["date"] ?? "";
        $this->movie_id = isset($data["movie_id"]) ? (int)$data["movie_id"] : 0;
        $this->type_id = isset($data["type_id"]) ? (int)$data["type_id"] : 0;
        $this->type = $data["type"] ?? "";
        $this->theater_id = isset($data["theater_id"]) ? (int)$data["theater_id"] : 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getMovieId(): int
    {
        return $this->movie_id;
    }

    public function getTypeId(): int
    {
        return $this->type_id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTheaterId(): int
    {
        return $this->theater_id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setTime(string $time)
    {
        $this->time = $time;
    }

    public function setDate(string $date)
    {
        $this->date = $date;
    }

    public function setMovieId(int $movie_id)
    {
        $this->movie_id = $movie_id;
    }

    public function setTypeId(int $type_id)
    {
        $this->type_id = $type_id;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function setTheaterId(int $theater_id)
    {
        $this->theater_id = $theater_id;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'time' => $this->time,
            'date' => $this->date,
            'movie_id' => $this->movie_id,
            'type_id' => $this->type_id,
            'type' => $this->type,
            'theater_id' => $this->theater_id
        ];
    }
}
