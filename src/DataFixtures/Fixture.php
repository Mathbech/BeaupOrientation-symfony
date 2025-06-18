<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture as FixtureBundle;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ObjectManager;
use RuntimeException;

class Fixture extends FixtureBundle
{
    protected string $filePath;

    public function __construct(private readonly string $dataPath) {}

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $sqlFile = file_get_contents($this->dataPath.$this->filePath);
        if (false === $sqlFile) {
            throw new RuntimeException('Fichier fixture illisible');
        }

        /** @var Connection $connection */
        $connection = $manager->getConnection();
        $connection->beginTransaction();
        try {
            $connection->executeQuery($sqlFile);
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            throw $e;
        }
        $connection->beginTransaction();

        $manager->flush();
    }
}
