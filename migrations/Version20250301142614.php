<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250301142614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE markers ADD courses_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE markers ADD CONSTRAINT FK_4189DF30F9295384 FOREIGN KEY (courses_id) REFERENCES courses (id)');
        $this->addSql('CREATE INDEX IDX_4189DF30F9295384 ON markers (courses_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE markers DROP FOREIGN KEY FK_4189DF30F9295384');
        $this->addSql('DROP INDEX IDX_4189DF30F9295384 ON markers');
        $this->addSql('ALTER TABLE markers DROP courses_id');
    }
}
