<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321101753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE caracter ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE caracter ADD CONSTRAINT FK_28D5DAC4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_28D5DAC4A76ED395 ON caracter (user_id)');
        $this->addSql('ALTER TABLE player ADD links LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caracter DROP FOREIGN KEY FK_28D5DAC4A76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_28D5DAC4A76ED395 ON caracter');
        $this->addSql('ALTER TABLE caracter DROP user_id');
        $this->addSql('ALTER TABLE player DROP links');
    }
}
