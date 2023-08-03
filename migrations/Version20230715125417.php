<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230715125417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fleet ADD destination_id INT DEFAULT NULL, ADD action_end DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE fleet ADD CONSTRAINT FK_A05E1E47816C6140 FOREIGN KEY (destination_id) REFERENCES stellar_object (id)');
        $this->addSql('CREATE INDEX IDX_A05E1E47816C6140 ON fleet (destination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fleet DROP FOREIGN KEY FK_A05E1E47816C6140');
        $this->addSql('DROP INDEX IDX_A05E1E47816C6140 ON fleet');
        $this->addSql('ALTER TABLE fleet DROP destination_id, DROP action_end');
    }
}
