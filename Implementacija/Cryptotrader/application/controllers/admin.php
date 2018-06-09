<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Kontroler za opcije kojima ima pristup administrator.
 * 
 * @author Luka Nikolic 0619/2015
 * 
 * @version 1.0
*/
class Admin extends CI_Controller {
    
    /**
     * Konstruktor za klasu Admin
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
     * Funkcija za ucitavanje interface-a za brisanje i dodavanje kriptovaluta.
     * 
     * @return void
     *
    */
    public function add_remove_currency() {
        if ($this->session->userdata('is_admin') == true){
            $userdata = array(
                'name' => $this->UserModel->get_name(),
                'surname' => $this->UserModel->get_surname(),
                'admin' => $this->UserModel->get_admin(),
            );
            $data = array(
                'userdata' => $userdata,
                'currencies' => $this->CryptocurrModel->get_currencies()
            );
            $this->load->view('add-remove_currency', $data);
        } else {
            redirect(base_url());
        }
    }
    
    /**
     * Funkcija za sprovodjenje dodavanja kriptovalute.
     * 
     * @return void
     *
    */
    public function add_currency() {
        if($this->session->userdata('is_admin') == true) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('add-id', 'Add-id', 'required');
            $this->form_validation->set_rules('add-currency', 'Add-currency', 'required');
            if($this->form_validation->run()){
                $id = $this->input->post('add-id');
                $name = $this->input->post('add-currency');
                try{
                    $success = $this->CryptocurrModel->add_currency($id, $name);
                    $this->session->set_flashdata('success', $success);
                    redirect(base_url().'admin/add_remove_currency');
                } catch (Exception $e) {
                    $this->session->set_flashdata('error', $e->getMessage());
                    redirect(base_url().'admin/add_remove_currency');
                }
            } else {
                $this->session->set_flashdata('error', $this->session->userdata('fields_error'));
                redirect(base_url().'admin/add_remove_currency');
            }
        } else {
            redirect(base_url());
        }
    }
    
    /**
     * Funkcija za sprovodjenje brisanja kriptovalute.
     * 
     * @return void
     *
    */
    public function remove_currency() {
        if($this->session->userdata('is_admin') == true) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('remove-id', 'Remove-id', 'required');
            $this->form_validation->set_rules('remove-currency', 'Remove-currency', 'required');
            if($this->form_validation->run()){
                $id = $this->input->post('remove-id');
                $name = $this->input->post('remove-currency');
                try{
                    $success = $this->CryptocurrModel->delete_currency($id, $name);
                    $this->session->set_flashdata('success', $success);
                    redirect(base_url().'admin/add_remove_currency');
                } catch (Exception $e) {
                    $this->session->set_flashdata('error', $e->getMessage());
                    redirect(base_url().'admin/add_remove_currency');
                }
            } else {
                $this->session->set_flashdata('error', $this->session->userdata('fields_error'));
                redirect(base_url().'admin/add_remove_currency');
            }
        } else {
            redirect(base_url());
        }
    }
}
