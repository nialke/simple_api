<?php

namespace App\Service;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Uid\Uuid;

class MessageService
{
    const MESSAGE_FILE_PATH = FILES_DIRECTORY.'/messages.txt';

    public function __construct(private readonly MessageRepository $messageRepository)
    {
    }

    public function createAndAddToFile(Message $message): Message
    {
        $this->messageRepository->save($message);
        $this->addMessageToFile($message);
        return $message;
    }

    /**
     * @return array<array<string, string>>
     */
    public function getJsonList(string $sortBy, string $sortOrder): array
    {
        $messages = $this->messageRepository->findAllSorted($sortBy, $sortOrder);

        $jsonData = [];

        foreach ($messages as $message) {
            $jsonData[] = $message->jsonSerialize();
        }

        return $jsonData;
    }

    public function getMessageByUuid(string $id): ?Message
    {
        $id = Uuid::fromString($id);
        return $this->messageRepository->find($id);
    }

    private function addMessageToFile(Message $message): void
    {
        $messageToAdd = $message->getCreatedAt()->format('Y-m-d H:i:s')."\t".$message->getContent()."\n";

        $filesystem = new Filesystem();

        if (!$filesystem->exists(self::MESSAGE_FILE_PATH)) {
            $filesystem->touch(self::MESSAGE_FILE_PATH);
        }

        $filesystem->appendToFile(self::MESSAGE_FILE_PATH, $messageToAdd, FILE_APPEND);
    }
}