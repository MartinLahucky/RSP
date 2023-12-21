<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220183039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ukol_user DROP FOREIGN KEY FK_C5E08CBBA76ED395');
        $this->addSql('ALTER TABLE ukol_user DROP FOREIGN KEY FK_C5E08CBBB29D6B6E');
        $this->addSql('DROP TABLE ukol_user');
        $this->addSql('ALTER TABLE clanek DROP FOREIGN KEY clanek_constr');
        $this->addSql('ALTER TABLE komentar_clanek DROP FOREIGN KEY komentar_clanek_constr');
        $this->addSql('ALTER TABLE komentar_ukol DROP FOREIGN KEY komentar_ukol_constr');
        $this->addSql('ALTER TABLE namitka DROP FOREIGN KEY namitka_constr');
        $this->addSql('ALTER TABLE posudek DROP FOREIGN KEY posudek_constr');
        $this->addSql('ALTER TABLE recenzni_rizeni DROP FOREIGN KEY recenzni_rizeni_constr');
        $this->addSql('ALTER TABLE ukol DROP FOREIGN KEY ukol_constr');
        $this->addSql('ALTER TABLE ukol ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE ukol ADD CONSTRAINT FK_9F46F168A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9F46F168A76ED395 ON ukol (user_id)');
        $this->addSql('ALTER TABLE verze_clanku DROP FOREIGN KEY verze_clanku_constr');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ukol_user (ukol_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C5E08CBBB29D6B6E (ukol_id), INDEX IDX_C5E08CBBA76ED395 (user_id), PRIMARY KEY(ukol_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ukol_user ADD CONSTRAINT FK_C5E08CBBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ukol_user ADD CONSTRAINT FK_C5E08CBBB29D6B6E FOREIGN KEY (ukol_id) REFERENCES ukol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE komentar_clanek ADD CONSTRAINT komentar_clanek_constr FOREIGN KEY (verze_clanku_id) REFERENCES verze_clanku (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posudek ADD CONSTRAINT posudek_constr FOREIGN KEY (clanek_id) REFERENCES clanek (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recenzni_rizeni ADD CONSTRAINT recenzni_rizeni_constr FOREIGN KEY (tisk_id) REFERENCES tisk (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE namitka ADD CONSTRAINT namitka_constr FOREIGN KEY (clanek_id) REFERENCES clanek (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clanek ADD CONSTRAINT clanek_constr FOREIGN KEY (recenzni_rizeni_id) REFERENCES recenzni_rizeni (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE verze_clanku ADD CONSTRAINT verze_clanku_constr FOREIGN KEY (clanek_id) REFERENCES clanek (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE komentar_ukol ADD CONSTRAINT komentar_ukol_constr FOREIGN KEY (ukol_id) REFERENCES ukol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ukol DROP FOREIGN KEY FK_9F46F168A76ED395');
        $this->addSql('DROP INDEX IDX_9F46F168A76ED395 ON ukol');
        $this->addSql('ALTER TABLE ukol DROP user_id');
        $this->addSql('ALTER TABLE ukol ADD CONSTRAINT ukol_constr FOREIGN KEY (clanek_id) REFERENCES clanek (id) ON DELETE CASCADE');
    }
}
