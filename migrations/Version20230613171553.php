<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613171553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE signing (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, left_club_instance_id INT DEFAULT NULL, joined_club_instance_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, transfer_amount INT DEFAULT NULL, season INT NOT NULL, INDEX IDX_F052224A99E6F5DF (player_id), INDEX IDX_F052224AA1B5FC93 (left_club_instance_id), INDEX IDX_F052224AD4FEF793 (joined_club_instance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE signing ADD CONSTRAINT FK_F052224A99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE signing ADD CONSTRAINT FK_F052224AA1B5FC93 FOREIGN KEY (left_club_instance_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE signing ADD CONSTRAINT FK_F052224AD4FEF793 FOREIGN KEY (joined_club_instance_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF7361190A32');
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF737294869C');
        $this->addSql('DROP TABLE article_club');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_club (article_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_8ACBF7361190A32 (club_id), INDEX IDX_8ACBF737294869C (article_id), PRIMARY KEY(article_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF7361190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF737294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE signing DROP FOREIGN KEY FK_F052224A99E6F5DF');
        $this->addSql('ALTER TABLE signing DROP FOREIGN KEY FK_F052224AA1B5FC93');
        $this->addSql('ALTER TABLE signing DROP FOREIGN KEY FK_F052224AD4FEF793');
        $this->addSql('DROP TABLE signing');
    }
}
