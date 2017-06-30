<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

   public function is_user_exist($user)
   {     
      $this->db->select('*');
      $this->db->from('dbo_user');
      $this->db->where('username', $user);
      $query = $this->db->get();
      return $query->num_rows()>0;
   }

   public function cek_user_pass($user,$pass)
   {
      $this->db->select('*');
      $this->db->from('dbo_user');
      $this->db->where('username', $user)
               ->where('password',$pass);
      $query = $this->db->get();
      return $query->num_rows()>0;
   }
   
   public function is_user_aktif($user)
   {
      $this->db->select('*');
      $this->db->from('dbo_user');
      $this->db->where('username', $user);
      $query = $this->db->get();
      $row = $query->row();
      return $row->aktif == 1;
   }

   public function get_user_type($user)
   {
      $this->db->select('*');
      $this->db->from('dbo_user');
      $this->db->where('username', $user);
      $query = $this->db->get();
      $row = $query->row();
      return $row->id_typeuser;
   }

   public function user_login($un)
   {
     $this->db->where('username', $un);
     $this->db->update('dbo_user', array('status'=>'1'));
   }  

   public function user_logout($un)
   {
     $tge=date("d-m-Y, H:i:s");
     $this->db->where('username', $un);
     $this->db->update('dbo_user', array('status'=>'0','tgl_end'=>$tge));
   }  

   public function get_login_info($user)
   {
      $this->db->select('*');
      $this->db->from('dbo_user bu');
      $this->db->join('dbo_typeuser btu', 'bu.id_typeuser=btu.id_typeuser');
      $this->db->where('bu.username', $user);
      $query = $this->db->get();
      $row = $query->row();
      return array('username'=>$row->username,'nm_typeuser'=>$row->nm_typeuser);
   }


}