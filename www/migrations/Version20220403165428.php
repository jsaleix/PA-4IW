<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220403165428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP CONSTRAINT fk_b6bd307f72f5a1aa');
        $this->addSql('DROP SEQUENCE channel_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE messages_id_seq CASCADE');
        $this->addSql('DROP TABLE channel');
        $this->addSql('ALTER TABLE invoice ADD tracking_nb VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD reception_address VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX idx_b6bd307f72f5a1aa');
        $this->addSql('ALTER TABLE message DROP channel_id');
        $this->addSql('ALTER TABLE product_report ADD reporter_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_report ADD CONSTRAINT FK_A6533620E1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A6533620E1CFE6F5 ON product_report (reporter_id)');
        $this->addSql('ALTER TABLE "user" ADD is_verified BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE channel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE channel (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE invoice DROP tracking_nb');
        $this->addSql('ALTER TABLE invoice DROP reception_address');
        $this->addSql('ALTER TABLE product_report DROP CONSTRAINT FK_A6533620E1CFE6F5');
        $this->addSql('DROP INDEX IDX_A6533620E1CFE6F5');
        $this->addSql('ALTER TABLE product_report DROP reporter_id');
        $this->addSql('ALTER TABLE "user" DROP is_verified');
        $this->addSql('ALTER TABLE message ADD channel_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT fk_b6bd307f72f5a1aa FOREIGN KEY (channel_id) REFERENCES channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b6bd307f72f5a1aa ON message (channel_id)');
    }
}
