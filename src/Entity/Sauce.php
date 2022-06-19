<?php

namespace App\Entity;

class Sauce
{
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $size): self
    {
        $this->name = $size;

        return $this;
    }

    public function toArray()
    {
        $json = [
            'name' => $this->name,
        ];

        return $json;
    }
}
