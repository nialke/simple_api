<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230916134305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create message table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA message');
        $this->addSql('CREATE TABLE message.message (id UUID NOT NULL, content VARCHAR(255) DEFAULT \'\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN message.message.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN message.message.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE message.message');
    }
}
