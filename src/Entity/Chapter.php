<?php

namespace Riotoon\Entity;

class Chapter
{
    private ?int $ch_id;
    private ?string $ch_num;
    private ?string $ch_path;
    private ?string $webtoon;
    private ?string $update_at;

    /**
     * Get the value of ch_id
     *
     * @return ?int
     */
    public function getId(): ?int {
        return $this->ch_id;
    }

    /**
     * Get the value of ch_num
     *
     * @return ?string
     */
    public function getNumber(): ?string {
        return $this->ch_num;
    }

    /**
     * Set the value of ch_num
     *
     * @param ?string $ch_num
     *
     * @return self
     */
    public function setNumber(?string $ch_num): self {
        $this->ch_num = $ch_num;
        return $this;
    }

    /**
     * Get the value of ch_path
     *
     * @return ?string
     */
    public function getPathView(): ?string {
        return $this->ch_path;
    }

    /**
     * Set the value of ch_path
     *
     * @param ?string $ch_path
     *
     * @return self
     */
    public function setPathView(?string $ch_path): self {
        $this->ch_path = $ch_path;
        return $this;
    }

    /**
     * Get the value of webtoon
     *
     * @return ?string
     */
    public function getWebtoon(): ?string {
        return $this->webtoon;
    }

    /**
     * Set the value of webtoon
     *
     * @param ?string $webtoon
     *
     * @return self
     */
    public function setWebtoon(?string $webtoon): self {
        $this->webtoon = $webtoon;
        return $this;
    }

    /**
     * Get the value of update_at
     *
     * @return ?string
     */
    public function getUpdateAt(): ?string {
        return $this->update_at;
    }
}
