<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Non_acptr_reas_model extends CI_Model {

   public function getdata($where)
   {      
      $this->db->select('*');
      $this->db->from('dbo_non_acptr_reas');
      if(!empty($where)){
        $this->db->where($where);      
      }

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

  public function getnm($where,$isjumlah=1,$istotal=0,$isprosen=0,$tmp=array(),$attr=array())
   {
	  $data = $this->getdata($where);    
    
    if(!empty($data)){
      foreach($data as $row)
      {
        $tmp[]=array(strtoupper($row['Nm_nonacptr_ind']),$attr); 
      }
      if($isjumlah==1){
       $tmp[]=array('JUMLAH',array());
      } 
    }   
    return $tmp;  
   }

}