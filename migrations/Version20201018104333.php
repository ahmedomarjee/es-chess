<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201018104333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE persitent_event_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE persistent_event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE persistent_event (id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, aggregate_id VARCHAR(255) NOT NULL, payload JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A824E6CDD0BBCCBE ON persistent_event (aggregate_id)');
        $this->addSql('COMMENT ON COLUMN persistent_event.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP TABLE persitent_event');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE persistent_event_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE persitent_event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE persitent_event (id INT NOT NULL, type VARCHAR(255) NOT NULL, aggregate_id VARCHAR(255) NOT NULL, payload JSON NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN persitent_event.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP TABLE persistent_event');
    }
}
