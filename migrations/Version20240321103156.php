<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321103156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE championship ADD organizing_club_id INT NOT NULL');
        $this->addSql('ALTER TABLE championship ADD CONSTRAINT FK_EBADDE6A55C13187 FOREIGN KEY (organizing_club_id) REFERENCES club (id)');
        $this->addSql('CREATE INDEX IDX_EBADDE6A55C13187 ON championship (organizing_club_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE championship DROP FOREIGN KEY FK_EBADDE6A55C13187');
        $this->addSql('DROP INDEX IDX_EBADDE6A55C13187 ON championship');
        $this->addSql('ALTER TABLE championship DROP organizing_club_id');
    }
}
