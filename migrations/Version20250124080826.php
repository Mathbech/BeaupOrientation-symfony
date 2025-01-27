<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124080826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE runners ADD teacher_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE runners ADD CONSTRAINT FK_DA96F92B2EBB220A FOREIGN KEY (teacher_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DA96F92B2EBB220A ON runners (teacher_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE runners DROP FOREIGN KEY FK_DA96F92B2EBB220A');
        $this->addSql('DROP INDEX IDX_DA96F92B2EBB220A ON runners');
        $this->addSql('ALTER TABLE runners DROP teacher_id_id');
    }
}
