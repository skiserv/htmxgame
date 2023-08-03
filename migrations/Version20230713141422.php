<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713141422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colony_building (id INT AUTO_INCREMENT NOT NULL, colony_id INT NOT NULL, type SMALLINT NOT NULL, level INT NOT NULL, INDEX IDX_B10EA40896ADBADE (colony_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE colony_resource (id INT AUTO_INCREMENT NOT NULL, colony_id INT NOT NULL, type SMALLINT NOT NULL, stock BIGINT NOT NULL, INDEX IDX_ECF031CA96ADBADE (colony_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE colony_building ADD CONSTRAINT FK_B10EA40896ADBADE FOREIGN KEY (colony_id) REFERENCES colony (id)');
        $this->addSql('ALTER TABLE colony_resource ADD CONSTRAINT FK_ECF031CA96ADBADE FOREIGN KEY (colony_id) REFERENCES colony (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colony_building DROP FOREIGN KEY FK_B10EA40896ADBADE');
        $this->addSql('ALTER TABLE colony_resource DROP FOREIGN KEY FK_ECF031CA96ADBADE');
        $this->addSql('DROP TABLE colony_building');
        $this->addSql('DROP TABLE colony_resource');
    }
}
