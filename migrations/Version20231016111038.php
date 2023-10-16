<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016111038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Make DoB optional (for now)
        $this->addSql(
            'ALTER TABLE user_profile
                CHANGE date_of_birth date_of_birth DATE DEFAULT NULL
                    COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'ALTER TABLE user_profile
                CHANGE date_of_birth date_of_birth DATE NOT NULL
                    COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
