<?php

class Discount_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_discounts(){
		$this->db->order_by('amount','desc');
        $result = $this->db->get('discount');
        return $result->result_array();
    }
    
    public function add_discount() {
        $data['amount'] = $this->input->post('amount');
        $data['percent'] = $this->input->post('percent');
        $this->db->insert('discount',$data);
    }
    
    public function get_edit_discount($amount,$percent) {    
        $this->db->where(array('amount'=>$amount, 'percent'=>$percent));
        $query = $this->db->get("discount");
        return $query->row_array();    
    }
    
    public function edit_discount($amount,$percent){
        $data['amount'] = $this->input->post('amount');
        $data['percent'] = $this->input->post('percent');
        $this->db->where(array('amount'=>$amount, 'percent'=>$percent));
        $this->db->update('discount', $data);
    }
    
    public function delete_discount($amount,$percent) {
        $this->db->delete('discount', array('amount' => $amount,'percent' => $percent));
    }
 
}

?>
