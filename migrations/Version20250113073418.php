<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113073418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parcours_markers (parcours_id INT NOT NULL, markers_id INT NOT NULL, INDEX IDX_CEE2E1666E38C0DB (parcours_id), INDEX IDX_CEE2E166D0EEC2B5 (markers_id), PRIMARY KEY(parcours_id, markers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parcours_markers ADD CONSTRAINT FK_CEE2E1666E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parcours_markers ADD CONSTRAINT FK_CEE2E166D0EEC2B5 FOREIGN KEY (markers_id) REFERENCES markers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parcours_markers DROP FOREIGN KEY FK_CEE2E1666E38C0DB');
        $this->addSql('ALTER TABLE parcours_markers DROP FOREIGN KEY FK_CEE2E166D0EEC2B5');
        $this->addSql('DROP TABLE parcours_markers');
    }
}
