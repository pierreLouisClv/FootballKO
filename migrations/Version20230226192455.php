<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226192455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club ADD club_name VARCHAR(255) DEFAULT NULL, CHANGE team_name city_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE media ADD associated_club_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C7A50F576 FOREIGN KEY (associated_club_id) REFERENCES club (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10C7A50F576 ON media (associated_club_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club DROP club_name, CHANGE city_name team_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C7A50F576');
        $this->addSql('DROP INDEX IDX_6A2CA10C7A50F576 ON media');
        $this->addSql('ALTER TABLE media DROP associated_club_id');
    }
}
