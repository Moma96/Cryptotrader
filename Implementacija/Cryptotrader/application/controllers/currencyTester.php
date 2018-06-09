<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kontroler za testiranje kriptovaluta.
 * 
 * @author Momcilo Nikolic 0579/2015
 * 
 * @version 1.0
*/
class CurrencyTester extends CI_Controller {
    
    /**
     * Id testirane kriptovalute.
     * 
     * @var int $currency
    */
    public $currency = 'eth';
    
    /**
     * Konstruktor za klasu CurrencyTester
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('CryptocurrModel');
        $this->load->model('PriceModel');
    }
    
    /**
     * Funkcija za generisanje cena kriptovaluta.
     * 
     * @return void
     *
    */
    public function generate_prices() {
        $starting_price = 500;
        $time_since = '2017-05-20 00:00:00';
        $max_price_jump = 0.01;
        $max_frequency = $this->PriceModel->frequency * 120;
        
        $price = $starting_price;
        $now = strtotime(date('Y-m-d H:i:s'));
        $time = strtotime($time_since);
        while ($time <= $now) {
            $this->PriceModel->add_price($this->currency, date('Y-m-d H:i:s', $time), $price);
            $price = mt_rand($price*100*(1 - $max_price_jump), $price*100*(1 + $max_price_jump))/100;
            $time += mt_rand($this->PriceModel->frequency, $max_frequency);
        }
        redirect(base_url());
    }
    
    /**
     * Funkcija za brisanje cena kriptovaluta.
     * 
     * @return void
     *
    */
    public function delete_prices() {
        $this->PriceModel->delete_all($this->currency);
        redirect(base_url());
    }
}
