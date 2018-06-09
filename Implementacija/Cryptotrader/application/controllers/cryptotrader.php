<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Pocetni kontroler.
 * 
 * @author Momcilo Nikolic 0579/2015
 * 
 * @version 1.0
*/
class Cryptotrader extends CI_Controller {
    
    /**
     * Konstruktor za klasu Cryptotrader
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
     * Funkcija za ucitavanje pocetne stranice za gosta, ili za korisnika.
     * 
     * @return void
     *
    */
    public function index() {
        if (!$this->session->userdata('fields_error')) { $this->session->set_userdata('fields_error', 'Please fill all necessary fields!'); }
        if (!$this->session->userdata('cryptoId1')) { $this->session->set_userdata('cryptoId1', 'btc'); }
        if (!$this->session->userdata('cryptoId2')) { $this->session->set_userdata('cryptoId2', 'usdt'); }
        try{
            if ($this->input->post('cryptoId1')) {
                if ($this->CryptocurrModel->check($this->input->post('cryptoId1'))) {
                    $this->session->set_userdata('cryptoId1', $this->input->post('cryptoId1'));
                }
            }
            if ($this->input->post('cryptoId2')) {
                if ($this->CryptocurrModel->check($this->input->post('cryptoId2'))) {
                    $this->session->set_userdata('cryptoId2', $this->input->post('cryptoId2')); 
                }
            }
            $cryptodata = array(
                'cryptoId1' => $this->session->userdata('cryptoId1'),
                'cryptoId2' => $this->session->userdata('cryptoId2'),
                'price' => $this->CryptocurrModel->get_price($this->session->userdata('cryptoId1')),
                'chartdata' => $this->PriceModel->get_chart_data($this->session->userdata('cryptoId1'), $this->session->userdata('cryptoId2'),
                            !$this->input->post('timespan')? '1y' : $this->input->post('timespan')),
                'type' => !$this->input->post('timespan')? '1y' : $this->input->post('timespan')
            );
            $data = array(
                'cryptodata' => $cryptodata
            );
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            $this->session->unset_userdata('cryptoId1');
            $this->session->unset_userdata('cryptoId2');
            redirect(base_url());
        }
        if($this->session->userdata('email')) {
            $userdata = array(
                'name' => $this->UserModel->get_name(),
                'surname' => $this->UserModel->get_surname(),
                'admin' => $this->UserModel->get_admin(),
            );
            $data['userdata'] = $userdata;
            $this->load->view('dashboard', $data);
        } else {
            $this->load->view('dashboard-guest', $data);
        }
    }
}
