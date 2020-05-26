<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrganizationRepository::class)
 */
class Organization
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $legalEntity;

    /**
     * @ORM\OneToMany(targetEntity=ChargePoint::class, mappedBy="cpo")
     */
    private $chargePoints;

    public function __construct()
    {
        $this->chargePoints = new ArrayCollection();
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

    public function getLegalEntity(): ?string
    {
        return $this->legalEntity;
    }

    public function setLegalEntity(?string $legalEntity): self
    {
        $this->legalEntity = $legalEntity;

        return $this;
    }

    /**
     * @return Collection|ChargePoint[]
     */
    public function getChargePoints(): Collection
    {
        return $this->chargePoints;
    }

    public function addChargePoint(ChargePoint $chargePoint): self
    {
        if (!$this->chargePoints->contains($chargePoint)) {
            $this->chargePoints[] = $chargePoint;
            $chargePoint->setCpo($this);
        }

        return $this;
    }

    public function removeChargePoint(ChargePoint $chargePoint): self
    {
        if ($this->chargePoints->contains($chargePoint)) {
            $this->chargePoints->removeElement($chargePoint);
            // set the owning side to null (unless already changed)
            if ($chargePoint->getCpo() === $this) {
                $chargePoint->setCpo(null);
            }
        }

        return $this;
    }
}
