<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329093456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE licence (id INT AUTO_INCREMENT NOT NULL, club_id INT NOT NULL, category_id INT NOT NULL, season_id INT NOT NULL, dossard INT NOT NULL, status SMALLINT DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, INDEX IDX_1DAAE64861190A32 (club_id), INDEX IDX_1DAAE64812469DE2 (category_id), INDEX IDX_1DAAE6484EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE licence_danseur (licence_id INT NOT NULL, danseur_id INT NOT NULL, INDEX IDX_125DF3F226EF07C9 (licence_id), INDEX IDX_125DF3F25942A4C7 (danseur_id), PRIMARY KEY(licence_id, danseur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE licence_comment (id INT AUTO_INCREMENT NOT NULL, licence_id INT DEFAULT NULL, INDEX IDX_5AC398DF26EF07C9 (licence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE licence ADD CONSTRAINT FK_1DAAE64861190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE licence ADD CONSTRAINT FK_1DAAE64812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE licence ADD CONSTRAINT FK_1DAAE6484EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE licence_danseur ADD CONSTRAINT FK_125DF3F226EF07C9 FOREIGN KEY (licence_id) REFERENCES licence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE licence_danseur ADD CONSTRAINT FK_125DF3F25942A4C7 FOREIGN KEY (danseur_id) REFERENCES danseur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE licence_comment ADD CONSTRAINT FK_5AC398DF26EF07C9 FOREIGN KEY (licence_id) REFERENCES licence (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licence DROP FOREIGN KEY FK_1DAAE64861190A32');
        $this->addSql('ALTER TABLE licence DROP FOREIGN KEY FK_1DAAE64812469DE2');
        $this->addSql('ALTER TABLE licence DROP FOREIGN KEY FK_1DAAE6484EC001D1');
        $this->addSql('ALTER TABLE licence_danseur DROP FOREIGN KEY FK_125DF3F226EF07C9');
        $this->addSql('ALTER TABLE licence_danseur DROP FOREIGN KEY FK_125DF3F25942A4C7');
        $this->addSql('ALTER TABLE licence_comment DROP FOREIGN KEY FK_5AC398DF26EF07C9');
        $this->addSql('DROP TABLE licence');
        $this->addSql('DROP TABLE licence_danseur');
        $this->addSql('DROP TABLE licence_comment');
    }
}
