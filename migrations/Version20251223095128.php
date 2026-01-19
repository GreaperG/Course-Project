<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223095128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory ADD version INT DEFAULT 1 NOT NULL, DROP item, DROP custom_field');
        $this->addSql('ALTER TABLE item ADD version INT DEFAULT 1 NOT NULL, ADD created_at VARCHAR(255) NOT NULL, ADD updated_at VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory ADD item VARCHAR(255) NOT NULL, ADD custom_field VARCHAR(255) NOT NULL, DROP version');
        $this->addSql('ALTER TABLE item DROP version, DROP created_at, DROP updated_at');
    }
}
