<?php
require_once("Model.php");

class Showtime extends Model
{
    private int $id;
    private string $time;
    private string $date;
    private int $movie_id;

    protected static string $table = "showtimes";

    public function __construct(array $data)
    {
        $this->id = isset($data["id"]) ? (int)$data["id"] : 0;
        $this->time = $data["time"] ?? "";
        $this->date = $data["date"] ?? "";
        $this->movie_id = isset($data["movie_id"]) ? (int)$data["movie_id"] : 0;
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

    public function toArray()
    {
        return [
            'id' => $this->id,
            'time' => $this->time,
            'date' => $this->date,
            'movie_id' => $this->movie_id
        ];
    }
}
