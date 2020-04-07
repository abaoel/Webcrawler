<?php

class Client {

    
    const USER_HTTP = '******';
    const PASS_HTTP = '******';
    const LOGIN_URL_HTTP = 'http://******';

    private $curl;

    function __construct()
    {
        if($this->curl == null)
        {
            $this->curl = new Curl();
            
        }
    }

    public function login()
    {
        
        //Get Token and Login User 
        $ch = curl_init(self::LOGIN_URL_HTTP);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        $response = curl_exec($ch);

        libxml_use_internal_errors(true);
        $dom = new DOMDocument;
        $dom->loadHTML($response);

        $tags = $dom->getElementsByTagName('input');
        
        for($i=0; $i < $tags->length;$i++)
        {
            $grab = $tags->item($i);
            if($grab->getAttribute('name')==='_token')
            {
                $token = $grab->getAttribute('value');
            }

        }

       

        $data = array(
            'email'=>self::USER_HTTP,
            'password'=> self::PASS_HTTP,
            '_token' => $token,
        );

        curl_setopt($ch, CURLOPT_URL, self::LOGIN_URL_HTTP);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        

        curl_setopt($ch, CURLOPT_URL, 'http://nabepero.xyz/crawler');
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        $response = str_get_html(curl_exec($ch));
        

        curl_close($ch);
        return $response;
        
       
    }

    public function getDataHtml($response) {
        $dom_table = new DOMDocument;
        $dom_table->loadHTML($response);
        $xpath = new DOMXPath($dom_table);
        
        $elements=$xpath->query('//table');

        foreach($elements as $element){
            echo $dom_table->saveHTML($element);
        }

        


    }

    public function getDataJSON() {
        // TODO: Implement getDataJSON() method.
    }
}