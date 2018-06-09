<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kontroler za opcije kojima ima pristup administrator.
 * 
 * @author Luka Nikolic 0619/2015
 * 
 * @version 1.0
*/
class Guest extends CI_Controller {
    
    /**
     * Konstruktor za klasu Cryptotrader
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
     * Funkcija za ucitavanje pocetne stranice za registrovanje korisnika.
     * 
     * @return void
     *
    */
    public function register() {
        $this->load->view('register');
    }
}