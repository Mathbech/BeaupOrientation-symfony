<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241007090114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE markers ADD teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE markers ADD CONSTRAINT FK_4189DF3041807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4189DF3041807E1D ON markers (teacher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE markers DROP FOREIGN KEY FK_4189DF3041807E1D');
        $this->addSql('DROP INDEX IDX_4189DF3041807E1D ON markers');
        $this->addSql('ALTER TABLE markers DROP teacher_id');
    }
}
