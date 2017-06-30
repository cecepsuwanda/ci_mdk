<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edu_lvl_model extends CI_Model {

   public function getdata($where)
   {      
      $this->db->select('*');
      $this->db->from('dbo_edu_lvl');
      if(!empty($where)){
        $this->db->where($where);      
      }
      $this->db->order_by("no_urut");

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

   function getnumrows()
   {
   	return $this->query->num_rows();
   }

  function getnm($where,$isjumlah=1)
   {
    $data = $this->getdata($where);   
    $tmp=array();
    if(!empty($data)){
      foreach($data as $row)
      {
        $tmp[]=array(strtoupper($row['Nm_edu_ind']),array()); 
      }
      if($isjumlah==1){
       $tmp[]=array('JUMLAH',array());
      } 
    }   
    return $tmp;  
   }

}