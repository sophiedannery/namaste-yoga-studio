<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027110416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    // 1) Ajouter les colonnes en "souple" (NULL autorisé) + défaut pour is_active
    $this->addSql("
        ALTER TABLE user
        ADD first_name VARCHAR(255) DEFAULT NULL,
        ADD last_name VARCHAR(255) DEFAULT NULL,
        ADD avatar_url VARCHAR(255) DEFAULT NULL,
        ADD is_active TINYINT(1) NOT NULL DEFAULT 1,
        ADD bio LONGTEXT DEFAULT NULL,
        ADD specialties LONGTEXT DEFAULT NULL,
        ADD created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
        ADD updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
    ");

    // 2) Backfill pour les lignes déjà présentes
    $this->addSql("UPDATE user SET first_name = COALESCE(first_name, 'User')");
    $this->addSql("UPDATE user SET last_name = COALESCE(last_name, 'Unknown')");
    $this->addSql("UPDATE user SET created_at = NOW() WHERE created_at IS NULL");
    $this->addSql("UPDATE user SET updated_at = NOW() WHERE updated_at IS NULL");
    $this->addSql("UPDATE user SET is_active = 1 WHERE is_active IS NULL");

    // 3) Resserrer les colonnes en NOT NULL (conforme à ton entité)
    $this->addSql("
        ALTER TABLE user
        MODIFY first_name VARCHAR(255) NOT NULL,
        MODIFY last_name VARCHAR(255) NOT NULL,
        MODIFY created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
        MODIFY updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
    ");
}

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP first_name, DROP last_name, DROP avatar_url, DROP is_active, DROP bio, DROP specialties, DROP created_at, DROP updated_at');
    }
}
