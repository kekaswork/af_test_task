<?php

namespace App\Tests\Controller;

use App\Application\Service\Client\ClientDtoValidator;
use App\Application\Service\Client\CreateClientService;
use App\Application\Service\Client\LoanEligibilityClientService;
use App\Application\Service\Client\FullUpdateClientService;
use App\Application\Service\Client\PartialUpdateClientService;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\ClientId;
use App\Infrastructure\Controller\ClientController;
use App\Tests\Mock\Repository\MockClientRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ClientControllerTest extends WebTestCase
{
    private const UUID_PATTERN = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

    private static ClientRepositoryInterface $clientRepository;
    private ClientController $controller;
    private static string $addedClientId = '';
    private static array $secondClient = [];

    protected function setUp(): void
    {
        if (! isset(self::$clientRepository)) {
            self::$clientRepository = new MockClientRepository();
        }
        $clientDtoValidator = new ClientDtoValidator(self::$clientRepository);
        $createClientService = new CreateClientService(self::$clientRepository, $clientDtoValidator);
        $fullUpdateClientService = new FullUpdateClientService(self::$clientRepository, $clientDtoValidator);
        $partialUpdateClientService = new PartialUpdateClientService(
            self::$clientRepository,
            $clientDtoValidator
        );
        $loanEligibilityClientService = new LoanEligibilityClientService(self::$clientRepository);
        $this->controller = new ClientController(
            $createClientService,
            $fullUpdateClientService,
            $partialUpdateClientService,
            $loanEligibilityClientService,
        );
    }

    public function testCreateClientSuccess()
    {
        $response = $this->performRequest('/api/clients', self::getEligibleClientRequestPayload());
        $this->assertEquals(RESPONSE::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // Assert that the response has status 'success'
        $this->assertEquals('success', $responseData['status']);

        // Assert that the 'client_id' key exists in the response
        $this->assertArrayHasKey('client_id', $responseData);

        // Assert that 'client_id' matches the UUID pattern
        $this->assertMatchesRegularExpression(
            self::UUID_PATTERN,
            $responseData['client_id']
        );

        self::$addedClientId = $responseData['client_id'];
    }

    public function testCreateClientWithExistingEmail()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['ssn'] = self::generateRandomSsn();
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function testCreateClientWithExistingSsn()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['email'] = self::generateRandomEmail();
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidEmail()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['email'] = 'invalidemail';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidSsn()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['ssn'] = 'invalidssn';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidState()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['state'] = 'unexisting state';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidZipCode()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['zip'] = '11111111';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidPhone()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['phone'] = 123123123;
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidFicoScore()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['fico_score'] = -900;
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithNoRequiredField()
    {
        $payload = self::getEligibleClientRequestPayload();
        $randomKey = array_rand($payload);
        unset($payload[$randomKey]);
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateClientWithInvalidDateOfBirth()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['date_of_birth'] = 'invaliddateofbirth';
        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testCreateSecondClientSuccess()
    {
        $payload = self::getNonEligibleClientRequestPayload();

        $response = $this->performRequest('/api/clients', $payload);
        $this->assertEquals(RESPONSE::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        // Assert that the response has status 'success'
        $this->assertEquals('success', $responseData['status']);

        // Assert that the 'client_id' key exists in the response
        $this->assertArrayHasKey('client_id', $responseData);

        // Assert that 'client_id' matches the UUID pattern
        $this->assertMatchesRegularExpression(
            self::UUID_PATTERN,
            $responseData['client_id'],
        );

        self::$secondClient = array_merge($payload, ['id' => $responseData['client_id']]);
    }

    public function testUpdateExistingClientSuccess()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['first_name'] = 'Mr. ' . $payload['first_name'];
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PUT');
        $this->assertEquals(RESPONSE::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateNonExistingClientSuccess()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['email'] = self::generateRandomEmail();
        $payload['ssn'] = self::generateRandomSsn();
        $randomClientId = ClientId::generate();
        $response = $this->performRequest('/api/clients/' . $randomClientId->getValue(), $payload, 'PUT');
        $this->assertEquals(RESPONSE::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateClientWithExistingEmail()
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['email'] = self::$secondClient['email'];
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PUT');
        $this->assertEquals(RESPONSE::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function testUpdateClientWithExistingSsn(): void
    {
        $payload = self::getEligibleClientRequestPayload();
        $payload['ssn'] = self::$secondClient['ssn'];
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PUT');
        $this->assertEquals(RESPONSE::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function testPartialUpdateClientSuccess(): void
    {
        $payload = [
            'first_name' => 'Almaz',
        ];
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PATCH');
        $this->assertEquals(RESPONSE::HTTP_OK, $response->getStatusCode());
    }

    public function testPartialUpdateNonExistingClient(): void
    {
        $payload = [
            'first_name' => 'almaz',
        ];
        $randomClientId = ClientId::generate();
        $response = $this->performRequest('/api/clients/' . $randomClientId, $payload, 'PATCH');
        $this->assertEquals(RESPONSE::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testPartialClientUpdateWithEmptyData(): void
    {
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, [], 'PATCH');
        $this->assertEquals(RESPONSE::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testPartialClientUpdateWithInvalidData(): void
    {
        $payload = [
            'email' => 'invalid email',
        ];
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PATCH');
        $this->assertEquals(RESPONSE::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testPartialClientUpdateWithDuplicatedEmail(): void
    {
        $payload = [
            'email' => self::$secondClient['email'],
        ];
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PATCH');
        $this->assertEquals(RESPONSE::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function testPartialClientUpdateWithDuplicatedSsn(): void
    {
        $payload = [
            'ssn' => self::$secondClient['ssn'],
        ];
        $response = $this->performRequest('/api/clients/' . self::$addedClientId, $payload, 'PATCH');
        $this->assertEquals(RESPONSE::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function testCheckEligibilityTrue(): void
    {
        $response = $this->performRequest(sprintf('/api/clients/%s/loan-eligibility', self::$addedClientId), [], 'GET');
        $this->assertEquals(RESPONSE::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('is_eligible', $responseData);
        $this->assertIsBool($responseData['is_eligible']);
        $this->assertTrue($responseData['is_eligible']);
    }

    public function testCheckEligibilityFalse(): void
    {
        $response = $this->performRequest(sprintf('/api/clients/%s/loan-eligibility', self::$secondClient['id']), [], 'GET');
        $this->assertEquals(RESPONSE::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('is_eligible', $responseData);
        $this->assertIsBool($responseData['is_eligible']);
        $this->assertFalse($responseData['is_eligible']);
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

    public static function getEligibleClientRequestPayload(): array
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
            "fico_score" => 800,
            "date_of_birth" => "1993-08-08",
        ];
    }

    public static function getNonEligibleClientRequestPayload(): array
    {
        return [
            "first_name" => "Almaz",
            "last_name" => "G",
            "ssn" => "243-46-6221",
            "street" => "1 King Street",
            "city" => "Los Angeles",
            "state" => "TX",
            "zip" => "90001",
            "email" => "almaz.new@gmail.com",
            "phone" => "+11234567890",
            "monthly_income" => 4000,
            "fico_score" => 800,
            "date_of_birth" => "1993-08-08",
        ];
    }

    public static function tearDownAfterClass(): void
    {
        MockClientRepository::clear();
    }

    public static function generateRandomEmail(string $domain = 'example.com'): string
    {
        $uniqueString = bin2hex(random_bytes(4));
        return "user-{$uniqueString}@$domain";
    }

    public static function generateRandomSsn(): string
    {
        $area = str_pad((string)random_int(100, 999), 3, '0', STR_PAD_LEFT);
        $group = str_pad((string)random_int(10, 99), 2, '0', STR_PAD_LEFT);
        $serial = str_pad((string)random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        return "$area-$group-$serial";
    }
}
