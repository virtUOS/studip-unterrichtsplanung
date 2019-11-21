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

    public function create(ApiTester $I)
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

        $expected = '{"user_id":"'. $this->user->id .'","name":"Test 1","templates_id":"1","metadata":null}';
        $expected = [
            'user_id'      => $this->user->id,
            'name'         => 'Test 1',
            'templates_id' => '1',
            'metadata'     => null
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $this->plan = json_decode($I->grabResponse());

        $I->sendGET('/plans');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }

    public function editNonExisting(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/plans/99', [
            'name'     => 'Test 2',
            'metadata' => '{"somestuff":"somevalue"}'
        ]);

        $I->seeResponseCodeIs(404);
    }


    public function editAndList(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/plans/' . $this->plan->id, [
            'name'     => 'Test 2',
            'metadata' => '{"somestuff":"somevalue"}'
        ]);

        $expected = [
            'user_id'      => $this->user->id,
            'name'         => 'Test 2',
            'templates_id' => '1',
            'metadata'     => '{"somestuff":"somevalue"}'
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $I->sendGET('/plans');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }

    public function delete(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/plans/' . $this->plan->id);

        $I->seeResponseCodeIs(200);
    }
}
