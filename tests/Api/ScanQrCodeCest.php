<?php
namespace App\Tests\Api;

use App\Tests\Support\ApiTester;


class ScanQrCodeCest
{
    public function _before(ApiTester $I)
    {
        // Avant chaque test
    }

    public function testScanQrCodeWithValidData(ApiTester $I)
    {
        $I->wantTo('Scan a QR code with valid data');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/logscan/scan', [
            "runner_id" => 2,
            "marker_code" => "BALISE-1",
            "scannedAt" => "2025-06-19T12:00:00Z",
            "point" => [
                "srid" => 4326,
                "type" => "Point",
                "coordinates" => [2.3522, 48.8566]
            ]
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'valid',
            'message' => 'Scan validé',
        ]);
    }

    public function testScanQrCodeWithInvalidMarkerCode(ApiTester $I)
    {
        $I->wantTo('Scan a QR code with invalid marlker code');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/logscan/scan', [
            "runner_id" => 2,
            "marker_code" => "Marker A",
            "scannedAt" => "2025-06-19T12:00:00Z",
            "point" => [
                "srid" => 4326,
                "type" => "Point",
                "coordinates" => [2.3515, 48.8555]
            ]
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
         $I->seeResponseContainsJson([
            'error' => 'Invalid marker_code format'
        ]);
    }

    public function testScanQrCodeWithTooLongDistance(ApiTester $I)
    {
        $I->wantTo('Scan a QR code with too long distance');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/logscan/scan', [
            "runner_id" => 2,
            "marker_code" => "BALISE-2",
            "scannedAt" => "2025-06-19T12:00:00Z",
            "point" => [
                "srid" => 4326,
                "type" => "Point",
                "coordinates" => [1.4450, 43.6020]
            ]
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'invalid',
            'message' => 'Trop loin du marker (distance: 285.35 m)'
        ]);
    }

}
