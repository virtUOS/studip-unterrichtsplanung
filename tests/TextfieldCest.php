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
            'text'          => 'Test 1'
        ]);

        $expected = '{"id":"1","structures_id":"1","text":"Test 1","user_id":"'. $this->user->id .'"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/textfields');
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
        $I->sendPUT('/textfields/2', [
            'structures_id' => 2,
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
        $I->sendPUT('/textfields/1', [
            'structures_id' => 2,
            'text'          => 'Test 2'
        ]);

        $expected = '{"id":"1","structures_id":"1","text":"Test 1","user_id":"'. $this->user->id .'"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);

        $I->sendGET('/textfields');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }
}
