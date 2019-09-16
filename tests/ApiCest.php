<?php
class ApiCest
{
    public function tryApi(ApiTester $I)
    {
        $ch = curl_init();

        $I->amHttpAuthenticated('tester', 'tester');
        $I->sendGET('/areas');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
