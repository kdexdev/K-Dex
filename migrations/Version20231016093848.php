<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016093848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Create the user profile table
        $this->addSql(
            'CREATE TABLE user_profile (
                id INT AUTO_INCREMENT NOT NULL,
                user_id INT NOT NULL,
                profile_picture_id CHAR(36) DEFAULT NULL
                    COMMENT \'(DC2Type:guid)\',
                display_name VARCHAR(255) DEFAULT NULL,
                date_of_birth DATE NOT NULL
                    COMMENT \'(DC2Type:date_immutable)\',
                description LONGTEXT DEFAULT NULL,
                location VARCHAR(255) DEFAULT NULL,
                timezone VARCHAR(64) DEFAULT NULL,

                UNIQUE INDEX UNIQ_D95AB405292E8AE2 (profile_picture_id),
                UNIQUE INDEX UNIQ_D95AB405A76ED395 (user_id),

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql(
            'ALTER TABLE user_profile
                ADD CONSTRAINT FK_D95AB405A76ED395
                FOREIGN KEY (user_id) REFERENCES user (id)');

        // Remove the avatar_id column from the user auth detail table
        $this->addSql('DROP INDEX UNIQ_8D93D64986383B10 ON user');
        $this->addSql('ALTER TABLE user DROP avatar_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'ALTER TABLE user_profile
                DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql(
            'ALTER TABLE user
                ADD avatar_id CHAR(36) DEFAULT NULL
                    COMMENT \'(DC2Type:guid)\'');
        $this->addSql(
            'CREATE UNIQUE INDEX UNIQ_8D93D64986383B10 ON user (avatar_id)');
    }
}
