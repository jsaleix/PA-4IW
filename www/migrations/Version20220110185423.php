<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110185423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE brand_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE color_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE img_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invoice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE material_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE price_proposal_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_appreciation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE report_reason_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sneaker_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sneaker_model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE brand (id INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(150) NOT NULL, description TEXT DEFAULT NULL, main_category BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE color (id INT NOT NULL, name VARCHAR(80) NOT NULL, description TEXT DEFAULT NULL, hexa_value VARCHAR(7) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE conversation (id INT NOT NULL, user_a_id INT NOT NULL, user_b_id INT NOT NULL, is_active BOOLEAN NOT NULL, create_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A8E26E9415F1F91 ON conversation (user_a_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E953EAB07F ON conversation (user_b_id)');
        $this->addSql('CREATE TABLE img (id INT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE invoice (id INT NOT NULL, sneaker_id INT NOT NULL, buyer_id INT NOT NULL, payment_status VARCHAR(40) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_90651744B44896C4 ON invoice (sneaker_id)');
        $this->addSql('CREATE INDEX IDX_906517446C755722 ON invoice (buyer_id)');
        $this->addSql('CREATE TABLE material (id INT NOT NULL, name VARCHAR(80) NOT NULL, description TEXT DEFAULT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE message (id INT NOT NULL, author_id INT NOT NULL, conversation_id INT NOT NULL, content TEXT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307FF675F31B ON message (author_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F9AC0396 ON message (conversation_id)');
        $this->addSql('CREATE TABLE price_proposal (id INT NOT NULL, product_id INT NOT NULL, buyer_id INT NOT NULL, status VARCHAR(100) NOT NULL, acceptation_date DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DD53AF654584665A ON price_proposal (product_id)');
        $this->addSql('CREATE INDEX IDX_DD53AF656C755722 ON price_proposal (buyer_id)');
        $this->addSql('CREATE TABLE product_appreciation (id INT NOT NULL, publisher_id INT NOT NULL, product_id INT NOT NULL, mark INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D170FCC040C86FCE ON product_appreciation (publisher_id)');
        $this->addSql('CREATE INDEX IDX_D170FCC04584665A ON product_appreciation (product_id)');
        $this->addSql('CREATE TABLE product_report (id INT NOT NULL, reason_id INT NOT NULL, product_id INT NOT NULL, reporter_id INT NOT NULL, status VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A653362059BB1592 ON product_report (reason_id)');
        $this->addSql('CREATE INDEX IDX_A65336204584665A ON product_report (product_id)');
        $this->addSql('CREATE INDEX IDX_A6533620E1CFE6F5 ON product_report (reporter_id)');
        $this->addSql('CREATE TABLE report_reason (id INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, type VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, can_sell BOOLEAN NOT NULL, is_admin BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sneaker (id INT NOT NULL, brand_id INT NOT NULL, sneaker_model_id INT DEFAULT NULL, publisher_id INT NOT NULL, name VARCHAR(255) NOT NULL, size DOUBLE PRECISION NOT NULL, description TEXT DEFAULT NULL, publication_date TIME(0) WITHOUT TIME ZONE NOT NULL, unused BOOLEAN NOT NULL, from_shop BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4259B88A44F5D008 ON sneaker (brand_id)');
        $this->addSql('CREATE INDEX IDX_4259B88A5500B247 ON sneaker (sneaker_model_id)');
        $this->addSql('CREATE INDEX IDX_4259B88A40C86FCE ON sneaker (publisher_id)');
        $this->addSql('CREATE TABLE sneaker_category (sneaker_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(sneaker_id, category_id))');
        $this->addSql('CREATE INDEX IDX_2A391C61B44896C4 ON sneaker_category (sneaker_id)');
        $this->addSql('CREATE INDEX IDX_2A391C6112469DE2 ON sneaker_category (category_id)');
        $this->addSql('CREATE TABLE sneaker_color (sneaker_id INT NOT NULL, color_id INT NOT NULL, PRIMARY KEY(sneaker_id, color_id))');
        $this->addSql('CREATE INDEX IDX_B6E9C998B44896C4 ON sneaker_color (sneaker_id)');
        $this->addSql('CREATE INDEX IDX_B6E9C9987ADA1FB5 ON sneaker_color (color_id)');
        $this->addSql('CREATE TABLE sneaker_material (sneaker_id INT NOT NULL, material_id INT NOT NULL, PRIMARY KEY(sneaker_id, material_id))');
        $this->addSql('CREATE INDEX IDX_50CB7035B44896C4 ON sneaker_material (sneaker_id)');
        $this->addSql('CREATE INDEX IDX_50CB7035E308AC6F ON sneaker_material (material_id)');
        $this->addSql('CREATE TABLE sneaker_model (id INT NOT NULL, brand_id INT NOT NULL, name VARCHAR(100) NOT NULL, description TEXT DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_72AF3A844F5D008 ON sneaker_model (brand_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, role_id INT NOT NULL, user_reports_id INT NOT NULL, name VARCHAR(110) NOT NULL, surname VARCHAR(80) NOT NULL, mail VARCHAR(110) NOT NULL, password VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, phone VARCHAR(12) DEFAULT NULL, join_date DATE NOT NULL, is_active BOOLEAN NOT NULL, id_stripe_connect VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D93D649D60322AC ON "user" (role_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DB396BDD ON "user" (user_reports_id)');
        $this->addSql('CREATE TABLE user_report (id INT NOT NULL, reason_id INT NOT NULL, reported_user_id INT NOT NULL, status VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A17D6CB959BB1592 ON user_report (reason_id)');
        $this->addSql('CREATE INDEX IDX_A17D6CB9E7566E ON user_report (reported_user_id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9415F1F91 FOREIGN KEY (user_a_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E953EAB07F FOREIGN KEY (user_b_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517446C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE price_proposal ADD CONSTRAINT FK_DD53AF654584665A FOREIGN KEY (product_id) REFERENCES sneaker (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE price_proposal ADD CONSTRAINT FK_DD53AF656C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_appreciation ADD CONSTRAINT FK_D170FCC040C86FCE FOREIGN KEY (publisher_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_appreciation ADD CONSTRAINT FK_D170FCC04584665A FOREIGN KEY (product_id) REFERENCES sneaker (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_report ADD CONSTRAINT FK_A653362059BB1592 FOREIGN KEY (reason_id) REFERENCES report_reason (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_report ADD CONSTRAINT FK_A65336204584665A FOREIGN KEY (product_id) REFERENCES sneaker (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_report ADD CONSTRAINT FK_A6533620E1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker ADD CONSTRAINT FK_4259B88A44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker ADD CONSTRAINT FK_4259B88A5500B247 FOREIGN KEY (sneaker_model_id) REFERENCES sneaker_model (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker ADD CONSTRAINT FK_4259B88A40C86FCE FOREIGN KEY (publisher_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker_category ADD CONSTRAINT FK_2A391C61B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker_category ADD CONSTRAINT FK_2A391C6112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C998B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker_color ADD CONSTRAINT FK_B6E9C9987ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker_material ADD CONSTRAINT FK_50CB7035B44896C4 FOREIGN KEY (sneaker_id) REFERENCES sneaker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker_material ADD CONSTRAINT FK_50CB7035E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sneaker_model ADD CONSTRAINT FK_72AF3A844F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649DB396BDD FOREIGN KEY (user_reports_id) REFERENCES user_report (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_report ADD CONSTRAINT FK_A17D6CB959BB1592 FOREIGN KEY (reason_id) REFERENCES report_reason (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_report ADD CONSTRAINT FK_A17D6CB9E7566E FOREIGN KEY (reported_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sneaker DROP CONSTRAINT FK_4259B88A44F5D008');
        $this->addSql('ALTER TABLE sneaker_model DROP CONSTRAINT FK_72AF3A844F5D008');
        $this->addSql('ALTER TABLE sneaker_category DROP CONSTRAINT FK_2A391C6112469DE2');
        $this->addSql('ALTER TABLE sneaker_color DROP CONSTRAINT FK_B6E9C9987ADA1FB5');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE sneaker_material DROP CONSTRAINT FK_50CB7035E308AC6F');
        $this->addSql('ALTER TABLE product_report DROP CONSTRAINT FK_A653362059BB1592');
        $this->addSql('ALTER TABLE user_report DROP CONSTRAINT FK_A17D6CB959BB1592');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744B44896C4');
        $this->addSql('ALTER TABLE price_proposal DROP CONSTRAINT FK_DD53AF654584665A');
        $this->addSql('ALTER TABLE product_appreciation DROP CONSTRAINT FK_D170FCC04584665A');
        $this->addSql('ALTER TABLE product_report DROP CONSTRAINT FK_A65336204584665A');
        $this->addSql('ALTER TABLE sneaker_category DROP CONSTRAINT FK_2A391C61B44896C4');
        $this->addSql('ALTER TABLE sneaker_color DROP CONSTRAINT FK_B6E9C998B44896C4');
        $this->addSql('ALTER TABLE sneaker_material DROP CONSTRAINT FK_50CB7035B44896C4');
        $this->addSql('ALTER TABLE sneaker DROP CONSTRAINT FK_4259B88A5500B247');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9415F1F91');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E953EAB07F');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517446C755722');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE price_proposal DROP CONSTRAINT FK_DD53AF656C755722');
        $this->addSql('ALTER TABLE product_appreciation DROP CONSTRAINT FK_D170FCC040C86FCE');
        $this->addSql('ALTER TABLE product_report DROP CONSTRAINT FK_A6533620E1CFE6F5');
        $this->addSql('ALTER TABLE sneaker DROP CONSTRAINT FK_4259B88A40C86FCE');
        $this->addSql('ALTER TABLE user_report DROP CONSTRAINT FK_A17D6CB9E7566E');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649DB396BDD');
        $this->addSql('DROP SEQUENCE brand_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE color_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE img_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invoice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE material_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE price_proposal_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_appreciation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_report_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE report_reason_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sneaker_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sneaker_model_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_report_id_seq CASCADE');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE img');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE price_proposal');
        $this->addSql('DROP TABLE product_appreciation');
        $this->addSql('DROP TABLE product_report');
        $this->addSql('DROP TABLE report_reason');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE sneaker');
        $this->addSql('DROP TABLE sneaker_category');
        $this->addSql('DROP TABLE sneaker_color');
        $this->addSql('DROP TABLE sneaker_material');
        $this->addSql('DROP TABLE sneaker_model');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_report');
    }
}
