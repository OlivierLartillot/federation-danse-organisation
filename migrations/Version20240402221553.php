<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402221553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription_championnat (id INT AUTO_INCREMENT NOT NULL, championnat_id INT NOT NULL, licence_id INT DEFAULT NULL, INDEX IDX_FDC95FF0627A0DA8 (championnat_id), INDEX IDX_FDC95FF026EF07C9 (licence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscription_championnat ADD CONSTRAINT FK_FDC95FF0627A0DA8 FOREIGN KEY (championnat_id) REFERENCES championship (id)');
        $this->addSql('ALTER TABLE inscription_championnat ADD CONSTRAINT FK_FDC95FF026EF07C9 FOREIGN KEY (licence_id) REFERENCES licence (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription_championnat DROP FOREIGN KEY FK_FDC95FF0627A0DA8');
        $this->addSql('ALTER TABLE inscription_championnat DROP FOREIGN KEY FK_FDC95FF026EF07C9');
        $this->addSql('DROP TABLE inscription_championnat');
    }
}
