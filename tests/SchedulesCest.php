<?php
class SchedulesCest
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
        $I->sendPOST('/schedules', $expected = [
            'content'          => 'Test 1',
            'plans_id'      => 1
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJSON($expected);

        $this->schedule = json_decode($I->grabResponse(), true);
    }


    public function getById(ApiTester $I)
    {
        $I->amHttpAuthenticated(
            $GLOBALS['container']['USERNAME'],
            $GLOBALS['container']['PASSWORD']
        );

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/schedules/' . $this->schedule['id']);

        $expected = [
            'content'  => 'Test 1',
            'plans_id' => 1
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
        $I->sendPUT('/schedules/999', [
            'content'  => 'Test 2',
            'plans_id' => 1
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
        $I->sendGET('/plans/1/schedules');

        $expected = [
            'content'  => 'Test 1',
            'plans_id' => 1
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
        $I->sendPUT('/schedules/' . $this->schedule['id'], $expected = [
            'content'  => 'Test 2',
            'plans_id' => 2
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
        $I->sendPOST('/schedules', [
            'content'  => 'Test 1',
            'plans_id' => 1
        ]);

        $schedules = json_decode($I->grabResponse());

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/schedules/' . $schedules->id);

        $I->seeResponseCodeIs(200);
    }
}
