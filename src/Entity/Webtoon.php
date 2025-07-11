<?php

namespace Riotoon\Entity;

use Riotoon\Service\BuildErrors;

class Webtoon
{
    private ?int $w_id;
    private ?string $title;
    private ?string $author;
    private ?string $synopsis;
    private ?string $cover;
    private ?int $release_year;
    private ?bool $status;
    private ?int $likes;
    private ?int $dislikes;
    private ?string $update_at;
    private $c_ids;
    private $categories;

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->w_id;
    }

    /**
     * Get the value of title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = clean(ucwords($title));

        return $this;
    }

    /**
     * Get the value of author
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @param string $author
     *
     * @return self
     */
    public function setAuthor(string $author): self
    {
        $this->author = clean(ucwords($author));

        return $this;
    }


    /**
     * Get the value of likes
     *
     * @return int|null
     */
    public function getLikes(): ?int
    {
        return $this->likes;
    }
    
    /**
     * Get the value of synopsis
     *
     * @return ?string
     */
    public function getSynopsis(): ?string {
        return $this->synopsis;
    }

    /**
     * Set the value of synopsis
     *
     * @param ?string $synopsis
     *
     * @return self
     */
    public function setSynopsis(?string $synopsis): self {
        $synopsis = nl2br(clean($synopsis));
        if (!strlen($synopsis >= 10))
           BuildErrors::setErrors('synopsis', 'Le synopsis doit contenir au moins 10 caractères');
        $this->synopsis = $synopsis;
        return $this;
    }

    /**
     * Get the value of cover
     *
     * @return ?string
     */
    public function getCover(): ?string {
        return $this->cover;
    }

    /**
     * Set the value of cover
     *
     * @param ?string $cover
     *
     * @return self
     */
    public function setCover(?string $cover): self {
        if ($cover == '')
            BuildErrors::setErrors('image', 'Impossible de charger une image vide');
        $this->cover = $cover;
        return $this;
    }

    /**
     * Get the value of release_year
     *
     * @return ?int
     */
    public function getReleaseYear(): ?int {
        return $this->release_year;
    }

    /**
     * Set the value of release_year
     *
     * @param ?int $release_year
     *
     * @return self
     */
    public function setReleaseYear(?int $release_year): self {
        if (!($release_year >= 1995 && $release_year <= date('Y')))
            BuildErrors::setErrors('year', 'Date comprise entre 1995 - ' . date('Y'));
        $this->release_year = $release_year;
        return $this;
    }
    
    /**
     * Get the value of status
     *
     * @return ?bool
     */
    public function getStatus(): ?bool {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param ?bool $status
     *
     * @return self
     */
    public function setStatus(string $st): self {
        $this->status = $st == "TERMINE" ? true : false;
        return $this;
    }

    /**
     * Set the value of likes
     *
     * @param ?int $likes
     *
     * @return self
     */
    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get the value of dislikes
     *
     * @return int|null
     */
    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }

    /**
     * Set the value of dislikes
     *
     * @param int $dislikes
     *
     * @return self
     */
    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    /**
     * Get the value of update_at
     */
    public function getUpdatedAt()
    {
        return $this->update_at;
    }

    /**
     * Get the value of categories
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Get the value of c_ids
     */
    public function getIDCategories() {
        return $this->c_ids;
    }
}
