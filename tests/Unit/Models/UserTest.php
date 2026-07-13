<?php

use App\Database\Query;
use App\Models\User;
use Tests\AbstractDatabaseTestCase;

class UserTest extends AbstractDatabaseTestCase
{
    public function testCreate(): void
    {
        $model = new User(new Query($this->db));

        $id = $model->create([
            'email' => 'john.smith@example.com',
            'password' => 'password',
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);

        $this->assertSame(1, $id);
    }
}