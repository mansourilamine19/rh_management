<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204174037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agency (id INT AUTO_INCREMENT NOT NULL, responsible_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, tel BIGINT DEFAULT NULL, INDEX IDX_70C0C6E6602AD315 (responsible_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agency_region (agency_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_D3BA3036CDEADB2A (agency_id), INDEX IDX_D3BA303698260155 (region_id), PRIMARY KEY(agency_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B023498260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, car_registration_number VARCHAR(255) DEFAULT NULL, cin BIGINT DEFAULT NULL, salary DOUBLE PRECISION DEFAULT NULL, price_per_package DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_3781EC10A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, rib VARCHAR(255) DEFAULT NULL, bank VARCHAR(255) DEFAULT NULL, cin BIGINT DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, salary VARCHAR(255) DEFAULT NULL, entry_date DATE DEFAULT NULL, UNIQUE INDEX UNIQ_5D9F75A1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locality (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E1D6B8E68BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pick_up (id INT AUTO_INCREMENT NOT NULL, locality_id INT NOT NULL, full_name VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, tel VARCHAR(255) DEFAULT NULL, tel2 VARCHAR(255) DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, number_item INT NOT NULL, number_pack INT NOT NULL, payment_method VARCHAR(255) NOT NULL, open_pack TINYINT(1) NOT NULL, exchange TINYINT(1) NOT NULL, notice LONGTEXT DEFAULT NULL, bar_code BIGINT NOT NULL, state ENUM(\'SHIPPER_WAIT\', \'SHIPPER_IN_PROGRESS\', \'SHIPPER_RETURN_RECEIVED\', \'SHIPPER_RETURN_PAIED\', \'SHIPPER_EXCHANGE\', \'SHIPPER_TO_TAKE\', \'SHIPPER_TAKED\', \'AGENCY_AT_STORE\', \'AGENCY_RETURN_TO_STORE\', \'AGENCY_RETURN_TO_AGENCY\', \'AGENCY_RETURN_TO_SHIPPER\', \'AGENCY_TO_CHECK\', \'DELIVERY_DELIVRED\', \'DELIVERY_FINAL_RETURN\', \'OTHER_NOT_RECEIVED\', \'OTHER_DELETED\', \'OTHER_UNKNOWN\') NOT NULL COMMENT \'(DC2Type:pick_up_status_enum)\', UNIQUE INDEX UNIQ_9E7B95A70D524AA (bar_code), INDEX IDX_9E7B95A88823A92 (locality_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(9) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipper (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name_page VARCHAR(255) DEFAULT NULL, delivery_price DOUBLE PRECISION DEFAULT NULL, return_price DOUBLE PRECISION DEFAULT NULL, with_extra TINYINT(1) DEFAULT NULL, rib VARCHAR(255) DEFAULT NULL, bank VARCHAR(255) DEFAULT NULL, vat_code VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, payment_method VARCHAR(255) DEFAULT NULL, card_number BIGINT DEFAULT NULL, type_supplier VARCHAR(255) DEFAULT NULL, hidden_cin TINYINT(1) DEFAULT NULL, with_billing TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_A926CAFDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, agency_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, tel VARCHAR(255) DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, INDEX IDX_8D93D649CDEADB2A (agency_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agency ADD CONSTRAINT FK_70C0C6E6602AD315 FOREIGN KEY (responsible_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE agency_region ADD CONSTRAINT FK_D3BA3036CDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agency_region ADD CONSTRAINT FK_D3BA303698260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B023498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE locality ADD CONSTRAINT FK_E1D6B8E68BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE pick_up ADD CONSTRAINT FK_9E7B95A88823A92 FOREIGN KEY (locality_id) REFERENCES locality (id)');
        $this->addSql('ALTER TABLE shipper ADD CONSTRAINT FK_A926CAFDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agency DROP FOREIGN KEY FK_70C0C6E6602AD315');
        $this->addSql('ALTER TABLE agency_region DROP FOREIGN KEY FK_D3BA3036CDEADB2A');
        $this->addSql('ALTER TABLE agency_region DROP FOREIGN KEY FK_D3BA303698260155');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B023498260155');
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC10A76ED395');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1A76ED395');
        $this->addSql('ALTER TABLE locality DROP FOREIGN KEY FK_E1D6B8E68BAC62AF');
        $this->addSql('ALTER TABLE pick_up DROP FOREIGN KEY FK_9E7B95A88823A92');
        $this->addSql('ALTER TABLE shipper DROP FOREIGN KEY FK_A926CAFDA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CDEADB2A');
        $this->addSql('DROP TABLE agency');
        $this->addSql('DROP TABLE agency_region');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE locality');
        $this->addSql('DROP TABLE pick_up');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE shipper');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
