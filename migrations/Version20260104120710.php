<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260104120710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_attribute DROP FOREIGN KEY `FK_7DC606939EEA759`');
        $this->addSql('ALTER TABLE inventory_attribute ADD CONSTRAINT FK_7DC606939EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY `FK_1F1B251E9EEA759`');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E9EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_attribute_value DROP FOREIGN KEY `FK_448E6020126F525E`');
        $this->addSql('ALTER TABLE item_attribute_value ADD CONSTRAINT FK_448E6020126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_attribute DROP FOREIGN KEY FK_7DC606939EEA759');
        $this->addSql('ALTER TABLE inventory_attribute ADD CONSTRAINT `FK_7DC606939EEA759` FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E9EEA759');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT `FK_1F1B251E9EEA759` FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item_attribute_value DROP FOREIGN KEY FK_448E6020126F525E');
        $this->addSql('ALTER TABLE item_attribute_value ADD CONSTRAINT `FK_448E6020126F525E` FOREIGN KEY (item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
