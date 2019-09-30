<?php
class TemplateCest
{
    public function createPlan(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/templates', [
            'name'         => 'Test 1'
        ]);

        $expected = '{"id":"1","name":"Test 1"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/templates');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }

    public function editAndListPlans(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/templates/1', [
            'name'     => 'Test 2'
        ]);

        $expected = '{"id":"1","name":"Test 2"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/templates');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }
}
