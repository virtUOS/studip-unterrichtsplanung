<?php
class InfotextsCest
{
    public function create(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/infotexts', [
            'structures_id' => 1,
            'templates_id'  => 1,
            'text'          => 'Test 1'
        ]);

        $expected = '{"id":"1","structures_id":"1","templates_id":"1","text":"Test 1"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/infotexts');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }

    public function editNonExisting(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/infotexts/2', [
            'structures_id' => 2,
            'templates_id'  => 2,
            'text'          => 'Test 2'
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
        $I->sendPUT('/infotexts/1', [
            'structures_id' => 2,
            'templates_id'  => 2,
            'text'          => 'Test 2'
        ]);

        $expected = '{"id":"1","structures_id":"2","templates_id":"2","text":"Test 2"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/infotexts');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }
}