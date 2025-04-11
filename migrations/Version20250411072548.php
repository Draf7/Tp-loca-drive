<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250411072548 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // Création de la table vehicle (inchangée)
        $this->addSql('CREATE TABLE vehicle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, brand VARCHAR(100) NOT NULL, model VARCHAR(100) NOT NULL, daily_price DOUBLE PRECISION NOT NULL)');
        
        // Nouvelle approche pour SQLite - modification de la table client
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, email, roles, password, first_name, last_name FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, license_obtained_at DATE NOT NULL DEFAULT CURRENT_DATE)');
        $this->addSql('INSERT INTO client (id, email, roles, password, first_name, last_name, license_obtained_at) SELECT id, email, roles, password, first_name, last_name, DATE(\'now\') FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
    }

    public function down(Schema $schema): void
    {
        // Version inchangée
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP INDEX UNIQ_C7440455E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client AS SELECT id, email, roles, password, first_name, last_name FROM client');
        $this->addSql('DROP TABLE client');
        $this->addSql('CREATE TABLE client (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO client (id, email, roles, password, first_name, last_name) SELECT id, email, roles, password, first_name, last_name FROM __temp__client');
        $this->addSql('DROP TABLE __temp__client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
    }
}