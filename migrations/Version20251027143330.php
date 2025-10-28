<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027143330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE suspension (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, admin_res_id INT NOT NULL, reason VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_82AF0500CB944F1A (student_id), INDEX IDX_82AF05001BB8D7FD (admin_res_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE suspension ADD CONSTRAINT FK_82AF0500CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suspension ADD CONSTRAINT FK_82AF05001BB8D7FD FOREIGN KEY (admin_res_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suspension DROP FOREIGN KEY FK_82AF0500CB944F1A');
        $this->addSql('ALTER TABLE suspension DROP FOREIGN KEY FK_82AF05001BB8D7FD');
        $this->addSql('DROP TABLE suspension');
    }
}
