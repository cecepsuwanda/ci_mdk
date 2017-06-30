<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
     
class Empmntstat_model extends CI_Model {
   
   private $query;   
   
   function __construct() {
	  
   }

   function getdata($where)
   {      
      $this->db->select('*');
      $this->db->from('dbo_empmnt_stat');
      $this->db->order_by("no_urut_empmnt");
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

   function getnm1($kd)
   {
      $data = $this->getdata("Kd_emp='$kd'");
      return empty($data) ? '' : $data[0]['Nm_emp_ind'];
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
				$tmp[]=array(strtoupper($row['Nm_emp_ind']),array()); 
			}
			if($isjumlah==1){
			 $tmp[]=array('JUMLAH',array());
			} 
		}		
	  return $tmp;	
   }
}
