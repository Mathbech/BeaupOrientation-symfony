<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250301104300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C6E38C0DB');
        $this->addSql('ALTER TABLE parcours DROP FOREIGN KEY FK_99B1DEE3A76ED395');
        $this->addSql('ALTER TABLE parcours_markers DROP FOREIGN KEY FK_CEE2E1666E38C0DB');
        $this->addSql('ALTER TABLE parcours_markers DROP FOREIGN KEY FK_CEE2E166D0EEC2B5');
        $this->addSql('DROP TABLE parcours');
        $this->addSql('DROP TABLE parcours_markers');
        $this->addSql('DROP INDEX IDX_A9A55A4C6E38C0DB ON courses');
        $this->addSql('ALTER TABLE courses DROP parcours_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parcours (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_99B1DEE3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE parcours_markers (parcours_id INT NOT NULL, markers_id INT NOT NULL, INDEX IDX_CEE2E166D0EEC2B5 (markers_id), INDEX IDX_CEE2E1666E38C0DB (parcours_id), PRIMARY KEY(parcours_id, markers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE parcours ADD CONSTRAINT FK_99B1DEE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE parcours_markers ADD CONSTRAINT FK_CEE2E1666E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parcours_markers ADD CONSTRAINT FK_CEE2E166D0EEC2B5 FOREIGN KEY (markers_id) REFERENCES markers (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses ADD parcours_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C6E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A9A55A4C6E38C0DB ON courses (parcours_id)');
    }
}
