<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Purchase
{
    public const STATUT_ATTENTE = 'ATTENTE';
    public const STATUT_PAYER   = 'PAYE';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La saisie du nom complet  est obligatoire")
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La saisie de l'adresse  est obligatoire")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La saisie du code postal  est obligatoire")
     * @Assert\Regex(  pattern="/^\d+$/",  message="Ce champ ne doit contenir que des chiffres.")
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La saisie de la ville est obligatoire")
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut = self::STATUT_ATTENTE;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchases")
     */
    private $User;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $PuchaseAt;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseItem::class, mappedBy="Purchase", orphanRemoval=true)
     *
     */
    /**@var  $purchaseItems \App\Entity\PurchaseItem */
    private $purchaseItems;


    public function __construct()
    {
        $this->purchaseItems = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @return void
     */
    public function prePersist(){
        if(empty($this->PuchaseAt)){
            $this->PuchaseAt = new DateTimeImmutable();
        }

    }
    /**
     * @ORM\PreFlush
     * @return void
     */
    public function preFlushCaculTotal(){
        $total= 0 ;
        foreach($this->purchaseItems as $item){
            $total += $item->getTotal();
        }
        $this->setTotal( $total);
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getPuchaseAt(): ?\DateTimeImmutable
    {
        return $this->PuchaseAt;
    }

    public function setPuchaseAt(\DateTimeImmutable $PuchaseAt): self
    {

        $this->PuchaseAt = $PuchaseAt;

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
            $purchaseItem->setPurchase($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self
    {
        if ($this->purchaseItems->removeElement($purchaseItem)) {
            // set the owning side to null (unless already changed)
            if ($purchaseItem->getPurchase() === $this) {
                $purchaseItem->setPurchase(null);
            }
        }

        return $this;
    }


}
