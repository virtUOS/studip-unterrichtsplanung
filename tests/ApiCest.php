<?php
class ApiCest
{
    public function tryApi(ApiTester $I)
    {
        $I->amHttpAuthenticated('tester', 'tester');
        $I->sendGET('/plans');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function createPlan(ApiTester $I)
    {
        $I->amHttpAuthenticated('tester', 'tester');

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/plans', [
            'templates_id' => 1,
            'name'         => 'Test 1'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"id":"1","user_id":"0b2d7e24bfee348b28b4efc8baa2eaf8","name":"Test 1","templates_id":"1"}');
    }
}
