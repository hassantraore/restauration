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
    private $exploitation_charge = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $financial_product = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $financial_charge = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $no_current_product = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $no_current_charge = [];

    #[ORM\Column(type: 'float')]
    private $impot;

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

    public function getImpot(): ?float
    {
        return $this->impot;
    }

    public function setImpot(float $impot): self
    {
        $this->impot = $impot;

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
}
