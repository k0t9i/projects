<?php

class AccessTokenCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function createAccessTokenWithInvalidCredentials(FunctionalTester $I)
    {
        $I->wantTo('create access token with invalid credentials');
        $I->sendPOST('/access-tokens', [
            'login' => 'this.is.invalid.login',
            'password' => 'this.is.invalid.password'
        ]);
        $I->seeResponseCodeIs(403);
    }
    
    public function createAccessToken(FunctionalTester $I)
    {
        $I->wantTo('create access token');
        $I->sendPOST('/access-tokens', [
            'login' => 'admin',
            'password' => 'admin'
        ]);
        $I->seeResponseCodeIs(201);
    }
    
    public function createAccessTokenByDisabledUser(FunctionalTester $I)
    {
        $I->wantTo('create access token by disabled user');
        $I->sendPOST('/access-tokens', [
            'login' => 'disabled',
            'password' => 'disabled'
        ]);
        $I->seeResponseCodeIs(403);
    }
}
