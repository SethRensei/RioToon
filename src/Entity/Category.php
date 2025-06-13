<?php

namespace Riotoon\Entity;

class Category
{
    protected int $c_id;
    protected ?string $label;
    private ?int $webtoon_count;

    /**
     * Get the value of c_id
     *
     * @return int
     */
    public function getId(): int {
        return $this->c_id;
    }

    /**
     * Get the value of label
     *
     * @return ?string
     */
    public function getName(): ?string {
        return $this->label;
    }

    /**
     * Get the value of webtoon_count
     *
     * @return ?int
     */
    public function getWebtoonCount(): ?int {
        return $this->webtoon_count;
    }
}
