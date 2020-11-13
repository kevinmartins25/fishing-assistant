<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113210606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE city_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE daylight_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moon_phase_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE city (id INT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE daylight (id INT NOT NULL, city_id INT NOT NULL, date DATE NOT NULL, sunrise TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sunset TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9D57C19A8BAC62AF ON daylight (city_id)');
        $this->addSql('CREATE TABLE moon_phase (id INT NOT NULL, date DATE NOT NULL, state VARCHAR(255) CHECK(state IN (\'NM\', \'WAXC\', \'FQ\', \'WAXG\', \'FM\', \'WANG\', \'LQ\', \'WANC\')) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN moon_phase.state IS \'(DC2Type:MoonStateType)\'');
        $this->addSql('ALTER TABLE daylight ADD CONSTRAINT FK_9D57C19A8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE daylight DROP CONSTRAINT FK_9D57C19A8BAC62AF');
        $this->addSql('DROP SEQUENCE city_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE daylight_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moon_phase_id_seq CASCADE');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE daylight');
        $this->addSql('DROP TABLE moon_phase');
    }
}
