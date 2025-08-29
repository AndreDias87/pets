<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250829164832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds type, breed and sex columns to pet table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pet ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pet ADD breed_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pet ADD sex VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B85C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B85A8B4A30F FOREIGN KEY (breed_id) REFERENCES breed (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E4529B85C54C8C93 ON pet (type_id)');
        $this->addSql('CREATE INDEX IDX_E4529B85A8B4A30F ON pet (breed_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pet DROP CONSTRAINT FK_E4529B85C54C8C93');
        $this->addSql('ALTER TABLE pet DROP CONSTRAINT FK_E4529B85A8B4A30F');
        $this->addSql('DROP INDEX IDX_E4529B85C54C8C93');
        $this->addSql('DROP INDEX IDX_E4529B85A8B4A30F');
        $this->addSql('ALTER TABLE pet DROP type_id');
        $this->addSql('ALTER TABLE pet DROP breed_id');
        $this->addSql('ALTER TABLE pet DROP sex');
    }
}
