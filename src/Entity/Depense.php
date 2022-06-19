<?php

namespace App\Entity;

class Depense
{
    private $label;
    private $mount;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getMount(): ?float
    {
        return $this->mount;
    }

    public function setMount(?float $mount): self
    {
        $this->mount = $mount;

        return $this;
    }

    public function toArray()
    {
        $json = [
            'label' => $this->label,
            'mount' => $this->mount,
        ];

        return $json;
    }
}
