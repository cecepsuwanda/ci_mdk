<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends CI_Model {

   public function get_user_menu($id_type_user)
   {
      
      $query = $this->read_menudb($id_type_user);

      $tmp_menu = array();
      if($query->num_rows()>0)
      {
           foreach ($query->result() as $row)
           {
              $tmp = array('id'=>$row->id_menu,'id_induk'=>$row->id_induk_menu,'menu'=>$row->nm_menu,
           	   	                   'link'=>(($row->link_menu=='' or $row->link_menu==null) ? '#' : $row->link_menu)
           	   	                   );
              
              $query1 = $this->read_menudb($id_type_user,$row->id_menu);  
              if($query1->num_rows()>0)
              {
                 $tmp1=array();      
                 foreach ($query1->result() as $row1)
                 {
                    $tmp_tmp1 = array('id'=>$row1->id_menu,'id_induk'=>$row1->id_induk_menu,'menu'=>$row1->nm_menu,
           	   	                   'link'=>(($row1->link_menu=='' or $row1->link_menu==null) ? '#' : base_url().'index.php/dashboard/'.$row1->link_menu)
           	   	                );
                    
                    $query2 = $this->read_menudb($id_type_user,$row1->id_menu);
                    if($query2->num_rows()>0)
                    {
                       $tmp2=array();      
                       foreach ($query2->result() as $row2)
                       {
                           $tmp_tmp2 = array('id'=>$row2->id_menu,'id_induk'=>$row2->id_induk_menu,'menu'=>$row2->nm_menu,
           	   	                              'link'=>(($row2->link_menu=='' or $row2->link_menu==null) ? '#' : base_url().'index.php/dashboard/'.$row2->link_menu)
           	   	                            ); 

                           $query3 = $this->read_menudb($id_type_user,$row2->id_menu);
                           if($query3->num_rows()>0)
                           {
                           	   $tmp3=array();      
                               foreach ($query3->result() as $row3)
                               {
                                   $tmp_tmp3 = array('id'=>$row3->id_menu,'id_induk'=>$row3->id_induk_menu,'menu'=>$row3->nm_menu,
           	   	                              'link'=>(($row3->link_menu=='' or $row3->link_menu==null) ? '#' : base_url().'index.php/dashboard/'.$row3->link_menu)
           	   	                            ); 
                                   $tmp3[] = $tmp_tmp3;
                               }
                               $tmp_tmp2['submenu']=$tmp3;
                           }

                           $tmp2[] = $tmp_tmp2;
                       }
                       $tmp_tmp1['submenu']=$tmp2;
                    }
                    if(!in_array($row1->id_menu,array(269,76))){
                     $tmp1[] = $tmp_tmp1; 
                    } 
                 }                 
                  $tmp['submenu']=$tmp1;                
              } 
              if(!in_array($row->id_menu,array(50))){
               $tmp_menu[] =  $tmp;
              }
           }        
      }

      return $tmp_menu;
   }

   private function read_menudb($id_type_user,$id_menu=null)
   {
   	 $this->db->select('*');
     $this->db->from('dbo_menu bm');
     $this->db->join('dbo_typeuser_menu_akses btm', 'btm.id_menu=bm.id_menu');
     $this->db->where('btm.id_typeuser', $id_type_user);
     if(is_null($id_menu)){
        $this->db->where('bm.id_induk_menu is NULL');
     }else{
     	$this->db->where('bm.id_induk_menu',$id_menu);
     }
     $this->db->order_by("bm.no_urut_menu"); 
     $query = $this->db->get();
     return $query;
   }


}