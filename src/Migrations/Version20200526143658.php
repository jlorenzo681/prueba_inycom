<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526143658 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE charge_point ADD cpo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE charge_point ADD CONSTRAINT FK_A181FF0C3C35C7EF FOREIGN KEY (cpo_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_A181FF0C3C35C7EF ON charge_point (cpo_id)');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C3C35C7EF');
        $this->addSql('DROP INDEX IDX_C1EE637C3C35C7EF ON organization');
        $this->addSql('ALTER TABLE organization DROP cpo_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE charge_point DROP FOREIGN KEY FK_A181FF0C3C35C7EF');
        $this->addSql('DROP INDEX IDX_A181FF0C3C35C7EF ON charge_point');
        $this->addSql('ALTER TABLE charge_point DROP cpo_id');
        $this->addSql('ALTER TABLE organization ADD cpo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C3C35C7EF FOREIGN KEY (cpo_id) REFERENCES charge_point (id)');
        $this->addSql('CREATE INDEX IDX_C1EE637C3C35C7EF ON organization (cpo_id)');
    }
}
