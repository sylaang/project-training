<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329211427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, produits_id INT DEFAULT NULL, quantite NUMERIC(10, 2) NOT NULL, INDEX IDX_6EEAA67D67B3B43D (users_id), INDEX IDX_6EEAA67DCD11A2CF (produits_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D67B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DCD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D67B3B43D');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DCD11A2CF');
        $this->addSql('DROP TABLE commande');
    }
}
