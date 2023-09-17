<?php

namespace App\Controller;

use App\Entity\Message;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message', name: 'message_')]
class MessageController extends AbstractController
{
    public function __construct(private readonly MessageService $messageService)
    {
    }

    #[Route('/list', name: 'list', methods: 'GET')]
    public function list(
        #[MapQueryParameter] string $sortBy = 'createdAt',
        #[MapQueryParameter] string $sortOrder = 'desc'
    ): JsonResponse {
        $availableSortBy = [
            'createdAt',
            'id'
        ];

        $availableSortOrder = [
            'asc',
            'desc'
        ];

        if ($sortBy != null && !in_array($sortBy, $availableSortBy)) {
            return new JsonResponse(
                'Invalid sortBy. Allowed values are "createdAt" and "id".',
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($sortOrder != null && !in_array($sortOrder, $availableSortOrder)) {
            return new JsonResponse(
                'Invalid sortOrder. Allowed values are "asc" and "desc".',
                Response::HTTP_BAD_REQUEST
            );
        }

        $jsonList = $this->messageService->getJsonList($sortBy, $sortOrder);
        return new JsonResponse($jsonList, Response::HTTP_OK);
    }

    #[Route('/create', name: 'create', methods: 'POST')]
    public function create(#[MapQueryParameter] string $content): JsonResponse
    {
        $message = new Message($content);
        $message = $this->messageService->createAndAddToFile($message);

        return new JsonResponse(['ID' => $message->getId()], Response::HTTP_CREATED);
    }

    #[Route('/show/{id}', name: 'show', methods: 'GET')]
    public function getMessage(string $id): JsonResponse
    {
        $message = $this->messageService->getMessageByUuid($id);

        if ($message === null) {
            return new JsonResponse('Not found message with id '.$id, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($message->jsonSerialize(), Response::HTTP_OK);
    }
}