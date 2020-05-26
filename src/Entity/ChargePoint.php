<?php

namespace App\Entity;

use App\Repository\ChargePointRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChargePointRepository::class)
 */
class ChargePoint
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
    private $identity;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="chargePoints")
     */
    private $cpo;

    public function __construct()
    {
        $this->cpo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentity(): ?string
    {
        return $this->identity;
    }

    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function getCpo(): ?Organization
    {
        return $this->cpo;
    }

    public function setCpo(?Organization $cpo): self
    {
        $this->cpo = $cpo;

        return $this;
    }
}
