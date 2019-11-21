<?php
class StructureCest
{
    public function create(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/structures', [
            'name'         => 'Test 1'
        ]);

        $expected = [
            'parent_id' => 0,
            'name'      => 'Test 1'
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $I->sendGET('/structures');
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
        $I->sendPUT('/structures/2', [
            'name'     => 'Test 2',
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
        $I->sendPUT('/structures/1', [
            'name'     => 'Test 2'
        ]);

        $expected = [
            'parent_id' => 0,
            'name'      => 'Test 2'
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $I->sendGET('/structures');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }

    public function addChildStructureWithMissingParent(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/structures', [
            'name'         => 'Test 3',
            'parent_id'    => 3
        ]);

        $I->seeResponseCodeIs(404);
    }

    public function addChildStructure(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/structures', [
            'name'         => 'Test 3',
            'parent_id'    => 1
        ]);

        $expected = [
            'parent_id' => 1,
            'name'      => 'Test 3'
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);
    }
}
