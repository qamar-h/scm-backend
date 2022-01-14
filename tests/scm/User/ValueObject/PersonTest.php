<?php

use PHPUnit\Framework\TestCase;
use SCM\User\ValueObject\Person;

class PersonTest extends TestCase
{
    public function testPersonGetDefaultValues(): void
    {
        $person = new Person();
        $this->assertEquals('', $person->getFirstname());
        $this->assertEquals('', $person->getLastname());
        $this->assertEquals(null, $person->getDateOfBirthday());
        $this->assertEquals(true, $person->getGender());
    }

    public function testPersonGetBasicInformations(): void
    {
        $person = new Person('Qamar', 'Hayat', new \DateTime('1988-12-26'), true);
        $this->assertEquals('Qamar', $person->getFirstname());
        $this->assertEquals('Hayat', $person->getLastname());
        $this->assertEquals(true, $person->getGender());
    }
    
    public function testPersonGetFullNameSuccess(): void
    {
        $person = new Person('Qamar', 'Hayat');
        $this->assertEquals('Qamar Hayat', $person->getFullname());
    }

    public function testPersonGetAgeReturnZero(): void
    {
        $person = new Person();
        $this->assertEquals(0, $person->getAge());
    }

    public function testPersonGetAge(): void
    {
        $person = new Person('Qamar', 'Hayat', new \DateTime('1987-12-26'));
        $this->assertEquals( 34, $person->getAge());
    }
}
