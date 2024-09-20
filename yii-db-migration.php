<?php

declare(strict_types=1);

use Yiisoft\Cache\ArrayCache;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Pgsql\Connection;
use Yiisoft\Db\Pgsql\Driver;

return [
    /**
     * Database connection instance. For example, MySQL connection:
     *
     * ```php
     * 'db' => new \Yiisoft\Db\Mysql\Connection(
     *     new \Yiisoft\Db\Mysql\Driver('mysql:host=mysql;dbname=mydb', 'user', 'q1w2e3r4'),
     *     new \Yiisoft\Db\Cache\SchemaCache(new \Yiisoft\Cache\ArrayCache()),
     * ),
     * ```
     *
     * @see https://github.com/yiisoft/db/blob/master/docs/en/README.md#create-connection
     */
    'db' => new Connection(
          new Driver('pgsql:host=delivery_db;dbname=postgres', 'delivery', 'delivery'),
          new SchemaCache(new ArrayCache()),
     ),
    /**
     * Namespace of new migration classes.
     */
    'newMigrationNamespace' => '',

    /**
     * List of namespaces containing the migration classes.
     */
    'sourceNamespaces' => [
        'Infrastructure\Adapters\Postgres\Migrations',
    ],

    /**
     * Path to the directory for new migration classes. This path is used when you are using migrations without
     * namespaces.
     */
    'newMigrationPath' => __DIR__ . '/Infrastructure/Adapters/Postgres/Migrations',

    /**
     * List of directories containing the migration classes.
     * Migration classes located at these paths should be declared without a namespace.
     * Use the "sourceNamespaces" option in case you are using namespaced migrations.
     */
    'sourcePaths' => [],

    /**
     * The name of the database table for storing migration history information.
     */
    'historyTable' => '{{%migration}}',

    /**
     * The maximum length of a migration name.
     */
    'migrationNameLimit' => 180,

    /**
     * Indicates whether the table names generated should consider the `tablePrefix` setting of the DB connection.
     * For example, if the table name is `post`, the generator will return `{{%post}}`.
     */
    'useTablePrefix' => true,

    /**
     * PSR-11 compatible DI container that used for automatic dependencies resolving when creating migration instances.
     */
    'container' => null,

    /**
     * The maximum length of an SQL output in console.
     */
    'maxSqlOutputLength' => null,
];
