<?php
namespace App\Tests\Api;

use App\Tests\Support\ApiTester;
class RunnerTestCest
{
    public function _before(ApiTester $I)
    {
        // Avant chaque test
    }

    public function testPostLoginProfessor(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/runners/login', [
            'code' => 'Course1-1'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => 1,
            'course' => 'Course A',
            'name' => 'Prof1',
            'isTeacher' => true,
            'teacherId' => 2,
            'courseId' => 1
        ]);
    }

    public function testPostInvalidRunner(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/runners/login', [
            'code' => 'Course1-999' // Code invalide
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function testPostLoginRunner(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/runners/login', [
            'code' => 'Course1-2'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => 2,
            'course' => 'Course A',
            'name' => 'Runner 1',
            'isTeacher' => false,
            'teacherId' => null,
            'courseId' => 1
        ]);
    }

    public function testPostLoginRunnerOtherCourses(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/runners/login', [
            'code' => 'Course2-2'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'id' => 4,
            'course' => 'Course B',
            'name' => 'Runner 2',
            'isTeacher' => false,
            'teacherId' => null,
            'courseId' => 2
        ]);
    }
}
