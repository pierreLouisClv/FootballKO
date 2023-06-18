<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230618075056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_player (article_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_F2DEEDE57294869C (article_id), INDEX IDX_F2DEEDE599E6F5DF (player_id), PRIMARY KEY(article_id, player_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_player ADD CONSTRAINT FK_F2DEEDE57294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_player ADD CONSTRAINT FK_F2DEEDE599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_player DROP FOREIGN KEY FK_F2DEEDE57294869C');
        $this->addSql('ALTER TABLE article_player DROP FOREIGN KEY FK_F2DEEDE599E6F5DF');
        $this->addSql('DROP TABLE article_player');
    }
}
