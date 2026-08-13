<?php

namespace AlexRoden\Importers\Soap;

use SoapClient;

class BookClient
{
    private SoapClient $client;

    /**
     * @throws \SoapFault
     */
    public function __construct(string $baseUrl, string $username, string $password)
    {
        $this->client = new SoapClient(
            "{$baseUrl}/soap.php?wsdl",
            [
                'login' => $username,
                'password' => $password,
                'authentication' => SOAP_AUTHENTICATION_BASIC,
                'location' => 'http://api:8080/soap.php',
                'exceptions' => true,
                'trace' => true,
            ]
        );
    }

    public function getBooks(): array
    {
        $response = $this->client->getBooks();

        return $response->book ?? [];
    }
}