<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180125175158 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE channel (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_A2F98E47A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE channels_lines (channel_id INT NOT NULL, line_id INT NOT NULL, INDEX IDX_3347099C72F5A1AA (channel_id), INDEX IDX_3347099C4D7B7542 (line_id), PRIMARY KEY(channel_id, line_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE line (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type VARCHAR(64) NOT NULL, INDEX IDX_D114B4F6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E47A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE channels_lines ADD CONSTRAINT FK_3347099C72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE channels_lines ADD CONSTRAINT FK_3347099C4D7B7542 FOREIGN KEY (line_id) REFERENCES line (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE line ADD CONSTRAINT FK_D114B4F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE channels_lines DROP FOREIGN KEY FK_3347099C72F5A1AA');
        $this->addSql('ALTER TABLE channels_lines DROP FOREIGN KEY FK_3347099C4D7B7542');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE channels_lines');
        $this->addSql('DROP TABLE line');
    }
}
