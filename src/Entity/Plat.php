<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
/**
 * @Vich\Uploadable
 */
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'array', nullable: true)]
    private $size = [];

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $promotion;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    /**
     * @Vich\UploadableField(mapping="plat_image", fileNameProperty="imageName")
     * @Assert\NotBlank()
     *
     * @var File
     */
    private $imageFile;

    #[ORM\Column(type: 'array', nullable: true)]
    private $sauce = [];

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'plats')]
    private $ingredient;

    public function __construct()
    {
        $this->ingredient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSize(): ?array
    {
        return $this->size;
    }

    public function setSize(?array $_size)
    {
        if (!empty($_size) && $_size == $this->size) {
            reset($_size);
            $_size[key($_size)] = clone current($_size);
        }
        $this->size = $_size;
    }

    public function addSize(Size $_size)
    {
        $exist = false;
        foreach ($this->size as $tag) {
            if ($tag->getSizeName() == $_size->getSizeName()) {
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
            if ($size->getSizeName() !== $delete_size->getSizeName()) {
                $tmp1[] = $size;
            }
        }
        $this->setSize($tmp1);
    }

    public function addSauce(Sauce $_sauce)
    {
        $exist = false;
        foreach ($this->sauce as $tag) {
            if ($tag->getName() == $_sauce->getName()) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            $this->sauce[] = $_sauce;
        }
    }

    public function removeSauce(Sauce $delete_sauce): void
    {
        $tmp1 = [];
        foreach ($this->sauce as $sauce) {
            if ($sauce->getName() !== $delete_sauce->getName()) {
                $tmp1[] = $sauce;
            }
        }
        $this->setSauce($tmp1);
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

    public function getPromotion(): ?bool
    {
        return $this->promotion;
    }

    public function setPromotion(?bool $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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

    public function getSauce(): ?array
    {
        return $this->sauce;
    }

    public function setSauce(?array $sauce): self
    {
        $this->sauce = $sauce;

        return $this;
    }

    public function __toString()
    {
        $json = [
            'id' => $this->id,
            'price' => $this->price,
            'size' => $this->size,
        ];

        return json_encode($json);
    }

    public function toArray()
    {
        $json = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'size' => $this->size,
            'promotion' => $this->promotion,
            'imageName' => $this->imageName,
            'category' => $this->category,
        ];

        return $json;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredient(): Collection
    {
        return $this->ingredient;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredient->contains($ingredient)) {
            $this->ingredient[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredient->removeElement($ingredient);

        return $this;
    }
}
