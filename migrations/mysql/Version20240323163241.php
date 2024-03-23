<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240323163241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player ADD handle VARCHAR(180), ADD roles JSON COMMENT \'(DC2Type:json)\', ADD password VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(180) NOT NULL');
        $roles=json_encode(array('ROLE_USER'));
        $this->addSql('UPDATE player SET roles = \''.$roles.'\'');
        $this->addSql('UPDATE player SET password = \'changeme\'');
        $this->addSql('UPDATE player SET handle = name');
        $this->addSql('ALTER TABLE player CHANGE handle handle VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_HANDLE ON player (handle)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_HANDLE ON player');
        $this->addSql('ALTER TABLE player DROP handle, DROP roles, DROP password, CHANGE name name VARCHAR(255) NOT NULL');
    }
}
