<?php

class AccessTokenCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }
    
    public function createAccessToken(FunctionalTester $I)
    {
        $I->wantTo('create access token');
        $I->sendPOST('/access-tokens', [
            'login' => 'admin',
            'password' => 'admin'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([]);
        $I->seeResponseCodeIs(201);
        
        $I->sendPOST('/access-tokens', [
            'login' => 'this.is.invalid.login',
            'password' => 'this.is.invalid.password'
        ]);
        $I->seeResponseCodeIs(403);
        
        $I->sendPOST('/access-tokens', [
            'login' => 'disabled',
            'password' => 'disabled'
        ]);
        $I->seeResponseCodeIs(403);
    }
    
    public function viewAccessTokenList(FunctionalTester $I)
    {
        $I->wantTo('view access token list');
        $I->sendGET('/access-tokens', [
            'access-token' => 'admin-token'
        ]);
        $I->seeResponseCodeIs(200);
    }
}
