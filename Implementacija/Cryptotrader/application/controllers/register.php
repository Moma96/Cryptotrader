<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kontroler za logovanje korisnika.
 * 
 * @author Luka Nikolic 0619/2015
 * 
 * @version 1.0
*/
class Register extends CI_Controller {
    
    /**
     * Konstruktor za klasu Register
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
    }
    
    /**
     * Funkcija za sprovodjenje registrovanja.
     * 
     * @return void
     *
    */
    public function index() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[45]');
        $this->form_validation->set_rules('surname', 'Surname', 'trim|required|max_length[45]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[45]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[45]');
        $this->form_validation->set_rules('password-repeat', 'Password-repeat', 'trim|required|max_length[45]');
        if($this->form_validation->run()) {
            $name = $this->input->post('name');
            $surname = $this->input->post('surname');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $password_repeat = $this->input->post('password-repeat');
            try {
                if ($password === $password_repeat) {
                    $this->UserModel->register($name, $surname, $email, $password);
                } else {
                    throw new Exception("Passwords are not identical!");
                }
                redirect(base_url());
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $e->getMessage());
                redirect(base_url().'guest/register');
            }
        } else {
            $this->session->set_flashdata('error', $this->session->userdata('fields_error'));
            redirect(base_url().'guest/register');
        }
    }
}
