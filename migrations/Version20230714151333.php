<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230714151333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fleet (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, position_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_A05E1E4799E6F5DF (player_id), INDEX IDX_A05E1E47DD842E46 (position_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ship (id INT AUTO_INCREMENT NOT NULL, fleet_id INT NOT NULL, type SMALLINT NOT NULL, INDEX IDX_FA30EB244B061DF9 (fleet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fleet ADD CONSTRAINT FK_A05E1E4799E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE fleet ADD CONSTRAINT FK_A05E1E47DD842E46 FOREIGN KEY (position_id) REFERENCES stellar_object (id)');
        $this->addSql('ALTER TABLE ship ADD CONSTRAINT FK_FA30EB244B061DF9 FOREIGN KEY (fleet_id) REFERENCES fleet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fleet DROP FOREIGN KEY FK_A05E1E4799E6F5DF');
        $this->addSql('ALTER TABLE fleet DROP FOREIGN KEY FK_A05E1E47DD842E46');
        $this->addSql('ALTER TABLE ship DROP FOREIGN KEY FK_FA30EB244B061DF9');
        $this->addSql('DROP TABLE fleet');
        $this->addSql('DROP TABLE ship');
    }
}
