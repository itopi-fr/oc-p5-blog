<?php

namespace App\Entity;

class File
{
    private int $id;
    private string $title;
    private string $url;
    private string $ext;
    private string $mime;
    private float $weight_kb;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getExt(): string
    {
        return $this->ext;
    }

    /**
     * @param string $ext
     * @return void
     */
    public function setExt(string $ext): void
    {
        $this->ext = $ext;
    }

    /**
     * @return string
     */
    public function getMime(): string
    {
        return $this->mime;
    }

    /**
     * @param string $mime
     * @return void
     */
    public function setMime(string $mime): void
    {
        $this->mime = $mime;
    }

    /**
     * @return float
     */
    public function getWeightKb(): float
    {
        return $this->weight_kb;
    }

    /**
     * @param float $weight_kb
     * @return void
     */
    public function setWeightKb(float $weight_kb): void
    {
        $this->weight_kb = $weight_kb;
    }

    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */


}