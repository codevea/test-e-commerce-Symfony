<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617191449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE carrier CHANGE price price INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` CHANGE carrier_price carrier_price INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_detail CHANGE product_price product_price INT NOT NULL, CHANGE product_tva product_tva INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product CHANGE price price INT NOT NULL, CHANGE tva tva INT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE carrier CHANGE price price DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE tva tva DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_detail CHANGE product_price product_price DOUBLE PRECISION NOT NULL, CHANGE product_tva product_tva DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` CHANGE carrier_price carrier_price DOUBLE PRECISION NOT NULL
        SQL);
    }
}
