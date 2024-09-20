<?php
declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require __DIR__ . '/vendor/autoload.php';

Type::addType('uuid', \Ramsey\Uuid\Doctrine\UuidType::class);

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/src'],
    isDevMode: true,
);

// configuring the database connection
$connection = DriverManager::getConnection([
    'driver' => 'pdo_pgsql',
    'dbname' => 'postgres',
    'host' => 'delivery_db',
    'user' => 'delivery',
    'password' => 'delivery',
    'path' => __DIR__ . '/db.sqlite',
], $config);

// obtaining the entity manager
$entityManager = new EntityManager($connection, $config);


$productRepository = $entityManager->getRepository(\Infrastructure\Adapters\Postgres\Models\CourierModel::class);
$products = $productRepository->findAll();
var_dump($products);