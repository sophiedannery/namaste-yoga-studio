<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027141748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, cancelled_by_id INT DEFAULT NULL, class_type_id INT NOT NULL, room_id INT DEFAULT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', capacity INT NOT NULL, price NUMERIC(7, 2) DEFAULT NULL, details LONGTEXT DEFAULT NULL, status VARCHAR(50) NOT NULL, cancelled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', cancel_reason VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D044D5D441807E1D (teacher_id), INDEX IDX_D044D5D4187B2D12 (cancelled_by_id), INDEX IDX_D044D5D439EB6F (class_type_id), INDEX IDX_D044D5D454177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D441807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4187B2D12 FOREIGN KEY (cancelled_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D439EB6F FOREIGN KEY (class_type_id) REFERENCES class_type (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D454177093 FOREIGN KEY (room_id) REFERENCES room (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D441807E1D');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4187B2D12');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D439EB6F');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D454177093');
        $this->addSql('DROP TABLE session');
    }
}
