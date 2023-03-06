<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306131731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caracter DROP FOREIGN KEY FK_28D5DAC499E6F5DF');
        $this->addSql('DROP INDEX IDX_28D5DAC499E6F5DF ON caracter');
        $this->addSql('ALTER TABLE caracter DROP player_id');
        $this->addSql('ALTER TABLE player ADD caracter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A657D89F04A FOREIGN KEY (caracter_id) REFERENCES caracter (id)');
        $this->addSql('CREATE INDEX IDX_98197A657D89F04A ON player (caracter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caracter ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE caracter ADD CONSTRAINT FK_28D5DAC499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_28D5DAC499E6F5DF ON caracter (player_id)');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A657D89F04A');
        $this->addSql('DROP INDEX IDX_98197A657D89F04A ON player');
        $this->addSql('ALTER TABLE player DROP caracter_id');
    }
}
