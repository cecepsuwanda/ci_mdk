<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_1_model extends CI_Model {


   private function getkdwl($idkec,$iddesa,$iddusun,$idrt,$pref='',$opt='AND',$field='')
	{
		$kd_wl='';
		if($idrt)
		{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_neigh = '$idrt' $opt";
			$kd='"'.$idrt.'"'; 
		}
		elseif($iddusun)
		{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_subvill = '$iddusun' $opt"; 
			$kd=$iddusun; 
		}
		elseif($iddesa)
		{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_vill = '$iddesa' $opt"; 
			$kd=$iddesa;
		}
		elseif($idkec)
		{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_subdist = '$idkec' $opt";
			$kd=$idkec; 
		}else{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_dist = '1017' $opt";
			$kd=1017;	
		}

		if(!empty($field))
		{
			$kd_wl=($pref!=''? $pref.'.':'')."$field = '$kd' $opt";
		}


		return $kd_wl;		
	}


   public function rekap_1($idkec,$iddesa,$iddusun,$idrt,$pageno,$rows_per_page,$where,$order)
    {
         $col = array(0=>'a.Kd_fam',1=>'a.nama',2=>'b.jml');

		 $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt);
		
		
        
        //$tb->pageno = $pageno;
       // $tb->rows_per_page = $rows_per_page;
		
		$sql_txt = "$kd_wl Kd_fammbrtyp=1 and Kd_mutasi IS NULL";		
		if((!empty($where)) and (!empty($_SESSION['sql_txt'])) and ($_SESSION['sql_txt']==$sql_txt)){           
           $jmltotaldata = $this->session->userdata('jmltotaldata');
		}else{  
		  $this->db->select("a.Kd_fam,a.nama,b.jml");
          $this->db->from('vw_fam_indv a inner join (select kd_fam,count(kd_fam) as jml from dbo_individu group by kd_fam) b on a.kd_fam=b.kd_fam');
          $this->db->group_by('a.Kd_fam');
        
		  if(!empty($order)){
		     $this->db->order_by($col[$order[0]].' '.$order[1]); 
		  }

		  $this->db->where($sql_txt); 
		  $data = $this->db->get();
		  $jmltotaldata = $data->num_rows();
          $jmldisplaydata = $data->num_rows();

          $this->db->select("a.Kd_fam,a.nama,b.jml");
          $this->db->from('vw_fam_indv a inner join (select kd_fam,count(kd_fam) as jml from dbo_individu group by kd_fam) b on a.kd_fam=b.kd_fam');
          $this->db->group_by('a.Kd_fam');
        
		  if(!empty($order)){
		     $this->db->order_by($col[$order[0]].' '.$order[1]); 
		  }

		  $this->db->where($sql_txt);  
          if ($rows_per_page > 0) {
		 	$this->db->limit($rows_per_page,($pageno - 1) * $rows_per_page);
		  }          
          $data = $this->db->get();
		  
          
          $this->session->set_userdata('sql_txt',$sql_txt); 
          $this->session->set_userdata('jmltotaldata',$jmltotaldata);
        }         
        
       if(!empty($where)){ 
          $this->db->select("a.Kd_fam,a.nama,b.jml");
          $this->db->from('vw_fam_indv a inner join (select kd_fam,count(kd_fam) as jml from dbo_individu group by kd_fam) b on a.kd_fam=b.kd_fam');
          $this->db->group_by('a.Kd_fam');
        
		  if(!empty($order)){
		    $this->db->order_by($col[$order[0]].' '.$order[1]); 
		  }		  

		  $str = " AND ((a.nama LIKE '%$where%') or (a.Kd_fam LIKE '%$where%') or (b.jml like '%$where%'))";
          $this->db->where("$kd_wl Kd_fammbrtyp=1 and Kd_mutasi IS NULL $str"); 

          $data = $this->db->get();
          $jmldisplaydata = $data->num_rows();		

          $this->db->select("a.Kd_fam,a.nama,b.jml");
          $this->db->from('vw_fam_indv a inner join (select kd_fam,count(kd_fam) as jml from dbo_individu group by kd_fam) b on a.kd_fam=b.kd_fam');
          $this->db->group_by('a.Kd_fam');
        
		  if(!empty($order)){
		    $this->db->order_by($col[$order[0]].' '.$order[1]); 
		  }		  

		  $str = " AND ((a.nama LIKE '%$where%') or (a.Kd_fam LIKE '%$where%') or (b.jml like '%$where%'))";
          $this->db->where("$kd_wl Kd_fammbrtyp=1 and Kd_mutasi IS NULL $str"); 

          if ($rows_per_page > 0) {
		 	$this->db->limit($rows_per_page,($pageno - 1) * $rows_per_page);
		  }  

          $data = $this->db->get(); 

       }

    $tmp_data=array();
    if(!empty($data))
    {
      foreach ($data->result_array() as $row) {
        $tmp_row = array();
        $tmp_row[] = $row['Kd_fam'];
      
		$tmp_row[]=$row['nama'];
        $tmp_row[] = $row['jml'];

        $tmp_data[]=$tmp_row;
      }
    }

		return  array('totaldata'=>$jmltotaldata,'displaydata'=>$jmldisplaydata,'data'=>$tmp_data);

    }


}