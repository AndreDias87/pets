<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250829155830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create breed table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE breed (id SERIAL NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, is_dangerous BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F8AF884FC54C8C93 ON breed (type_id)');
        $this->addSql('ALTER TABLE breed ADD CONSTRAINT FK_F8AF884FC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE breed DROP CONSTRAINT FK_F8AF884FC54C8C93');
        $this->addSql('DROP TABLE breed');
    }
}
