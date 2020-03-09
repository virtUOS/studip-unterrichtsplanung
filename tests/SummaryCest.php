<?php
class SummaryCest
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
        $I->sendPOST('/summary', $expected = [
            'structures_id' => 1,
            'text'          => 'Test 1',
            'plans_id'      => 1
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $this->summary = json_decode($I->grabResponse(), true);
    }


    public function getById(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/summary/' . $this->summary['id']);

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
        $I->sendPUT('/summary/999', [
            'structures_id' => 2,
            'text'          => 'Test 2',
            'plans_id'       => 1
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
        $I->sendPUT('/summary/' . $this->summary['id'], $expected = [
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
        $I->sendGET('/summary/2/2');

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
        $I->sendPOST('/summary', $expected = [
            'structures_id' => 1,
            'text'          => 'Test Metadata',
            'plans_id'      => 1,
            'metadata'      => 'Testdaten'
        ]);

        $summary = json_decode($I->grabResponse());

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/summary/' . $summary->id, $expected = [
            'structures_id' => 1,
            'text'          => 'Test Metadata',
            'plans_id'      => 1,
            'metadata'      => 'andere Testdaten'
        ]);

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
        $I->sendPOST('/summary', [
            'structures_id' => 1,
            'text'          => 'Test 1',
            'plans_id'      => 1
        ]);

        $summary = json_decode($I->grabResponse());

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/summary/' . $summary->id);

        $I->seeResponseCodeIs(200);
    }
}
