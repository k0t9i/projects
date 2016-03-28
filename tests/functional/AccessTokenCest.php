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
        $I->seeResponseCodeIs(201);
        $I->seeResponseMatchesJsonType($this->getAccessTokenJsonType());
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];
        $I->seeInDatabase('access_token', [
            'token' => $token
        ]);
        
        $I->sendPOST('/access-tokens', [
            'login' => 'this.is.invalid.login',
            'password' => 'this.is.invalid.password'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(403);
        
        $I->sendPOST('/access-tokens', [
            'login' => 'disabled',
            'password' => 'disabled'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(403);
    }
    
    public function viewAccessTokenList(FunctionalTester $I)
    {
        $I->wantTo('view access token list');
        
        $I->amBearerAuthenticated('admin-token');
        $I->sendGET('/access-tokens');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType($this->getAccessTokenJsonType(), '$[0]');
        
        $I->amBearerAuthenticated('chief-token');
        $I->sendGET('/access-tokens');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(403);
        
        $I->amBearerAuthenticated('manager-token');
        $I->sendGET('/access-tokens');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(403);
        
        $I->amBearerAuthenticated('performer-token');
        $I->sendGET('/access-tokens');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(403);
    }
    
    public function viewAccessToken(FunctionalTester $I)
    {
        $I->wantTo('view access token');
        
        $I->amBearerAuthenticated('admin-token');
        $id = $I->grabFromDatabase('access_token', 'id', [
            'token' => 'admin-token'
        ]);
        $I->sendGET('/access-tokens/' . $id);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType($this->getAccessTokenJsonType());
        
        $I->amBearerAuthenticated('admin-token');
        $id = $I->grabFromDatabase('access_token', 'id', [
            'token' => 'manager-token'
        ]);
        $I->sendGET('/access-tokens/' . $id);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $types = $this->getAccessTokenJsonType();
        unset($types['token']);
        $I->seeResponseMatchesJsonType($types);
        
        $I->amBearerAuthenticated('performer-token');
        $id = $I->grabFromDatabase('access_token', 'id', [
            'token' => 'performer-token'
        ]);
        $I->sendGET('/access-tokens/' . $id);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType($this->getAccessTokenJsonType());
        
        $I->amBearerAuthenticated('performer-token');
        $id = $I->grabFromDatabase('access_token', 'id', [
            'token' => 'admin-token'
        ]);
        $I->sendGET('/access-tokens/' . $id);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(403);
    }
    
    public function deleteAllAccessTokens(FunctionalTester $I)
    {
        $I->wantTo('delete all access tokens');
        
        $I->amBearerAuthenticated('admin-token');
        $I->sendDELETE('/access-tokens/delete-all');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeInDatabase('access_token', [
            'token' => 'admin-token'
        ]);
    }
    
    public function deleteAccessToken(FunctionalTester $I)
    {
        $I->wantTo('delete access token');
        
        $I->amBearerAuthenticated('admin-token');
        $id = $I->grabFromDatabase('access_token', 'id', [
            'token' => 'admin-token'
        ]);
        $I->sendDELETE('/access-tokens/' . $id);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(204);
        $I->dontSeeInDatabase('access_token', [
            'token' => 'admin-token'
        ]);
    }
    
    private function getAccessTokenJsonType()
    {
        return [
            'id' => 'integer',
            'idUser' => 'integer',
            'expiresIn' => 'string:date',
            'createdAt' => 'string:date',
            'token' => 'string'
        ];
    }
}
