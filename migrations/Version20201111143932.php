<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111143932 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE daylight_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moon_phase_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE daylight (id INT NOT NULL, date DATE NOT NULL, sunrise TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sunset TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TYPE moon_state AS ENUM (\'New Moon\', \'Waxing Crescent\', \'First Quarter\', \'Waxing Gibbous\', \'Full Moon\', \'Waning Gibbous\', \'Last Quarter\', \'Waning Crescent\')');
        $this->addSql('CREATE TABLE moon_phase (id INT NOT NULL, date DATE NOT NULL, state moon_state)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE daylight_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moon_phase_id_seq CASCADE');
        $this->addSql('DROP TABLE daylight');
        $this->addSql('DROP TABLE moon_phase');
    }
}
