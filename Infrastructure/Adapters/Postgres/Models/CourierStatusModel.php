<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Postgres\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'courier_statuses')]
class CourierStatusModel
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;
}