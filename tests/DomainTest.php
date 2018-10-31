<?php

class DomainTest extends TestCase
{

    public function testAddDomain()
    {
        $url = 'https://ru.hexlet.io';
        $this->post('/domains', ['address' => $url]);
        $this->seeInDatabase('domains', ['name' => $url]);
    }
}
