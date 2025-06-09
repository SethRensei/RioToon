<?php

namespace Riotoon\Entity;

class Category
{
    protected int $c_id;
    protected ?string $label;

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
}
