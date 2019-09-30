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
    }

    public function create(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );


        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/structures/1/interdeps', [
            'references'    => '{"1":true,"2":false}'
        ]);

        $expected = '{"structures_id":"1","references":"{\"1\":true,\"2\":false}","user_id":"'. $this->user->id .'","id":"1_'. $this->user->id .'"}';

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
        $I->sendPUT('/structures/5/interdeps', [
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
        $I->sendPUT('/structures/1/interdeps', [
            'references'    => '{"1":false,"2":true}'
        ]);

        $expected = '{"structures_id":"1","references":"{\"1\":false,\"2\":true}","user_id":"'. $this->user->id .'","id":"1_'. $this->user->id .'"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }

    public function getByIdStructureId(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/structures/1/interdeps');

        $expected = '{"structures_id":"1","references":"{\"1\":false,\"2\":true}","user_id":"'. $this->user->id .'","id":"1_'. $this->user->id .'"}';

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains($expected);
    }
}
