<?php
class ApiCest
{
    const USERNAME = 'tester';
    const PASSWORD = 'tester';

    public function tryApi(ApiTester $I)
    {
        $I->amHttpAuthenticated(self::USERNAME, self::PASSWORD);

        $I->sendGET('/user');
        $user = json_decode($I->grabResponse());

        $this->user = $user;

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function createPlan(ApiTester $I)
    {
        $I->amHttpAuthenticated(self::USERNAME, self::PASSWORD);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/plans', [
            'templates_id' => 1,
            'name'         => 'Test 1'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"id":"1","user_id":"'. $this->user->id .'","name":"Test 1","templates_id":"1"}');

        $I->sendGET('/plans');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"id":"1","user_id":"'. $this->user->id .'","name":"Test 1","templates_id":"1"}');
    }

    public function editAndListPlans(ApiTester $I)
    {
        $I->amHttpAuthenticated(self::USERNAME, self::PASSWORD);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/plans/1', [
            'templates_id' => 2,
            'name'         => 'Test 2'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"id":"1","user_id":"'. $this->user->id .'","name":"Test 2","templates_id":"2"}');

        $I->sendGET('/plans');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"id":"1","user_id":"'. $this->user->id .'","name":"Test 2","templates_id":"2"}');
    }
}
