<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120130922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE komentar_ukol (id INT AUTO_INCREMENT NOT NULL, ukol_id INT NOT NULL, user_id INT NOT NULL, datum DATE NOT NULL, text VARCHAR(10000) NOT NULL, INDEX IDX_C3D79CA4B29D6B6E (ukol_id), INDEX IDX_C3D79CA4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ukol (id INT AUTO_INCREMENT NOT NULL, clanek_id INT NOT NULL, deadline DATE NOT NULL, UNIQUE INDEX UNIQ_9F46F1683F69A15D (clanek_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ukol_user (ukol_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C5E08CBBB29D6B6E (ukol_id), INDEX IDX_C5E08CBBA76ED395 (user_id), PRIMARY KEY(ukol_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE komentar_ukol ADD CONSTRAINT FK_C3D79CA4B29D6B6E FOREIGN KEY (ukol_id) REFERENCES ukol (id)');
        $this->addSql('ALTER TABLE komentar_ukol ADD CONSTRAINT FK_C3D79CA4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ukol ADD CONSTRAINT FK_9F46F1683F69A15D FOREIGN KEY (clanek_id) REFERENCES clanek (id)');
        $this->addSql('ALTER TABLE ukol_user ADD CONSTRAINT FK_C5E08CBBB29D6B6E FOREIGN KEY (ukol_id) REFERENCES ukol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ukol_user ADD CONSTRAINT FK_C5E08CBBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE komentar_ukol DROP FOREIGN KEY FK_C3D79CA4B29D6B6E');
        $this->addSql('ALTER TABLE komentar_ukol DROP FOREIGN KEY FK_C3D79CA4A76ED395');
        $this->addSql('ALTER TABLE ukol DROP FOREIGN KEY FK_9F46F1683F69A15D');
        $this->addSql('ALTER TABLE ukol_user DROP FOREIGN KEY FK_C5E08CBBB29D6B6E');
        $this->addSql('ALTER TABLE ukol_user DROP FOREIGN KEY FK_C5E08CBBA76ED395');
        $this->addSql('DROP TABLE komentar_ukol');
        $this->addSql('DROP TABLE ukol');
        $this->addSql('DROP TABLE ukol_user');
    }
}
