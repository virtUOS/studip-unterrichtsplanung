<?php
class StructureCest
{
    public function createPlan(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/structures', [
            'name'         => 'Test 1'
        ]);

        $expected = '{"id":"1","parent_id":"0","name":"Test 1"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/structures');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }

    public function editNonExistingTemplate(ApiTester $I)
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

    public function editAndListPlans(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/structures/1', [
            'name'     => 'Test 2'
        ]);

        $expected = '{"id":"1","parent_id":"0","name":"Test 2"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/structures');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }
}
