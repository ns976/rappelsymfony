<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    use OutilsEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom de doit pas être vide")
     * @Assert\Length(min=3, max=255, minMessage="Le nom doit faire au moins {{ limit }} caractères", maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères")
     * @Assert\Length( max=10, maxMessage="Le nom ne doit pas dépasser {{ limit }} caractères",groups={"large_name"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le prix  de doit pas être vide")
     * @Assert\GreaterThan(message="Le prix doit être plus grand que {{ value }}", value=0)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description  de doit pas être vide")
     * @Assert\LessThan(value=10,message="La description  doit faie au moins {{ value }} caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url(message="La photo doit être une URL valide")
     * @Assert\NotBlank(message="La photo  ne  doit pas être vide")
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     */
    private $userCreate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="La quantité est obligatoire")
     * @Assert\Regex(pattern="/^\d+$/" , message="Le champ doit contenir uniquement des chiffres.")
     */
    private $quantite;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseItem::class, mappedBy="Product")
     */
    private $purchaseItems;



    public function __construct()
    {
        $this->purchaseItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getuppercasename():string{
        return strtoupper($this->getName());
    }

    public function getName(): ?string
    {
        return $this->name;
    }



    public function getPrice(): ?int
    {
        return $this->price/100;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getUserCreate(): ?User
    {
        return $this->userCreate;
    }

    public function setUserCreate(?User $userCreate): self
    {
        $this->userCreate = $userCreate;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseItem>
     */
    public function getPurchaseItems(): Collection
    {
        return $this->purchaseItems;
    }

    public function addPurchaseItem(PurchaseItem $purchaseItem): self
    {
        if (!$this->purchaseItems->contains($purchaseItem)) {
            $this->purchaseItems[] = $purchaseItem;
            $purchaseItem->setProduct($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self
    {
        if ($this->purchaseItems->removeElement($purchaseItem)) {
            // set the owning side to null (unless already changed)
            if ($purchaseItem->getProduct() === $this) {
                $purchaseItem->setProduct(null);
            }
        }

        return $this;
    }


}
