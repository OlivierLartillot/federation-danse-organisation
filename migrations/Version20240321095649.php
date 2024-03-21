<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321095649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, min_age SMALLINT NOT NULL, max_age SMALLINT NOT NULL, nb_min SMALLINT NOT NULL, nb_max SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE championship (id INT AUTO_INCREMENT NOT NULL, season_id INT NOT NULL, championship_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', championship_inscriptions_limit_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', place VARCHAR(255) NOT NULL, number SMALLINT NOT NULL, INDEX IDX_EBADDE6A4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, correspondents LONGTEXT DEFAULT NULL, adress LONGTEXT DEFAULT NULL, phone_primary VARCHAR(10) DEFAULT NULL, phone_secondary VARCHAR(10) DEFAULT NULL, email_primary VARCHAR(255) DEFAULT NULL, email_secondary VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, web_site VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization_team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, organization_role VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(15) NOT NULL, is_current_season TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE championship ADD CONSTRAINT FK_EBADDE6A4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE championship DROP FOREIGN KEY FK_EBADDE6A4EC001D1');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE championship');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE organization_team');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
