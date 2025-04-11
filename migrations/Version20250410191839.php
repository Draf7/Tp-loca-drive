<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410191839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, brand VARCHAR(100) NOT NULL, model VARCHAR(100) NOT NULL, daily_price DOUBLE PRECISION NOT NULL)');
        
        // Modification pour SQLite - Ajout en 2 étapes
        $this->addSql('ALTER TABLE client ADD COLUMN license_obtained_at DATE DEFAULT NULL');
        $this->addSql('UPDATE client SET license_obtained_at = DATE(\'now\') WHERE license_obtained_at IS NULL');
       //  $this->addSql('ALTER TABLE client CHANGE COLUMN license_obtained_at license_obtained_at DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, email, roles, password, first_name, last_name FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO client (id, email, roles, password, first_name, last_name) SELECT id, email, roles, password, first_name, last_name FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
    }
}