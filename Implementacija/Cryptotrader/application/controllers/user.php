<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kontroler za mogucnosti koje ima korisnik.
 * 
 * @author Momcilo Nikolic 0579/2015
 * 
 * @version 1.0
*/
class User extends CI_Controller {
    
    /**
     * Konstruktor za klasu User
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('CryptocurrModel');
        $this->load->model('PriceModel');
    }
    
    /**
     * Funkcija za ucitavanje interface-a za ulaganje kriptovaluta.
     * 
     * @return void
     *
    */
    public function deposit() {
        if ($this->session->userdata('email')) {
            $userdata = array(
                'name' => $this->UserModel->get_name(),
                'surname' => $this->UserModel->get_surname(),
                'admin' => $this->UserModel->get_admin()
            );
            $cryptodata = array(
                'cryptoId1' => $this->session->userdata('cryptoId1')
            );
            $data = array(
                'userdata' => $userdata,
                'cryptodata' => $cryptodata
            );
            $this->load->view('deposit', $data);
        } else {
            redirect(base_url());
        }
    }
    
    /**
     * Funkcija za ucitavanje interface-a za podizanje kriptovaluta.
     * 
     * @return void
     *
    */
    public function withdraw() {
        if ($this->session->userdata('email')) {
            $userdata = array(
                'name' => $this->UserModel->get_name(),
                'surname' => $this->UserModel->get_surname(),
                'admin' => $this->UserModel->get_admin(),
                'available' => $this->UserModel->get_available(
                               $this->UserModel->get_wallet(
                               $this->session->userdata('cryptoId1')))
            );
            $cryptodata = array(
                'cryptoId1' => $this->session->userdata('cryptoId1')
            );
            $data = array(
                'userdata' => $userdata,
                'cryptodata' => $cryptodata
            );
            $this->load->view('withdraw', $data);
        } else {
            redirect(base_url());
        }
    }
    
    /**
     * Funkcija za ucitavanje interface-a sa pregledom profila.
     * 
     * @return void
     *
    */
    public function account() {
        if ($this->session->userdata('email')) {
            $userdata = array(
                'name' => $this->UserModel->get_name(),
                'surname' => $this->UserModel->get_surname(),
                'email' => $this->UserModel->get_email(),
                'admin' => $this->UserModel->get_admin(),
                'total_value' => $this->UserModel->sum_wallets()
            );
            $data = array(
                'userdata' => $userdata
            );
            $this->load->view('my_account', $data);
        } else {
            redirect(base_url());
        }
    }
    
    /**
     * Funkcija za ucitavanje interface-a sa transakcijama.
     * 
     * @return void
     *
    */
    public function transactions() {
        if ($this->session->userdata('email')) {
            $userdata = array(
                'name' => $this->UserModel->get_name(),
                'surname' => $this->UserModel->get_surname(),
                'admin' => $this->UserModel->get_admin(),
            );
            $transactiondata = array(
                'active_asks' => $this->UserModel->get_active_asks(),
                'active_bids' => $this->UserModel->get_active_bids(),
                'completed_transactions' => $this->UserModel->get_completed_transactions()
            );
            $data = array(
                'userdata' => $userdata,
                'transactiondata' => $transactiondata
            );
            $this->load->view('transactions', $data);
        } else {
            redirect(base_url());
        }
    }
    
    /**
     * Funkcija za ucitavanje interface-a sa wallet-ima korisnika.
     * 
     * @return void
     *
    */
    public function wallets() {
        if ($this->session->userdata('email')) {
            $userdata = array(
                'name' => $this->UserModel->get_name(),
                'surname' => $this->UserModel->get_surname(),
                'admin' => $this->UserModel->get_admin()
            );
            $data = array(
                'userdata' => $userdata,
                'wallets' => $this->UserModel->get_wallets()
            );
            $this->load->view('wallets', $data);
        } else {
            redirect(base_url());
        }
    }
    
    /**
     * Funkcija za log out korisnika.
     * 
     * @return void
     *
    */
    public function logout() {
        $this->UserModel->delete_session_data();
        $this->session->sess_destroy();
        redirect(base_url());
    }
}
