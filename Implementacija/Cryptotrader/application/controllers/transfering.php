<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kontroler za ulaganje i podizanje kriptovaluta.
 * 
 * @author Luka Nikolic 0619/2015
 * 
 * @version 1.0
*/
class Transfering extends CI_Controller {
    
    /**
     * Konstruktor za klasu Transfering
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('WalletModel');
    }
    
    /**
     * Funkcija za sprovodjenje ulaganja kriptovalute.
     * 
     * @return void
     *
    */
    public function deposit() {
        try{
            if ($this->input->post('cryptoId1')) {
                if ($this->CryptocurrModel->check($this->input->post('cryptoId1'))) {
                    $this->session->set_userdata('cryptoId1', $this->input->post('cryptoId1'));
                }
            }
            if ($this->input->post('amount')) {
                $amount = $this->input->post('amount');
                $success = $this->WalletModel->deposit($this->session->userdata('cryptoId1'), $this->UserModel->get_email(), $amount);
                $this->session->set_flashdata('success', $success);
            }
            redirect(base_url().'user/deposit');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            redirect(base_url().'user/deposit');
        }
    }
    
    /**
     * Funkcija za sprovodjenje podizanja kriptovalute.
     * 
     * @return void
     *
    */
    public function withdraw() {
        try{
            if ($this->input->post('cryptoId1')) {
                if ($this->CryptocurrModel->check($this->input->post('cryptoId1'))) {
                    $this->session->set_userdata('cryptoId1', $this->input->post('cryptoId1'));
                }
            }
            if ($this->input->post('amount')) {
                $amount = $this->input->post('amount');
                $success = $this->WalletModel->withdraw($this->session->userdata('cryptoId1'), $this->UserModel->get_email(), $amount);
                $this->session->set_flashdata('success', $success);
        }
        redirect(base_url().'user/withdraw');
        } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect(base_url().'user/withdraw');
        }
    }
}
