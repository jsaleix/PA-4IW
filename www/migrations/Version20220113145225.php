<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113145225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE img_id_seq CASCADE');
        $this->addSql('CREATE TABLE sneaker_user (sneaker_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(sneaker_id, user_id))');
        $this->addSql('CREATE INDEX IDX_520450ACB44896C4 ON sneaker_user (sneaker_id)');
        $this->addSql('CREATE INDEX IDX_520450ACA76ED395 ON sneaker_user (user_id)');
        $this->addSql('ALTER TABLE sneaker_user ADD CONSTRAINT FK_520450ACB44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker_user ADD CONSTRAINT FK_520450ACA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE img');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE img_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE img (id INT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE sneaker_user');
    }
}
