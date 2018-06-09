<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kontroler za logovanje korisnika.
 * 
 * @author Luka Nikolic 0619/2015
 * 
 * @version 1.0
*/
class Login extends CI_Controller {
    
     /**
     * Konstruktor za klasu Login
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
    }
    
    /**
     * Funkcija za sprovodjenje logovanja.
     * 
     * @return void
     *
    */
    public function index() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[45]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[45]');
        if($this->form_validation->run()){
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            try{
                $this->UserModel->login($email, $password);
                redirect(base_url());
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect(base_url());
            }
        } else {
            $this->session->set_flashdata('error', $this->session->userdata('fields_error'));
            redirect(base_url());
        }
    }
}
