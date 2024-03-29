<?php

namespace App\Entity;

/**
 * File entity
 */
class File
{
    /**
     * @var int
     */
    private int $file_id = 0;

    /**
     * @var string
     */
    private string $title = '';

    /**
     * @var string
     */
    private string $url = '';

    /**
     * @var string
     */
    private string $ext = '';

    /**
     * @var string
     */
    private string $mime = '';

    /**
     * @var float
     */
    private float $size = 0.0;


    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */
    /**
     * @return int
     */
    public function getFileId(): int
    {
        return $this->file_id;
    }


    /**
     * @param int $fileId
     * @return void
     */
    public function setFileId(int $fileId): void
    {
        $this->file_id = $fileId;
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
    public function getSize(): float
    {
        return $this->size;
    }


    /**
     * @param float $size
     * @return void
     */
    public function setSize(float $size): void
    {
        $this->size = $size;
    }


}
