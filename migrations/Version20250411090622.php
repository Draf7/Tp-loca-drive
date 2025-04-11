<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411090622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C7440455E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, email, roles, password, first_name, last_name, license_obtained_at FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, license_obtained_at DATE NOT NULL)');
        $this->addSql('INSERT INTO client (id, email, roles, password, first_name, last_name, license_obtained_at) SELECT id, email, roles, password, first_name, last_name, license_obtained_at FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__vehicle AS SELECT id, brand, model, daily_price FROM vehicle');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('CREATE TABLE vehicle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, daily_price DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO vehicle (id, brand, model, daily_price) SELECT id, brand, model, daily_price FROM __temp__vehicle');
        $this->addSql('DROP TABLE __temp__vehicle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C7440455E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, email, roles, password, first_name, last_name, license_obtained_at FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, license_obtained_at DATE DEFAULT CURRENT_DATE NOT NULL)');
        $this->addSql('INSERT INTO client (id, email, roles, password, first_name, last_name, license_obtained_at) SELECT id, email, roles, password, first_name, last_name, license_obtained_at FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__vehicle AS SELECT id, brand, model, daily_price FROM vehicle');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('CREATE TABLE vehicle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, brand VARCHAR(100) NOT NULL, model VARCHAR(100) NOT NULL, daily_price DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO vehicle (id, brand, model, daily_price) SELECT id, brand, model, daily_price FROM __temp__vehicle');
        $this->addSql('DROP TABLE __temp__vehicle');
    }
}
