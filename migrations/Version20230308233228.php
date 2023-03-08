<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308233228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE banque_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE historique_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE operation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_operation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE banque (id INT NOT NULL, compte_courant NUMERIC(10, 2) NOT NULL, livret_a NUMERIC(10, 2) DEFAULT NULL, epargne NUMERIC(10, 2) DEFAULT NULL, ticket_restaurant NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE historique (id INT NOT NULL, montant NUMERIC(10, 2) DEFAULT NULL, mois VARCHAR(30) DEFAULT NULL, annee INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE operation (id INT NOT NULL, type_id INT NOT NULL, montant NUMERIC(10, 2) NOT NULL, detail VARCHAR(255) DEFAULT NULL, date_operation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1981A66DC54C8C93 ON operation (type_id)');
        $this->addSql('CREATE TABLE type_operation (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, recurrence VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DC54C8C93 FOREIGN KEY (type_id) REFERENCES type_operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA banque');
        $this->addSql('DROP SEQUENCE banque_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE historique_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE operation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_operation_id_seq CASCADE');
        $this->addSql('ALTER TABLE operation DROP CONSTRAINT FK_1981A66DC54C8C93');
        $this->addSql('DROP TABLE banque');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE type_operation');
    }
}
