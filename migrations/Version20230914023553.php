<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230914023553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_token (id CHAR(36) NOT NULL, user_id CHAR(36) NOT NULL, token VARCHAR(180) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id CHAR(36) NOT NULL, user_id CHAR(36) NOT NULL, product_count INT NOT NULL, cart_total NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_items (cart_id CHAR(36) NOT NULL, item_id CHAR(36) NOT NULL, INDEX IDX_BEF484451AD5CDBF (cart_id), INDEX IDX_BEF48445126F525E (item_id), PRIMARY KEY(cart_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id CHAR(36) NOT NULL, cart_id CHAR(36) NOT NULL, product_id CHAR(36) NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_line (id CHAR(36) NOT NULL, order_id CHAR(36) NOT NULL, product_id CHAR(36) NOT NULL, quantity INT NOT NULL, price NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id CHAR(36) NOT NULL, user_id CHAR(36) NOT NULL, product_count INT NOT NULL, order_total NUMERIC(10, 0) NOT NULL, address VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_order_lines (order_id CHAR(36) NOT NULL, order_line_id CHAR(36) NOT NULL, INDEX IDX_D72D87ED8D9F6D38 (order_id), INDEX IDX_D72D87EDBB01DC09 (order_line_id), PRIMARY KEY(order_id, order_line_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id CHAR(36) NOT NULL, reference VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, description VARCHAR(180) NOT NULL, price_amount NUMERIC(10, 0) NOT NULL, price_currency_iso_code VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL, name VARCHAR(180) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, password VARCHAR(180) DEFAULT NULL, address VARCHAR(180) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF484451AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF48445126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE order_order_lines ADD CONSTRAINT FK_D72D87ED8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE order_order_lines ADD CONSTRAINT FK_D72D87EDBB01DC09 FOREIGN KEY (order_line_id) REFERENCES order_line (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY FK_BEF484451AD5CDBF');
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY FK_BEF48445126F525E');
        $this->addSql('ALTER TABLE order_order_lines DROP FOREIGN KEY FK_D72D87ED8D9F6D38');
        $this->addSql('ALTER TABLE order_order_lines DROP FOREIGN KEY FK_D72D87EDBB01DC09');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_items');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE order_line');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE order_order_lines');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user');
    }
}
