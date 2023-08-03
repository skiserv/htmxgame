<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713132437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colony (id INT AUTO_INCREMENT NOT NULL, stellar_object_id INT NOT NULL, player_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_C41C77DC2ECC4908 (stellar_object_id), INDEX IDX_C41C77DC99E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_98197A65A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE star_system (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stellar_object (id INT AUTO_INCREMENT NOT NULL, star_system_id INT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_7583924956C2D4D0 (star_system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE colony ADD CONSTRAINT FK_C41C77DC2ECC4908 FOREIGN KEY (stellar_object_id) REFERENCES stellar_object (id)');
        $this->addSql('ALTER TABLE colony ADD CONSTRAINT FK_C41C77DC99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stellar_object ADD CONSTRAINT FK_7583924956C2D4D0 FOREIGN KEY (star_system_id) REFERENCES star_system (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colony DROP FOREIGN KEY FK_C41C77DC2ECC4908');
        $this->addSql('ALTER TABLE colony DROP FOREIGN KEY FK_C41C77DC99E6F5DF');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65A76ED395');
        $this->addSql('ALTER TABLE stellar_object DROP FOREIGN KEY FK_7583924956C2D4D0');
        $this->addSql('DROP TABLE colony');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE star_system');
        $this->addSql('DROP TABLE stellar_object');
    }
}
