<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220122202250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649d60322ac');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP TABLE role');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT fk_8a8e26e9415f1f91');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT fk_8a8e26e953eab07f');
        $this->addSql('DROP INDEX idx_8a8e26e953eab07f');
        $this->addSql('DROP INDEX idx_8a8e26e9415f1f91');
        $this->addSql('ALTER TABLE conversation DROP user_a_id');
        $this->addSql('ALTER TABLE conversation DROP user_b_id');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT fk_906517446c755722');
        $this->addSql('DROP INDEX idx_906517446c755722');
        $this->addSql('ALTER TABLE invoice DROP buyer_id');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT fk_b6bd307ff675f31b');
        $this->addSql('DROP INDEX idx_b6bd307ff675f31b');
        $this->addSql('ALTER TABLE message DROP author_id');
        $this->addSql('ALTER TABLE price_proposal DROP CONSTRAINT fk_dd53af656c755722');
        $this->addSql('DROP INDEX idx_dd53af656c755722');
        $this->addSql('ALTER TABLE price_proposal DROP buyer_id');
        $this->addSql('ALTER TABLE product_appreciation DROP CONSTRAINT fk_d170fcc040c86fce');
        $this->addSql('DROP INDEX idx_d170fcc040c86fce');
        $this->addSql('ALTER TABLE product_appreciation DROP publisher_id');
        $this->addSql('ALTER TABLE product_report DROP CONSTRAINT fk_a6533620e1cfe6f5');
        $this->addSql('DROP INDEX idx_a6533620e1cfe6f5');
        $this->addSql('ALTER TABLE product_report DROP reporter_id');
        $this->addSql('ALTER TABLE sneaker DROP CONSTRAINT fk_4259b88a40c86fce');
        $this->addSql('DROP INDEX idx_4259b88a40c86fce');
        $this->addSql('ALTER TABLE sneaker DROP publisher_id');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649db396bdd');
        $this->addSql('DROP INDEX idx_8d93d649d60322ac');
        $this->addSql('DROP INDEX idx_8d93d649db396bdd');
        $this->addSql('ALTER TABLE "user" ADD email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP role_id');
        $this->addSql('ALTER TABLE "user" DROP user_reports_id');
        $this->addSql('ALTER TABLE "user" DROP name');
        $this->addSql('ALTER TABLE "user" DROP surname');
        $this->addSql('ALTER TABLE "user" DROP mail');
        $this->addSql('ALTER TABLE "user" DROP avatar');
        $this->addSql('ALTER TABLE "user" DROP address');
        $this->addSql('ALTER TABLE "user" DROP city');
        $this->addSql('ALTER TABLE "user" DROP phone');
        $this->addSql('ALTER TABLE "user" DROP join_date');
        $this->addSql('ALTER TABLE "user" DROP is_active');
        $this->addSql('ALTER TABLE "user" DROP id_stripe_connect');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE user_report DROP CONSTRAINT fk_a17d6cb9e7566e');
        $this->addSql('DROP INDEX idx_a17d6cb9e7566e');
        $this->addSql('ALTER TABLE user_report DROP reported_user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, can_sell BOOLEAN NOT NULL, is_admin BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('ALTER TABLE "user" ADD role_id INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD user_reports_id INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD name VARCHAR(110) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD surname VARCHAR(80) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD mail VARCHAR(110) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD avatar VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD city VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD phone VARCHAR(12) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD join_date DATE NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD is_active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD id_stripe_connect VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" DROP email');
        $this->addSql('ALTER TABLE "user" DROP roles');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d649d60322ac FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d649db396bdd FOREIGN KEY (user_reports_id) REFERENCES user_report (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d649d60322ac ON "user" (role_id)');
        $this->addSql('CREATE INDEX idx_8d93d649db396bdd ON "user" (user_reports_id)');
        $this->addSql('ALTER TABLE conversation ADD user_a_id INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD user_b_id INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT fk_8a8e26e9415f1f91 FOREIGN KEY (user_a_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT fk_8a8e26e953eab07f FOREIGN KEY (user_b_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8a8e26e953eab07f ON conversation (user_b_id)');
        $this->addSql('CREATE INDEX idx_8a8e26e9415f1f91 ON conversation (user_a_id)');
        $this->addSql('ALTER TABLE sneaker ADD publisher_id INT NOT NULL');
        $this->addSql('ALTER TABLE sneaker ADD CONSTRAINT fk_4259b88a40c86fce FOREIGN KEY (publisher_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_4259b88a40c86fce ON sneaker (publisher_id)');
        $this->addSql('ALTER TABLE invoice ADD buyer_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT fk_906517446c755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_906517446c755722 ON invoice (buyer_id)');
        $this->addSql('ALTER TABLE message ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT fk_b6bd307ff675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b6bd307ff675f31b ON message (author_id)');
        $this->addSql('ALTER TABLE price_proposal ADD buyer_id INT NOT NULL');
        $this->addSql('ALTER TABLE price_proposal ADD CONSTRAINT fk_dd53af656c755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_dd53af656c755722 ON price_proposal (buyer_id)');
        $this->addSql('ALTER TABLE product_appreciation ADD publisher_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_appreciation ADD CONSTRAINT fk_d170fcc040c86fce FOREIGN KEY (publisher_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d170fcc040c86fce ON product_appreciation (publisher_id)');
        $this->addSql('ALTER TABLE product_report ADD reporter_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_report ADD CONSTRAINT fk_a6533620e1cfe6f5 FOREIGN KEY (reporter_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_a6533620e1cfe6f5 ON product_report (reporter_id)');
        $this->addSql('ALTER TABLE user_report ADD reported_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_report ADD CONSTRAINT fk_a17d6cb9e7566e FOREIGN KEY (reported_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_a17d6cb9e7566e ON user_report (reported_user_id)');
    }
}
