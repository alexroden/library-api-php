<?php

namespace AlexRoden\Importers\Soap;

use SoapClient;
use SoapFault;

class BookClient
{
    private SoapClient $client;

    /**
     * @throws SoapFault
     */
    public function __construct(string $baseUrl, string $username, string $password)
    {
        $this->client = new SoapClient(
            "{$baseUrl}/soap.php?wsdl",
            [
                'login' => $username,
                'password' => $password,
                'authentication' => SOAP_AUTHENTICATION_BASIC,
                /*
                 * The WSDL advertises localhost, which is not reachable from
                 * another container, so the endpoint is overridden here.
                 */
                'location' => "{$baseUrl}/soap.php",
                'exceptions' => true,
                'trace' => true,
            ]
        );
    }

    /**
     * The full record for a single book, including the description, tags and
     * category the list method leaves out.
     *
     * @throws SoapFault
     */
    public function getBook(int $id): BookDetail
    {
        $response = $this->client->getBook(['id' => $id]);

        return BookDetail::fromResponse($response->book);
    }

    /**
     * @return BookSummary[]
     *
     * @throws SoapFault
     */
    public function getBooks(): array
    {
        $response = $this->client->getBooks();

        $books = $response->book ?? [];

        /*
         * A single repeated element comes back as an object rather than an
         * array, so it is normalised before mapping.
         */
        if (!is_array($books)) {
            $books = [$books];
        }

        return array_map(
            static fn (object $book): BookSummary => BookSummary::fromResponse($book),
            $books
        );
    }
}
