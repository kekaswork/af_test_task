<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\Client\CreateClientService;
use App\Application\Dto\ClientDto;
use App\Application\Service\Client\UpdateClientService;
use App\Domain\Client\Exception\ClientAlreadyExistsException;
use App\Domain\Client\Exception\ClientNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/clients')]
class ClientController extends AbstractController
{
    public function __construct(
        private readonly CreateClientService $createClientService,
        private readonly UpdateClientService $updateClientService,
    ) {
    }

    #[Route('', name: 'Create new client', methods: ['POST'], format: 'json')]
    public function createClient(
        #[MapRequestPayload] ClientDto $clientDto
    ): JsonResponse {
        try {
            $clientId = $this->createClientService->execute($clientDto);

            return new JsonResponse([
                'status' => 'success',
                'client_id' => $clientId,
            ], Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid request payload. Please check your input data.',
            ], Response::HTTP_BAD_REQUEST);
        } catch (ClientAlreadyExistsException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'A client with this email or SSN already exists.',
            ], Response::HTTP_CONFLICT);
        }
    }

    #[Route('/{id}', name: 'Update client data by client ID', methods: ['PUT'], format: 'json')]
    public function updateClient(
        string $id,
        #[MapRequestPayload] ClientDto $clientDto,
    ): JsonResponse {
        try {
            $clientId = $this->updateClientService->execute(
                $clientDto->setId($id),
            );

            return new JsonResponse([
                'status' => 'success',
                'client_id' => $clientId,
            ], Response::HTTP_OK);
        } catch (ClientNotFoundException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Client not found.',
            ], Response::HTTP_NOT_FOUND);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid request payload. Please check your input data.',
            ], Response::HTTP_BAD_REQUEST);
        } catch (ClientAlreadyExistsException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'A client with this email or SSN already exists.',
            ], Response::HTTP_CONFLICT);
        }
    }
}
