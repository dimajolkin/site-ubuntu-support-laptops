<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191221233803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE hardware_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE hardware_image (id INT NOT NULL, hardware_id INT NOT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, height INT NOT NULL, width INT NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_397A61BC9CC762B ON hardware_image (hardware_id)');
        $this->addSql('ALTER TABLE hardware_image ADD CONSTRAINT FK_397A61BC9CC762B FOREIGN KEY (hardware_id) REFERENCES hardware (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX uniq_64c19c15e237e06');
        $this->addSql('DROP INDEX uniq_f52233f65e237e06');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE hardware_image_id_seq CASCADE');
        $this->addSql('DROP TABLE hardware_image');
        $this->addSql('CREATE UNIQUE INDEX uniq_f52233f65e237e06 ON vendor (name)');
        $this->addSql('CREATE UNIQUE INDEX uniq_64c19c15e237e06 ON category (name)');
    }
}
