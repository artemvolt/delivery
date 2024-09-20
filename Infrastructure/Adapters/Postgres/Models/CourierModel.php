<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Postgres\Models;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity]
#[ORM\Table(name: 'couriers')]
class CourierModel
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $uuid;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $transport_id;

    #[ORM\Column(type: 'integer')]
    private int $location_x;

    #[ORM\Column(type: 'integer')]
    private int $location_y;

    #[ORM\Column(type: 'integer')]
    private int $status_id;

    #[ORM\OneToOne(targetEntity: CourierStatusModel::class)]
    private CourierStatusModel|null $status;

    #[ORM\OneToOne(targetEntity: TransportModel::class)]
    private TransportModel|null $transport;
}