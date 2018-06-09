<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model za dohvatanje informacija vezanih za transakcije iz baze.
 * 
 * @author Momcilo Nikolic 0579/2015
 * 
 * @version 1.0
*/
class TransactionModel extends CI_Model {
    
    /**
     * Konstruktor za klasu TransactionModel.
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('WalletModel');
        $this->load->model('CryptocurrModel');
        $this->load->model('UserModel');
    }
   
    /**
     * Funkcija koja prihvata transakciju vrsi transfer kriptovaluta.
     *
     * @param string $email
     * @param DBtransaction $transaction
     * @param double $amount
     *
     * @return void
     *
    */
    public function accept($email, $transaction, $amount) {
        $this->update_transaction($transaction, $transaction->availAmount - $amount);
        $this->CryptocurrModel->update_price($transaction->cryptoId, $transaction->pricePU);
        
        $totalPrice = $transaction->pricePU*$amount;
        
        if ($transaction->type == 'ask') {
            $this->WalletModel->transfer($email, $transaction->email, 'usdt', $totalPrice);
            $this->WalletModel->transfer($transaction->email, $email, $transaction->cryptoId, $amount);
        } else {
            $this->WalletModel->transfer($email, $transaction->email, $transaction->cryptoId, $amount);
            $this->WalletModel->transfer($transaction->email, $email, 'usdt', $totalPrice);
        }
    }
    
    /**
     * Funkcija za dodavanje transakcije.
     *
     * @param string $type
     * @param string $email
     * @param string $cryptoId
     * @param double $pricePU
     * @param double $amount
     *
     * @return void
     *
     * @throws NonExistingCurrencyException
     * @throws AlreadyOneAskException
     * @throws AlreadyOneBidException
     * 
    */
    public function add($type, $email, $cryptoId, $pricePU, $amount) {
        $active = $this->get_transactions($type, $email, $cryptoId, 1, null);
        if ($active) {
            $curr = $this->CryptocurrModel->get_currency($cryptoId);
            throw new Exception('You have already one '.$type.' for '.$curr->name.'!');
        }
        $this->add_transaction($type, $email, $cryptoId, $pricePU, $amount);
    }
    
    /**
     * Funkcija za dohvatanje transakcija koje ne pripadaju korisniku.
     *
     * @param string $type
     * @param string $cryptoId
     * @param string $email
     * @param double $pricePU
     *
     * @return DBtransaction[]
     * 
    */
    public function get($type, $cryptoId, $email, $pricePU) {
        $cryptoId = $this->CryptocurrModel->check($cryptoId);
        if (isset($pricePU)) { $this->db->where('pricePU', $pricePU); }
        $this->db->where('cryptoId', $cryptoId);
        $this->db->where('email !=', $email);
        $this->db->where('type', $type);
        $this->db->order_by('pricePU',($type == 'ask'? 'ASC' : 'DESC'));
        $this->db->where('timeClosed', null);
        $this->db->limit(5);
        $query=$this->db->get('transaction');
        return $query->result();
    }
    
    /**
     * Funkcija za dohvatanje aktivnih transakcija korisnika.
     *
     * @param string $type
     * @param string $email
     *
     * @return DBtransaction[]
     * 
    */
    public function get_active($type, $email) {
        return $this->get_transactions($type, $email, null, 1, null);
    }
    
    /**
     * Funkcija za dohvatanje zavrsenih transakcija korisnika.
     *
     * @param string $email
     *
     * @return DBtransaction[]
     * 
    */
    public function get_completed($email) {
        return $this->get_transactions(null, $email, null, 0, null);
    }
    
    /**
     * Funkcija za brisanje transakcija.
     *
     * @param string $type
     * @param string $email
     * @param string $cryptoId
     *
     * @return void
     * 
    */
    public function delete($type, $email, $cryptoId) {
        $this->delete_transactions($type, $email, $cryptoId, null);
    }
    
    /**
     * Funkcija za brisanje aktivnih transakcija.
     *
     * @param string $cryptoId
     *
     * @return void
     * 
    */
    public function delete_active($cryptoId) {
        $this->delete_transactions(null, null, $cryptoId, 1);
    }
    
    /**
     * Funkcija za otkazivanje transakcija.
     *
     * @param DBtransaction $transaction
     *
     * @return void
     * 
    */
    public function cancel($transaction) {
        $now = date('Y-m-d H:i:s');
        $this->db->set('timeClosed', $now);
        $this->db->where('email', $transaction->email);
        $this->db->where('cryptoId', $transaction->cryptoId);
        $this->db->where('timeOpened', $transaction->timeOpened);
        $this->db->update('transaction');
    }
    
    /**
     * Genericka funkcija za dohvatanje transakcije.
     *
     * @param string $type
     * @param string $email
     * @param string $cryptoId
     * @param short $active
     *
     * @return DBtransaction
     * 
    */
    public function get_transaction($type, $email, $cryptoId, $active) {
        $cryptoId = $this->CryptocurrModel->check($cryptoId);
        $this->db->where('email', $email);
        $this->db->where('cryptoId', $cryptoId);
        $this->db->where('type', $type);
        if ($active == 1) {
            $this->db->where('timeClosed', null);
        } else if ($active == 0) {
            $this->db->where('timeClosed !=', null);
        }
        $query = $this->db->get('transaction');
        return $query->row();
    }
    
    /**
     * Funkcija za azuriranje transakcija.
     *
     * @param DBtransaction $transaction
     * @param double $update_amount
     *
     * @return void
     * 
    */
    private function update_transaction($transaction, $update_amount) {
        $this->db->set('availAmount', $update_amount);
        if ($update_amount == 0) {
            $now = date('Y-m-d H:i:s');
            $this->db->set('timeClosed', $now);
        }
        $this->db->where('email', $transaction->email);
        $this->db->where('cryptoId', $transaction->cryptoId);
        $this->db->where('timeOpened', $transaction->timeOpened);
        $this->db->update('transaction');
    }
    
    /**
     * Funkcija za dodavanje transakcije.
     *
     * @param string $type
     * @param string $email
     * @param string $cryptoId
     * @param double $pricePU
     * @param double $amount
     *
     * @return void
     * 
    */
    private function add_transaction($type, $email, $cryptoId, $pricePU, $amount) {
        $cryptoId = $this->CryptocurrModel->check($cryptoId);
        $now = date('Y-m-d H:i:s');
        $transaction = array(
        'type' => $type,
        'email' => $email,
        'cryptoId' => $cryptoId,
        'pricePU' => $pricePU,
        'initAmount' => $amount,
        'availAmount' => $amount,
        'timeOpened' => $now
        );
        $this->db->insert('transaction', $transaction);
    }
    
    /**
     * Genericka funkcija za dohvatanje transakcija.
     *
     * @param string $type
     * @param string $email
     * @param string $cryptoId
     * @param short $active
     * @param int $top
     *
     * @return DBtransaction[]
     * 
    */
    private function get_transactions($type, $email, $cryptoId, $active, $top) {
        if (isset($cryptoId)) {
            $cryptoId = $this->CryptocurrModel->check($cryptoId);
            $this->db->where('cryptoId', $cryptoId);
        }
        if (isset($type)) { $this->db->where('type', $type); }
        if (isset($email)) { $this->db->where('email', $email); }
        if (isset($top)) { $this->db->limit($top); }
         if ($active == 1) {
            $this->db->where('timeClosed', null);
            $this->db->order_by('timeOpened', 'DESC');
        } else if ($active == 0) {
            $this->db->where('timeClosed !=', null);
            $this->db->order_by('timeClosed', 'DESC');
        }
        $query=$this->db->get('transaction');
        
        return (($active == 1 && $type == 'ask')? $query->row() : $query->result());
    }
    
    /**
     * Genericka funkcija za brisanje transakcija.
     *
     * @param string $type
     * @param string $email
     * @param string $cryptoId
     * @param short $active
     *
     * @return void
     * 
    */
    private function delete_transactions($type, $email, $cryptoId, $active) {
        if (isset($cryptoId)) {
            $cryptoId = $this->CryptocurrModel->check($cryptoId);
            $this->db->where('cryptoId', $cryptoId);
        }
        if (isset($type)) { $this->db->where('type', $type); }
        if (isset($email)) { $this->db->where('email', $email); }
        if ($active == 1) {
            $this->db->where('timeClosed', null);
        } else if ($active == 0) {
            $this->db->where('timeClosed !=', null);
        }
        $this->db->delete('transaction');
    }
}
