<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unit_detail_model extends CI_Model {

   
   function getdata($where)
   {      
      $this->db->select('*');
      $this->db->from('dbo_unit_detail');
      $this->db->order_by("no_unit_detail");
      $this->db->where($where);      
      $this->query = $this->db->get();
      $hsl=array();
      if($this->query->num_rows()>0)
      {
        foreach($this->query->result_array() as $row)
        {
           $hsl[]=$row;
        }
      }
      return $hsl; 
   }

   public function get_unit_detail($id_unit_detail)
   {
      $this->db->select('*');
      $this->db->from('dbo_unit_detail');
      $this->db->where('id_unit_detail', $id_unit_detail);
      $query = $this->db->get();
      $row = $query->row();
      return $row->id_unit_1;
   }

   public function get_list_unit_detail($id_unit,$id_unit_detail_idk)
   {
      $this->db->select('*');
      $this->db->from('dbo_unit_detail');
      $this->db->where('id_unit', $id_unit);
      $this->db->where('id_unit_detail_idk', $id_unit_detail_idk);
      $this->db->order_by('no_unit_detail'); 
      $query = $this->db->get();
      
      $list = array();
      if($query->num_rows()>0)
      {
        foreach ($query->result() as $row) {
        	$list[$row->id_unit_detail]=$row->no_unit_detail;
        }
      }
      return $list;
   }

   public function get_list_kec()
   {
      $data = $this->get_list_unit_detail('11','1017');
      return $data;	
   } 

   public function get_list_desa($kdkec)
   {
      $data = $kdkec=='' ? array() : $this->get_list_unit_detail('12',$kdkec);
      return $data;  
   }

   public function get_list_dusun($kddesa)
   {
      $data = $kddesa=='' ? array() : $this->get_list_unit_detail('13',$kddesa);
      return $data;  
   }

   public function get_list_rt($kddusun)
   {
      $data = $kddusun=='' ? array() : $this->get_list_unit_detail('14',$kddusun);
      return $data;  
   } 

   public function getnm($where)
   {
     $data=$this->getdata($where);
      return (isset($data[0]['no_unit_detail']) ? $data[0]['no_unit_detail'] : '');
   }

   public function get_unit_detail_idk($id_unit_detail)
   {
     $data = $this->getdata("id_unit_detail = $id_unit_detail");
     return (isset($data[0]['id_unit_detail_idk']) ? $data[0]['id_unit_detail_idk'] : ''); 
   }

}