<?php
require_once("Model.php");

class Movie extends Model
{
    private string $name;
    private string $description;
    private string $genre;
    private string $release_date;
    private int $running_time; // in minutes
    private string $image;
    private string $hero_image;
    private string $language;

    protected static string $table = "movies";

    public function __construct(array $data)
    {
        $this->name = $data["name"];
        $this->description = $data["description"];
        $this->genre = $data["genre"];
        $this->release_date = $data["release_date"];
        $this->running_time = (int)$data["running_time"];
        $this->image = $data["image"];
        $this->language = $data["language"];
        $this->hero_image = $data["hero_image"];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getReleaseDate(): string
    {
        return $this->release_date;
    }

    public function getRunningTime(): int
    {
        return $this->running_time;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getHeroImage(): string
    {
        return $this->hero_image;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setGenre(string $genre)
    {
        $this->genre = $genre;
    }

    public function setReleaseDate(string $release_date)
    {
        $this->release_date = $release_date;
    }

    public function setRunningTime(int $running_time)
    {
        $this->running_time = $running_time;
    }

    public function setImage(string $image)
    {
        $this->image = $image;
    }

    public function setHeroImage(string $hero_image)
    {
        $this->hero_image = $hero_image;
    }

    public function setLanguage(string $language)
    {
        $this->language = $language;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'genre' => $this->genre,
            'release_date' => $this->release_date,
            'running_time' => $this->running_time,
            'image' => $this->image,
            'hero_image' => $this->hero_image,
            'language' => $this->language
        ];
    }
}
