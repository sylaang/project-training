<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322143937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD categories_id INT NOT NULL, ADD imagename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A21214B7 FOREIGN KEY (categories_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27A21214B7 ON produit (categories_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP description');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A21214B7');
        $this->addSql('DROP INDEX IDX_29A5EC27A21214B7 ON produit');
        $this->addSql('ALTER TABLE produit DROP categories_id, DROP imagename');
    }
}
