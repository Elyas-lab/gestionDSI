<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114065213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE GROUPE ADD (valeur VARCHAR2(255) NOT NULL)');
        // $this->addSql('ALTER TABLE MESSENGER_MESSAGES MODIFY (id NUMBER(20) DEFAULT messenger_messages_seq.nextval)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe DROP (valeur)');
        $this->addSql('ALTER TABLE messenger_messages MODIFY (ID NUMBER(20) DEFAULT NULL)');
    }
}
