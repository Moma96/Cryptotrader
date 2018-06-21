<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kontroler za testiranje kriptovaluta.
 * 
 * @author Momcilo Nikolic 0579/2015
 * 
 * @version 1.0
*/
class Trade extends CI_Controller {
    
    /**
     * Konstruktor za klasu Trade
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('WalletModel');
        $this->load->model('TransactionModel');
        $this->load->model('TradeModel');
        $this->load->model('PriceModel');
    }

    /**
     * Funkcija za ucitavanje interface-a za kupovinu i prodaju kritpovaluta.
     * 
     * @return void
     *
    */
    public function index() {
        if ($this->session->userdata('email')) {
            try{
                if ($this->input->post('cryptoId1')) {
                    if ($this->CryptocurrModel->check($this->input->post('cryptoId1'))) {
                        $this->session->set_userdata('cryptoId1', $this->input->post('cryptoId1'));
                    }
                }
                if ($this->session->userdata('cryptoId1') == 'usdt') {
                    $this->session->set_userdata('cryptoId1', 'btc');
                }
                $userdata = array(
                    'name' => $this->UserModel->get_name(),
                    'surname' => $this->UserModel->get_surname(),
                    'admin' => $this->UserModel->get_admin(),
                    'availableUSDT' => $this->UserModel->get_available($this->UserModel->get_wallet('usdt')),
                    'availableCurr' => $this->UserModel->get_available($this->UserModel->get_wallet($this->session->userdata('cryptoId1')))
                );
                $cryptodata = array(
                    'cryptoId1' => $this->session->userdata('cryptoId1'),
                    'name' => $this->CryptocurrModel->get_currency($this->session->userdata('cryptoId1'))->name,
                    'price' => $this->CryptocurrModel->get_price($this->session->userdata('cryptoId1')),
                    'chartdata' => $this->PriceModel->get_chart_data($this->session->userdata('cryptoId1'), 'usdt', '1y'),
                    'type' => '1y'
                );
                $transactiondata = array(
                    'asks' => $this->TransactionModel->get('ask', $this->session->userdata('cryptoId1'), $this->session->userdata('email'), null),
                    'bids' => $this->TransactionModel->get('bid', $this->session->userdata('cryptoId1'), $this->session->userdata('email'), null)
                );
                $data = array(
                    'userdata' => $userdata,
                    'cryptodata' => $cryptodata,
                    'transactiondata' => $transactiondata
                );
                $this->load->view('trade', $data);
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect(base_url().'trade');
            }
        } else {
            redirect(base_url());
        }
    }
    
    /**
     * Funkcija za sprovodjenje kupovanja kriptovalute.
     * 
     * @return void
     *
    */
    public function buy() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('buy-price', 'Buy-price', 'required');
        $this->form_validation->set_rules('buy-quantity', 'Buy-quantity', 'required');
        if($this->form_validation->run()) {
            try{
                $success = $this->TradeModel->buy($this->session->userdata('cryptoId1'), $this->UserModel->get_email(),
                                       $this->input->post('buy-price'), $this->input->post('buy-quantity'));
                $this->session->set_flashdata('success', $success);
                redirect(base_url().'trade');
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect(base_url().'trade');
            }
        } else {
           $this->session->set_flashdata('error', $this->session->userdata('fields_error'));
           redirect(base_url().'trade');
        }
    }
    
    /**
     * Funkcija za sprovodjenje prodavanja kriptovalute.
     * 
     * @return void
     *
    */
    public function sell() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('sell-price', 'Sell-price', 'required');
        $this->form_validation->set_rules('sell-quantity', 'Sell-quantity', 'required');
        if($this->form_validation->run()) {
            try{
                $success = $this->TradeModel->sell($this->session->userdata('cryptoId1'), $this->UserModel->get_email(),
                                        $this->input->post('sell-price'), $this->input->post('sell-quantity'));
                $this->session->set_flashdata('success', $success);
                redirect(base_url().'trade');
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect(base_url().'trade');
            }
        } else {
           $this->session->set_flashdata('error', $this->session->userdata('fields_error'));
           redirect(base_url().'trade');
        }
    }
}