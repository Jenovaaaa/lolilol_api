<?php
class Settings {
    
    public static $apiKey = 'RGAPI-91a7e872-efae-402d-bd8f-c9245c5f0093';
    public static $apiUrl = 'api.riotgames.com/lol/';
    
    public static $apiUri = array(
        'summonerByName' => 'summoner/v4/summoners/by-name/',
        'currentGame' => 'spectator/v4/active-games/by-summoner/',
        'championsMasteries' => 'champion-mastery/v4/champion-masteries/by-summoner/'
    );
}