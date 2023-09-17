<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: 'message', schema: 'message')]
class Message
{

    public function __construct(string $content)
    {
        $this->content = $content;
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(
        name: 'content',
        type: 'string',
        length: 255,
        nullable: false,
        options: ['default' => '']

    )]
    private ?string $content = null;

    #[ORM\Column(
        name: 'created_at',
        type: 'datetime_immutable',
        nullable: false,
        options: ['default' => 'CURRENT_TIMESTAMP']
    )]
    private ?DateTimeImmutable $createdAt = null;

    public function getId(): Uuid
    {
        return $this->id;
    }


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
          'id' => $this->getId(),
          'content' => $this->getContent(),
          'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s')
        ];
    }
}