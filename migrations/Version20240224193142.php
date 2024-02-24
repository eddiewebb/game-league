<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224193142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__player_session AS SELECT id, session_id, game_role_id, score, is_winner FROM player_session');
        $this->addSql('DROP TABLE player_session');
        $this->addSql('CREATE TABLE player_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, session_id INTEGER NOT NULL, game_role_id INTEGER NOT NULL, player_id INTEGER NOT NULL, score INTEGER DEFAULT NULL, is_winner BOOLEAN DEFAULT NULL, CONSTRAINT FK_B1B02A91613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B1B02A9143C15D83 FOREIGN KEY (game_role_id) REFERENCES game_role (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B1B02A9199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO player_session (id, session_id, game_role_id, score, is_winner) SELECT id, session_id, game_role_id, score, is_winner FROM __temp__player_session');
        $this->addSql('DROP TABLE __temp__player_session');
        $this->addSql('CREATE INDEX IDX_B1B02A9143C15D83 ON player_session (game_role_id)');
        $this->addSql('CREATE INDEX IDX_B1B02A91613FECDF ON player_session (session_id)');
        $this->addSql('CREATE INDEX IDX_B1B02A9199E6F5DF ON player_session (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__player_session AS SELECT id, session_id, game_role_id, score, is_winner FROM player_session');
        $this->addSql('DROP TABLE player_session');
        $this->addSql('CREATE TABLE player_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, session_id INTEGER NOT NULL, game_role_id INTEGER NOT NULL, score INTEGER DEFAULT NULL, is_winner BOOLEAN DEFAULT NULL, CONSTRAINT FK_B1B02A91613FECDF FOREIGN KEY (session_id) REFERENCES session (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B1B02A9143C15D83 FOREIGN KEY (game_role_id) REFERENCES game_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO player_session (id, session_id, game_role_id, score, is_winner) SELECT id, session_id, game_role_id, score, is_winner FROM __temp__player_session');
        $this->addSql('DROP TABLE __temp__player_session');
        $this->addSql('CREATE INDEX IDX_B1B02A91613FECDF ON player_session (session_id)');
        $this->addSql('CREATE INDEX IDX_B1B02A9143C15D83 ON player_session (game_role_id)');
    }
}
