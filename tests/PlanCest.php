<?php
class PlanCest
{
    public function tryApi(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->sendGET('/user');
        $user = json_decode($I->grabResponse());

        $this->user = $user;

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function createPlan(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/plans', [
            'templates_id' => 1,
            'name'         => 'Test 1'
        ]);

        $expected = '{"id":"1","user_id":"'. $this->user->id .'","name":"Test 1","templates_id":"1","metadata":null}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/plans');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }

    public function editNonExistingPlan(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/plans/2', [
            'name'     => 'Test 2',
            'metadata' => '{"somestuff":"somevalue"}'
        ]);

        $I->seeResponseCodeIs(404);
    }


    public function editAndListPlans(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/plans/1', [
            'name'     => 'Test 2',
            'metadata' => '{"somestuff":"somevalue"}'
        ]);

        $expected = '{"id":"1","user_id":"'. $this->user->id .'","name":"Test 2","templates_id":"1","metadata":"{\"somestuff\":\"somevalue\"}"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/plans');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }
}
