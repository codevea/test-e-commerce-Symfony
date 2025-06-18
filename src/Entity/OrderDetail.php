<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
class OrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $myOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $productName = null;

    #[ORM\Column(length: 255)]
    private ?string $productIllustration = null;

    #[ORM\Column]
    private ?int $productQuantity = null;

    #[ORM\Column]
    private ?int $productPrice = null;

    #[ORM\Column]
    private ?int $productTva = null;

    // Calcul du prix du produit HT
    public function getProductOrderDetailPriceHt()
    { 
        return round(($this->getProductPrice()  / 100) - ( $this->getProductTva()  / 100));
    }

    // Calcul T.T.C du produit * par la quantity
    public function getTotalProductOrderDetailTtcQuantity()
    {
        $coef = 1 + ($this->getProductTva() / 100);
        return  (($this->getProductPrice()/ 100) * $coef) * $this->getProductQuantity();
    }

    // Calcul d'un produit T.T.C
    public function getProductOrderDetailTtc()
    {
        $coef = 1 + ($this->getProductTva() / 100);
        return  ($this->getProductPrice() * $coef);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMyOrder(): ?Order
    {
        return $this->myOrder;
    }

    public function setMyOrder(?Order $myOrder): static
    {
        $this->myOrder = $myOrder;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductIllustration(): ?string
    {
        return $this->productIllustration;
    }

    public function setProductIllustration(string $productIllustration): static
    {
        $this->productIllustration = $productIllustration;

        return $this;
    }

    public function getProductQuantity(): ?int
    {
        return $this->productQuantity;
    }

    public function setProductQuantity(int $productQuantity): static
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    public function getProductPrice(): ?int
    {
        return $this->productPrice;
    }

    public function setProductPrice(int $productPrice): static
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductTva(): ?int
    {
        return $this->productTva;
    }

    public function setProductTva(int $productTva): static
    {
        $this->productTva = $productTva;

        return $this;
    }
}
