<?php
class InfotextsCest
{
    public function create(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        // create template
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/templates', [
            'name'         => 'Test Infotext'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $this->template = json_decode($I->grabResponse(), true);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/infotexts', $expected = [
            'structures_id' => 1,
            'templates_id'  => $this->template['id'],
            'text'          => 'Test 1'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $this->infotext = json_decode($I->grabResponse(), true);

        $I->sendGET('/infotexts');
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
        $I->sendPUT('/infotexts/999', [
            'structures_id' => 2,
            'templates_id'  => $this->template['id'],
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
        $I->sendPUT('/infotexts/' . $this->infotext['id'], $expected = [
            'structures_id' => 2,
            'templates_id'  => $this->template['id'],
            'text'          => 'Test 2'
        ]);


        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $I->sendGET('/infotexts');
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
        $I->sendDELETE('/infotexts/' . $this->infotext['id']);

        $I->seeResponseCodeIs(200);
    }
}
