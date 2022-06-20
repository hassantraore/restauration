<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'array')]
    private $exploitation_products = [];

    #[ORM\Column(type: 'array')]
    private $exploitation_charge = [];

    #[ORM\Column(type: 'float')]
    private $exploitation_result;

    #[ORM\Column(type: 'array', nullable: true)]
    private $financial_product = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $financial_charge = [];

    #[ORM\Column(type: 'float', nullable: true)]
    private $financial_result;

    #[ORM\Column(type: 'float')]
    private $current_result;

    #[ORM\Column(type: 'array', nullable: true)]
    private $no_current_product = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $no_current_charge = [];

    #[ORM\Column(type: 'float', nullable: true)]
    private $no_current_result;

    #[ORM\Column(type: 'float')]
    private $result_before_impot;

    #[ORM\Column(type: 'float')]
    private $impot;

    #[ORM\Column(type: 'float')]
    private $result_net;

    #[ORM\ManyToOne(targetEntity: Month::class, inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private $month;

    #[ORM\ManyToOne(targetEntity: Year::class, inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private $year;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExploitationCharge(): ?array
    {
        return $this->exploitation_charge;
    }

    public function setExploitationCharge(array $exploitation_charge): self
    {
        $this->exploitation_charge = $exploitation_charge;

        return $this;
    }

    public function addExploitationCharge(Depense $depense)
    {
        $exist = false;
        foreach ($this->exploitation_charge as $tag) {
            if ($tag->getLabel() == $depense->getLabel()) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            $this->exploitation_charge[] = $depense;
        }
    }

    public function removeExploitationCharge(Depense $depense): void
    {
        $tmp1 = [];
        foreach ($this->exploitation_charge as $tag) {
            if ($tag->getLabel() !== $depense->getLabel()) {
                $tmp1[] = $tag;
            }
        }
        $this->setExploitationCharge($tmp1);
    }

    public function getExploitationResult(): ?float
    {
        return $this->exploitation_result;
    }

    public function setExploitationResult(float $exploitation_result): self
    {
        $this->exploitation_result = $exploitation_result;

        return $this;
    }

    public function getFinancialProduct(): ?array
    {
        return $this->financial_product;
    }

    public function setFinancialProduct(?array $financial_product): self
    {
        $this->financial_product = $financial_product;

        return $this;
    }

    public function addFinancialProduct(Depense $depense)
    {
        $exist = false;
        foreach ($this->financial_product as $tag) {
            if ($tag->getLabel() == $depense->getLabel()) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            $this->financial_product[] = $depense;
        }
    }

    public function removeFinancialProduct(Depense $depense): void
    {
        $tmp1 = [];
        foreach ($this->financial_product as $tag) {
            if ($tag->getLabel() !== $depense->getLabel()) {
                $tmp1[] = $tag;
            }
        }
        $this->setFinancialProduct($tmp1);
    }

    public function getFinancialCharge(): ?array
    {
        return $this->financial_charge;
    }

    public function setFinancialCharge(?array $financial_charge): self
    {
        $this->financial_charge = $financial_charge;

        return $this;
    }

    public function addFinancialCharge(Depense $depense)
    {
        $exist = false;
        foreach ($this->financial_charge as $tag) {
            if ($tag->getLabel() == $depense->getLabel()) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            $this->financial_charge[] = $depense;
        }
    }

    public function removeFinancialCharge(Depense $depense): void
    {
        $tmp1 = [];
        foreach ($this->financial_charge as $tag) {
            if ($tag->getLabel() !== $depense->getLabel()) {
                $tmp1[] = $tag;
            }
        }
        $this->setFinancialCharge($tmp1);
    }

    public function getFinancialResult(): ?float
    {
        return $this->financial_result;
    }

    public function setFinancialResult(?float $financial_result): self
    {
        $this->financial_result = $financial_result;

        return $this;
    }

    public function getCurrentResult(): ?float
    {
        return $this->current_result;
    }

    public function setCurrentResult(float $current_result): self
    {
        $this->current_result = $current_result;

        return $this;
    }

    public function getNoCurrentProduct(): ?array
    {
        return $this->no_current_product;
    }

    public function setNoCurrentProduct(?array $no_current_product): self
    {
        $this->no_current_product = $no_current_product;

        return $this;
    }

    public function addNoCurrentProduct(Depense $depense)
    {
        $exist = false;
        foreach ($this->no_current_product as $tag) {
            if ($tag->getLabel() == $depense->getLabel()) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            $this->no_current_product[] = $depense;
        }
    }

    public function removeNoCurrentProduct(Depense $depense): void
    {
        $tmp1 = [];
        foreach ($this->no_current_product as $tag) {
            if ($tag->getLabel() !== $depense->getLabel()) {
                $tmp1[] = $tag;
            }
        }
        $this->setNoCurrentProduct($tmp1);
    }

    public function getNoCurrentCharge(): ?array
    {
        return $this->no_current_charge;
    }

    public function setNoCurrentCharge(?array $no_current_charge): self
    {
        $this->no_current_charge = $no_current_charge;

        return $this;
    }

    public function addNoCurrentCharge(Depense $depense)
    {
        $exist = false;
        foreach ($this->no_current_charge as $tag) {
            if ($tag->getLabel() == $depense->getLabel()) {
                $exist = true;
                break;
            }
        }
        if (!$exist) {
            $this->no_current_charge[] = $depense;
        }
    }

    public function removeNoCurrentCharge(Depense $depense): void
    {
        $tmp1 = [];
        foreach ($this->no_current_charge as $tag) {
            if ($tag->getLabel() !== $depense->getLabel()) {
                $tmp1[] = $tag;
            }
        }
        $this->setNoCurrentCharge($tmp1);
    }

    public function getNoCurrentResult(): ?float
    {
        return $this->no_current_result;
    }

    public function setNoCurrentResult(?float $no_current_result): self
    {
        $this->no_current_result = $no_current_result;

        return $this;
    }

    public function getResultBeforeImpot(): ?float
    {
        return $this->result_before_impot;
    }

    public function setResultBeforeImpot(float $result_before_impot): self
    {
        $this->result_before_impot = $result_before_impot;

        return $this;
    }

    public function getImpot(): ?float
    {
        return $this->impot;
    }

    public function setImpot(float $impot): self
    {
        $this->impot = $impot;

        return $this;
    }

    public function getResultNet(): ?float
    {
        return $this->result_net;
    }

    public function setResultNet(float $result_net): self
    {
        $this->result_net = $result_net;

        return $this;
    }

    public function getMonth(): ?Month
    {
        return $this->month;
    }

    public function setMonth(?Month $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?Year
    {
        return $this->year;
    }

    public function setYear(?Year $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getExploitationProducts(): ?array
    {
        return $this->exploitation_products;
    }

    public function setExploitationProducts(array $exploitation_products): self
    {
        $this->exploitation_products = $exploitation_products;

        return $this;
    }
}
