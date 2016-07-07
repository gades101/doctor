<?php

class Discount_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_discounts(){
        $result = $this->db->get('discount');
        return $result->result_array();
    }
    
    public function add_discount() {
        $data['amount'] = $this->input->post('amount');
        $data['percent'] = $this->input->post('percent');
        $this->db->insert('discount',$data);
    }
    
    public function get_edit_treatment($id) {    
        $this->db->where("id", $id);
        $query = $this->db->get("treatments");
        return $query->row_array();    
    }
    
    public function edit_discounts($id){
        $data['treatment'] = $this->input->post('treatment');
        $data['price'] = $this->input->post('treatment_price');
        $data['count'] = $this->input->post('treatment_count');
        $this->db->where('id', $id);
        $this->db->update('treatments', $data);
    }
    
    public function delete_discount($amount,$percent) {
        $this->db->delete('discount', array('amount' => $amount,'percent' => $percent));
    }
 
}

?>
