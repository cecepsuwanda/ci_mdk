<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
        
        if($this->session->userdata('un')){ 
           
           $data['menu'] = $this->get_menu();
        
           $this->load->view('dashboard',$data);
        }else{
           redirect('/login'); 
        }
	}
  
  private function get_menu()
  {
    $idu = $this->session->userdata('idu'); 
    $data = $this->Menu_model->get_user_menu($idu);
    return $data;
  }

  private function filter_limit()
  {
      $id_area = $this->session->userdata('id_area');
      $iduntd  = $this->session->userdata('iduntd');

      $data['cmb_filter']['kec']=array();
      $data['kec_select']='';
      $data['cmb_filter']['kec']=$this->Unit_detail_model->get_list_kec();
      
      $data['cmb_filter']['desa']=array();
      $data['desa_select']='';
      if($id_area==12){
          $data['kec_select']=$iduntd;
          $data['cmb_filter']['desa']=$this->Unit_detail_model->get_list_desa($iduntd);  
        }
      
      $data['cmb_filter']['dusun']=array();
      $data['dusun_select']='';
      if($id_area==13){
          $data['kec_select']=$this->Unit_detail_model->get_unit_detail_idk($iduntd);
          $data['cmb_filter']['desa']=$this->Unit_detail_model->get_list_desa($data['kec_select']); 
          $data['desa_select']=$iduntd;
          $data['cmb_filter']['dusun']=$this->Unit_detail_model->get_list_dusun($iduntd);  
        }
      
      $data['cmb_filter']['rt']=array();
      $data['rt_select']='';
      if($id_area==14){
        $data['desa_select']=$this->Unit_detail_model->get_unit_detail_idk($iduntd);        
        $data['cmb_filter']['dusun']=$this->Unit_detail_model->get_list_dusun($data['desa_select']);
        $data['kec_select']=$this->Unit_detail_model->get_unit_detail_idk($data['desa_select']);
        $data['cmb_filter']['desa']=$this->Unit_detail_model->get_list_desa($data['kec_select']);         
        $data['dusun_select']=$iduntd;
        $data['cmb_filter']['rt']=$this->Unit_detail_model->get_list_rt($iduntd);  
      }


      if($id_area==15)
        {            
        $data['dusun_select']=$this->Unit_detail_model->get_unit_detail_idk($iduntd);
        $data['cmb_filter']['rt']=$this->Unit_detail_model->get_list_rt($data['dusun_select']);  
        $data['desa_select']=$this->Unit_detail_model->get_unit_detail_idk($data['dusun_select']);        
        $data['cmb_filter']['dusun']=$this->Unit_detail_model->get_list_dusun($data['desa_select']);
        $data['kec_select']=$this->Unit_detail_model->get_unit_detail_idk($data['desa_select']);
        $data['cmb_filter']['desa']=$this->Unit_detail_model->get_list_desa($data['kec_select']);         
        $data['rt_select']=$iduntd;   
        }
     return $data;       
  }
 
  public function get_unit_detail()
  {
    
    $html_txt ='';    
    $iddusun = $this->input->post('iddusun');
    $iddesa = $this->input->post('iddesa');
    $idkec = $this->input->post('idkec');
    
    $mycmb = new mycmb;
    if(!empty($iddusun))
    {
      
      $data=$this->Unit_detail_model->get_list_rt($iddusun);
      $html_txt=$mycmb->cmb_option($data,'-- Semua RT --');
    }elseif (!empty($iddesa)) {
      
      $data=$this->Unit_detail_model->get_list_dusun($iddesa);
      $html_txt=$mycmb->cmb_option($data,'-- Semua Dusun/RW --'); 
    }elseif (!empty($idkec)) {         
          
          $data=$this->Unit_detail_model->get_list_desa($idkec);
          $html_txt=$mycmb->cmb_option($data,'-- Semua Desa/Kelurahan --');
          
    }
    echo $html_txt;
  }  
  
  public function pelaporan_filter()
  {
     $iddusun = $this->input->post('iddusun');
     $iddesa = $this->input->post('iddesa');
     $idkec = $this->input->post('idkec');
     $idrt = $this->input->post('idrt');
     $folder = $this->input->post('folder');
     $subfolder = $this->input->post('subfolder');
     $page = $this->input->post('page');

     switch($subfolder)
     {
       case 'tabel_3_+' : 
         $tmp_dt_tb = $this->Pelaporan_model->tabel_3_1($idkec,$iddesa,$iddusun,$idrt); 
         break; 
       
        case 'report_ks_tabel_1' :
            $cmbks = $this->input->post('cmbks');
            $data['ks'] = $cmbks;
            $tmp_dt_tb = $this->Pelaporan_model->$subfolder($idkec,$iddesa,$iddusun,$cmbks);
         break;

        case 'report_ks_tabel_2' :
            $cmbks = $this->input->post('cmbks');
            $data['ks'] = $cmbks;
            $tmp_dt_tb = $this->Pelaporan_model->$subfolder($idkec,$iddesa,$iddusun,$cmbks);
         break; 

       case 'report_ks_tabel_3' :
            $cmbks = $this->input->post('cmbks');
            $cmbumur = $this->input->post('cmbumur'); 
            $tmpdata[] = $this->input->post('data1'); 
            $tmpdata[] = $this->input->post('data2'); 
            $edu = $this->input->post('edu');

            $data['ks'] = $cmbks;
            $data['cmbumur'] = $cmbumur;
            $data['edu'] = $edu;
            $data['dtumur'] = $tmpdata;

            $tmp_dt_tb = $this->Pelaporan_model->$subfolder($idkec,$iddesa,$iddusun,$cmbks,$cmbumur,$tmpdata,$edu);
         break;
         
       default :
         $tmp_dt_tb = $this->Pelaporan_model->$subfolder($idkec,$iddesa,$iddusun,$idrt);   

     }

     $data['idkec']=$idkec;
     $data['iddesa']=$iddesa;
     $data['iddusun']=$iddusun;
     $data['idrt']=$idrt;
     $data['tmp_dt_tb']=$tmp_dt_tb;

     echo $this->load->view($folder.'/'.$subfolder.'/hsl_filter.php',$data,true);  
     
  } 

  public function rekap_1_serverside()
  {
     $param = $this->input->post();
     
     $data = $this->Rekap_1_model->rekap_1($param['idkec'],$param['iddesa'],$param['iddusun'],$param['idrt'],(intval($param['iDisplayStart'])/intval($param['iDisplayLength']))+1,intval($param['iDisplayLength']),$param['sSearch'],array($param['iSortCol_0'],$param['sSortDir_0']));
    
       $totalrecords=$data['totaldata'];
       $totaldisplayrecords=$data['displaydata'];

    $output = array('sEcho' => intval($param['sEcho']),
                    'iTotalRecords' => $totalrecords,
                    'iTotalDisplayRecords' => $totaldisplayrecords,
                    'aaData'=>array()   
      );

    if(!empty($data['data']))
    {
      foreach ($data['data'] as $row) {        
        $output['aaData'][]=$row;
      }
    }

    echo json_encode($output);
     
  } 

  public function rekap_1_filter()
  {
     $iddusun = $this->input->post('iddusun');
     $iddesa = $this->input->post('iddesa');
     $idkec = $this->input->post('idkec');
     $idrt = $this->input->post('idrt');
     $folder = $this->input->post('folder');
     $page = $this->input->post('page');

     $data['idkec']=$idkec;
     $data['iddesa']=$iddesa;
     $data['iddusun']=$iddusun;
     $data['idrt']=$idrt;
     

     echo $this->load->view($folder.'/hsl_filter.php',$data,true);  
     
  } 

   public function report_ks_filter()
  {
     $iddusun = $this->input->post('iddusun');
     $iddesa = $this->input->post('iddesa');
     $idkec = $this->input->post('idkec');
     $idrt = $this->input->post('idrt');
     $folder = $this->input->post('folder');
     $page = $this->input->post('page');

     
     $tmp_dt_tb=$this->Report_ks_model->report_ks($idkec,$iddesa,$iddusun,$idrt);

     $data['idkec']=$idkec;
     $data['iddesa']=$iddesa;
     $data['iddusun']=$iddusun;
     $data['idrt']=$idrt;
     $data['tmp_dt_tb']=$tmp_dt_tb;

     echo $this->load->view($folder.'/hsl_filter.php',$data,true);  
     
  } 

  public function rekap_register_data_keluarga_filter()
  {
     $iddusun = $this->input->post('iddusun');
     $iddesa = $this->input->post('iddesa');
     $idkec = $this->input->post('idkec');
     $idrt = $this->input->post('idrt');
     $jns_rpt = $this->input->post('jns_rpt');
     $folder = $this->input->post('folder');
     $page = $this->input->post('page');

     $data['idkec']=$idkec;
     $data['iddesa']=$iddesa;
     $data['iddusun']=$iddusun;
     $data['idrt']=$idrt;
     $data['jns_rpt']=$jns_rpt;  

     echo $this->load->view($folder.'/'.$jns_rpt.'.php',$data,true);  
     
  } 

  public function rekap_data_keluarga_filter()
  {
     $iddusun = $this->input->post('iddusun');
     $iddesa = $this->input->post('iddesa');
     $idkec = $this->input->post('idkec');
     $idrt = $this->input->post('idrt');
     $jns_rpt = $this->input->post('jns_rpt');
     $folder = $this->input->post('folder');
     $page = $this->input->post('page');

     $tmp_dt_tb=$this->Rekap_data_keluarga_model->$jns_rpt($idkec,$iddesa,$iddusun,$idrt); 

     $data['idkec']=$idkec;
     $data['iddesa']=$iddesa;
     $data['iddusun']=$iddusun;
     $data['idrt']=$idrt;
     $data['jns_rpt']=$jns_rpt;
     $data['tmp_dt_tb']=$tmp_dt_tb;  

     echo $this->load->view($folder.'/'.$jns_rpt.'.php',$data,true);  
     
  } 

	public function pelaporan($param1,$param2)
	{
      $data['menu'] = $this->get_menu();
      $tmp_data=$this->filter_limit();      
      $data=array_merge($data,$tmp_data);
      
      $this->load->view('pelaporan/'.$param1.'/'.$param2,$data);
	}

  public function rekap_1($param1)
  {
      $data['menu'] = $this->get_menu();
      $tmp_data=$this->filter_limit();      
      $data=array_merge($data,$tmp_data);
      
      $this->load->view('rekap_1/'.$param1,$data);
  }

 public function rekap_register_data_keluarga($param1)
  {
      $data['menu'] = $this->get_menu();
      $tmp_data=$this->filter_limit();      
      $data=array_merge($data,$tmp_data);
      $this->load->view('rekap_register_data_keluarga/'.$param1,$data);
  }

  public function rekap_data_keluarga($param1)
  {
      $data['menu'] = $this->get_menu();
      $tmp_data=$this->filter_limit();      
      $data=array_merge($data,$tmp_data);
      $this->load->view('rekap_data_keluarga/'.$param1,$data);
  }

  public function report_ks($param1)
  {
      $data['menu'] = $this->get_menu();
      $tmp_data=$this->filter_limit();      
      $data=array_merge($data,$tmp_data);
      $this->load->view('report_ks/'.$param1,$data);
  }
  

}