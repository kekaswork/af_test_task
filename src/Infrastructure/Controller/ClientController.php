<?php

namespace App\Infrastructure\Controller;

use App\Application\Dto\ClientPartialUpdateRequestDto;
use App\Application\Service\Client\CreateClientService;
use App\Application\Dto\ClientDto;
use App\Application\Service\Client\LoanEligibilityClientService;
use App\Application\Service\Client\FullUpdateClientService;
use App\Application\Service\Client\PartialUpdateClientService;
use App\Domain\Client\Enum\ClientOperationType;
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
        private readonly FullUpdateClientService $fullUpdateClientService,
        private readonly PartialUpdateClientService $partialUpdateClientService,
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

    #[Route('/{id}', name: 'Full update by client ID', methods: ['PUT'], format: 'json')]
    public function fullUpdateClient(
        string $id,
        #[MapRequestPayload] ClientDto $clientDto,
    ): JsonResponse {
        try {
            $result = $this->fullUpdateClientService->execute(
                $clientDto->setId($id),
            );
            $response = [
                'status' => 'success',
                'client_id' => $result->getId(),
            ];
            $status = match ($result->getOperation()) {
                ClientOperationType::CREATED => Response::HTTP_CREATED,
                default => Response::HTTP_OK,
            };
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

    #[Route('/{id}', name: 'Partial update by client ID', methods: ['PATCH'], format: 'json')]
    public function partialUpdateClient(
        string $id,
        #[MapRequestPayload] ClientPartialUpdateRequestDto $clientDto,
    ): JsonResponse {
        try {
            $result = $this->partialUpdateClientService->execute(
                $clientDto->setId($id),
            );
            $response = [
                'status' => 'success',
                'client_id' => $result->getId(),
            ];
            // Let's return the same response code (200) regardless of whether the data was updated.
            // If there's a need, we can adjust the logic and return HTTP_NO_CONTENT or HTTP_NOT_MODIFIED instead.
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
