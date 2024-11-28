<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\Client\CreateClientService;
use App\Application\Dto\ClientDto;
use App\Application\Service\Client\LoanEligibilityClientService;
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
        private readonly LoanEligibilityClientService $loanEligibilityClientService,
    ) {
    }

    #[Route('', name: 'Create new client', methods: ['POST'], format: 'json')]
    public function createClient(
        #[MapRequestPayload] ClientDto $clientDto
    ): JsonResponse {
        try {
            $clientId = $this->createClientService->execute($clientDto);

            $response = [
                'status' => 'success',
                'client_id' => $clientId,
            ];
            $status = Response::HTTP_CREATED;
        } catch (\InvalidArgumentException $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
            $status = Response::HTTP_BAD_REQUEST;
        } catch (ClientAlreadyExistsException $e) {
            $response = [
                'status' => 'error',
                'message' => 'A client with this email or SSN already exists.',
            ];
            $status = Response::HTTP_CONFLICT;
        } catch (\Throwable $e) {
            $response = [
                'status' => 'error',
                'message' => 'Internal error.',
            ];
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        } finally {
            return new JsonResponse($response, $status);
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
            $response = [
                'status' => 'success',
                'client_id' => $clientId,
            ];
            $status = Response::HTTP_OK;
        } catch (ClientNotFoundException $e) {
            $response = [
                'status' => 'error',
                'client_id' => 'Client not found.',
            ];
            $status = Response::HTTP_NOT_FOUND;
        } catch (\InvalidArgumentException $e) {
            $response = [
                'status' => 'error',
                'client_id' => $e->getMessage(),
            ];
            $status = Response::HTTP_BAD_REQUEST;
        } catch (ClientAlreadyExistsException $e) {
            $response = [
                'status' => 'error',
                'client_id' => 'A client with this email or SSN already exists.',
            ];
            $status = Response::HTTP_CONFLICT;
        } catch (\Throwable $e) {
            $response = [
                'status' => 'error',
                'message' => 'Internal error.',
            ];
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        } finally {
            return new JsonResponse($response, $status);
        }
    }

    #[Route('/{uuid}/loan-eligibility', name: 'Check if the current user is eligible for loan', methods: ['GET'], format: 'json')]
    public function checkClientLoanEligibility(
        string $uuid,
    ): JsonResponse {
        try {
            $isEligible = $this->loanEligibilityClientService->execute($uuid);
            $response = [
                'status' => 'success',
                'is_eligible' => $isEligible,
            ];
            $status = Response::HTTP_OK;
        } catch (ClientNotFoundException $e) {
            $response = [
                'status' => 'error',
                'client_id' => 'Client not found.',
            ];
            $status = Response::HTTP_NOT_FOUND;
        } catch (\InvalidArgumentException $e) {
            $response = [
                'status' => 'error',
                'client_id' => $e->getMessage(),
            ];
            $status = Response::HTTP_BAD_REQUEST;
        } catch (\Throwable $e) {
            $response = [
                'status' => 'error',
                'message' => 'Internal error.',
            ];
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        } finally {
            return new JsonResponse($response, $status);
        }
    }
}
