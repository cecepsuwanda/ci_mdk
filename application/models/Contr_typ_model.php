<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contr_typ_model extends CI_Model {

   public function getdata($where)
   {      
      $this->db->select('*');
      $this->db->from('dbo_contr_typ');
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
				$tmp[]=array(strtoupper($row['Nm_contyp_ind']),$attr);
            if($isprosen==1){
              $tmp[]=array('%',$attr); 
            }
			}
			if($isjumlah==1){
			 $tmp[]=array('JUMLAH',$attr);
			} 
         if($istotal==1){
          $tmp[]=array('TOTAL',$attr);
         }
		}		
	  return $tmp;	
   }

}