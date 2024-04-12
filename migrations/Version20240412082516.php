<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412082516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE danseur_documents (id INT AUTO_INCREMENT NOT NULL, danseur_id INT DEFAULT NULL, identity VARCHAR(255) DEFAULT NULL, max_validity_identity_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', medical_certificate VARCHAR(255) DEFAULT NULL, max_validity_certificate_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', photographic_permission VARCHAR(255) DEFAULT NULL, max_validity_photographic_permission_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', UNIQUE INDEX UNIQ_62B5991A5942A4C7 (danseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE danseur_documents ADD CONSTRAINT FK_62B5991A5942A4C7 FOREIGN KEY (danseur_id) REFERENCES danseur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE danseur_documents DROP FOREIGN KEY FK_62B5991A5942A4C7');
        $this->addSql('DROP TABLE danseur_documents');
    }
}
