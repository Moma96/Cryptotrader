<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model za dohvatanje cena kriptovaluta iz baze
 * 
 * @author Momcilo Nikolic 0579/2015
 * 
 * @version 1.0
*/
class PriceModel extends CI_Model {
    
    /**
     * Koliko se najcesce azuriraju cene kriptovaluta, izrazeno u sekundama.
     * 
     * @var int $frequency
    */
    public $frequency = 60;
    
    /**
     * Pri dohvatanju cena, korak u prikazivanju grafika izmedju dve vrednosti,
     * izrazeno u sekundama.
     * 
     * @var int $time_step
    */
    public $time_step = 600;
    
    /**
     * Pri prikazivanju grafika, na koliko vrednosti se prikaze vreme na x osi.
     * 
     * @var int $time_appearance_frequency
    */
    public $time_appearance_frequency = 6;
    
    /**
     * Tipovi grafa.
     * 
     * @var Type[]
    */
    public $types;
    
    /**
     * Konstruktor za klasu PriceModel.
     *
     * @return void
     *
    */
    public function __construct() {
        parent::__construct();
        $this->load->model('CryptocurrModel');
        $this->types = array(
            '1d' => array(
                'timespan' => 3600*24,
                'step' => $this->time_step,
                'format' => 'H:i'
            ),
            '1w' => array(
                'timespan' => 3600*24*7,
                'step' => $this->time_step*7,
                'format' => 'M j'
            ),
            '1m' => array(
                'timespan' => 3600*24*30,
                'step' => $this->time_step*30,
                'format' => 'M j'
            ),
            '6m' => array(
                'timespan' => 3600*24*30*6,
                'step' => $this->time_step*30*6,
                'format' => 'M j'
            ),
            '1y' => array(
                'timespan' => 3600*24*30*12,
                'step' => $this->time_step*30*12,
                'format' => 'Y M'
            )
        );
    }

    /**
     * Funkcija koja vraca cene zadate prve valute podeljene cenama zadate
     * druge valute, tako rasporedjene da budu zgodne za prikaz na grafiku.
     *
     * @param string $cryptoId1
     * @param string $cryptoId2
     * @param string $type
     *
     * @return ChartData
    */
    public function get_chart_data($cryptoId1, $cryptoId2, $type) {
        $prices1 = $this->get_prices($cryptoId1, $this->types[$type]['timespan']);
        $prices2 = $this->get_prices($cryptoId2, $this->types[$type]['timespan']);
        $format = $this->types[$type]['format'];
        
        $now = strtotime(date('Y-m-d H:i:s'));
        $time = $now - $this->types[$type]['timespan'];
        $step = $this->types[$type]['step'];
        
        $p1 = 0; // prices1 counter
        $p2 = 0; // prices2 counter
        for ($c = 0; $time <= $now; $c++) {
            
            while ($p1 < count($prices1) - 1 && date("Y-m-d H:i:s",$time) > $prices1[$p1]->time) { $p1++; }
            while ($p2 < count($prices2) - 1 && date("Y-m-d H:i:s",$time) > $prices2[$p2]->time) { $p2++; }
            
            $chartdata['prices'][$c] = ($prices2[$p2 - 1]->price == 0)? 0 : $prices1[$p1 - 1]->price/$prices2[$p2 - 1]->price;
            $chartdata['times'][$c] = ($c % $this->time_appearance_frequency == 0)? date($format, $time) : '';
            $time += $step;
        }
        $max = max($chartdata['prices']);
        $min = min($chartdata['prices']);
        $chartdata['max'] = ($max == 0)? 1 : $max + ($max - $min)/4;
        $chartdata['min'] = $min - ($max - $min)/4;
        $chartdata['high'] = $max;
        $chartdata['low'] = $min;
        if ($chartdata['min'] < 0) { $chartdata['min'] = 0; }
        $chartdata['stepSize'] = ($chartdata['max'] - $chartdata['min'])/10;
        return $chartdata;
    }
    
    /**
     * Funkcija koja dodaje novu cenu ukoliko je proslo dovoljno sekundi,
     * u suprotnom azurira poslednju cenu i azurira trenutnu cenu kriptovalute.
     *
     * @param string $cryptoId
     * @param double $newprice
     *
     * @return void
    */
    public function add($cryptoId, $newprice) {
        $now = date('Y-m-d H:i:s');
        $latest = $this->get_latest($cryptoId);
        
        if (!$latest || strtotime($now) - strtotime($latest->time) > $this->frequency) {
            $this->add_price($cryptoId, $now, $newprice);
        } else {
            $this->update_price($latest, $newprice);
        }
    }
    
    /**
     * Funkcija za brisanje svih cena zadate kriptovalute
     *
     * @param string $cryptoId
     *
     * @return void
    */
    public function delete_all($cryptoId) {
        $cryptoId = $this->CryptocurrModel->check($cryptoId);
        $this->db->where('cryptoId', $cryptoId);
        $this->db->delete('price');
    }
    
    /**
     * Funkcija za dohvatanje cena kriptovalute u zadatom vremenskom rasponu.
     *
     * @param string $cryptoId
     * @param long $timespan
     *
     * @return DBprice[]
    */
    private function get_prices($cryptoId, $timespan) {
        $cryptoId = $this->CryptocurrModel->check($cryptoId);
        $now = date('Y-m-d H:i:s');
        $then = date('Y-m-d H:i:s', strtotime($now) - $timespan);
        $this->db->where('time >=', $then);
        $this->db->where('cryptoId', $cryptoId);
        $this->db->order_by('time', 'asc');
        $query = $this->db->get('price');
        $prices = $query->result();
        if (!$prices) {
            $prices[-1] = $this->get_one_before($cryptoId, $then);
            $prices[0] = $prices[-1];
        } else {
            $prices[-1] = $this->get_one_before($cryptoId, $prices[0]->time);
        }
        return $prices;
    }
    
    /**
     * Funkcija za dohvatanje prve cene pre zadatog vremena.
     *
     * @param string $cryptoId
     * @param long $time
     *
     * @return DBprice
    */
    private function get_one_before($cryptoId, $time) {
        $this->db->where('cryptoId', $cryptoId);
        $this->db->limit(1);
        $this->db->order_by('time', 'desc');
        $this->db->where('time <', $time);
        $query = $this->db->get('price');
        $price = $query->row();
        if (!$price) {
            $price = new \stdClass();
            $price->price = 0;
            $price->time = $time;
        }
        return $price;
    }
    
    /**
     * Funkcija za dodavanje cene kriptovalute.
     *
     * @param string $cryptoId
     * @param long $time
     * @param double $newprice
     *
     * @return void
    */
    public function add_price($cryptoId, $time, $newprice) {
        $cryptoId = $this->CryptocurrModel->check($cryptoId);
        $price = array(
        'time' => $time,
        'cryptoId' => $cryptoId,
        'price' => $newprice,
        );
        $this->db->insert('price', $price);
    }
    
    /**
     * Funkcija za azuriranje poslednje cene kriptovalute.
     *
     * @param DBprice $price
     * @param double $newprice
     *
     * @return void
    */
    private function update_price($price, $newprice) {
        $this->db->set('price', $newprice);
        $this->db->where('cryptoId', $price->cryptoId);
        $this->db->where('time', $price->time);
        $this->db->update('price');
    }
    
    /**
     * Funkcija za dohvatanje poslednje cene za zadatu kriptovalutu.
     *
     * @param string $cryptoId
     *
     * @return DBprice
    */
    private function get_latest($cryptoId) {
        $cryptoId = $this->CryptocurrModel->check($cryptoId);
        $this->db->where('cryptoId', $cryptoId);
        $this->db->order_by('time','desc');
        $this->db->limit(1);
        $query = $this->db->get('price');
        return $query->row();
    }
}
