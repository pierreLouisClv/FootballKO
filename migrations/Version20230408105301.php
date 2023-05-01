<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408105301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF7361190A32');
        $this->addSql('ALTER TABLE article_club DROP FOREIGN KEY FK_8ACBF737294869C');
        $this->addSql('DROP TABLE article_club');
        $this->addSql('ALTER TABLE player ADD info_id INT DEFAULT NULL, ADD day_return INT DEFAULT NULL, ADD date_of_return_is_exact TINYINT(1) DEFAULT NULL, DROP downtime, DROP progress');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A655D8BC1F8 FOREIGN KEY (info_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_98197A655D8BC1F8 ON player (info_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_club (article_id INT NOT NULL, club_id INT NOT NULL, INDEX IDX_8ACBF737294869C (article_id), INDEX IDX_8ACBF7361190A32 (club_id), PRIMARY KEY(article_id, club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF7361190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_club ADD CONSTRAINT FK_8ACBF737294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A655D8BC1F8');
        $this->addSql('DROP INDEX IDX_98197A655D8BC1F8 ON player');
        $this->addSql('ALTER TABLE player ADD downtime VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD progress VARCHAR(255) DEFAULT NULL, DROP info_id, DROP day_return, DROP date_of_return_is_exact');
    }
}
