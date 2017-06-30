<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Indikator_ks_model extends CI_Model {

   public function getdata($where)
   {      
      $this->db->select('*');
      $this->db->from('dbo_indikator_ks');
      if(!empty($where)){
        $this->db->where($where);      
      }
      $this->db->order_by("id_ind_ks_idk,no_ind_ks"); 
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

  function getnm($where,$isjumlah=1,$istotal=0,$isprosen=0,$attr=array())
   {
    
    $data = $this->getdata($where);   
    $tmp=array();
    if(!empty($data)){
      foreach($data as $row)
      {
        $tmp[]=array(strtoupper($row['nm_ind_ks']),$attr);
                if($isprosen==1){
                   $tmp[]=array('%',array()); 
                 }
      }
      if($isjumlah==1){
       $tmp[]=array('JUMLAH',array());
      } 
            if($istotal==1){
              $tmp[]=array('TOTAL',array());
            }
    }   
    return $tmp;  
   }

}