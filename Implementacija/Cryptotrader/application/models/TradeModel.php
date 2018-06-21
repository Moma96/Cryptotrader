<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model za dohvatanje informacija vezanih za trgovanje kriptovalutama.
 * 
 * @author Momcilo Nikolic 0579/2015
 * 
 * @version 1.0
*/
class TradeModel extends CI_Model {
    
    /**
     * Provizija koju uzima menjacnica.
     * 
     * @var float $fee
    */
    public $fee = 0.001;
    
    /**
     * Konstruktor za klasu TradeModel.
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('WalletModel');
        $this->load->model('CryptocurrModel');
        $this->load->model('UserModel');
        $this->load->model('TransactionModel');
    }
    
    /**
     * Funkcija za kupovanje kriptovaluta. Prvo proverava
     * sve dostupne ask-ove sa zadatom cenom kriptovalute,
     * a potom prihvata u zeljenoj kolicini. Ukoliko korisnik
     * zeli vise da kupi, nego sto ima ask-ova za tu cenu,
     * automatski postavlja svoj bid sa tom cenom i ostatkom kolicine.
     *
     * @param string $cryptoId
     * @param string $email
     * @param double $pricePU
     * @param double $amount
     *
     * @return string
     *
     * @throws NonExistingCurrencyException
     * @throws NotEnoughTetherException
     * @throws AlreadyOneBidException
     *
    */
    public function buy($cryptoId, $email, $pricePU, $amount) {
        $totalPrice = $this->get_total_price($pricePU*$amount);
        if ($this->UserModel->get_available($this->UserModel->get_wallet('usdt')) < $totalPrice) {
            throw new Exception('You don\'t have enough available Tether to carry on with the transaction!');
        }
        
        $transactions = $this->TransactionModel->get('ask', $cryptoId, $email, $pricePU);
        $available = $this->sum_available($transactions);
            
        if ($amount > $available) {
            $this->TransactionModel->add('bid', $email, $cryptoId, $pricePU, $amount - $available);
            $this->accept_all($transactions, $email, $available);
            $accepted = $available;
        } else {
            $this->accept_all($transactions, $email, $amount);
            $accepted = $amount;
        }
        if ($accepted > 0){
            $this->CryptocurrModel->update_price($cryptoId, $pricePU);
        }
        $this->pay_fee($amount*$pricePU, $email, 'usdt');
        $rest = $amount - $accepted;
        return "Accepted $accepted ".strtoupper($cryptoId)." and added bid with ".$rest." ".strtoupper($cryptoId)."!";
    }
    
    /**
     * Funkcija za prodaju kriptovaluta. Prvo proverava
     * sve dostupne bid-ove sa zadatom cenom kriptovalute,
     * a potom prihvata u zeljenoj kolicini. Ukoliko korisnik
     * zeli vise da proda, nego sto ima bid-ova za tu cenu,
     * automatski postavlja svoj ask sa tom cenom i ostatkom kolicine.
     *
     * @param string $cryptoId
     * @param string $email
     * @param double $pricePU
     * @param double $amount
     *
     * @return string
     *
     * @throws NonExistingCurrencyException
     * @throws NotEnoughCurrencyException
     * @throws AlreadyOneAskException
     *
    */
    public function sell($cryptoId, $email, $pricePU, $amount) {
        $totalPrice = $this->get_total_price($amount);
        if ($this->UserModel->get_available($this->UserModel->get_wallet($cryptoId)) < $totalPrice) {
            $curr = $this->CryptocurrModel->get_currency($cryptoId);
            throw new Exception('You don\'t have enough available '.$curr->name.' to carry on with the transaction!');
        }
        
        $transactions = $this->TransactionModel->get('bid', $cryptoId, $email, $pricePU);
        $available = $this->sum_available($transactions);
        
        if ($amount > $available) {
            $this->TransactionModel->add('ask', $email, $cryptoId, $pricePU, $amount - $available);
            $this->accept_all($transactions, $email, $available);
            $accepted = $available;
        } else {
            $this->accept_all($transactions, $email, $amount);
            $accepted = $amount;
        }
        if ($accepted > 0){
            $this->CryptocurrModel->update_price($cryptoId, $pricePU);
        }
        $this->pay_fee($amount, $email, $cryptoId);
        $rest = $amount - $accepted;
        return "Accepted $accepted ".strtoupper($cryptoId)." and added bid with ".$rest." ".strtoupper($cryptoId)."!";
    }
    
    /**
     * Funkcija koja racuna dodatu proviziju na prosledjenu sumu.
     *
     * @param double $amount
     *
     * @return double
     *
    */
    public function get_total_price($amount) {
        return $amount*(1 + $this->fee);
    }
    
    /**
     * Funkcija koja racuna zbir dostupnih suma u nizu transakcija.
     *
     * @param DBtransaction[] $transactions
     *
     * @return double
     *
    */
    private function sum_available($transactions) {
        $available = 0;
        foreach ($transactions as $transaction) {
            $available += $transaction->availAmount;
        } 
        return $available;
    }
    
    /**
     * Pomocna funkcija za prihvatanje transackija.
     *
     * @param DBtransaction[] $transactions
     * @param string $email
     * @param double $amount
     *
     * @return double
     *
    */
    private function accept_all($transactions, $email, $amount) {
        if (empty($transactions) || $amount == 0) return;
        foreach ($transactions as $transaction) {
            if ($amount >= $transaction->availAmount) {
                $this->TransactionModel->accept($email, $transaction, $transaction->availAmount);
                $amount -= $transaction->availAmount;
            } else {
                $this->TransactionModel->accept($email, $transaction, $amount);
                break;
            }
        }
    }
    
    /**
     * Placanje provizije na zadatu sumu svim administratorima podjednako.
     *
     * @param string $email
     * @param double $amount
     * @param string $cryptoId
     *
     * @return void
     *
    */
    private function pay_fee($amount, $email, $cryptoId) {
        $fee = $amount*$this->fee;
        $admins = $this->UserModel->get_admins();
        foreach ($admins as $admin) {
            $this->WalletModel->transfer($email, $admin->email, $cryptoId, $fee/count($admins));
        }
    }
}
