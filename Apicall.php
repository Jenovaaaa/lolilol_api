<?php

class Apicall {

    private $players;
    private $region;
    private $name;

    public function __construct($name = '', $region = 'euw1') {
        $this->name = $name;
        $this->region = $region;
    }

    private function callUrlApi($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, str_replace(' ', '%20', $url));
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
            
    
    }

    private function getSummonerByName() {
        $name = $this->getRandomUsername();
        $url ='https://' .$this->region . '.' . Settings::$apiUrl . Settings::$apiUri['summonerByName'] . $this->name . '?api_key=' . Settings::$apiKey;
        $data = $this->callUrlApi($url);
        return $data;
    }

    private function getCurrentGame($encryptedSummonerId) {
        $url ='https://' . $this->region . '.' . Settings::$apiUrl . Settings::$apiUri['currentGame'] . $encryptedSummonerId . '?api_key=' . Settings::$apiKey;
        $data = $this->callUrlApi($url);
        return $data;
    }

    private function getRandomUsername() {
        $url = 'https://euw1.api.riotgames.com/lol/spectator/v4/featured-games?api_key=' . Settings::$apiKey;
        $data = $this->callUrlApi($url);
        return $data['gameList'][0]['participants'][0]['summonerName'];
    }

    private function sortParticipants($fullGameInformations) {
        $participantsData = [];   
        for ($i = 0; $i < count($fullGameInformations); $i++) {
            $participantsData[$i]['summonerName'] = $fullGameInformations[$i]['summonerName'];
            $participantsData[$i]['summonerId'] = $fullGameInformations[$i]['summonerId'];
            $participantsData[$i]['teamId'] = $fullGameInformations[$i]['teamId'];
            $participantsData[$i]['championId'] = $fullGameInformations[$i]['championId'];
        }
        return $participantsData;
    }

    private function addChampionsMasteries() {
        for ($i = 0; $i < count($this->players); $i++) {
            $championMastery = $this->callUrlApi('https://' . $this->region . '.' . Settings::$apiUrl . Settings::$apiUri['championsMasteries'] . $this->players[$i]['summonerId'] . '/by-champion/' . $this->players[0]['championId'] . '?api_key=' . Settings::$apiKey);
            $this->players[$i]['championLevel'] = $championMastery['championLevel'];
            $this->players[$i]['championPoints'] = $championMastery['championPoints'];
        } 
    }

    public function addSummonerLevel() {
        for ($i = 0; $i < count($this->players); $i++) {
            $summonerInformations = $this->callUrlApi('https://' .$this->region . '.' . Settings::$apiUrl . Settings::$apiUri['summonerByName'] . $this->players[$i]['summonerName'] . '?api_key=' . Settings::$apiKey);
            $this->players[$i]['summonerLevel'] = $summonerInformations['summonerLevel'];
        } 
    }

    public function getSummonersGameInformations($region, $name) {
        $this->name = $this->getRandomUsername();
        $mainSummonerInfo = $this->getSummonerByName();
        $mainSummonerId = $mainSummonerInfo['id'];
        $currentGameInformations = $this->getCurrentGame($mainSummonerId);
        $this->players = $this->sortParticipants($currentGameInformations['participants']);
        $this->addChampionsMasteries();
        $this->addSummonerLevel();
        return(json_encode($this->players));
    }
    
}