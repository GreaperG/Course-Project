<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260107155313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_attribute_value DROP FOREIGN KEY `FK_448E6020B6E62EFA`');
        $this->addSql('ALTER TABLE item_attribute_value ADD CONSTRAINT FK_448E6020B6E62EFA FOREIGN KEY (attribute_id) REFERENCES inventory_attribute (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_attribute_value DROP FOREIGN KEY FK_448E6020B6E62EFA');
        $this->addSql('ALTER TABLE item_attribute_value ADD CONSTRAINT `FK_448E6020B6E62EFA` FOREIGN KEY (attribute_id) REFERENCES inventory_attribute (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
