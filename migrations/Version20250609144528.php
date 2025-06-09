<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250609144528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE log_scan (id INT AUTO_INCREMENT NOT NULL, runner_id INT DEFAULT NULL, marker_id INT DEFAULT NULL, scanned_at DATETIME NOT NULL, point POINT DEFAULT NULL COMMENT \'(DC2Type:geometry_point)\', INDEX IDX_D5DD5DDB3C7FB593 (runner_id), INDEX IDX_D5DD5DDB474460EB (marker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE log_scan ADD CONSTRAINT FK_D5DD5DDB3C7FB593 FOREIGN KEY (runner_id) REFERENCES runners (id)');
        $this->addSql('ALTER TABLE log_scan ADD CONSTRAINT FK_D5DD5DDB474460EB FOREIGN KEY (marker_id) REFERENCES markers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log_scan DROP FOREIGN KEY FK_D5DD5DDB3C7FB593');
        $this->addSql('ALTER TABLE log_scan DROP FOREIGN KEY FK_D5DD5DDB474460EB');
        $this->addSql('DROP TABLE log_scan');
    }
}
