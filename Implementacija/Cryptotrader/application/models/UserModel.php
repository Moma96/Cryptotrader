<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model za dohvatanje informacija vezanih za korisnika
 * i azuriranje sesije i baze.
 * 
 * @author Momcilo Nikolic 0579/2015
 * 
 * @version 1.0
*/
class UserModel extends CI_Model {
    
    /**
     * Konstruktor za klasu UserModel.
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('WalletModel');
        $this->load->model('TransactionModel');
        $this->load->model('CryptocurrModel');
    }
    
    /**
     * Funkcija za logovanje korisnika. Na kraju funkcije se sacuvaju
     * konstantni podaci vezani za korisnika.
     *
     * @param string $email
     * @param string $password
     *
     * @return void
     *
     * @throws EmailNotValidException
     * @throws NoUserException
     * @throws WrongPasswordException
     *
    */
    public function login($email, $password) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('That is not a valid e-mail format!');
        }
        $user = $this->get_user($email);
        if (empty($user)) {
            throw new Exception('There is no user with that e-mail!');
        }
        $encrypted_pass = md5($password);
        if ($user->password != $encrypted_pass) {
            throw new Exception('Wrong password! Try again!');
        }
        $this->save_session_data($user->email, $user->name, $user->surname);
    }
    
    /**
     * Funkcija za registrovanje korisnika. Na kraju funkcije se korsnik
     * automatski loguje na sajt.
     * 
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $password
     *
     * @return void
     *
     * @throws EmailNotValidException
     * @throws NoUserException
     * @throws WrongPasswordException
     *
    */
    public function register($name, $surname, $email, $password) {
        if ($this->get_user($email)) {
            throw new Exception('There is already an user with that e-mail!');
        }
        $encrypted_pass = md5($password);
        $user = array(
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'password' => $encrypted_pass
        );
        $this->db->insert('user', $user);
        $this->login($email, $password);
    }
    
    /**
     * Getter za ime korisnika
     *
     * @return string
     *
    */
    public function get_name() {
        return $this->session->userdata('name');
    }
    
    /**
     * Getter za prezime korisnika
     *
     * @return string
     *
    */
    public function get_surname() {
        return $this->session->userdata('surname');
    }
    
    /**
     * Getter za e-mail korisnika
     *
     * @return string
     *
    */
    public function get_email() {
        return $this->session->userdata('email');
    }
    
    /**
     * Funkcija koja vraca true ako je korisnik administrator, u suprotnom
     * vraca false.
     *
     * @return boolean
     *
    */
    public function get_admin() {
        return $this->session->userdata('is_admin');
    }
    
    /**
     * Funkcija koja vraca sve wallet-e korisnika uz dodatno polje 'avail'
     * u kom je dostupna suma na svakom wallet-u.
     *
     * @return DBwallet[]
     *
    */
    public function get_wallets() {
        if ($this->session->userdata('email')) {
            $wallets = $this->WalletModel->get_wallets($this->session->userdata('email'));
            foreach($wallets as $wallet) {
                $wallet->avail = $this->get_available($wallet);
            }
            return $wallets;
        }
    }
    
    /**
     * Funkcija koja vraca wallet korisnika sa zadatim id-em kriptovalute.
     *
     * @param string $cryptoId
     * 
     * @return DBwallet
     *
    */
    public function get_wallet($cryptoId) {
        return $this->WalletModel->get_wallet($cryptoId, $this->session->userdata('email'));
    }
    
    /**
     * Funkcija koja vraca sve aktivne ask-ove koje je korisnik postavio.
     * 
     * @return DBtransaction[]
     *
    */
    public function get_active_asks() {
        if ($this->session->userdata('email')) {
            return $this->TransactionModel->get_active('ask', $this->session->userdata('email'));
        }
    }
    
    /**
     * Funkcija koja vraca sve aktivne bid-ove koje je korisnik postavio.
     * 
     * @return DBtransaction[]
     *
    */
    public function get_active_bids() {
        if ($this->session->userdata('email')) {
            return $this->TransactionModel->get_active('bid', $this->session->userdata('email'));
        }
    }
    
    /**
     * Funkcija koja vraca sve zavrsene transakcije korisnika.
     * 
     * @return DBtransaction[]
     *
    */
    public function get_completed_transactions() {
        if ($this->session->userdata('email')) {
            return $this->TransactionModel->get_completed($this->session->userdata('email'));
        }
    }
    
    /**
     * Funkcija koja cuva na sesiju konstantne podatke vezane za korisnika.
     * 
     * @return void
     *
    */
    private function save_session_data($email, $name, $surname) {
        $session_data = array(
            'email' => $email,
            'name' => $name,
            'surname' => $surname,
            'is_admin' => $this->is_admin($email)
        );
        $this->session->set_userdata($session_data);
    }
    
    /**
     * Funkcija koja brise sa sesije podatke vezane za korisnika.
     * 
     * @return void
     *
    */
    public function delete_session_data() {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('surname');
        $this->session->unset_userdata('isadmin');
    }
    
    /**
     * Funkcija koja korisnika iz baze podataka.
     * 
     * @param string $email
     * 
     * @return DBuser
     *
    */
    private function get_user($email){
        $this->db->where('email',$email);
        $query = $this->db->get('user');
        return $query->row();
    }
    
    /**
     * Funkcija koja utvrdjuje da li je korisnik administrator.
     * 
     * @param string $email
     * 
     * @return boolean
     *
    */
    private function is_admin($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('admin');
        return ($query->row()? true : false);
    }
    
    /**
     * Funkcija koja racuna kolicinu koja je dostupna u wallet-u korisnika.
     * 
     * @param DBwallet $wallet
     * 
     * @return double
     *
    */
    public function get_available($wallet) {
        if (empty($wallet)) { return 0; }
        $available = $wallet->amount;
        if ($wallet->cryptoId == 'usdt') {
            $transactions = $this->get_active_bids();
            if ($transactions) {
                foreach ($transactions as $transaction) {
                    $available -= $transaction->pricePU*$transaction->availAmount;
                }
            }
        } else {
            $transaction = $this->TransactionModel->get_transaction('ask', $this->get_email(), $wallet->cryptoId, 1);
            if ($transaction) { $available -= $transaction->availAmount; }
        }
        return $available;
    }
    
    /**
     * Funkcija koja dohvata sve administratore iz baze.
     *  
     * @return DBadmin[]
     *
    */
    public function get_admins() {
        $query=$this->db->get('admin');
        return $query->result();
    }
    
    /**
     * Funkcija koja izracunava ukupnu vrednost svih kriptovaluta
     * koje korisnik poseduje.
     *  
     * @return double
     *
    */
    public function sum_wallets() {
        $wallets = $this->get_wallets();
        $value = 0;
        foreach ($wallets as $wallet) {
            $value += $wallet->amount*$this->CryptocurrModel->get_price($wallet->cryptoId);
        }
        return $value;
    }
}