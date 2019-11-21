<?php
class TextfieldCest
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
        $I->sendPOST('/textfields', [
            'structures_id' => 1,
            'text'          => 'Test 1',
            'plan_id'       => 1
        ]);

        $expected = [
            'structures_id' => 1,
            'text'          => 'Test 1',
            'plans_id'      => 1
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }


    public function getById(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/textfields/1');

        $expected = [
            'structures_id' => 1,
            'text'          => 'Test 1',
            'plans_id'      => 1
        ];


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
        $I->sendPUT('/textfields/2', [
            'structures_id' => 2,
            'text'          => 'Test 2',
            'plans_id'       => 1
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
        $I->sendPUT('/textfields/1', [
            'structures_id' => 2,
            'text'          => 'Test 2',
            'plans_id'       => 2
        ]);

        $expected = [
            'structures_id' => 2,
            'text'          => 'Test 2',
            'plans_id'      => 2
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }

    public function getByPlanIdAndStructureId(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/textfields/2/2');

        $expected = [
            'structures_id' => 2,
            'text'          => 'Test 2',
            'plans_id'      => 2
        ];

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
        $I->sendPOST('/textfields', [
            'structures_id' => 1,
            'text'          => 'Test 1',
            'plan_id'       => 1
        ]);

        $textfield = json_decode($I->grabResponse());

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/textfields/' . $textfield->id);

        $I->seeResponseCodeIs(200);
    }
}
