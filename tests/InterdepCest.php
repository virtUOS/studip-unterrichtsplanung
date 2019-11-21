<?php
class InterdepCest
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

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/plans', [
            'templates_id' => 1,
            'name'         => 'Test 1'
        ]);

        $I->seeResponseCodeIs(200);
        $this->plan = json_decode($I->grabResponse());
    }

    public function create(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/interdeps/' . $this->plan->id . '/1', [
            'references'    => '{"1":true,"2":false}'
        ]);

        $expected = [
            'plans_id'      => $this->plan->id,
            'structures_id' => 1,
            'references'    => '{"1":true,"2":false}'
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }

    public function createOnNonExistingPlan(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/interdeps/99/1', [
            'references'    => '{"1":true,"2":false}'
        ]);

        $I->seeResponseCodeIs(403);
    }

    public function editNonExistingStructure(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/interdeps/' . $this->plan->id . '/5', [
            'references'    => '{"1":true,"2":false}'
        ]);

        $I->seeResponseCodeIs(404);
    }

    public function editNonExistingPlan(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/interdeps/99/1', [
            'references'    => '{"1":true,"2":false}'
        ]);

        $I->seeResponseCodeIs(404);
    }

    public function edit(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/interdeps/' . $this->plan->id . '/1', [
            'references'    => '{"1":false,"2":true}'
        ]);

        $expected = [
            'plans_id'      => $this->plan->id,
            'structures_id' => 1,
            'references'    => '{"1":false,"2":true}'
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }

    public function getByPlandAndStructureId(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/interdeps/' . $this->plan->id . '/1');

        $expected = [
            'plans_id'      => $this->plan->id,
            'structures_id' => 1,
            'references'    => '{"1":false,"2":true}'
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }
}
