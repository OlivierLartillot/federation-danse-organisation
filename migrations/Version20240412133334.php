<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412133334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE danseur_documents ADD validated_for_season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE danseur_documents ADD CONSTRAINT FK_62B5991A9A18B2C8 FOREIGN KEY (validated_for_season_id) REFERENCES season (id)');
        $this->addSql('CREATE INDEX IDX_62B5991A9A18B2C8 ON danseur_documents (validated_for_season_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE danseur_documents DROP FOREIGN KEY FK_62B5991A9A18B2C8');
        $this->addSql('DROP INDEX IDX_62B5991A9A18B2C8 ON danseur_documents');
        $this->addSql('ALTER TABLE danseur_documents DROP validated_for_season_id');
    }
}
