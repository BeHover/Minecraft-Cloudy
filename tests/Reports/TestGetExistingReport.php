<?php

namespace App\Tests\Reports;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestGetExistingReport extends WebTestCase
{
    public function testGetExistingReport(): void
    {
        $client = static::createClient();
        $reportId = "1ee272e9-420b-6974-b5ab-bc17b810c7a5";
        $locale = "en_EN";

        $client->request('GET', '/api/reports/'.$reportId.'?locale='.$locale);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertNotEmpty($responseData);
    }
    public function testGetExistingReportWithoutLocale(): void
    {
        $client = static::createClient();
        $reportId = "1ee272e9-420b-6974-b5ab-bc17b810c7a5";

        $client->request('GET', '/api/reports/'.$reportId);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertNotEmpty($responseData);
    }
}