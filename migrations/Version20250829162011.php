<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250829162011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert initial dog and cat breeds into breed table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Dog breeds (type_id = 1)
        $this->addSql("
            INSERT INTO breed (type_id, name, is_dangerous) VALUES
            (1, 'Labrador Retriever', false),
            (1, 'German Shepherd', true),
            (1, 'Rottweiler', true),
            (1, 'Golden Retriever', false),
            (1, 'Bulldog', false),
            (1, 'Beagle', false),
            (1, 'Doberman Pinscher', true),
            (1, 'Poodle', false),
            (1, 'Boxer', false),
            (1, 'Dachshund', false),
            (1, 'Siberian Husky', false),
            (1, 'Great Dane', true)
        ");

        // Cat breeds (type_id = 2)
        $this->addSql("
            INSERT INTO breed (type_id, name, is_dangerous) VALUES
            (2, 'Persian', false),
            (2, 'Siamese', false),
            (2, 'Maine Coon', false),
            (2, 'Bengal', false),
            (2, 'Sphynx', false),
            (2, 'Ragdoll', false),
            (2, 'British Shorthair', false),
            (2, 'Scottish Fold', false),
            (2, 'Abyssinian', false),
            (2, 'Russian Blue', false),
            (2, 'Norwegian Forest', false),
            (2, 'Savannah', false)
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('TRUNCATE TABLE breed');
    }
}
