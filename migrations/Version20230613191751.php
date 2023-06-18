<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613191751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF737294869C');
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF7361190A32');
        $this->addSql('DROP TABLE article_club');
        $this->addSql('ALTER TABLE signing DROP FOREIGN KEY FK_F052224A99E6F5DF');
        $this->addSql('DROP INDEX IDX_F052224A99E6F5DF ON signing');
        $this->addSql('ALTER TABLE signing ADD player_instance_id INT DEFAULT NULL, ADD player VARCHAR(255) DEFAULT NULL, DROP player_id');
        $this->addSql('ALTER TABLE signing ADD CONSTRAINT FK_F052224A62669B88 FOREIGN KEY (player_instance_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_F052224A62669B88 ON signing (player_instance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_club (article_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_8ACBF737294869C (article_id), INDEX IDX_8ACBF7361190A32 (club_id), PRIMARY KEY(article_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF737294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF7361190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signing DROP FOREIGN KEY FK_F052224A62669B88');
        $this->addSql('DROP INDEX IDX_F052224A62669B88 ON signing');
        $this->addSql('ALTER TABLE signing ADD player_id INT NOT NULL, DROP player_instance_id, DROP player');
        $this->addSql('ALTER TABLE signing ADD CONSTRAINT FK_F052224A99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_F052224A99E6F5DF ON signing (player_id)');
    }
}
