<?php

namespace App\Infrastructure\Controller;

use App\Application\Dto\LoanDto;
use App\Application\Service\Client\CreateLoanService;
use App\Domain\Client\Exception\ClientAlreadyExistsException;
use App\Domain\Client\Exception\ClientNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/loans')]
class LoanController extends AbstractController
{
    public function __construct(
        private readonly CreateLoanService $createLoanService,
    ) {
    }

    #[Route('', name: 'Create new loan', methods: ['POST'], format: 'json')]
    public function creteLoan(
        #[MapRequestPayload] LoanDto $loanDto,
    ): JsonResponse {
        try {
            $result = $this->createLoanService->execute($loanDto);
            $response = array_merge([
                'status' => 'success',
            ], $result);
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
