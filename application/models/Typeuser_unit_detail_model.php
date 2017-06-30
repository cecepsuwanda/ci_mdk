<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Typeuser_unit_detail_model extends CI_Model {

   public function get_unit_detail($type_user)
   {
      $this->db->select('*');
      $this->db->from('dbo_typeuser_unit_detail');
      $this->db->where('id_typeuser', $type_user);
      $query = $this->db->get();
      $row = $query->row();
      return $row->id_unit_detail;
   }

}