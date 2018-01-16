<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Etix\Etix;

final class EtixTest extends TestCase
{
    public function testHelloWorld()
    {
        $this->assertEquals(
            'Hello World!',
            Etix::HelloWorld()
        );
    }
    public function testForbidden()
    {
        $this->expectException(GuzzleHttp\Exception\ClientException::class);
        $etix = new Etix();
        $etix->getVenues();
    }
}

