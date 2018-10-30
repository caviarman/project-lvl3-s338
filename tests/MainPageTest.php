<?php

class MainPageTest extends TestCase
{
    /**
     * A basic response test.
     *
     * @return void
     */
    public function testApplication()
    {
        $status = $this->call('GET', '/')->status();
        $this->assertEquals(200, $status);
    }
}
