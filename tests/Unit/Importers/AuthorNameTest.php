<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Importers;

use AlexRoden\Importers\Soap\AuthorName;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AuthorNameTest extends TestCase
{
    #[DataProvider('names')]
    public function testSplitsTheNameIntoFirstAndLastName(
        string $author,
        string $firstName,
        string $lastName,
    ): void {
        $name = AuthorName::fromString($author);

        $this->assertEquals($firstName, $name->firstName);
        $this->assertEquals($lastName, $name->lastName);
    }

    public static function names(): array
    {
        return [
            'two words' => ['Elias Langley', 'Elias', 'Langley'],
            'multi word last name' => ['Jean de la Fontaine', 'Jean', 'de la Fontaine'],
            'double barrelled last name' => ['Nadia Fenwick-Hale', 'Nadia', 'Fenwick-Hale'],
            'single word' => ['Aristotle', 'Aristotle', ''],
            'surrounding whitespace' => ['  Rowan Ashcroft  ', 'Rowan', 'Ashcroft'],
            'repeated whitespace' => ["Lydia \t Blackwood", 'Lydia', 'Blackwood'],
            'repeated whitespace in last name' => ['Theodore  van  Owen', 'Theodore', 'van Owen'],
        ];
    }

    public function testRejectsAnEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);

        AuthorName::fromString("  \n ");
    }
}
