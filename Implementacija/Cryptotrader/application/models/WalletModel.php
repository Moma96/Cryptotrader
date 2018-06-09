<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model za dohvatanje i azuriranje informacija vezanih za wallet-e.
 * 
 * @author Luka Nikolic 0619/2015
 * 
 * @version 1.0
*/
class WalletModel extends CI_Model {
    
    /**
     * Konstruktor za klasu WalletModel
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('CryptocurrModel');
    }
    
    /**
     * Funkcija za ulaganje kriptovaluta. Ukoliko korisnik vec ima
     * neku kolicinu zadate valute, kolicina ce se azurirati, u suprotnom
     * ce se napraviti novi wallet sa zadatom sumom.
     * 
     * @param string $cryptoId
     * @param string $email
     * @param double $amount
     *
     * @return string
     *
     * @throws NonExistingCurrencyException
     *
    */
    public function deposit($cryptoId, $email, $amount) {
        $wallet = $this->get_wallet($cryptoId, $email);
        if (!$wallet){
            $this->create_wallet($cryptoId, $email, $amount);
        }
        else {
            $update_amount = $wallet->amount + $amount;
            $this->update_wallet($wallet, $update_amount);
        }
        return "You have successfully deposited $amount ".strtoupper($cryptoId).'!';
    }
    
    /**
     * Funkcija za povlacenje kriptovaluta. Ukoliko korisnik povuce sav novac,
     * wallet ce biti izbrisan, u suprotnom ce mu se suma azurirati.
     * 
     * @param string $cryptoId
     * @param string $email
     * @param double $amount
     *
     * @return string
     *
     * @throws NonExistingCurrencyException
     * @throws NotEnoughFundsException
     *
    */
    public function withdraw($cryptoId, $email, $amount) {
        $wallet = $this->get_wallet($cryptoId, $email);
        if (!$wallet || $wallet->amount - $amount < 0) {
            throw new Exception('You have not enough funds on that wallet to proceed with the withdrawal');
        }
        else if ($wallet->amount == $amount) {
            $this->delete_wallet($wallet);
        }
        else {
            $update_amount = $wallet->amount - $amount;
            $this->update_wallet($wallet, $update_amount);
        }
        return "You have successfully withdrawn $amount ".strtoupper($cryptoId).'!';
    }
    
    /**
     * Funkcija za transfer kriptovalute sa jednog wallet-a na drugi.
     * 
     * @param string $sourceEmail
     * @param string $destEmail
     * @param string $cryptoId
     * @param double $amount
     *
     * @return void
     *
     * @throws NonExistingCurrencyException
     * @throws NotEnoughFundsException
     *
    */
    public function transfer($sourceEmail, $destEmail, $cryptoId, $amount) {
        $this->WalletModel->withdraw($cryptoId, $sourceEmail, $amount);
        $this->WalletModel->deposit($cryptoId, $destEmail, $amount);
    }
    
    /**
     * Funkcija za dohvatanje jednog wallet-a.
     * 
     * @param string $cryptoId
     * @param string $email
     *
     * @return DBwallet
     *
    */
    public function get_wallet($cryptoId, $email) {
        $cryptoId = $this->CryptocurrModel->check($cryptoId);
        $this->db->where('cryptoId',$cryptoId);
        $this->db->where('email',$email);
        $query = $this->db->get('wallet');
        return $query->row();
    }
    
    /**
     * Funkcija za dohvatanje vise wallet-a.
     * 
     * @param string $email
     *
     * @return DBwallet[]
     *
    */
    public function get_wallets($email) {
        $this->db->where('email', $email);
        $query=$this->db->get('wallet');
        return $query->result();
    }
    
    /**
     * Funkcija za brisanje vise wallet-a.
     * 
     * @param string $cryptoId
     *
     * @return void
     *
    */
    public function delete_wallets($cryptoId) {
        $cryptoId = $this->CryptocurrModel->check($cryptoId);
        $this->db->where('cryptoId', $cryptoId);
        $this->db->delete('wallet');
    }
    
    /**
     * Funkcija za brisanje jednog wallet-a.
     * 
     * @param DBwallet $wallet
     *
     * @return void
     *
    */
    private function delete_wallet($wallet) {
        if (!$wallet){ return; }
        $this->db->where('cryptoId', $wallet->cryptoId);
        $this->db->where('email', $wallet->email);
        $this->db->delete('wallet');
    }
    
    /**
     * Funkcija za kreiranje wallet-a.
     * 
     * @param string $cryptoId
     * @param string $email
     * @param double $amount
     *
     * @return void
     *
    */
    private function create_wallet($cryptoId, $email, $amount) {
        $wallet = array(
        'cryptoId' => $cryptoId,
        'email' => $email,
        'amount' => $amount,
        );
        $this->db->insert('wallet', $wallet);
    }
    
    /**
     * Funkcija za azuriranje wallet-a.
     * 
     * @param DBwallet $wallet
     * @param double $update_amount
     *
     * @return void
     *
    */
    private function update_wallet($wallet, $update_amount) {
        if (!$wallet){ return; }
        $this->db->set('amount', $update_amount);
        $this->db->where('cryptoId', $wallet->cryptoId);
        $this->db->where('email', $wallet->email);
        $this->db->update('wallet');
    }
}
