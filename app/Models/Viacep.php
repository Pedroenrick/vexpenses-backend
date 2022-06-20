<?php 

namespace App\Models;

use GuzzleHttp\Client;

final class Viacep{
    private String $cep;
    private String $url = "http://viacep.com.br/ws/";
    private String $endpoint = "/json/";

    public function __construct(String $cep){
        $this->cep = $cep;
    }

    public function getCep(): String{
        return $this->cep;
    }

    public function getUrl(): String{
        return $this->url;
    }

    public function getEndpoint(): String{
        return $this->endpoint;
    }

    public function getUrlCompleta(): String{
        return $this->url . $this->cep . $this->endpoint;
    }

    static public function getAddress(String $cep){
        $viacep = new Viacep($cep);

        $guzzle = new Client();
        $address = $guzzle->request('GET', $viacep->getUrlCompleta())->getBody()->getContents();

        $address = json_decode($address);

        return $address;
    }
}