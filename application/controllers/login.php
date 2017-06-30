<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$data = array();
		$data['pesan']="";
		$data['un']="";
		$data['psw']="";		
		if($this->input->post('login')=='login'){   
		  
		  $psw = $this->input->post('psw');
		  $md5psw = md5($psw);
		  $un = $this->input->post('un');
		  $untrm = trim($un);
          
          $data['un']=$un;
		  $data['psw']=$psw;

		  if($this->User_model->is_user_exist($untrm))
		  {
            if($this->User_model->cek_user_pass($untrm,$md5psw))
		    {

               if($this->User_model->is_user_aktif($untrm))
		       {
                   $this->session->set_userdata('un', $untrm);                   
                   $idu=$this->User_model->get_user_type($untrm);
                   $this->session->set_userdata('idu',$idu);
                   $this->User_model->user_login($untrm);
                   $iduntd=$this->Typeuser_unit_detail_model->get_unit_detail($idu);
                   $this->session->set_userdata('iduntd',$iduntd);
                   $id_area=$this->Unit_detail_model->get_unit_detail($iduntd);
                   $this->session->set_userdata('id_area',$id_area);
                   
                   redirect('/dashboard');

		       }else{
		    	  $data['pesan']="Anda Telah Nonaktif, Silahkan Hubungi ADMIN";
		        }

		    }else{
		    	$data['pesan']="Username atau Password Anda Tidak Valid";

		    }
		  
		  } else{
		  	$data['pesan']="Username atau Password Anda Tidak Valid";
		  }

        }
		$this->load->view('login',$data);
	}

	public function logout()
	{
        $un = $this->session->userdata('un');
        $this->User_model->user_logout($un);

        $data = array('idus','un','lvl','idu','iduntd','bkb_kec','id_area','id_c_m_g');
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();

		$this->index();
	}

    

}
