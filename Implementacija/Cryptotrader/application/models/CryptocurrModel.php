<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model za dohvatanje i azuriranje informacija vezanih za kriptovalute.
 * 
 * @author Luka Nikolic 0619/2015
 * 
 * @version 1.0
*/
class CryptocurrModel extends CI_Model {
    
    /**
     * Konstruktor za klasu WalletModel
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('WalletModel');
        $this->load->model('TransactionModel');
        $this->load->model('PriceModel');
    }
    
    /**
     * Funkcija za dodavanje kriptovaluta.
     * 
     * @param string $cryptoId
     * @param string $name
     *
     * @return string
     *
     * @throws AlreadyExistingCurrencyException
     *
    */
    public function add_currency($cryptoId, $name) {
        $cryptoId = strtolower($cryptoId);
        if ($this->get_currency($cryptoId)){
            throw new Exception('There is already a cryptocurrency in the database with that id!');
        }
        $currency = array(
        'cryptoId' => $cryptoId,
        'name' => $name
        );
        $this->db->insert('currency', $currency);
        return strtoupper($cryptoId).' was successfully added!';
    }
    
    /**
     * Funkcija za brisanje kriptovaluta.
     * 
     * @param string $cryptoId
     * @param string $name
     *
     * @return string
     *
     * @throws NonExistingCurrencyException
     * @throws WrongCurrencyNameException
     *
    */
    public function delete_currency($cryptoId, $name) {
        $cryptoId = strtolower($cryptoId);
        if ($cryptoId == 'btc' || $cryptoId == 'usdt') {
            throw new Exception('Bitcoin and Tether can not be deleted!');
        }
        $curr = $this->get_currency($cryptoId);
        if (empty($curr)) {
            throw new Exception('There is no cryptocurrency in the database with that id!');
        }
        if ($name != $curr->name) {
            throw new Exception('Wrong name! Try again!');
        }
        $this->WalletModel->delete_wallets($cryptoId);
        $this->TransactionModel->delete_active($cryptoId);
        $this->PriceModel->delete_all($cryptoId);
        $this->db->where('cryptoId', $cryptoId);
        $this->db->delete('currency');
        return strtoupper($cryptoId).' was successfully removed!';
    }
    
    /**
     * Funkcija za dohvatanje svih kriptovaluta.
     *
     * @return DBcurrency[]
     * 
    */
    public function get_currencies() {
        $this->db->order_by('cryptoId', 'ASC');
        $query = $this->db->get('currency');
        return $query->result();
    }
    
    /**
     * Funkcija za dohvatanje kritpovalute.
     * 
     * @param string $cryptoId
     *
     * @return DBcurrency
     *
    */
    public function get_currency($cryptoId) {
        $cryptoId = strtolower($cryptoId);
        $this->db->where('cryptoId',$cryptoId);
        $query = $this->db->get('currency');
        return $query->row();
    }
    
    /**
     * Funkcija za dohvatanje trenutne cene kriptovalute.
     * 
     * @param string $cryptoId
     *
     * @return double
     *
     * @throws NonExistingCurrencyException
     *
    */
    public function get_price($cryptoId) {
        $cryptoId = $this->check($cryptoId);
        return floatval($this->get_currency($cryptoId)->currPrice);
    }
    
    /**
     * Funkcija koja proverava da li kriptovaluta postoji u bazi.
     * 
     * @param string $cryptoId
     *
     * @return string
     *
     * @throws NonExistingCurrencyException
     *
    */
    public function check($cryptoId) {
        $cryptoId = strtolower($cryptoId);
        if (!$this->get_currency($cryptoId)){
            throw new Exception("That cryptocurrency does not exist in our database!");
        }
        return $cryptoId;
    }
    
    /**
     * Funkcija koja azurira cenu kriptovalute.
     * 
     * @param string $cryptoId
     * @param double $price
     *
     * @return void
     *
     * @throws NonExistingCurrencyException
     *
    */
    public function update_price($cryptoId, $price) {
        $cryptoId = strtolower($cryptoId);
        $this->db->set('currPrice', $price);
        $this->db->where('cryptoId', $cryptoId);
        $this->db->update('currency');
        
        $this->PriceModel->add($cryptoId, $price);
    }
}
