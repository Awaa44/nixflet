<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260217085411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE serie CHANGE name name VARCHAR(255) NOT NULL, CHANGE overview overview LONGTEXT DEFAULT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE genres genres VARCHAR(255) NOT NULL, CHANGE backdrop backdrop VARCHAR(255) DEFAULT NULL, CHANGE poster poster VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA3A93345E237E06A4265897 ON serie (name, first_air_date)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_AA3A93345E237E06A4265897 ON serie');
        $this->addSql('ALTER TABLE serie CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE overview overview LONGTEXT DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE status status VARCHAR(255) NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE genres genres VARCHAR(255) NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE backdrop backdrop VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE poster poster VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`');
    }
}
