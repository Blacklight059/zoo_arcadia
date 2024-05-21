<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520130859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food_consumption (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, animal_id INT NOT NULL, date_time DATETIME NOT NULL, food_type VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, INDEX IDX_8D49FB37A76ED395 (user_id), INDEX IDX_8D49FB378E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitat_comment (id INT AUTO_INCREMENT NOT NULL, habitat_id INT DEFAULT NULL, veterinarian_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, comment_date DATETIME NOT NULL, INDEX IDX_C86D6DCEAFFE2D26 (habitat_id), INDEX IDX_C86D6DCE804C8213 (veterinarian_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vet_report (id INT AUTO_INCREMENT NOT NULL, animal_id INT DEFAULT NULL, veterinarian_id INT DEFAULT NULL, animal_state VARCHAR(255) NOT NULL, food_offered VARCHAR(255) NOT NULL, food_weight DOUBLE PRECISION NOT NULL, visit_date DATETIME NOT NULL, state_details LONGTEXT DEFAULT NULL, INDEX IDX_864389518E962C16 (animal_id), INDEX IDX_86438951804C8213 (veterinarian_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE food_consumption ADD CONSTRAINT FK_8D49FB37A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE food_consumption ADD CONSTRAINT FK_8D49FB378E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE habitat_comment ADD CONSTRAINT FK_C86D6DCEAFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id)');
        $this->addSql('ALTER TABLE habitat_comment ADD CONSTRAINT FK_C86D6DCE804C8213 FOREIGN KEY (veterinarian_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vet_report ADD CONSTRAINT FK_864389518E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE vet_report ADD CONSTRAINT FK_86438951804C8213 FOREIGN KEY (veterinarian_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE veterinarian');
        $this->addSql('DROP TABLE employee');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, firstname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE veterinarian (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, firstname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, firstname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE food_consumption DROP FOREIGN KEY FK_8D49FB37A76ED395');
        $this->addSql('ALTER TABLE food_consumption DROP FOREIGN KEY FK_8D49FB378E962C16');
        $this->addSql('ALTER TABLE habitat_comment DROP FOREIGN KEY FK_C86D6DCEAFFE2D26');
        $this->addSql('ALTER TABLE habitat_comment DROP FOREIGN KEY FK_C86D6DCE804C8213');
        $this->addSql('ALTER TABLE vet_report DROP FOREIGN KEY FK_864389518E962C16');
        $this->addSql('ALTER TABLE vet_report DROP FOREIGN KEY FK_86438951804C8213');
        $this->addSql('DROP TABLE food_consumption');
        $this->addSql('DROP TABLE habitat_comment');
        $this->addSql('DROP TABLE vet_report');
    }
}
