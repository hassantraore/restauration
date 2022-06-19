<?php

namespace App\Entity;

use App\Repository\DrinkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DrinkRepository::class)]
/**
 * @Vich\Uploadable
 */
class Drink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'array', nullable: true)]
    private $size = [];

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    /**
     * @Vich\UploadableField(mapping="drink_image", fileNameProperty="imageName")
     * @Assert\NotBlank()
     *
     * @var File
     */
    private $imageFile;

    #[ORM\ManyToMany(targetEntity: Plat::class, mappedBy: 'drink')]
    private $plats;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?array
    {
        return $this->size;
    }

    public function setSize(?array $_size): self
    {
        $this->size = $_size;

        return $this;
    }

    public function addSize(Size $_size)
    {
        $exist = false;
        foreach ($this->size as $tag) {
            if ($tag->getName() == $_size->getName()) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            $this->size[] = $_size;
        }
    }

    public function removeSize(Size $delete_size): void
    {
        $tmp1 = [];
        foreach ($this->size as $size) {
            if ($size->getName() !== $delete_size->getName()) {
                $tmp1[] = $size;
            }
        }
        $this->setSize($tmp1);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): self
    {
        if (!$this->plats->contains($plat)) {
            $this->plats[] = $plat;
            $plat->addDrink($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        if ($this->plats->removeElement($plat)) {
            $plat->removeDrink($this);
        }

        return $this;
    }

    public function toArray()
    {
        $json = [
            'id' => $this->id,
            'name' => $this->name,

            'price' => $this->price,
            'size' => $this->size,

            'imageName' => $this->imageName,
        ];

        return $json;
    }
}
