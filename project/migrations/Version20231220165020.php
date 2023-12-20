<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220165020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clanek (id INT AUTO_INCREMENT NOT NULL, recenzni_rizeni_id INT NOT NULL, user_id INT NOT NULL, stav_redakce VARCHAR(50) NOT NULL, stav_autor VARCHAR(50) NOT NULL, nazev_clanku VARCHAR(50) NOT NULL, INDEX IDX_60591765D0286FB4 (recenzni_rizeni_id), INDEX IDX_60591765A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE komentar_clanek (id INT AUTO_INCREMENT NOT NULL, verze_clanku_id INT NOT NULL, user_id INT NOT NULL, datum VARCHAR(10) NOT NULL, text VARCHAR(10000) NOT NULL, INDEX IDX_3415CE5350D48026 (verze_clanku_id), INDEX IDX_3415CE53A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE komentar_ukol (id INT AUTO_INCREMENT NOT NULL, ukol_id INT NOT NULL, user_id INT NOT NULL, datum VARCHAR(10) NOT NULL, text VARCHAR(10000) NOT NULL, INDEX IDX_C3D79CA4B29D6B6E (ukol_id), INDEX IDX_C3D79CA4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE namitka (id INT AUTO_INCREMENT NOT NULL, clanek_id INT NOT NULL, datum VARCHAR(10) NOT NULL, text_namitky VARCHAR(10000) NOT NULL, UNIQUE INDEX UNIQ_4D851C623F69A15D (clanek_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posudek (id INT AUTO_INCREMENT NOT NULL, clanek_id INT NOT NULL, user_id INT NOT NULL, posudek_soubor VARCHAR(1000) NOT NULL, zpristupnen_autorovi TINYINT(1) NOT NULL, aktualnost INT NOT NULL, zajimavost INT NOT NULL, originalita INT NOT NULL, odborna_uroven INT NOT NULL, jazykova_uroven INT NOT NULL, INDEX IDX_E7013CA53F69A15D (clanek_id), INDEX IDX_E7013CA5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recenzni_rizeni (id INT AUTO_INCREMENT NOT NULL, tisk_id INT NOT NULL, od VARCHAR(10) NOT NULL, do VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_48A89B9161E583EB (tisk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tisk (id INT AUTO_INCREMENT NOT NULL, datum VARCHAR(10) NOT NULL, kapacita INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ukol (id INT AUTO_INCREMENT NOT NULL, clanek_id INT NOT NULL, deadline VARCHAR(10) NOT NULL, description VARCHAR(500) NOT NULL, done TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9F46F1683F69A15D (clanek_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ukol_user (ukol_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C5E08CBBB29D6B6E (ukol_id), INDEX IDX_C5E08CBBA76ED395 (user_id), PRIMARY KEY(ukol_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE verze_clanku (id INT AUTO_INCREMENT NOT NULL, clanek_id INT NOT NULL, datum_nahrani VARCHAR(10) NOT NULL, soubor_clanek VARCHAR(1000) NOT NULL, zpristupnen_recenzentum TINYINT(1) NOT NULL, INDEX IDX_FE9155453F69A15D (clanek_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clanek ADD CONSTRAINT FK_60591765D0286FB4 FOREIGN KEY (recenzni_rizeni_id) REFERENCES recenzni_rizeni (id)');
        $this->addSql('ALTER TABLE clanek ADD CONSTRAINT FK_60591765A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE komentar_clanek ADD CONSTRAINT FK_3415CE5350D48026 FOREIGN KEY (verze_clanku_id) REFERENCES verze_clanku (id)');
        $this->addSql('ALTER TABLE komentar_clanek ADD CONSTRAINT FK_3415CE53A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE komentar_ukol ADD CONSTRAINT FK_C3D79CA4B29D6B6E FOREIGN KEY (ukol_id) REFERENCES ukol (id)');
        $this->addSql('ALTER TABLE komentar_ukol ADD CONSTRAINT FK_C3D79CA4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE namitka ADD CONSTRAINT FK_4D851C623F69A15D FOREIGN KEY (clanek_id) REFERENCES clanek (id)');
        $this->addSql('ALTER TABLE posudek ADD CONSTRAINT FK_E7013CA53F69A15D FOREIGN KEY (clanek_id) REFERENCES clanek (id)');
        $this->addSql('ALTER TABLE posudek ADD CONSTRAINT FK_E7013CA5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recenzni_rizeni ADD CONSTRAINT FK_48A89B9161E583EB FOREIGN KEY (tisk_id) REFERENCES tisk (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ukol ADD CONSTRAINT FK_9F46F1683F69A15D FOREIGN KEY (clanek_id) REFERENCES clanek (id)');
        $this->addSql('ALTER TABLE ukol_user ADD CONSTRAINT FK_C5E08CBBB29D6B6E FOREIGN KEY (ukol_id) REFERENCES ukol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ukol_user ADD CONSTRAINT FK_C5E08CBBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE verze_clanku ADD CONSTRAINT FK_FE9155453F69A15D FOREIGN KEY (clanek_id) REFERENCES clanek (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clanek DROP FOREIGN KEY FK_60591765D0286FB4');
        $this->addSql('ALTER TABLE clanek DROP FOREIGN KEY FK_60591765A76ED395');
        $this->addSql('ALTER TABLE komentar_clanek DROP FOREIGN KEY FK_3415CE5350D48026');
        $this->addSql('ALTER TABLE komentar_clanek DROP FOREIGN KEY FK_3415CE53A76ED395');
        $this->addSql('ALTER TABLE komentar_ukol DROP FOREIGN KEY FK_C3D79CA4B29D6B6E');
        $this->addSql('ALTER TABLE komentar_ukol DROP FOREIGN KEY FK_C3D79CA4A76ED395');
        $this->addSql('ALTER TABLE namitka DROP FOREIGN KEY FK_4D851C623F69A15D');
        $this->addSql('ALTER TABLE posudek DROP FOREIGN KEY FK_E7013CA53F69A15D');
        $this->addSql('ALTER TABLE posudek DROP FOREIGN KEY FK_E7013CA5A76ED395');
        $this->addSql('ALTER TABLE recenzni_rizeni DROP FOREIGN KEY FK_48A89B9161E583EB');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE ukol DROP FOREIGN KEY FK_9F46F1683F69A15D');
        $this->addSql('ALTER TABLE ukol_user DROP FOREIGN KEY FK_C5E08CBBB29D6B6E');
        $this->addSql('ALTER TABLE ukol_user DROP FOREIGN KEY FK_C5E08CBBA76ED395');
        $this->addSql('ALTER TABLE verze_clanku DROP FOREIGN KEY FK_FE9155453F69A15D');
        $this->addSql('DROP TABLE clanek');
        $this->addSql('DROP TABLE komentar_clanek');
        $this->addSql('DROP TABLE komentar_ukol');
        $this->addSql('DROP TABLE namitka');
        $this->addSql('DROP TABLE posudek');
        $this->addSql('DROP TABLE recenzni_rizeni');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE tisk');
        $this->addSql('DROP TABLE ukol');
        $this->addSql('DROP TABLE ukol_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE verze_clanku');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
