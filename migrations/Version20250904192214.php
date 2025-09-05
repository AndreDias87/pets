<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250904192214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make unknown and mix type agnostic';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DELETE FROM breed WHERE name='Unknown' OR name='Mix'
        ");
        $this->addSql("
            INSERT INTO breed (type_id, name, is_dangerous) VALUES
            (null, 'Unknown', false),
            (null, 'Mix', true)
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql("
            INSERT INTO breed (type_id, name, is_dangerous) VALUES
            (1, 'Unknown', false),
            (1, 'Mix', true)
        ");

        $this->addSql("
            INSERT INTO breed (type_id, name, is_dangerous) VALUES
            (2, 'Unknown', false),
            (2, 'Mix', false)
        ");
    }
}
