<?php

namespace App\Entity;

class Size
{
    private $sizeName;
    private $newPrice;
    private $oldPrice;

    public function getSizeName(): ?string
    {
        return $this->sizeName;
    }

    public function setSizeName(?string $sizeName): self
    {
        $this->sizeName = $sizeName;

        return $this;
    }

    public function getNewPrice(): ?float
    {
        return $this->newPrice;
    }

    public function setNewPrice(?float $newPrice): self
    {
        $this->newPrice = $newPrice;

        return $this;
    }

    public function getOldPrice(): ?float
    {
        return $this->oldPrice;
    }

    public function setOldPrice(?float $oldPrice): self
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    public function toArray()
    {
        $json = [
            'name' => $this->name,
            'newPrice' => $this->newPrice,
        ];

        return $json;
    }
}
