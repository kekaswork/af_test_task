<?php

namespace App\Tests\Controller;

use App\Application\Service\Client\CreateClientService;
use App\Application\Service\Client\LoanEligibilityClientService;
use App\Application\Service\Client\UpdateClientService;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\AggregateRootId;
use App\Domain\Client\ValueObject\ClientId;
use App\Infrastructure\Controller\ClientController;
use App\Infrastructure\Doctrine\Types\AggregateRootType;
use App\Tests\Mock\Repository\MockClientRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ClientControllerTest extends WebTestCase
{
    private static ClientRepositoryInterface $clientRepository;
    private CreateClientService $createClientService;
    private UpdateClientService $updateClientService;
    private LoanEligibilityClientService $loanEligibilityClientService;
    private ClientController $controller;
    private static string $addedClientId = '';

    protected function setUp(): void
    {
        if (! isset(self::$clientRepository)) {
            self::$clientRepository = new MockClientRepository();
        }
        $this->createClientService = new CreateClientService(self::$clientRepository);
        $this->updateClientService = new UpdateClientService(self::$clientRepository);
        $this->loanEligibilityClientService = new LoanEligibilityClientService(self::$clientRepository);
        $this->controller = new ClientController($this->createClientService, $this->updateClientService, $this->loanEligibilityClientService);
    }

    public function testCreateClientSuccess()
    {
        $response = $this->performRequest('/api/clients', $this->getClientRequestPayload());
        $this->assertEquals(RESPONSE::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // Assert that the response has status 'success'
        $this->assertEquals('success', $responseData['status']);

        // Assert that the 'client_id' key exists in the response
        $this->assertArrayHasKey('client_id', $responseData);

        // Assert that 'client_id' matches the UUID pattern
        $this->assertMatchesRegularExpression(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $responseData['client_id']
        );

        self::$addedClientId = $responseData['client_id'];
    }

    public function testCreateClientWithExistingEmail()
    {
        $payload = $this->getClientRequestPayload();
        $payload['ssn'] = '143-46-6222';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function testCreateClientWithExistingSsn()
    {
        $payload = $this->getClientRequestPayload();
        $payload['email'] = 'unique' . $payload['email'];
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidEmail()
    {
        $payload = $this->getClientRequestPayload();
        $payload['email'] = 'invalidemail';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidSsn()
    {
        $payload = $this->getClientRequestPayload();
        $payload['ssn'] = 'invalidssn';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidState()
    {
        $payload = $this->getClientRequestPayload();
        $payload['state'] = 'unexisting state';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidZipCode()
    {
        $payload = $this->getClientRequestPayload();
        $payload['zip'] = '11111111';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidPhone()
    {
        $payload = $this->getClientRequestPayload();
        $payload['phone'] = 123123123;
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidFicoScore()
    {
        $payload = $this->getClientRequestPayload();
        $payload['fico_score'] = -900;
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithNoRequiredField()
    {
        $payload = $this->getClientRequestPayload();
        $randomKey = array_rand($payload);
        unset($payload[$randomKey]);
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidDateOfBirth()
    {
        $payload = $this->getClientRequestPayload();
        $payload['date_of_birth'] = 'invaliddateofbirth';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateSecondClientSuccess()
    {
        $payload = $this->getClientRequestPayload();
        $payload['email'] = 'unique.' . $payload['email'];
        $payload['ssn'] = '143-46-6222';

        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(RESPONSE::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // Assert that the response has status 'success'
        $this->assertEquals('success', $responseData['status']);

        // Assert that the 'client_id' key exists in the response
        $this->assertArrayHasKey('client_id', $responseData);

        // Assert that 'client_id' matches the UUID pattern
        $this->assertMatchesRegularExpression(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $responseData['client_id']
        );
    }

    public function testUpdateClientSuccess()
    {
        $payload = $this->getClientRequestPayload();
        $payload['first_name'] = 'Mr. ' . $payload['first_name'];
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PUT');
        $this->assertEquals(RESPONSE::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateNonExistingClient()
    {
        $payload = $this->getClientRequestPayload();
        $randomClientId = ClientId::generate();
        $response = $this->performRequest('/api/clients/' . $randomClientId->getValue(), $payload, 'PUT');
        $this->assertEquals(RESPONSE::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testUpdateClientWithExistingEmail()
    {
        $payload = $this->getClientRequestPayload();
        $payload['email'] = 'unique.' . $payload['email'];
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PUT');
        $this->assertEquals(RESPONSE::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function testUpdateClientWithExistingSsn()
    {
        $payload = $this->getClientRequestPayload();
        $payload['ssn'] = '143-46-6222';
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PUT');
        $this->assertEquals(RESPONSE::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function performRequest(string $uri, array $payload, string $method = 'POST'): Response
    {
        $client = static::createClient();
        $client->getContainer()->set(
            ClientController::class,
            $this->controller,
        );
        $container = self::getContainer();
        $this->controller->setContainer($container);
        $client->request(
            $method,
            $uri,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload),
        );
        return $client->getResponse();
    }

    public function getClientRequestPayload(): array
    {
        return [
            "first_name" => "Almaz",
            "last_name" => "G",
            "ssn" => "143-46-6221",
            "street" => "1 King Street",
            "city" => "Los Angeles",
            "state" => "CA",
            "zip" => "90001",
            "email" => "almaz@gmail.com",
            "phone" => "+11234567890",
            "monthly_income" => 4000,
            "fico_score" => 300,
            "date_of_birth" => "1993-08-08",
        ];
    }

    public static function tearDownAfterClass(): void
    {
        MockClientRepository::clear();
    }
}
