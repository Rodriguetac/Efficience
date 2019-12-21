<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191221153408 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mail ADD departement_id INT NOT NULL');
        $this->addSql('ALTER TABLE mail ADD CONSTRAINT FK_5126AC48CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('CREATE INDEX IDX_5126AC48CCF9E01E ON mail (departement_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mail DROP FOREIGN KEY FK_5126AC48CCF9E01E');
        $this->addSql('DROP INDEX IDX_5126AC48CCF9E01E ON mail');
        $this->addSql('ALTER TABLE mail DROP departement_id');
    }
}
