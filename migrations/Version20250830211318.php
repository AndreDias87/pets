<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250830211318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert unknown and mix breeds for both dogs and cats';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
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

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql("
            DELETE FROM breed WHERE name='Unknown' OR name='Mix'
        ");
    }
}
