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
        $I->sendPOST('/textfields', $expected = [
            'structures_id' => 1,
            'text'          => 'Test 1',
            'plans_id'      => 1,
            'position'      => 1
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $this->textfield = json_decode($I->grabResponse(), true);
    }


    public function getById(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/textfields/'. $this->textfield['id']);

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
        $I->sendPUT('/textfields/999', [
            'structures_id' => 2,
            'text'          => 'Test 2',
            'plans_id'       => 1
        ]);

        $I->seeResponseCodeIs(404);
    }

    public function getByPlanId(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/plans/1/textfields');

        $expected = [
            'structures_id' => 1,
            'text'          => 'Test 1',
            'plans_id'      => 1
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON(['data' => [0 => ['attributes' => $expected]]]);
    }

    public function edit(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/textfields/' . $this->textfield['id'], $expected = [
            'structures_id' => 2,
            'text'          => 'Test 2',
            'plans_id'       => 2
        ]);

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

    public function createAndEditWithMetadata(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/textfields', $expected = [
            'structures_id' => 1,
            'text'          => 'Test Metadata',
            'plans_id'      => 1,
            'metadata'      => 'Testdaten',
            'position'      => 1
        ]);

        $textfield = json_decode($I->grabResponse());

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/textfields/' . $textfield->id, $expected = [
            'structures_id' => 1,
            'text'          => 'Test Metadata',
            'plans_id'      => 1,
            'metadata'      => 'andere Testdaten'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }

    public function setPositions(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/plans/1/textfields/position', [
            'textfields' => [
                'id'       => $this->textfield['id'],
                'position' => 99
            ],
            'plans_id'   => 1
        ]);

        $positions = json_decode($I->grabResponse());
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
            'plans_id'      => 1,
            'position'      => 1
        ]);

        $textfield = json_decode($I->grabResponse());

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/textfields/' . $textfield->id);

        $I->seeResponseCodeIs(200);
    }
}
