<?php

namespace App\Tests\Reports;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestGetDataReport extends WebTestCase
{
    public function testGetDataReport(): void
    {
        $client = static::createClient();
        $reportId = "1ee272e9-420b-6974-b5ab-bc17b810c7a5";
        $locale = "en_EN";

        $client->request('GET', '/api/reports/'.$reportId.'?locale='.$locale);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals("1ee272e9-3c67-6b94-bf2c-bc17b810c7a5", $responseData["createdBy"]["id"]);
        $this->assertEquals("CHYZHOV", $responseData["createdBy"]["username"]);
        $this->assertEquals("1ee272e9-4121-6ce8-807d-bc17b810c7a5", $responseData["type"]["id"]);
        $this->assertEquals("Жалоба на игрока", $responseData["type"]["name"]);
        $this->assertFalse($responseData["isActive"]);
        $this->assertNotEmpty($responseData);
    }
    public function testGetDataReportWithoutLocale(): void
    {
        $client = static::createClient();
        $reportId = "1ee272e9-420b-6974-b5ab-bc17b810c7a5";

        $client->request('GET', '/api/reports/'.$reportId);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals("1ee272e9-3c67-6b94-bf2c-bc17b810c7a5", $responseData["createdBy"]["id"]);
        $this->assertEquals("CHYZHOV", $responseData["createdBy"]["username"]);
        $this->assertEquals("1ee272e9-4121-6ce8-807d-bc17b810c7a5", $responseData["type"]["id"]);
        $this->assertEquals("Жалоба на игрока", $responseData["type"]["name"]);
        $this->assertFalse($responseData["isActive"]);
        $this->assertNotEmpty($responseData);
    }
}