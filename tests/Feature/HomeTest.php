<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testHomePage()
    {
        $response = $this->get('/');

        $response->assertSeeText('Hello world!');
        $response->assertSeeText(':P');
    }

    public function testContactPage()
    {
        $response = $this->get('/contact');

        $response->assertSeeText('Contact page');
        $response->assertSeeText(':OO');
    }
}
