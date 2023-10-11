<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011102202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creation of the user table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE user (
                id INT AUTO_INCREMENT NOT NULL,
                username VARCHAR(90) NOT NULL,
                avatar_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL,
                roles JSON NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                last_visited_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',

                UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
                UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id),
                UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
    }
}
