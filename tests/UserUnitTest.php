<?php

namespace App\Tests;

use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();
        $birthDate = new DateTime();

        $user->setFirstName('prenom')
            ->setLastName('Nom')
            ->setEmail('true@test.com')
            ->setPassword('password')
            ->setDateOfBirth($birthDate)
            ->setRoles(['ROLE_USER']);

        $this->assertTrue($user->getFirstName() === 'prenom');
        $this->assertTrue($user->getLastName() === 'nom');
        $this->assertTrue($user->getEmail() === 'true@test.com');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getDateOfBirth() === $birthDate);
        $this->assertTrue($user->getRoles() === ['ROLE_USER']);
    }

    public function testIsFalse()
    {
        $user = new User();
        $birthDate = new DateTime();

        $user->setFirstName('prenom')
            ->setLastName('Nom')
            ->setEmail('true@test.com')
            ->setPassword('password')
            ->setDateOfBirth($birthDate)
            ->setRoles(['ROLE_USER']);

        $this->assertfalse($user->getFirstName() === 'false');
        $this->assertfalse($user->getLastName() === 'false');
        $this->assertfalse($user->getEmail() === 'false@test.com');
        $this->assertfalse($user->getPassword() === 'false');
        $this->assertfalse($user->getDateOfBirth() === $birthDate);
        $this->assertfalse($user->getRoles() === ['false']);
    }

    public function testIsEmpty()
    {
        $user = new User();

        $this->assertEmpty($user->getFirstName() );
        $this->assertEmpty($user->getLastName() );
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword() );
        $this->assertEmpty($user->getDateOfBirth() );
        $this->assertEmpty($user->getRoles() );

    }
}
