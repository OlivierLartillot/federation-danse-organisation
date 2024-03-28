<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328132353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE clubs_sql_fdo');
        $this->addSql('ALTER TABLE user ADD last_connection DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clubs_sql_fdo (ID INT UNSIGNED AUTO_INCREMENT NOT NULL, Club VARCHAR(60) CHARACTER SET latin1 DEFAULT \'\' NOT NULL COLLATE `latin1_swedish_ci`, Responsable VARCHAR(60) CHARACTER SET latin1 DEFAULT \'\' NOT NULL COLLATE `latin1_swedish_ci`, Adresse VARCHAR(200) CHARACTER SET latin1 DEFAULT \'\' NOT NULL COLLATE `latin1_swedish_ci`, Telephone VARCHAR(20) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, Telephone2 VARCHAR(20) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, Fax VARCHAR(20) CHARACTER SET latin1 DEFAULT \'sans\' COLLATE `latin1_swedish_ci`, Email VARCHAR(60) CHARACTER SET latin1 DEFAULT \'\' NOT NULL COLLATE `latin1_swedish_ci`, Email2 VARCHAR(60) CHARACTER SET latin1 DEFAULT \'\' NOT NULL COLLATE `latin1_swedish_ci`, Internet VARCHAR(80) CHARACTER SET latin1 DEFAULT \'\' NOT NULL COLLATE `latin1_swedish_ci`, PRIMARY KEY(ID)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user DROP last_connection');
    }
}
