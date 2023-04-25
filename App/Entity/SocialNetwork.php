<?php

namespace App\Entity;

/**
 * Represents a social network (id, title, image, link, order).
 * @package App\Entity
 * @Entity @Table(name="social_network")
 */
class SocialNetwork
{
    private int $snId;
    private string $title;
    private string $imgUrl;
    private string $linkUrl;
    private int $snOrder;


    /**
     * ----------------------------------------------------------------------------------------------- Getters & Setters
     */
    /**
     * @return int
     */
    public function getSnId(): int
    {
        return $this->snId;
    }

    /**
     * @param int $snId
     * @return SocialNetwork
     */
    public function setSnId(int $snId): SocialNetwork
    {
        $this->snId = $snId;
        return $this;
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
     * @return SocialNetwork
     */
    public function setTitle(string $title): SocialNetwork
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    /**
     * @param string $imgUrl
     * @return SocialNetwork
     */
    public function setImgUrl(string $imgUrl): SocialNetwork
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getLinkUrl(): string
    {
        return $this->linkUrl;
    }

    /**
     * @param string $linkUrl
     * @return SocialNetwork
     */
    public function setLinkUrl(string $linkUrl): SocialNetwork
    {
        $this->linkUrl = $linkUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getSnOrder(): int
    {
        return $this->snOrder;
    }

    /**
     * @param int $snOrder
     * @return SocialNetwork
     */
    public function setSnOrder(int $snOrder): SocialNetwork
    {
        $this->snOrder = $snOrder;
        return $this;
    }
}
