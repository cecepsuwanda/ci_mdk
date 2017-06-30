<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pelaporan_model extends CI_Model {

    private $arr_umur_b;
	private $arr_umur_bb;
	private $arr_ks;
	private $arr_ks_b;
	private $arr_ks_bb;

    private function isviewexist($vwname)
    {
    	$sql = "SHOW FULL TABLES WHERE table_type LIKE 'view' and tables_in_".$this->db->database." like  '%$vwname%'";
    	$query = $this->db->query($sql);
        return $query->num_rows()>0;         
    }

    private function createview($viewname,$sql)
    {        
		$sql = "create view $viewname as $sql";
		$query = $this->db->query($sql);		
	}

	private function get_empmnt_stat($where)
	{
      $this->db->select('*');
      $this->db->from('dbo_empmnt_stat');
      
     if(!empty($where)){ 
        $this->db->where($where);
      }

      $this->db->order_by("no_urut_empmnt");
      $query = $this->db->get();
      $hsl=array();
      if($query->num_rows()>0)
      {
        foreach($query->result_array() as $row)
        {
           $hsl[]=$row;
        }
      }
      return $hsl; 
	}

	private function get_contr_typ($where)
	{
      $this->db->select('*');
      $this->db->from('dbo_contr_typ');
      if(!empty($where)){ 
        $this->db->where($where);
      }
      $query = $this->db->get();
      $hsl=array();
      if($query->num_rows()>0)
      {
        foreach($query->result_array() as $row)
        {
           $hsl[]=$row;
        }
      }
      return $hsl; 
	}

	private function get_non_acptr_reas($where)
	{
      $this->db->select('*');
      $this->db->from('dbo_non_acptr_reas');
      if(!empty($where)){ 
        $this->db->where($where);
      }
      $query = $this->db->get();
      $hsl=array();
      if($query->num_rows()>0)
      {
        foreach($query->result_array() as $row)
        {
           $hsl[]=$row;
        }
      }
      return $hsl; 
	}

    private function get_edu_lvl($where)
	{
      $this->db->select('*');
      $this->db->from('dbo_edu_lvl');
      if(!empty($where)){
        $this->db->where($where);
      }
      $this->db->order_by("no_urut");
      $query = $this->db->get();
      $hsl=array();
      if($query->num_rows()>0)
      {
        foreach($query->result_array() as $row)
        {
           $hsl[]=$row;
        }
      }
      return $hsl; 
	}

	private function get_unit_detail($where)
	{
      
      $sql = "select * from dbo_unit_detail ";
      
      if(!empty($where)){
        $sql.="where $where";
      }
       $sql.="order by no_unit_detail";
      
      $query = $this->db->query($sql);
      $hsl=array();
      if($query->num_rows()>0)
      {
        foreach($query->result_array() as $row)
        {
           $hsl[]=$row;
        }
      }
      return $hsl; 
	}

	private function get_indikator_ks($where)
		{
	      $this->db->select('*');
	      $this->db->from('dbo_indikator_ks');
	      if(!empty($where)){
	        $this->db->where($where);
	      }
	      $this->db->order_by("no_ind_ks");
	      $query = $this->db->get();
	      $hsl=array();
	      if($query->num_rows()>0)
	      {
	        foreach($query->result_array() as $row)
	        {
	           $hsl[]=$row;
	        }
	      }
	      return $hsl; 
		}



	public function __construct() {
        parent::__construct();
        $isviewexist = $this->isviewexist('vw_fam_indv');
		if(!$isviewexist)
		{
			$this->createview('vw_fam_indv',"SELECT fam.*,kd_indv,kd_fammbrtyp,nama,kd_gen,tgl_lahir,tpt_lahir,kd_edu,kd_martl,kd_emp,kd_mutasi,keterangan,posyandu,kd_unik_indv FROM dbo_family fam INNER JOIN dbo_individu idv ON fam.Kd_fam=idv.Kd_fam");
		}  
    }

    private function getrentang($awal,$akhir=0,$pref='')
	{
		$tgl = $this->gettoday();           

		$y_awal=$tgl['y']-$awal;              	  
		$uq_awal=$y_awal."-".$tgl['m']."-".$tgl['d'];

		$y_akhir=$tgl['y']-($akhir+1);              	  
		$uq_akhir=$y_akhir."-".$tgl['m']."-".$tgl['d'];

		if($akhir==0){
			return ($pref!=''? $pref.'.' : '')."Tgl_lahir <= '$uq_awal'";
		}else{
			return ($pref!=''? $pref.'.' : '')."Tgl_lahir <= '$uq_awal' AND ".($pref!=''? $pref.'.' : '')."Tgl_lahir > '$uq_akhir'";
		} 
	} 

    private function gettoday()
	{
		$d=date('j'); 
		$m=date('n'); 
		$y=date('Y'); 
		
		if($d<10)
		$d="0".$d;
		if($m<10)
		$m="0".$m;

		return array('d'=>$d,'m'=>$m,'y'=>$y);
	}

    private function array_klmpumur($pref='',$awl=15,$jml_kls=9,$rent=4)
	{
		$tgl = $this->gettoday();

		$u_skrg=$tgl['y'].$tgl['m'].$tgl['d'];

		for ($i=0; $i<$jml_kls ; $i++) {             	
			
			$akh = ($awl+$rent+1);
			$y_awl = $tgl['y']-$awl;
			$y_akh = $tgl['y']-($awl+$rent+1); 
			$uq_awl=$y_awl."-".$tgl['m']."-".$tgl['d'];	  
			$uq_akh=$y_akh."-".$tgl['m']."-".$tgl['d'];	 

			if($rent>0){  
				$this->arr_umur_b[$awl.'-'.($akh-1)]=
				($pref!=''? $pref.'.' : '')."Tgl_lahir <= '$uq_awl' AND ".($pref!=''? $pref.'.' : '')."Tgl_lahir > '$uq_akh'"; 

				$this->arr_umur_bb[$awl.'-'.($akh-1)]=$awl.'_'.$akh;
			}else{
				$this->arr_umur_b[$awl]=
				($pref!=''? $pref.'.' : '')."Tgl_lahir <= '$uq_awl' AND ".($pref!=''? $pref.'.' : '')."Tgl_lahir > '$uq_akh'"; 

				$this->arr_umur_bb[$awl]=$awl;
			}
			$awl = $akh;
		}               
	}

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

	private function build_rc($data)
	{
		$rc=array();
		if($data->num_rows()>0)
		{
			foreach($data->result_array() as $row)
			{
				foreach($row as $key=>$v)
				{ 
					$rc[$key]=$v;                    
				}
			}
		}

		return $rc;		  
	}

	private function fjml_ttl($jml,$idx_jml,$dt,$idx_dt)
	{
		if($idx_jml=='' and $idx_dt==''){
			$tmp_jml = $jml + $dt;
		}elseif($idx_dt==''){
			$tmp_jml = (isset($jml[$idx_jml]) ? $jml[$idx_jml] + $dt : $dt);
		}else{
			$tmp_jml = (isset($jml[$idx_jml]) ? ($jml[$idx_jml] + (isset($dt[$idx_dt]) ? $dt[$idx_dt] : 0)) : (isset($dt[$idx_dt]) ? $dt[$idx_dt] : 0));   	  
		}   	       
		return $tmp_jml;
	}

	private function fkurangi($jml,$idx_jml,$dt,$idx_dt)
	{
		if($idx_jml=='' and $idx_dt==''){
			$tmp_jml = $jml - $dt;
		}elseif($idx_dt==''){
			$tmp_jml = (isset($jml[$idx_jml]) ? $jml[$idx_jml] - $dt : $dt);
		}else{
			$tmp_jml = (isset($jml[$idx_jml]) ? ($jml[$idx_jml] - (isset($dt[$idx_dt]) ? $dt[$idx_dt] : 0)) : (isset($dt[$idx_dt]) ? $dt[$idx_dt] : 0));   	  
		}   	       
		return $tmp_jml;
	}

	private function frupiah($dt,$idx,$digit=0)
	{
       if($idx=='')
       {
         $txt = number_format($dt,$digit,',','.');
       }else{
       	$txt = number_format((isset($dt[$idx]) ? $dt[$idx] : 0),$digit,',','.');
       }

       return $txt;
	}

	private function fprosen($dt1,$idx_dt1,$dt2,$idx_dt2,$kali=100)
	{
		if($idx_dt1=='' and $idx_dt2==''){
			$tmp_prosen = ($dt2 !=0 ? round((($dt1/$dt2)*$kali),2) : 0 );
		}elseif($idx_dt2==''){
			$tmp_prosen = ($dt2 !=0 ? round((((isset($dt1[$idx_dt1]) ? $dt1[$idx_dt1] : 0)/$dt2)*$kali),2) : 0 );
		}
		else{
		  if(isset($dt2[$idx_dt2])){	
			$tmp_prosen = ($dt2[$idx_dt2] !=0 ? round((((isset($dt1[$idx_dt1]) ? $dt1[$idx_dt1] : 0)/$dt2[$idx_dt2])*$kali),2) : 0 );   	  
		  }else{
		  	$tmp_prosen =0;
		  }
		}   	       
		return $tmp_prosen;
	} 

	private function arrayks()
	{
		$this->arr_ks['pra_s']="Kd_prosplvl=1 OR Kd_prosplvl=6";
		$this->arr_ks['ks_1']="Kd_prosplvl=2 OR Kd_prosplvl=7";
		$this->arr_ks['ks_2']="Kd_prosplvl=3";
		$this->arr_ks['ks_3']="Kd_prosplvl=4";
		$this->arr_ks['ks_3_p']="Kd_prosplvl=5";
		
		$this->arr_ks_b['pra_s']="PRA S";
		$this->arr_ks_b['ks_1']="KS I";
		$this->arr_ks_b['ks_2']="KS II";
		$this->arr_ks_b['ks_3']="KS III";
		$this->arr_ks_b['ks_3_p']="KS III+";
		
		$this->arr_ks_bb['pra_s']="pra_s";
		$this->arr_ks_bb['ks_1']="ks_1";
		$this->arr_ks_bb['ks_2']="ks_2";
		$this->arr_ks_bb['ks_3']="ks_3";
		$this->arr_ks_bb['ks_3_p']="ks_3_p";
	}


    public function tabel_1($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');
		$this->array_klmpumur();		
		
		$data = $this->get_empmnt_stat('Kd_emp != 6');

		$str='';
		foreach($data as  $rc)
		{
			$arr_emp[]=$rc['Kd_emp'];
			
			foreach($this->arr_umur_b as $idx=>$isi)
			{
				
				$str= (!empty($str) ? $str.',':'') .
				"SUM(Kd_emp=$rc[Kd_emp] AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_$rc[Kd_emp]";
				
			}
		}

		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str.=",SUM(Kd_emp=6 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_tkrj";
		}

		$rent=$this->getrentang(15,59,'');              
		
		
		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("($rent)");  
        $this->db->where("Kd_mutasi IS NULL");        
        $data = $this->db->get();

		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$jmlh_krj=array();
		$jmlh_tkrj=0;
		$i=1;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;
			$jmlh=0;
			foreach($arr_emp as $idxx=>$isii)
			{
				$str_rc="j_".$isi."_".$isii; 
				$jmlh=$this->fjml_ttl($rc,$str_rc,$jmlh,'');				
				$jmlh_krj[$isii]=$this->fjml_ttl($jmlh_krj,$isii,$rc,$str_rc);
				$tmp_dt_tb[]=$this->frupiah($rc,$str_rc);
			}
			$str_rc_tkrj="j_".$isi."_tkrj";			
			$jmlh_tkrj=$this->fjml_ttl($rc,$str_rc_tkrj,$jmlh_tkrj,'');

			$tmp_dt_tb[]=$this->frupiah($jmlh,'');		   
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_tkrj); 	   
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc_tkrj,$jmlh,''),'');	  
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();    
		$jmlh_tot=0; 
		foreach($arr_emp as $idx=>$isi)
		{
			$jmlh_tot=$this->fjml_ttl($jmlh_krj,$isi,$jmlh_tot,'');
			$footer_tb[]=$this->frupiah($jmlh_krj,$isi);	   	   
			
		}
		$footer_tb[]=$this->frupiah($jmlh_tot,''); 		   
		$footer_tb[]=$this->frupiah($jmlh_tkrj,'');
		$footer_tb[]=$this->frupiah($jmlh_tot+$jmlh_tkrj,''); 		   
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    } 

    public function tabel_2($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');
		$this->array_klmpumur();

		$str='';
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str=(!empty($str) ? $str.',':'') ."SUM(Kd_gen=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_lk,
							SUM(Kd_gen=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pr,
							SUM(Kd_gen=1 AND Kd_emp!=6 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_lk_krj,
							SUM(Kd_gen=2 AND Kd_emp!=6 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pr_krj";
			
		}

		$rent=$this->getrentang(15,59);	              
		
		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("($rent)");  
        $this->db->where("Kd_mutasi IS NULL");
		$data = $this->db->get();

		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$i=1;
		$jmlh_lk=0;
        $jmlh_lk_krj=0;
        $jmlh_pr=0;
        $jmlh_pr_krj=0;

		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_rc_lk="j_".$isi."_lk"; 
			$str_rc_pr="j_".$isi."_pr"; 
			$str_rc_lk_krj="j_".$isi."_lk_krj"; 
			$str_rc_pr_krj="j_".$isi."_pr_krj";
			$jmlh_lk=$this->fjml_ttl($rc,$str_rc_lk,$jmlh_lk,'');
			$jmlh_pr=$this->fjml_ttl($rc,$str_rc_pr,$jmlh_pr,'');
			$jmlh_lk_krj=$this->fjml_ttl($rc,$str_rc_lk_krj,$jmlh_lk_krj,''); 
			$jmlh_pr_krj=$this->fjml_ttl($rc,$str_rc_pr_krj,$jmlh_pr_krj,''); 

			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;    
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_lk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_pr); 
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc_lk,$rc,$str_rc_pr),''); 	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_lk_krj);	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_pr_krj); 	
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc_lk_krj,$rc,$str_rc_pr_krj),''); 	
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	            
		
		$footer_tb=array();	   
		$footer_tb[]=$this->frupiah($jmlh_lk,'');	
		$footer_tb[]=$this->frupiah($jmlh_pr,''); 		
		$footer_tb[]=$this->frupiah($jmlh_lk+$jmlh_pr,''); 		
		$footer_tb[]=$this->frupiah($jmlh_lk_krj,''); 		
		$footer_tb[]=$this->frupiah($jmlh_pr_krj,''); 		
		$footer_tb[]=$this->frupiah($jmlh_lk_krj+$jmlh_pr_krj,''); 
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);

    }

    public function tabel_3($idkec,$iddesa,$iddusun,$idrt)
    {
      	$kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');
		$this->array_klmpumur();              
		
		$rent = $this->getrentang(60);
		$this->arr_umur_b['60+']=$rent;
		$this->arr_umur_bb['60+']='60';

		$str='';
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			
			$str=(!empty($str) ? $str.',':'') ."SUM(Kd_fammbrtyp=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_kk,
							SUM(Kd_fammbrtyp=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_istr,
							SUM((Kd_fammbrtyp=3 AND Kd_gen=1) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ank_lk,
							SUM((Kd_fammbrtyp=3 AND Kd_gen=2) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ank_pr,
							SUM((Kd_fammbrtyp=4 AND Kd_gen=1) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ln_lk,
							SUM((Kd_fammbrtyp=4 AND Kd_gen=2) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ln_pr,
							SUM(($isi)) AS j_".$this->arr_umur_bb[$idx]."_jml";
			
		}

		$rent=$this->getrentang(15);
		
		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("($rent)");  
        $this->db->where("Kd_mutasi IS NULL");
		$data = $this->db->get();
		
		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$i=1;
        $jmlh_kk=0;
        $jmlh_istr=0;
        $jmlh_ank_lk=0;
        $jmlh_ank_pr=0;
        $jmlh_ln_lk=0;
        $jmlh_ln_pr=0;
        $jmlh_ttl=0;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_rc_kk="j_".$isi."_kk"; 
			$str_rc_istr="j_".$isi."_istr"; 
			$str_rc_ank_lk="j_".$isi."_ank_lk"; 
			$str_rc_ank_pr="j_".$isi."_ank_pr";
			$str_rc_ln_lk="j_".$isi."_ln_lk"; 
			$str_rc_ln_pr="j_".$isi."_ln_pr";
			$str_rc_jml="j_".$isi."_jml";

			$jmlh_kk=$this->fjml_ttl($rc,$str_rc_kk,$jmlh_kk,'');
			$jmlh_istr=$this->fjml_ttl($rc,$str_rc_istr,$jmlh_istr,'');
			$jmlh_ank_lk=$this->fjml_ttl($rc,$str_rc_ank_lk,$jmlh_ank_lk,''); 
			$jmlh_ank_pr=$this->fjml_ttl($rc,$str_rc_ank_pr,$jmlh_ank_pr,'');
			$jmlh_ln_lk=$this->fjml_ttl($rc,$str_rc_ln_lk,$jmlh_ln_lk,'');
			$jmlh_ln_pr=$this->fjml_ttl($rc,$str_rc_ln_pr,$jmlh_ln_pr,'');
			$jmlh_ttl=$this->fjml_ttl($rc,$str_rc_jml,$jmlh_ttl,'');

			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;    
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_kk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_istr); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_ank_lk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_ank_pr);	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_ln_lk);	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_ln_pr); 	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_jml);	
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	            
		
		$footer_tb=array();	   
		$footer_tb[]=$this->frupiah($jmlh_kk,'');	
		$footer_tb[]=$this->frupiah($jmlh_istr,'');		   		
		$footer_tb[]=$this->frupiah($jmlh_ank_lk,''); 		
		$footer_tb[]=$this->frupiah($jmlh_ank_pr,''); 		
		$footer_tb[]=$this->frupiah($jmlh_ln_lk,''); 		
		$footer_tb[]=$this->frupiah($jmlh_ln_pr,'');
		$footer_tb[]=$this->frupiah($jmlh_ttl,''); 
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }


    public function tabel_3_1($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');

		$this->array_klmpumur('',15,5,0);              
		$this->array_klmpumur('',20,8,4); 

		$rent=$this->getrentang(60);             
		
		$this->arr_umur_b['60+']=$rent;
		$this->arr_umur_bb['60+']='60';

		$str='';
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			
			$str=(!empty($str) ? $str.',':'')."SUM(Kd_fammbrtyp=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_kk,
							SUM(Kd_fammbrtyp=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_istr,
							SUM((Kd_fammbrtyp=3 AND Kd_gen=1) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ank_lk,
							SUM((Kd_fammbrtyp=3 AND Kd_gen=2) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ank_pr,
							SUM((Kd_fammbrtyp=4 AND Kd_gen=1) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ln_lk,
							SUM((Kd_fammbrtyp=4 AND Kd_gen=2) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ln_pr,
							SUM(($isi)) AS j_".$this->arr_umur_bb[$idx]."_jml";
			
		}

		$rent=$this->getrentang(15);  
				
		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("($rent)");  
        $this->db->where("Kd_mutasi IS NULL");
		$data = $this->db->get();
		
		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$i=1;
		$jmlh_kk=0;
        $jmlh_istr=0;
        $jmlh_ank_lk=0;
        $jmlh_ank_pr=0;
        $jmlh_ln_lk=0;
        $jmlh_ln_pr=0;
        $jmlh_ttl=0;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_rc_kk="j_".$isi."_kk"; 
			$str_rc_istr="j_".$isi."_istr"; 
			$str_rc_ank_lk="j_".$isi."_ank_lk"; 
			$str_rc_ank_pr="j_".$isi."_ank_pr";
			$str_rc_ln_lk="j_".$isi."_ln_lk"; 
			$str_rc_ln_pr="j_".$isi."_ln_pr";
			$str_rc_jml="j_".$isi."_jml";

			$jmlh_kk=$this->fjml_ttl($rc,$str_rc_kk,$jmlh_kk,'');
			$jmlh_istr=$this->fjml_ttl($rc,$str_rc_istr,$jmlh_istr,'');
			$jmlh_ank_lk=$this->fjml_ttl($rc,$str_rc_ank_lk,$jmlh_ank_lk,''); 
			$jmlh_ank_pr=$this->fjml_ttl($rc,$str_rc_ank_pr,$jmlh_ank_pr,'');
			$jmlh_ln_lk=$this->fjml_ttl($rc,$str_rc_ln_lk,$jmlh_ln_lk,'');
			$jmlh_ln_pr=$this->fjml_ttl($rc,$str_rc_ln_pr,$jmlh_ln_pr,'');
			$jmlh_ttl=$this->fjml_ttl($rc,$str_rc_jml,$jmlh_ttl,'');

			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;    
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_kk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_istr); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_ank_lk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_ank_pr);	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_ln_lk);	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_ln_pr); 	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_jml);	
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();	   
		$footer_tb[]=$this->frupiah($jmlh_kk,'');	
		$footer_tb[]=$this->frupiah($jmlh_istr,'');		   		
		$footer_tb[]=$this->frupiah($jmlh_ank_lk,''); 		
		$footer_tb[]=$this->frupiah($jmlh_ank_pr,''); 		
		$footer_tb[]=$this->frupiah($jmlh_ln_lk,''); 		
		$footer_tb[]=$this->frupiah($jmlh_ln_pr,'');
		$footer_tb[]=$this->frupiah($jmlh_ttl,''); 
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }
    
    public function tabel_4($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'f');              
		$this->array_klmpumur('',15,7,4);
		
		
		$dt_contr = $this->get_contr_typ('');  

		if(!empty($dt_contr)){
			$str='';
			foreach ($dt_contr as $row) {
				foreach($this->arr_umur_b as $idx=>$isi)
				{
					
					$str=(!empty($str) ? $str.',':'') ."SUM(contyp=$row[Kd_contyp] AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_$row[Kd_contyp]";
					
				}
			}
		} 
		
		foreach($this->arr_umur_b as $idx=>$isi)
		{					   
			$str.=",SUM(j_ank=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ank_1";
			$str.=",SUM(j_ank=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ank_2";
			$str.=",SUM(j_ank=3 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ank_3";
			$str.=",SUM(j_ank>3 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ank_3_p";   
		}


		$rent=$this->getrentang(15,49);

		$sql_from="( ".    
		"SELECT f.Kd_contyp AS contyp,f.Tgl_lahir AS tgl_lahir,(SELECT COUNT(*) 
										FROM dbo_individu ii 
										WHERE f.Kd_fam = ii.Kd_fam AND ii.Kd_fammbrtyp = 3 AND ii.Kd_mutasi IS NULL) AS j_ank ".
		"FROM vw_fam_indv f ".
		"WHERE $kd_wl (".
		"((f.Kd_contyp in (1,3,4,5,6,7)) AND f.Kd_fammbrtyp = 2) ".  
		"OR ((f.Kd_contyp in (2,8)) AND f.Kd_fammbrtyp = 1) ".
		") AND ($rent) AND f.Kd_mutasi IS NULL".") AS di ";
		$this->db->_protect_identifiers = FALSE;
		$this->db->select($str);
        $this->db->from($sql_from);       
		$data = $this->db->get();
		
		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$i=1;
		$jmlttl=0;
		$arr_jml=array();
		$jmlh_ank_1 =0;
		$jmlh_ank_2 =0;
		$jmlh_ank_3 =0;
		$jmlh_ank_3_p =0;

		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_ank_1="j_".$isi."_ank_1"; 
			$str_ank_2="j_".$isi."_ank_2"; 
			$str_ank_3="j_".$isi."_ank_3"; 
			$str_ank_3_p="j_".$isi."_ank_3_p";			     

			$jmlh_ank_1=$this->fjml_ttl($rc,$str_ank_1,$jmlh_ank_1,'');
			$jmlh_ank_2=$this->fjml_ttl($rc,$str_ank_2,$jmlh_ank_2,'');
			$jmlh_ank_3=$this->fjml_ttl($rc,$str_ank_3,$jmlh_ank_3,'');
			$jmlh_ank_3_p=$this->fjml_ttl($rc,$str_ank_3_p,$jmlh_ank_3_p,'');		     

			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;    

			
			$jml=0;
			
			if(!empty($dt_contr)){
				foreach ($dt_contr as $row) {
					$str = "j_".$isi."_".$row['Kd_contyp'];
					$jml = $this->fjml_ttl($rc,$str,$jml,'');
					$arr_jml[$row['Kd_contyp']] = $this->fjml_ttl($arr_jml,$row['Kd_contyp'],$rc,$str);
					$tmp_dt_tb[]=$this->frupiah($rc,$str);
				}
			}   
			
			$tmp_dt_tb[]=$this->frupiah($jml,'');
			$jmlttl = $jmlttl + $jml;

			$tmp_dt_tb[]=$this->frupiah($rc,$str_ank_1);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ank_2); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ank_3);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ank_3_p);	
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		if(!empty($arr_jml))
		{
			foreach ($arr_jml as $value) 
			{
				$footer_tb[]=$this->frupiah($value,'');
			}
		} 
		
		$footer_tb[]=$this->frupiah($jmlttl,'');            
		$footer_tb[]=$this->frupiah($jmlh_ank_1,'');	
		$footer_tb[]=$this->frupiah($jmlh_ank_2,'');		   		
		$footer_tb[]=$this->frupiah($jmlh_ank_3,''); 		
		$footer_tb[]=$this->frupiah($jmlh_ank_3_p,''); 		
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 

    }

    public function tabel_5($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');              
		$this->array_klmpumur('',15,7,4);
		
		$dt_nonacptr = $this->get_non_acptr_reas('');  

		if(!empty($dt_nonacptr)){
			$str='';
			foreach ($dt_nonacptr as $row) {
				foreach($this->arr_umur_b as $idx=>$isi)
				{
					$str=(!empty($str) ? $str.',':'') ."SUM(Kd_nonacptr=$row[Kd_nonacptr] AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_$row[Kd_nonacptr]";
					
				}
			}
		} 
		
		foreach($this->arr_umur_b as $idx=>$isi)
		{					   
			$str.=",SUM($isi) AS j_".$this->arr_umur_bb[$idx]."_pus";					     
		}

		foreach($this->arr_umur_b as $idx=>$isi)
		{					   
			$str.=",SUM(Kd_nonacptr IS NULL AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pus_kb";					     
		}

		$rent=$this->getrentang(15,49);

        $this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("pus = '1' ");  
        $this->db->where("((((Kd_contyp in (1,3,4,5,6,7)) 
													AND Kd_fammbrtyp =2) ".  
		"OR ((Kd_contyp in (2,8)) AND Kd_fammbrtyp =1)) 
												OR (Kd_nonacptr IS NOT NULL AND Kd_fammbrtyp=2)) 
												AND ($rent) AND Kd_mutasi IS NULL");
		$data = $this->db->get();

		$rc=$this->build_rc($data);
		
		$dt_tb=array();
		$i=1;
		$jmlttl=0;
		$arr_jml=array();
		$jmlh_pus=0;
		$jmlh_pus_kb = 0;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_pus="j_".$isi."_pus"; 
			$str_pus_kb="j_".$isi."_pus_kb"; 
			
			$jmlh_pus=$this->fjml_ttl($rc,$str_pus,$jmlh_pus,'');
			$jmlh_pus_kb=$this->fjml_ttl($rc,$str_pus_kb,$jmlh_pus_kb,''); 
			
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;    
			

			$tmp_dt_tb[]=$this->frupiah($rc,$str_pus);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pus_kb); 

			$jml=0;
			
			if(!empty($dt_nonacptr)){
				foreach ($dt_nonacptr as $row) {
					$str = "j_".$isi."_".$row['Kd_nonacptr'];
					$jml = $this->fjml_ttl($rc,$str,$jml,''); 
					$arr_jml[$row['Kd_nonacptr']] =$this->fjml_ttl($arr_jml,$row['Kd_nonacptr'],$rc,$str); 
					$tmp_dt_tb[]=$this->frupiah($rc,$str);
				}
			}   
			
			$tmp_dt_tb[]=$this->frupiah($jml,'');
			$jmlttl = $jmlttl + $jml;
			
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array(); 
		$footer_tb[]=$this->frupiah($jmlh_pus,'');	
		$footer_tb[]=$this->frupiah($jmlh_pus_kb,'');

		if(!empty($arr_jml))
		{
			foreach ($arr_jml as $value) 
			{
				$footer_tb[]=$this->frupiah($value,'');
			}
		} 
		
		$footer_tb[]=$this->frupiah($jmlttl,'');
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_6($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');

		$this->array_klmpumur('',0,16,4);
		
		$rent=$this->getrentang(80);
		
		$this->arr_umur_b['80+']=$rent;              
		$this->arr_umur_bb['80+']='80';
		
		$str='';
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str=(!empty($str) ? $str.',':'') ."SUM(Kd_gen=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_lk,
								SUM(Kd_gen=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pr";
			
		}			                    

		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("Kd_mutasi IS NULL");  
       	$data = $this->db->get();
		
		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$i=1;
		$jml=0;
		$jmlttl=0;
		$jmlh_lk=0;
		$jmlh_pr=0;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_lk="j_".$isi."_lk"; 
			$str_pr="j_".$isi."_pr"; 
			
			$jmlh_lk=$this->fjml_ttl($rc,$str_lk,$jmlh_lk,'');
			$jmlh_pr=$this->fjml_ttl($rc,$str_pr,$jmlh_pr,'');
			$jml = $this->fjml_ttl($rc,$str_lk,$rc,$str_pr);
			$jmlttl = $jmlttl + $jml; 
			
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;                

			$tmp_dt_tb[]=$this->frupiah($rc,$str_lk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pr);
			$tmp_dt_tb[]=$this->frupiah($jml,''); 

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$footer_tb[]=$this->frupiah($jmlh_lk,'');	
		$footer_tb[]=$this->frupiah($jmlh_pr,'');
		$footer_tb[]=$this->frupiah($jmlttl,'');
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_7($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');              
		
		$this->array_klmpumur('',45,5,0);
		
		$str='';
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str=(!empty($str) ? $str.',':'') ."SUM(Kd_nonacptr IS NULL AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pus_kb";
			
		}
		
		
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			
			$str.=",SUM($isi) AS j_".$this->arr_umur_bb[$idx]."_pus";
			
		}

		$rent=$this->getrentang(45,49);		    

		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("pus = '1'");
        $this->db->where("((((Kd_contyp in (1,3,4,5,6,7)) AND Kd_fammbrtyp =2) OR ((Kd_contyp in (2,8)) AND Kd_fammbrtyp =1))OR (Kd_nonacptr IS NOT NULL AND Kd_fammbrtyp=2)) AND ($rent) AND Kd_mutasi IS NULL");  
       	$data = $this->db->get();

		
		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$i=1;
		$jml=0;
		$jmlttl=0;
		$jmlh_pus=0;
		$jmlh_pus_kb=0;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_pus="j_".$isi."_pus"; 
			$str_pus_kb="j_".$isi."_pus_kb"; 
			
			$jmlh_pus=$this->fjml_ttl($rc,$str_pus,$jmlh_pus,'');
			$jmlh_pus_kb=$this->fjml_ttl($rc,$str_pus_kb,$jmlh_pus_kb,'');
			$prosen = $this->fprosen($rc,$str_pus_kb,$rc,$str_pus);
			
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;                

			$tmp_dt_tb[]=$this->frupiah($rc,$str_pus);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pus_kb);
			$tmp_dt_tb[]=$this->frupiah($prosen,'',2).'%'; 

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		$footer_tb=array();
		$footer_tb[]=$this->frupiah($jmlh_pus,'');	
		$footer_tb[]=$this->frupiah($jmlh_pus_kb,'');
		$prosen = $this->fprosen($jmlh_pus_kb,'',$jmlh_pus,'');
		$footer_tb[]=$this->frupiah($prosen,'',2).'%';
		
		
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_8($idkec,$iddesa,$iddusun,$idrt)
    {
    	$kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');  

		$this->arr_umur_b['5-6']=$this->getrentang(5,6);
		$this->arr_umur_b['7-12']=$this->getrentang(7,12);
		$this->arr_umur_b['13-15']=$this->getrentang(13,15);
		$this->arr_umur_b['16-18']=$this->getrentang(16,18);
		$this->arr_umur_b['19-23']=$this->getrentang(19,23);

		$this->arr_umur_bb['5-6']='5_6';
		$this->arr_umur_bb['7-12']='7_12';
		$this->arr_umur_bb['13-15']='13_15';
		$this->arr_umur_bb['16-18']='16_18';
		$this->arr_umur_bb['19-23']='19_23';

		$dt_edulvl = $this->get_edu_lvl('Kd_edu != 0'); 
                
		if(!empty($dt_edulvl)){
			$str='';
			foreach ($dt_edulvl as $rc) {                
				foreach($this->arr_umur_b as $idx=>$isi)
				{
					
					$str=(!empty($str) ? $str.',':'') ."SUM(Kd_edu=$rc[Kd_edu] AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_$rc[Kd_edu]";
					
				}
			}
			
		}

		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str.=",SUM(Kd_edu=0 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_tskl";
		}	

		$rent=$this->getrentang(5,23);
						
        $this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("($rent)");
        $this->db->where("Kd_mutasi IS NULL");  
       	$data = $this->db->get();


		$rc=$this->build_rc($data);
		
		$dt_tb=array();
		$i=1;
		$arrjml=array();
		$jmlttl=0;
		$jmlttl1=0;
		$jmlh_tskl=0;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_tskl="j_".$isi."_tskl";                  
			
			$jmlh_tskl=$this->fjml_ttl($rc,$str_tskl,$jmlh_tskl,'');
			
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;                

			$tmp_dt_tb[]=$this->frupiah($rc,$str_tskl);
			$jml=0;
			$jml1=0;
			if(!empty($dt_edulvl)){
				foreach ($dt_edulvl as $row) {
					$str="j_".$isi."_$row[Kd_edu]";
					$jml=$this->fjml_ttl($rc,$str,$jml,'');
					
					$arrjml[$row['Kd_edu']] = $this->fjml_ttl($arrjml,$row['Kd_edu'],$rc,$str);
					$tmp_dt_tb[]=$this->frupiah($rc,$str);
				}
			}  
			$jmlttl=$jmlttl+$jml;
			$tmp_dt_tb[]=$this->frupiah($jml,'');
			$jml1=$this->fjml_ttl($rc,$str_tskl,$jml,'');
			$tmp_dt_tb[]=$this->frupiah($jml1,'');
			$jmlttl1=$jmlttl1+$jml1;

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	            
		
		$footer_tb=array();   
		$footer_tb[]=$this->frupiah($jmlh_tskl,'');	
		
		if(!empty($dt_edulvl)){
			foreach ($dt_edulvl as $row) {
				$footer_tb[]=$this->frupiah($arrjml,$row['Kd_edu']);
			}

		}
		$footer_tb[]=$this->frupiah($jmlttl,'');	   		
		$footer_tb[]=$this->frupiah($jmlttl1,'');	
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_9($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');              
		
		$this->arr_umur_b['5-6']=$this->getrentang(5,6);
		$this->arr_umur_b['7-12']=$this->getrentang(7,12);
		$this->arr_umur_b['13-15']=$this->getrentang(13,15);
		$this->arr_umur_b['16-18']=$this->getrentang(16,18);
		$this->arr_umur_b['19-23']=$this->getrentang(19,23);

		$this->arr_umur_bb['5-6']='5_6';
		$this->arr_umur_bb['7-12']='7_12';
		$this->arr_umur_bb['13-15']='13_15';
		$this->arr_umur_bb['16-18']='16_18';
		$this->arr_umur_bb['19-23']='19_23';
		
		$str='';
		
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			
			$str=(!empty($str) ? $str.',':'') .
			"SUM(Kd_edu=0 AND Kd_gen=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_tskl_lk,
								SUM(Kd_edu=0 AND Kd_gen=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_tskl_pr,
								SUM(Kd_edu!=0 AND Kd_gen=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_skl_lk,
								SUM(Kd_edu!=0 AND Kd_gen=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_skl_pr";
			
		} 


		$rent=$this->getrentang(5,23);	    

		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("($rent)");
        $this->db->where("Kd_mutasi IS NULL");  
       	$data = $this->db->get();
		
		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$i=1;
		$jmlh_tskl_lk=0;
		$jmlh_tskl_pr=0;
		$jmlh_skl_lk=0;
		$jmlh_skl_pr=0;
		$jml=0;
		$jml1=0;
		$jml2=0;
		$jmlttl=0;
		$jmlttl1=0;
		$jmlttl2=0;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_tskl_lk="j_".$isi."_tskl_lk";
			$str_tskl_pr="j_".$isi."_tskl_pr"; 
			$str_skl_lk="j_".$isi."_skl_lk";
			$str_skl_pr="j_".$isi."_skl_pr";                    
			
			$jmlh_tskl_lk=$this->fjml_ttl($rc,$str_tskl_lk,$jmlh_tskl_lk,'');
			$jmlh_tskl_pr=$this->fjml_ttl($rc,$str_tskl_pr,$jmlh_tskl_pr,'');
			$jml = $this->fjml_ttl($rc,$str_tskl_lk,$rc,$str_tskl_pr);
			$jmlttl=$jmlttl+$jml;
			$jmlh_skl_lk=$this->fjml_ttl($rc,$str_skl_lk,$jmlh_skl_lk,''); 
			$jmlh_skl_pr=$this->fjml_ttl($rc,$str_skl_pr,$jmlh_skl_pr,'');
			$jml1 = $this->fjml_ttl($rc,$str_skl_lk,$rc,$str_skl_pr);
			$jmlttl1=$jmlttl1+$jml1;

			$jml2=$jml+$jml1;
			$jmlttl2 = $jmlttl2 + $jml2;
			
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;                

			$tmp_dt_tb[]=$this->frupiah($rc,$str_tskl_lk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_tskl_pr);
			$tmp_dt_tb[]=$this->frupiah($jml,'');
			$tmp_dt_tb[]=$this->frupiah($rc,$str_skl_lk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_skl_pr);
			$tmp_dt_tb[]=$this->frupiah($jml1,'');
			$tmp_dt_tb[]=$this->frupiah($jml2,'');

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();            
		$footer_tb[]=$this->frupiah($jmlh_tskl_lk,'');	
		$footer_tb[]=$this->frupiah($jmlh_tskl_pr,'');
		$footer_tb[]=$this->frupiah($jmlttl,'');
		$footer_tb[]=$this->frupiah($jmlh_skl_lk,'');	
		$footer_tb[]=$this->frupiah($jmlh_skl_pr,'');
		$footer_tb[]=$this->frupiah($jmlttl1,'');
		$footer_tb[]=$this->frupiah($jmlttl2,'');		   
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_10($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');   

		$this->array_klmpumur('',0,16,4);           
		
		$rent=$this->getrentang(80);			  
		$this->arr_umur_b['80+']=$rent;             
		$this->arr_umur_bb['80+']='80';

		$dt_edulvl = $this->get_edu_lvl('Kd_edu != 0');

		if(!empty($dt_edulvl)){
			$str='';
			foreach ($dt_edulvl as $rc) {                
				foreach($this->arr_umur_b as $idx=>$isi)
				{
					$str=(!empty($str) ? $str.',':'') ."SUM(Kd_edu=$rc[Kd_edu] AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_$rc[Kd_edu]";
					
				}
			}
			
		}

		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str.=",SUM(Kd_edu=0 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_tskl";
		}	

		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("Kd_mutasi IS NULL");  
       	$data = $this->db->get();
		
		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$i=1;
		$arrjml=array();
		$jmlttl=0;
		$jmlttl1=0;
		$jmlh_tskl=0;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			
			$str_tskl="j_".$isi."_tskl";                  
			
			$jmlh_tskl=$this->fjml_ttl($rc,$str_tskl,$jmlh_tskl,'');
			
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;                

			$tmp_dt_tb[]=$this->frupiah($rc,$str_tskl);
			$jml=0;
			$jml1=0;
			if(!empty($dt_edulvl)){
				foreach ($dt_edulvl as $row) {
					$str="j_".$isi."_$row[Kd_edu]";
					$jml=$this->fjml_ttl($rc,$str,$jml,'');
					
					$arrjml[$row['Kd_edu']] = $this->fjml_ttl($arrjml,$row['Kd_edu'],$rc,$str);
					$tmp_dt_tb[]=$this->frupiah($rc,$str);
				}
			}  
			$jmlttl=$jmlttl+$jml;
			$tmp_dt_tb[]=$this->frupiah($jml,'');
			$jml1=$this->fjml_ttl($rc,$str_tskl,$jml,'');
			$tmp_dt_tb[]=$this->frupiah($jml1,'');
			$jmlttl1=$jmlttl1+$jml1;

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();           
		$footer_tb[]=$this->frupiah($jmlh_tskl,'');

		if(!empty($dt_edulvl))
		{
			foreach ($dt_edulvl as $row) 
			{
				$footer_tb[]=$this->frupiah($arrjml,$row['Kd_edu']);
			}
		}

		$footer_tb[]=$this->frupiah($jmlttl,'');	   		
		$footer_tb[]=$this->frupiah($jmlttl1,'');	
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_11($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');

		$this->array_klmpumur('',0,16,4);
		
		$rent=$this->getrentang(80);			  
		$this->arr_umur_b['80+']=$rent;             
		$this->arr_umur_bb['80+']='80';
				
		$data = $this->get_empmnt_stat('Kd_emp != 6');

		$str='';
		foreach($data as  $rc)
		{
			$arr_emp[]=$rc['Kd_emp'];
			
			foreach($this->arr_umur_b as $idx=>$isi)
			{
				$str=(!empty($str) ? $str.',':'') ."SUM(Kd_emp=$rc[Kd_emp] AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_$rc[Kd_emp]";
				
			}
		}

		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str.=",SUM(Kd_emp=6 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_tkrj";
		}              
		
		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("Kd_mutasi IS NULL");  
       	$data = $this->db->get();
		
		$rc=$this->build_rc($data);              
		
		$dt_tb=array();
		$jmlh_krj=array();
		$i=1;
		$jmlh_tkrj=0;
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;
			
			$str_rc_tkrj="j_".$isi."_tkrj";
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_tkrj);
			$jmlh_tkrj=$this->fjml_ttl($rc,$str_rc_tkrj,$jmlh_tkrj,'');
			
			$jmlh=0;
			foreach($arr_emp as $idxx=>$isii)
			{
				$str_rc="j_".$isi."_".$isii; 
				$jmlh=$this->fjml_ttl($rc,$str_rc,$jmlh,'');
				$jmlh_krj[$isii]=$this->fjml_ttl($jmlh_krj,$isii,$rc,$str_rc);
				$tmp_dt_tb[]=$this->frupiah($rc,$str_rc); 
			}				 

			$tmp_dt_tb[]=$this->frupiah($jmlh,'');		   
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc_tkrj,$jmlh,''),'');	  
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$footer_tb[]=$this->frupiah($jmlh_tkrj,'');		      

		$jmlh_tot=0; 
		foreach($arr_emp as $idx=>$isi)
		{
			$jmlh_tot=$this->fjml_ttl($jmlh_krj,$isi,$jmlh_tot,'');
			$footer_tb[]=$this->frupiah($jmlh_krj,$isi);		   	   
			
		}
		$footer_tb[]=$this->frupiah($jmlh_tot,''); 		   
		$footer_tb[]=$this->frupiah($jmlh_tot+$jmlh_tkrj,'');	   
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_12($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');

		$this->array_klmpumur('',15,13,4);
		
		$rent=$this->getrentang(80);			  
		$this->arr_umur_b['80+']=$rent;             
		$this->arr_umur_bb['80+']='80';   
		
		$str='';			   
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str=(!empty($str) ? $str.',':'') ."SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pra_s,
							SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_1,
							SUM(Kd_prosplvl=3 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_2,
							SUM(Kd_prosplvl=4 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3,
							SUM(Kd_prosplvl=5 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3_p,
							SUM(Kd_gen=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_lk,
							SUM(Kd_gen=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pr"; 
			
		}			               
		
		$rent=$this->getrentang(15);

		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("Kd_mutasi IS NULL");  
        $this->db->where($rent);
        $this->db->where("Kd_fammbrtyp = 1");
       	$data = $this->db->get();

		$rc=$this->build_rc($data);
		
		$dt_tb=array();
		$i=1;
		$jml=0;
		$arrjml = array();
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;
			
			$str_pra_s="j_".$isi."_pra_s";
			$str_ks_1="j_".$isi."_ks_1";
			$str_ks_2="j_".$isi."_ks_2";
			$str_ks_3="j_".$isi."_ks_3";
			$str_ks_3_p="j_".$isi."_ks_3_p";
			$str_lk="j_".$isi."_lk";
			$str_pr="j_".$isi."_pr";                 

			$tmp_dt_tb[]=$this->frupiah($rc,$str_lk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pr);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pra_s);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_1);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_2);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3_p);		   
			
			$arrjml['lk'] = $this->fjml_ttl($arrjml,'lk',$rc,$str_lk);
			$arrjml['pr'] = $this->fjml_ttl($arrjml,'pr',$rc,$str_pr);
			$arrjml['pra_s'] = $this->fjml_ttl($arrjml,'pra_s',$rc,$str_pra_s);
			$arrjml['ks_1'] = $this->fjml_ttl($arrjml,'ks_1',$rc,$str_ks_1);
			$arrjml['ks_2'] = $this->fjml_ttl($arrjml,'ks_2',$rc,$str_ks_2);
			$arrjml['ks_3'] = $this->fjml_ttl($arrjml,'ks_3',$rc,$str_ks_3);
			$arrjml['ks_3_p'] = $this->fjml_ttl($arrjml,'ks_3_p',$rc,$str_ks_3_p);

			$jml = $this->fjml_ttl($rc,$str_lk,$rc,$str_pr); 
			$tmp_dt_tb[]=$this->frupiah($jml,'');
			$arrjml['jml'] = $this->fjml_ttl($arrjml,'jml',$jml,'');

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array(); 
		foreach ($arrjml as $value) 
		{
			$footer_tb[]=$this->frupiah($value,'');
		} 
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_13($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');

		$this->array_klmpumur('',15,9,4); 

		$str='';			   
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str=(!empty($str) ? $str.',':'') ."SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pra_s,
							SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_1,
							SUM(Kd_prosplvl=3 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_2,
							SUM(Kd_prosplvl=4 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3,
							SUM(Kd_prosplvl=5 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3_p,
							SUM(Kd_gen=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_lk,
							SUM(Kd_gen=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pr,
							SUM(Kd_emp!=6 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_krj,
							SUM(Kd_emp=6 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_tkrj"; 
			
		}			               
		
		$rent=$this->getrentang(15,59);

		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("Kd_mutasi IS NULL");
        $this->db->where($rent);  
       	$data = $this->db->get();

		$rc=$this->build_rc($data);
		
		$dt_tb=array();
		$i=1;
		$jml=0;
		$arrjml = array();
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;
			
			$str_pra_s="j_".$isi."_pra_s";
			$str_ks_1="j_".$isi."_ks_1";
			$str_ks_2="j_".$isi."_ks_2";
			$str_ks_3="j_".$isi."_ks_3";
			$str_ks_3_p="j_".$isi."_ks_3_p";
			$str_lk="j_".$isi."_lk";
			$str_pr="j_".$isi."_pr";
			$str_krj="j_".$isi."_krj";
			$str_tkrj="j_".$isi."_tkrj";                 

			$tmp_dt_tb[]=$this->frupiah($rc,$str_lk);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pr);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_krj);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_tkrj);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pra_s);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_1);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_2);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3_p);		   
			
			$arrjml['lk'] =$this->fjml_ttl($arrjml,'lk',$rc,$str_lk);
			$arrjml['pr'] = $this->fjml_ttl($arrjml,'pr',$rc,$str_pr);
			$arrjml['krj'] = $this->fjml_ttl($arrjml,'krj',$rc,$str_krj);
			$arrjml['tkrj'] = $this->fjml_ttl($arrjml,'tkrj',$rc,$str_tkrj);
			$arrjml['pra_s'] = $this->fjml_ttl($arrjml,'pra_s',$rc,$str_pra_s);
			$arrjml['ks_1'] = $this->fjml_ttl($arrjml,'ks_1',$rc,$str_ks_1);
			$arrjml['ks_2'] = $this->fjml_ttl($arrjml,'ks_2',$rc,$str_ks_2);
			$arrjml['ks_3'] = $this->fjml_ttl($arrjml,'ks_3',$rc,$str_ks_3);
			$arrjml['ks_3_p'] = $this->fjml_ttl($arrjml,'ks_3_p',$rc,$str_ks_3_p);

			$jml = $this->fjml_ttl($rc,$str_krj,$rc,$str_tkrj); 
			$tmp_dt_tb[]=$this->frupiah($jml,'');
			$arrjml['jml'] = $this->fjml_ttl($arrjml,'jml',$jml,'');

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array(); 
		foreach ($arrjml as $value) 
		{
			$footer_tb[]=$this->frupiah($value,'');
		} 
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function tabel_14($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');

		$this->array_klmpumur('',15,13,4); 

		$rent=$this->getrentang(80);			  
		$this->arr_umur_b['80+']=$rent;             
		$this->arr_umur_bb['80+']='80'; 

		$str='';			   
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str=(!empty($str) ? $str.',':'') .
			"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pra_s,
							SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_1,
							SUM(Kd_prosplvl=3 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_2,
							SUM(Kd_prosplvl=4 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3,
							SUM(Kd_prosplvl=5 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3_p"; 
			
		}	

		
		$dt_edulvl = $this->get_edu_lvl('');

		if(!empty($dt_edulvl)){
			
			foreach ($dt_edulvl as $rc) {                
				foreach($this->arr_umur_b as $idx=>$isi)
				{
					$str=(!empty($str) ? $str.',':'') ."SUM(Kd_edu=$rc[Kd_edu] AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_$rc[Kd_edu]";
					
				}
			}
			
		}

		
		$rent=$this->getrentang(15);

		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl); 
        $this->db->where("Kd_mutasi IS NULL");
        $this->db->where($rent);
        $this->db->where("Kd_fammbrtyp = 1");  
       	$data = $this->db->get();

		$rc=$this->build_rc($data);
		
		$dt_tb=array();
		$i=1;
		$jml1=0;
		$arrjml = array();
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;
			
			$str_pra_s="j_".$isi."_pra_s";
			$str_ks_1="j_".$isi."_ks_1";
			$str_ks_2="j_".$isi."_ks_2";
			$str_ks_3="j_".$isi."_ks_3";
			$str_ks_3_p="j_".$isi."_ks_3_p";
			
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pra_s);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_1);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_2);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3_p);

			$arrjml['ks_pra_s'] = $this->fjml_ttl($arrjml,'ks_pra_s',$rc,$str_pra_s);
			$arrjml['ks_1'] = $this->fjml_ttl($arrjml,'ks_1',$rc,$str_ks_1);
			$arrjml['ks_2'] = $this->fjml_ttl($arrjml,'ks_2',$rc,$str_ks_2);
			$arrjml['ks_3'] = $this->fjml_ttl($arrjml,'ks_3',$rc,$str_ks_3);
			$arrjml['ks_3_p'] = $this->fjml_ttl($arrjml,'ks_3_p',$rc,$str_ks_3_p);		   
			
			$jml=0;
			$jml0=0;
			if(!empty($dt_edulvl)){
				foreach ($dt_edulvl as $row) { 
					$str="j_".$isi."_$row[Kd_edu]";
					$tmp_dt_tb[]=$this->frupiah($rc,$str);
					if($row['Kd_edu']>0)
					{
						$jml=$this->fjml_ttl($rc,$str,$jml,'');
					}else{
						$jml0=isset($rc[$str]) ? $rc[$str] : 0;
					}
					$arrjml['edu_'.$row['Kd_edu']] = $this->fjml_ttl($arrjml,'edu_'.$row['Kd_edu'],$rc,$str);
				}
			}	 
			$tmp_dt_tb[]=number_format($jml,0,',','.');
			$arrjml['jml'] = $this->fjml_ttl($arrjml,'jml',$jml,'');			     

			$jml1 = $jml + $this->fjml_ttl($rc,$str_pra_s,$jml0,'')  + $this->fjml_ttl($rc,$str_ks_1,$rc,$str_ks_2) + $this->fjml_ttl($rc,$str_ks_3,$rc,$str_ks_3_p); 
			$tmp_dt_tb[]=number_format($jml1,0,',','.');
			$arrjml['jml1'] = $this->fjml_ttl($arrjml,'jml1',$jml1,'');

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array(); 
		foreach ($arrjml as $value) 
		{
			$footer_tb[]=number_format($value,0,',','.');
		} 
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_15($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt);

		$this->array_klmpumur('',15,13,4); 

		$rent=$this->getrentang(80,0,'');
		$this->arr_umur_b['80+']=$rent;             
		$this->arr_umur_bb['80+']='80'; 

		$str='';			   
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str=(!empty($str) ? $str.',':'') .
			"SUM((prosplvl=1 OR prosplvl=6) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pra_s,
							SUM((prosplvl=2 OR prosplvl=7) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_1,
							SUM(prosplvl=3 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_2,
							SUM(prosplvl=4 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3,
							SUM(prosplvl=5 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3_p,
							SUM($isi) AS j_".$this->arr_umur_bb[$idx]."_kk,
							SUM(CASE WHEN ($isi) THEN blt_pos END) AS j_".$this->arr_umur_bb[$idx]."_blt_pos,
							SUM(CASE WHEN ($isi) THEN blt_tpos END) AS j_".$this->arr_umur_bb[$idx]."_blt_tpos"; 
			
		}	
		
		
		$rent=$this->getrentang(15);

		$rent1=$this->getrentang(0,4,'dii');

		$this->db->select($str);
        $this->db->from("(SELECT df.Kd_prosplvl AS prosplvl,
							   df.Tgl_lahir AS tgl_lahir,
							   (SELECT COUNT(*) FROM dbo_individu dii WHERE df.Kd_fam=dii.Kd_fam AND ($rent1) AND dii.Posyandu='1') AS blt_pos,
							   (SELECT COUNT(*) FROM dbo_individu dii WHERE df.Kd_fam=dii.Kd_fam AND ($rent1) AND dii.Posyandu='0') AS blt_tpos 
						FROM vw_fam_indv df  
						WHERE $kd_wl df.Kd_mutasi IS NULL AND $rent AND Kd_fammbrtyp=1) AS tbl");        
       	$data = $this->db->get();

		$rc=$this->build_rc($data);
		
		$dt_tb=array();
		$i=1;
		
		$arrjml = array();
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;
			
			$str_pra_s="j_".$isi."_pra_s";
			$str_ks_1="j_".$isi."_ks_1";
			$str_ks_2="j_".$isi."_ks_2";
			$str_ks_3="j_".$isi."_ks_3";
			$str_ks_3_p="j_".$isi."_ks_3_p";
			$str_kk="j_".$isi."_kk";
			$str_blt_pos="j_".$isi."_blt_pos";
			$str_blt_tpos="j_".$isi."_blt_tpos";                               
			
			$tmp_dt_tb[]=$this->frupiah($rc,$str_kk);
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_blt_pos,$rc,$str_blt_tpos),'');
			$tmp_dt_tb[]=$this->frupiah($rc,$str_blt_pos);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_blt_tpos);

			$tmp_dt_tb[]=$this->frupiah($rc,$str_pra_s);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_1);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_2);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3_p);

			$arrjml['jml_kk'] = $this->fjml_ttl($arrjml,'jml_kk',$rc,$str_kk);
			$arrjml['jml'] = $this->fjml_ttl($arrjml,'jml',$this->fjml_ttl($rc,$str_blt_pos,$rc,$str_blt_tpos),'');
			$arrjml['jml_pos'] = $this->fjml_ttl($arrjml,'jml_pos',$rc,$str_blt_pos);
			$arrjml['jml_tpos'] = $this->fjml_ttl($arrjml,'jml_tpos',$rc,$str_blt_tpos);
			$arrjml['ks_pra_s'] = $this->fjml_ttl($arrjml,'ks_pra_s',$rc,$str_pra_s);
			$arrjml['ks_1'] = $this->fjml_ttl($arrjml,'ks_1',$rc,$str_ks_1);
			$arrjml['ks_2'] = $this->fjml_ttl($arrjml,'ks_2',$rc,$str_ks_2);
			$arrjml['ks_3'] = $this->fjml_ttl($arrjml,'ks_3',$rc,$str_ks_3);
			$arrjml['ks_3_p'] = $this->fjml_ttl($arrjml,'ks_3_p',$rc,$str_ks_3_p);                 
			

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array(); 
		foreach ($arrjml as $value) 
		{
			$footer_tb[]=number_format($value,0,',','.');
		} 
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function tabel_16($idkec,$iddesa,$iddusun,$idrt)
    {
    	$kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt);

		$this->array_klmpumur('',15,7,4); 

		$str='';			   
		foreach($this->arr_umur_b as $idx=>$isi)
		{
			$str=(!empty($str) ? $str.',':'') .
			"SUM((prosplvl=1 OR prosplvl=6) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pra_s,
							SUM((prosplvl=2 OR prosplvl=7) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_1,
							SUM(prosplvl=3 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_2,
							SUM(prosplvl=4 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3,
							SUM(prosplvl=5 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3_p,
							SUM($isi) AS j_".$this->arr_umur_bb[$idx]."_pus,
							SUM(consrc=1 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pmt,
							SUM(consrc=2 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_swst,".
			"SUM(nonacptr IS NOT NULL AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_nonkb"; 
			
		}	
		
		
		$rent=$this->getrentang(15,49);
		
		$this->db->_protect_identifiers=false;
		$this->db->select($str);
        $form_str ="(SELECT Kd_prosplvl AS prosplvl,Kd_consrc AS consrc,Kd_nonacptr AS nonacptr,Tgl_lahir AS tgl_lahir FROM vw_fam_indv 
					  WHERE $kd_wl pus = '1' AND ((((Kd_contyp in (1,3,4,5,6,7)) AND Kd_fammbrtyp = 2) OR				                                (
						                                 ( Kd_contyp in (2,8)) AND Kd_fammbrtyp = 1
						                                )
						                               ) OR 
						                                (
						                                   Kd_nonacptr IS NOT NULL AND Kd_fammbrtyp = 2
						                                )
						                             ) 
													AND ($rent) AND Kd_mutasi IS NULL
						  ) AS tbl"; 

        $this->db->from($form_str);
       	$data = $this->db->get();

		$rc=$this->build_rc($data);
		
		$dt_tb=array();
		$i=1;
		
		$arrjml = array();
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;
			
			$str_pra_s="j_".$isi."_pra_s";
			$str_ks_1="j_".$isi."_ks_1";
			$str_ks_2="j_".$isi."_ks_2";
			$str_ks_3="j_".$isi."_ks_3";
			$str_ks_3_p="j_".$isi."_ks_3_p";
			$str_pus="j_".$isi."_pus";
			$str_pmt="j_".$isi."_pmt";
			$str_swst="j_".$isi."_swst";  
			$str_nonkb="j_".$isi."_nonkb";                              
			
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pus);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pmt);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_swst);
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_pmt,$rc,$str_swst),'');
			$tmp_dt_tb[]=$this->frupiah($rc,$str_nonkb);

			$tmp_dt_tb[]=$this->frupiah($rc,$str_pra_s);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_1);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_2);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3_p);

			$arrjml['jml_pus'] = $this->fjml_ttl($arrjml,'jml_pus',$rc,$str_pus);
			$arrjml['jml_pmt'] = $this->fjml_ttl($arrjml,'jml_pmt',$rc,$str_pmt);
			$arrjml['jml_swst'] = $this->fjml_ttl($arrjml,'jml_swst',$rc,$str_swst);
			$arrjml['jml'] = $this->fjml_ttl($arrjml,'jml', $this->fjml_ttl($rc,$str_pmt,$rc,$str_swst),'');
			$arrjml['jml_nonkb'] = $this->fjml_ttl($arrjml,'jml_nonkb',$rc,$str_nonkb);

			$arrjml['ks_pra_s'] = $this->fjml_ttl($arrjml,'ks_pra_s',$rc,$str_pra_s);
			$arrjml['ks_1'] = $this->fjml_ttl($arrjml,'ks_1',$rc,$str_ks_1);
			$arrjml['ks_2'] = $this->fjml_ttl($arrjml,'ks_2',$rc,$str_ks_2);
			$arrjml['ks_3'] = $this->fjml_ttl($arrjml,'ks_3',$rc,$str_ks_3);
			$arrjml['ks_3_p'] = $this->fjml_ttl($arrjml,'ks_3_p',$rc,$str_ks_3_p);                 
			

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array(); 
		foreach ($arrjml as $value) 
		{
			$footer_tb[]=number_format($value,0,',','.');
		} 
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function tabel_17($idkec,$iddesa,$iddusun,$idrt)
    {
    	$kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');

		$this->array_klmpumur('',15,7,4); 

		
		$dt_non = $this->get_non_acptr_reas('');

		$str='';
		if(!empty($dt_non)){ 

			foreach ($dt_non as $row) {
				
				foreach($this->arr_umur_b as $idx=>$isi)
				{
					$str=(!empty($str) ? $str.',':'') .
					"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_pra_s,
							SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_1,
							SUM(Kd_prosplvl=3 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_2,
							SUM(Kd_prosplvl=4 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3,
							SUM(Kd_prosplvl=5 AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_ks_3_p,
							SUM(Kd_nonacptr=$row[Kd_nonacptr] AND ($isi)) AS j_".$this->arr_umur_bb[$idx]."_$row[Kd_nonacptr]"; 
					
				}	

			}
		}
		
		$rent=$this->getrentang(15,49);
		
		$this->db->select($str);
        $this->db->from('vw_fam_indv');
        $this->db->where($kd_wl);
        $this->db->where("Kd_nonacptr IS NOT NULL");
        $this->db->where("pus = '1'");  
        $this->db->where("Kd_fammbrtyp = 2");  
        $this->db->where($rent);
        $this->db->where("Kd_mutasi IS NULL");
        
       	$data = $this->db->get();

		$rc=$this->build_rc($data);
		
		$dt_tb=array();
		$i=1;
		
		$arrjml = array();
		foreach($this->arr_umur_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$idx;
			
			$str_pra_s="j_".$isi."_pra_s";
			$str_ks_1="j_".$isi."_ks_1";
			$str_ks_2="j_".$isi."_ks_2";
			$str_ks_3="j_".$isi."_ks_3";
			$str_ks_3_p="j_".$isi."_ks_3_p";
			
			$jml=0;
			if(!empty($dt_non)){ 

				foreach ($dt_non as $row) {
					$str="j_".$isi."_$row[Kd_nonacptr]";
					$tmp_dt_tb[]=$this->frupiah($rc,$str);
					$jml = $this->fjml_ttl($rc,$str,$jml,'');
					$arrjml['kd_'.$row['Kd_nonacptr']] = 
					$this->fjml_ttl($arrjml,'kd_'.$row['Kd_nonacptr'],$rc,$str);
				}
			}   
			$tmp_dt_tb[]=number_format($jml,0,',','.');
			$arrjml['jml'] = $this->fjml_ttl($arrjml,'jml',$jml,'');
			
			$tmp_dt_tb[]=$this->frupiah($rc,$str_pra_s);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_1);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_2);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_ks_3_p);
			
			$arrjml['ks_pra_s'] = $this->fjml_ttl($arrjml,'ks_pra_s',$rc,$str_pra_s);
			$arrjml['ks_1'] = $this->fjml_ttl($arrjml,'ks_1',$rc,$str_ks_1);
			$arrjml['ks_2'] = $this->fjml_ttl($arrjml,'ks_2',$rc,$str_ks_2);
			$arrjml['ks_3'] = $this->fjml_ttl($arrjml,'ks_3',$rc,$str_ks_3);
			$arrjml['ks_3_p'] = $this->fjml_ttl($arrjml,'ks_3_p',$rc,$str_ks_3_p);                 
			

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array(); 
		foreach ($arrjml as $value) 
		{
			$footer_tb[]=number_format($value,0,',','.');
		} 
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function report_temanggung_tabel_1($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk  ');

				              
		$data = $this->get_unit_detail($kd_wl); 

		
		$sql_select = "SUM(Kd_fammbrtyp=1 AND Kd_gen=1) AS kk_lk,
									SUM(Kd_fammbrtyp=1 AND Kd_gen=2) AS kk_pr,
									SUM(Kd_gen=1) AS jd_lk,
									SUM(Kd_gen=2) AS jd_pr, ".
		"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND Kd_fammbrtyp=1 AND Kd_gen=1) AS pra_lk,
							SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND Kd_fammbrtyp=1 AND Kd_gen=2) AS pra_pr, ".
		"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND Kd_gen=1) AS pra_j_lk,
							SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND Kd_gen=2) AS pra_j_pr, ".
		"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND Kd_fammbrtyp=1 AND Kd_gen=1) AS ks_lk,
							SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND Kd_fammbrtyp=1 AND Kd_gen=2) AS ks_pr, ".
		"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND Kd_gen=1) AS ks_j_lk,
							SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND Kd_gen=2) AS ks_j_pr"; 
		
		
       	
		$dt_tb=array();
		$i=1;
		$arrjml = array();
		$pra_lk=0; 
		$pra_pr=0;
		$tot_kk_lk=0;	
		$tot_kk_pr=0;                 
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');                 
			
			 $this->db->select($sql_select);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wlq);
             $this->db->where("Kd_mutasi IS NULL");
             $data1 = $this->db->get();
		
			$rc = $this->build_rc($data1); 

			$jd=$this->fjml_ttl($rc,'kk_lk',$rc,'kk_pr'); 
			$jd_i=$this->fjml_ttl($rc,'jd_lk',$rc,'jd_pr');               

			$tmp_dt_tb[]=$this->frupiah($rc,'kk_lk');
			$tmp_dt_tb[]=$this->fprosen($rc,'kk_lk',$jd,''); 
			$tmp_dt_tb[]=$this->frupiah($rc,'kk_pr'); 
			$tmp_dt_tb[]=$this->fprosen($rc,'kk_pr',$jd,''); 
			$tmp_dt_tb[]=$this->frupiah($jd,'');

			$arrjml['kk_lk'] = $this->fjml_ttl($arrjml,'kk_lk',$rc,'kk_lk');
			$tot_kk_lk= $arrjml['kk_lk'];	
			$arrjml['kk_pr'] = $this->fjml_ttl($arrjml,'kk_pr',$rc,'kk_pr');
			$tot_kk_pr=$arrjml['kk_pr'];		        
			
			$arrjml['jd_lk'] = $this->fjml_ttl($arrjml,'jd_lk',$rc,'jd_lk');
			$tot_jd_lk=$arrjml['jd_lk'];
			$arrjml['jd_pr'] = $this->fjml_ttl($arrjml,'jd_pr',$rc,'jd_pr');
			$tot_jd_pr=$arrjml['jd_pr'];

			$arrjml['jd'] = $this->fjml_ttl($arrjml,'jd',$jd,'');
			$tot_jd=$arrjml['jd'];
			$arrjml['jd_i'] = $this->fjml_ttl($arrjml,'jd_i',$jd_i,'');
			$tot_jd_i=$arrjml['jd_i'];
			$arrjml['pra_lk'] = $this->fjml_ttl($arrjml,'pra_lk',$rc,'pra_lk');
			$pra_lk=$arrjml['pra_lk'];                 
			$arrjml['pra_pr'] = $this->fjml_ttl($arrjml,'pra_pr',$rc,'pra_pr');
			$pra_pr=$arrjml['pra_pr'];

			$arrjml['pra_j_lk'] = $this->fjml_ttl($arrjml,'pra_j_lk',$rc,'pra_j_lk');
			$pra_j_lk=$arrjml['pra_j_lk']; 
			$arrjml['pra_j_pr'] = $this->fjml_ttl($arrjml,'pra_j_pr',$rc,'pra_j_pr');
			$pra_j_pr=$arrjml['pra_j_pr'];
			
			$arrjml['ks_lk'] = $this->fjml_ttl($arrjml,'ks_lk',$rc,'ks_lk');
			$ks_lk=$arrjml['ks_lk']; 
			$arrjml['ks_pr'] = $this->fjml_ttl($arrjml,'ks_pr',$rc,'ks_pr');
			$ks_pr=$arrjml['ks_pr']; 
			
			$arrjml['ks_j_lk'] = $this->fjml_ttl($arrjml,'ks_j_lk',$rc,'ks_j_lk');
			$ks_j_lk=$arrjml['ks_j_lk']; 
			$arrjml['ks_j_pr'] = $this->fjml_ttl($arrjml,'ks_j_pr',$rc,'ks_j_pr');
			$ks_j_pr=$arrjml['ks_j_pr'];

			$tmp_dt_tb[]=$this->frupiah($rc,'jd_lk');
			$tmp_dt_tb[]=$this->fprosen($rc,'jd_lk',$jd_i,''); 
			$tmp_dt_tb[]=$this->frupiah($rc,'jd_pr'); 
			$tmp_dt_tb[]=$this->fprosen($rc,'jd_pr',$jd_i,''); 
			$tmp_dt_tb[]=$this->frupiah($jd_i,'');
			$tmp_dt_tb[]=$this->fprosen($jd_i,'',$jd,'',1); 
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($arrjml['kk_lk'],0,',','.');
		$tmp_footer[]=$this->fprosen($arrjml,'kk_lk',$arrjml,'jd');
		$tmp_footer[]=number_format($arrjml['kk_pr'],0,',','.');
		$tmp_footer[]=$this->fprosen($arrjml,'kk_pr',$arrjml,'jd');
		$tmp_footer[]=number_format($arrjml['jd'],0,',','.');
		$tmp_footer[]=number_format($arrjml['jd_lk'],0,',','.');
		$tmp_footer[]=$this->fprosen($arrjml,'jd_lk',$arrjml,'jd_i');
		$tmp_footer[]=number_format($arrjml['jd_pr'],0,',','.');
		$tmp_footer[]=$this->fprosen($arrjml,'jd_pr',$arrjml,'jd_i');
		$tmp_footer[]=number_format($arrjml['jd_i'],0,',','.');
		$tmp_footer[]=$this->fprosen($arrjml,'jd_i',$arrjml,'jd',1);

		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($pra_lk,0,',','.').'<br>'.$this->fprosen($pra_lk,'',$tot_kk_lk,'');           
		$tmp_footer[]=$this->fprosen($pra_lk,'',$pra_lk+$pra_pr,'');           
		$tmp_footer[]=number_format($pra_pr,0,',','.').'<br>'.$this->fprosen($pra_pr,'',$tot_kk_pr,'');           
		$tmp_footer[]=$this->fprosen($pra_pr,'',$pra_lk+$pra_pr,'');           
		$tmp_footer[]=number_format($pra_lk+$pra_pr,0,',','.').'<br>'. $this->fprosen($pra_lk+$pra_pr,'',$tot_jd,'');           
		$tmp_footer[]=number_format($pra_j_lk,0,',','.').'<br>'.$this->fprosen($pra_j_lk,'',$tot_jd_lk,'');
		$tmp_footer[]=$this->fprosen($pra_j_lk,'',$pra_j_lk+$pra_j_pr,'');
		$tmp_footer[]=number_format($pra_j_pr,0,',','.').'<br>'.$this->fprosen($pra_j_pr,'',$tot_jd_pr,'');
		$tmp_footer[]=$this->fprosen($pra_j_pr,'',$pra_j_lk+$pra_j_pr,'');
		$tmp_footer[]=number_format($pra_j_lk+$pra_j_pr,0,',','.').'<br>'.$this->fprosen($pra_j_lk+$pra_j_pr,'',$tot_jd_i,'');
		$tmp_footer[]=$this->fprosen($pra_j_lk+$pra_j_pr,'',$pra_lk+$pra_pr,'',1);

		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($ks_lk,0,',','.').'<br>'.$this->fprosen($ks_lk,'',$tot_kk_lk,'');
		$tmp_footer[]=$this->fprosen($ks_lk,'',$ks_lk+$ks_pr,'');
		$tmp_footer[]=number_format($ks_pr,0,',','.').'<br>'.$this->fprosen($ks_pr,'',$tot_kk_pr,'');
		$tmp_footer[]=$this->fprosen($ks_pr,'',$ks_lk+$ks_pr,'');           
		$tmp_footer[]=number_format($ks_lk+$ks_pr,0,',','.').'<br>'.$this->fprosen($ks_lk+$ks_pr,'',$tot_jd,'');
		$tmp_footer[]=number_format($ks_j_lk,0,',','.').'<br>'.$this->fprosen($ks_j_lk,'',$tot_jd_lk,'');
		$tmp_footer[]=$this->fprosen($ks_j_lk,'',$ks_j_lk+$ks_j_pr,'');
		$tmp_footer[]=number_format($ks_j_pr,0,',','.').'<br>'.$this->fprosen($ks_j_pr,'',$tot_jd_pr,'');
		$tmp_footer[]=$this->fprosen($ks_j_pr,'',$ks_j_lk+$ks_j_pr,'');
		$tmp_footer[]=number_format($ks_j_lk+$ks_j_pr,0,',','.').'<br>'.$this->fprosen($ks_j_lk+$ks_j_pr,'',$tot_jd_i,'');
		$tmp_footer[]=$this->fprosen($ks_j_lk+$ks_j_pr,'',$ks_lk+$ks_pr,'',1);

		$footer_tb[] = $tmp_footer;
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function report_temanggung_tabel_2($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');

		$rent = $this->getrentang(0,1);

		$data = $this->get_unit_detail($kd_wl); 

		
		$sql_select = "COUNT(*) AS jd_i,
								SUM(Kd_prosplvl=1 OR Kd_prosplvl=6) AS pra_j_lk,
								SUM(Kd_prosplvl=2 OR Kd_prosplvl=7) AS ks_j_lk, ".
		"SUM(($rent) AND Posyandu='1') AS py,
								SUM(($rent) AND Posyandu='0') AS tpy, ".
		"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND 
									(($rent) AND Posyandu='1')) AS pra_py,
								SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND 
									(($rent) AND Posyandu='0')) AS pra_tpy, ".
		"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND 
									(($rent) AND Posyandu='1')) AS ks_py,
								SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND 
									(($rent) AND Posyandu='0')) AS ks_tpy "; 
		
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();		        
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');                 
			
             $this->db->select($sql_select);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wlq);
             $this->db->where("Kd_mutasi IS NULL");
             $data1 = $this->db->get();


			
			$rc = $this->build_rc($data1);               
			
			$jd_i=isset($rc['jd_i']) ? $rc['jd_i'] : 0; 
			$py=isset($rc['py']) ? $rc['py'] : 0; 
			$tpy=isset($rc['tpy']) ? $rc['tpy'] : 0;                 

			$tmp_dt_tb[]=number_format($jd_i,0,',','.');
			$tmp_dt_tb[]=number_format($py,0,',','.');  
			$tmp_dt_tb[]=$this->fprosen($py,'',$py+$tpy,'');	
			$tmp_dt_tb[]=number_format($tpy,0,',','.');  
			$tmp_dt_tb[]=$this->fprosen($tpy,'',$py+$tpy,'');
			$tmp_dt_tb[]=number_format($py+$tpy,0,',','.');  
			$tmp_dt_tb[]=$this->fprosen($py+$tpy,'',$jd_i,'');

			$arrjml['jd_i'] =$this->fjml_ttl($arrjml,'jd_i',$jd_i,'');
			$arrjml['py'] = $this->fjml_ttl($arrjml,'py',$py,''); 
			$arrjml['tpy'] = $this->fjml_ttl($arrjml,'tpy',$tpy,''); 

			$arrjml['pra_j_lk'] = $this->fjml_ttl($arrjml,'pra_j_lk',$rc,'pra_j_lk');
			$arrjml['pra_py']   = $this->fjml_ttl($arrjml,'pra_py',$rc,'pra_py');
			$arrjml['pra_tpy']  = $this->fjml_ttl($arrjml,'pra_tpy',$rc,'pra_tpy');

			$arrjml['ks_j_lk'] = $this->fjml_ttl($arrjml,'ks_j_lk',$rc,'ks_j_lk');
			$arrjml['ks_py'] = $this->fjml_ttl($arrjml,'ks_py',$rc,'ks_py');
			$arrjml['ks_tpy'] = $this->fjml_ttl($arrjml,'ks_tpy',$rc,'ks_tpy');

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($arrjml['jd_i'],0,',','.');
		$tmp_footer[]=number_format($arrjml['py'],0,',','.');
		$tmp_footer[]=$this->fprosen($arrjml['py'],'',$arrjml['py']+$arrjml['tpy'],'');
		$tmp_footer[]=number_format($arrjml['tpy'],0,',','.');
		$tmp_footer[]=$this->fprosen($arrjml['tpy'],'',$arrjml['py']+$arrjml['tpy'],'');
		$tmp_footer[]=number_format($arrjml['py']+$arrjml['tpy'],0,',','.');
		$tmp_footer[]=$this->fprosen($arrjml['py']+$arrjml['tpy'],'',$arrjml['jd_i'],'',1000);
		

		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($arrjml['pra_j_lk'],0,',','.').'<br>'.$this->fprosen($arrjml,'pra_j_lk',$arrjml,'jd_i');         
		$tmp_footer[]=number_format($arrjml['pra_py'],0,',','.').'<br>'.$this->fprosen($arrjml,'pra_py',$arrjml,'py');           
		$tmp_footer[]=$this->fprosen($arrjml['pra_py'],'',$arrjml['pra_py']+$arrjml['pra_tpy'],'');           
		$tmp_footer[]=number_format($arrjml['pra_tpy'],0,',','.').'<br>'.$this->fprosen($arrjml,'pra_tpy',$arrjml,'tpy');           
		$tmp_footer[]=$this->fprosen($arrjml['pra_tpy'],'',$arrjml['pra_py']+$arrjml['pra_tpy'],'');           
		$tmp_footer[]=number_format($arrjml['pra_py']+$arrjml['pra_tpy'],0,',','.').'<br>'.
		$this->fprosen($arrjml['pra_py']+$arrjml['pra_tpy'],'',$arrjml['py']+$arrjml['tpy'],'');
		$tmp_footer[]=$this->fprosen($arrjml['pra_py']+$arrjml['pra_tpy'],'',$arrjml['pra_j_lk'],'',1000);
		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($arrjml['ks_j_lk'],0,',','.').'<br>'.$this->fprosen($arrjml,'ks_j_lk',$arrjml,'jd_i');
		$tmp_footer[]=number_format($arrjml['ks_py'],0,',','.').'<br>'.$this->fprosen($arrjml,'ks_py',$arrjml,'py');
		$tmp_footer[]=$this->fprosen($arrjml['ks_py'],'',$arrjml['ks_py']+$arrjml['ks_tpy'],'');
		$tmp_footer[]=number_format($arrjml['ks_tpy'],0,',','.').'<br>'.$this->fprosen($arrjml,'ks_tpy',$arrjml,'tpy');           
		$tmp_footer[]=$this->fprosen($arrjml['ks_tpy'],'',($arrjml['ks_py']+$arrjml['ks_tpy']),'');
		$tmp_footer[]=number_format($arrjml['ks_py']+$arrjml['ks_tpy'],0,',','.').'<br>'.
		$this->fprosen($arrjml['ks_py']+$arrjml['ks_tpy'],'',$arrjml['py']+$arrjml['tpy'],'');
		$tmp_footer[]=$this->fprosen($arrjml['ks_py']+$arrjml['ks_tpy'],'',$arrjml['ks_j_lk'],'',1000);          
		$footer_tb[] = $tmp_footer;
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_temanggung_tabel_3($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');

		$rent1549 = $this->getrentang(15,49);
		$rent05 = $this->getrentang(0,4);

		$data = $this->get_unit_detail($kd_wl); 

		
		$sql_select = "SUM($rent1549) AS sbr,
								SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($rent1549)) AS pra_sbr,
								SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($rent1549)) AS ks_sbr, ".
		"SUM(($rent05) AND Posyandu='1') AS py,
								SUM(($rent05) AND Posyandu='0') AS tpy, ".
		"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND (($rent05) AND Posyandu='1')) AS pra_py,
								SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND (($rent05) AND Posyandu='0')) AS pra_tpy, ".
		"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND (($rent05) AND Posyandu='1')) AS ks_py,
								SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND (($rent05) AND Posyandu='0')) AS ks_tpy  "; 
		
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();		        
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');                 
			
			
             $this->db->select($sql_select);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wlq);
             $this->db->where("((($rent1549) AND Kd_gen=2) OR 
										(($rent05) AND Kd_fammbrtyp=3)) 
										AND Kd_mutasi IS NULL");
             $data1 = $this->db->get();
		
			//$py=0; 
			//$tpy=0; 
			//$sbr=0;

			$rc = $this->build_rc($data1);               
			
			$sbr=isset($rc['sbr']) ? $rc['sbr'] : 0; 
			$py=isset($rc['py']) ? $rc['py']: 0; 
			$tpy=isset($rc['tpy']) ? $rc['tpy'] : 0; 
			
			$arrjml['tot_sbr'] =$this->fjml_ttl($arrjml,'tot_sbr',$sbr,'');
			$tot_sbr=$arrjml['tot_sbr'];

			$arrjml['tot_py'] =$this->fjml_ttl($arrjml,'tot_py',$py,'');
			$tot_py=$arrjml['tot_py'];
			
			$arrjml['tot_tpy'] =$this->fjml_ttl($arrjml,'tot_tpy',$tpy,'');
			$tot_tpy=$arrjml['tot_tpy'];                

			$arrjml['pra_sbr'] =$this->fjml_ttl($arrjml,'pra_sbr',$rc,'pra_sbr');
			$pra_sbr=$arrjml['pra_sbr']; 

			$arrjml['ks_sbr'] = $this->fjml_ttl($arrjml,'ks_sbr',$rc,'ks_sbr');
			$ks_sbr=$arrjml['ks_sbr'];

			$arrjml['pra_py'] = $this->fjml_ttl($arrjml,'pra_py',$rc,'pra_py');
			$pra_py=$arrjml['pra_py']; 
			
			$arrjml['ks_py'] = $this->fjml_ttl($arrjml,'ks_py',$rc,'ks_py');
			$ks_py=$arrjml['ks_py']; 
			
			$arrjml['pra_tpy'] = $this->fjml_ttl($arrjml,'pra_tpy',$rc,'pra_tpy');
			$pra_tpy=$arrjml['pra_tpy']; 
			
			$arrjml['ks_tpy'] = $this->fjml_ttl($arrjml,'ks_tpy',$rc,'ks_tpy');
			$ks_tpy=$arrjml['ks_tpy']; 

			$tmp_dt_tb[]=number_format($sbr,0,',','.');
			$tmp_dt_tb[]=number_format($py,0,',','.');  
			$tmp_dt_tb[]=$this->fprosen($py,'',$py+$tpy,'');	
			$tmp_dt_tb[]=number_format($tpy,0,',','.');  
			$tmp_dt_tb[]=$this->fprosen($tpy,'',$py+$tpy,'');
			$tmp_dt_tb[]=number_format($py+$tpy,0,',','.');  
			$tmp_dt_tb[]=$this->fprosen($py+$tpy,'',$sbr,'',1000); 

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($tot_sbr,0,',','.');
		$tmp_footer[]=number_format($tot_py,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_py,'',$tot_py+$tot_tpy,'');
		$tmp_footer[]=number_format($tot_tpy,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_tpy,'',$tot_py+$tot_tpy,'');
		$tmp_footer[]=number_format($tot_py+$tot_tpy,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_py+$tot_tpy,'',$tot_sbr,'',1000);
		

		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($pra_sbr,0,',','.').'<br>'.$this->fprosen($pra_sbr,'',$tot_sbr,'');         
		$tmp_footer[]=number_format($pra_py,0,',','.').'<br>'.$this->fprosen($pra_py,'',$tot_py,'');           
		$tmp_footer[]=$this->fprosen($pra_py,'',$pra_py+$pra_tpy,'');           
		$tmp_footer[]=number_format($pra_tpy,0,',','.').'<br>'.$this->fprosen($pra_tpy,'',$tot_tpy,'');           
		$tmp_footer[]=$this->fprosen($pra_tpy,'',$pra_py+$pra_tpy,'');           
		$tmp_footer[]=number_format($pra_py+$pra_tpy,0,',','.').'<br>'.
		$this->fprosen($pra_py+$pra_tpy,'',$tot_py+$tot_tpy,'');
		$tmp_footer[]=$this->fprosen($pra_py+$pra_tpy,'',$pra_sbr,'',1000);
		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($ks_sbr,0,',','.').'<br>'.$this->fprosen($ks_sbr,'',$tot_sbr,'');
		$tmp_footer[]=number_format($ks_py,0,',','.').'<br>'.$this->fprosen($ks_py,'',$tot_py,'');
		$tmp_footer[]=$this->fprosen($ks_py,'',$ks_py+$ks_tpy,'');
		$tmp_footer[]=number_format($ks_tpy,0,',','.').'<br>'.$this->fprosen($ks_tpy,'',$tot_tpy,'');           
		$tmp_footer[]=$this->fprosen($ks_tpy,'',($ks_py+$ks_tpy),'');
		$tmp_footer[]=number_format($ks_py+$ks_tpy,0,',','.').'<br>'.
		$this->fprosen($ks_py+$ks_tpy,'',$tot_py+$tot_tpy,'');
		$tmp_footer[]=$this->fprosen($ks_py+$ks_tpy,'',$ks_sbr,'',1000);          
		$footer_tb[] = $tmp_footer;
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function report_temanggung_tabel_4($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');

		$rent1 = $this->getrentang(0,1);
		$rent2 = $this->getrentang(1,4);
		$rent3 = $this->getrentang(5,6);
		$rent4 = $this->getrentang(7,15);
		$rent5 = $this->getrentang(16,21);
		$rent6 = $this->getrentang(22,59);
		$rent7 = $this->getrentang(60,0);

		$data = $this->get_unit_detail($kd_wl); 

		$sql_select = "SUM($rent1) AS th_0_1,
								SUM($rent2) AS th_1_5,
								SUM($rent3) AS th_5_6,
								SUM($rent4) AS th_7_15, ".
		"SUM($rent5) AS th_16_21,
								SUM($rent6) AS th_22_59,
								SUM($rent7) AS th_60, ".
		"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($rent1)) AS pra_th_0_1,
								SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($rent2)) AS pra_th_1_5, ".
		"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($rent3)) AS pra_th_5_6,
								SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($rent4)) AS pra_th_7_15, ".
		"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($rent5)) AS pra_th_16_21,
								SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND ($rent6)) AS pra_th_22_59,
								SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND $rent7) AS pra_th_60, ".
		"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($rent1)) AS ks_th_0_1,
								SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($rent2)) AS ks_th_1_5, ".
		"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($rent3)) AS ks_th_5_6,
								SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($rent4)) AS ks_th_7_15, ".
		"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($rent5)) AS ks_th_16_21,
								SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND ($rent6)) AS ks_th_22_59,
								SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND $rent7) AS ks_th_60 "; 
		
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();
		$idx=0;
		$tot_th=0;
		$tot_pra=0;	
		$tot_ks=0;	
		$pra_th_0_1=0;
		$pra_th_1_5=0;
		$pra_th_5_6=0;
		$pra_th_7_15=0;
		$pra_th_16_21=0;
		$pra_th_22_59=0;
		$pra_th_60=0;   
		$ks_th_0_1=0;
		$ks_th_1_5=0;
		$ks_th_5_6=0;
		$ks_th_7_15=0;
		$ks_th_16_21=0;
		$ks_th_22_59=0;
		$ks_th_60=0; 
		$tot_th_0_1=0;
		$tot_th_1_5=0;
		$tot_th_5_6=0;
		$tot_th_7_15=0;
		$tot_th_16_21=0;
		$tot_th_22_59=0;
		$tot_th_60=0;        
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');                 
			
			
             $this->db->select($sql_select);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wlq);
             $this->db->where("Kd_mutasi IS NULL");
             $data1 = $this->db->get();
			 $rc = $this->build_rc($data1);               
			
			$tot_th=$this->fjml_ttl($rc,'th_0_1',$tot_th,'')+
			$this->fjml_ttl($rc,'th_1_5',$rc,'th_5_6')+
			$this->fjml_ttl($rc,'th_7_15',$rc,'th_16_21')+
			$this->fjml_ttl($rc,'th_22_59',$rc,'th_60');
			
			$arr_th[$idx]['th_0_1']=isset($rc['th_0_1']) ? $rc['th_0_1'] : 0; 
			$arr_th[$idx]['th_1_5']=isset($rc['th_1_5']) ? $rc['th_1_5'] : 0; 
			$arr_th[$idx]['th_5_6']=isset($rc['th_5_6']) ? $rc['th_5_6'] : 0; 
			$arr_th[$idx]['th_7_15']=isset($rc['th_7_15']) ? $rc['th_7_15'] : 0; 
			$arr_th[$idx]['th_16_21']=isset($rc['th_16_21']) ? $rc['th_16_21'] : 0; 
			$arr_th[$idx]['th_22_59']=isset($rc['th_22_59']) ? $rc['th_22_59'] : 0; 
			$arr_th[$idx]['th_60']=isset($rc['th_60']) ? $rc['th_60'] : 0;
			
			$arr_th_tot[$idx]=$this->fjml_ttl($rc,'th_0_1',
			$this->fjml_ttl($rc,'th_1_5',$rc,'th_5_6'),'')+
			$this->fjml_ttl($rc,'th_7_15',$rc,'th_16_21')+
			$this->fjml_ttl($rc,'th_22_59',$rc,'th_60');
			
			$pra_th_0_1=$this->fjml_ttl($rc,'pra_th_0_1',$pra_th_0_1,''); 
			$pra_th_1_5=$this->fjml_ttl($rc,'pra_th_1_5',$pra_th_1_5,''); 
			$pra_th_5_6=$this->fjml_ttl($rc,'pra_th_5_6',$pra_th_5_6,'');
			$pra_th_7_15=$this->fjml_ttl($rc,'pra_th_7_15',$pra_th_7_15,''); 
			$pra_th_16_21=$this->fjml_ttl($rc,'pra_th_16_21',$pra_th_16_21,''); 
			$pra_th_22_59=$this->fjml_ttl($rc,'pra_th_22_59',$pra_th_22_59,''); 
			$pra_th_60=$this->fjml_ttl($rc,'pra_th_60',$pra_th_60,'');
			
			$tot_pra=$tot_pra+$pra_th_0_1+
			$pra_th_1_5+
			$pra_th_5_6+
			$pra_th_7_15+
			$pra_th_16_21+
			$pra_th_22_59+
			$pra_th_60;
			
			$ks_th_0_1=$this->fjml_ttl($rc,'ks_th_0_1',$ks_th_0_1,''); 
			$ks_th_1_5=$this->fjml_ttl($rc,'ks_th_1_5',$ks_th_1_5,''); 
			$ks_th_5_6=$this->fjml_ttl($rc,'ks_th_5_6',$ks_th_5_6,'');
			$ks_th_7_15=$this->fjml_ttl($rc,'ks_th_7_15',$ks_th_7_15,''); 
			$ks_th_16_21=$this->fjml_ttl($rc,'ks_th_16_21',$ks_th_16_21,''); 
			$ks_th_22_59=$this->fjml_ttl($rc,'ks_th_22_59',$ks_th_22_59,''); 
			$ks_th_60=$this->fjml_ttl($rc,'ks_th_60',$ks_th_60,'');
			
			$tot_ks=$tot_ks+$ks_th_0_1+$ks_th_1_5+$ks_th_5_6+$ks_th_7_15+$ks_th_16_21+$ks_th_22_59+$ks_th_60; 

			$tot_th_0_1=$tot_th_0_1+$arr_th[$idx]['th_0_1']; 
			$tot_th_1_5=$tot_th_1_5+$arr_th[$idx]['th_1_5']; 
			$tot_th_5_6=$tot_th_5_6+$arr_th[$idx]['th_5_6']; 
			$tot_th_7_15=$tot_th_7_15+$arr_th[$idx]['th_7_15']; 
			$tot_th_16_21=$tot_th_16_21+$arr_th[$idx]['th_16_21']; 
			$tot_th_22_59=$tot_th_22_59+$arr_th[$idx]['th_22_59']; 
			$tot_th_60=$tot_th_60+$arr_th[$idx]['th_60'];
			
			$tmp_dt_tb[]=number_format($arr_th[$idx]['th_0_1'],0,',','.');
			$tmp_dt_tb[]=$this->fprosen($arr_th[$idx]['th_0_1'],'',$arr_th_tot[$idx],''); 
			$tmp_dt_tb[]=number_format($arr_th[$idx]['th_1_5'],0,',','.');
			$tmp_dt_tb[]=$this->fprosen($arr_th[$idx]['th_1_5'],'',$arr_th_tot[$idx],'');
			$tmp_dt_tb[]=number_format($arr_th[$idx]['th_5_6'],0,',','.');
			$tmp_dt_tb[]=$this->fprosen($arr_th[$idx]['th_5_6'],'',$arr_th_tot[$idx],'');
			$tmp_dt_tb[]=number_format($arr_th[$idx]['th_7_15'],0,',','.');
			$tmp_dt_tb[]=$this->fprosen($arr_th[$idx]['th_7_15'],'',$arr_th_tot[$idx],'');
			$tmp_dt_tb[]=number_format($arr_th[$idx]['th_16_21'],0,',','.');
			$tmp_dt_tb[]=$this->fprosen($arr_th[$idx]['th_16_21'],'',$arr_th_tot[$idx],'');	
			$tmp_dt_tb[]=number_format($arr_th[$idx]['th_22_59'],0,',','.');
			$tmp_dt_tb[]=$this->fprosen($arr_th[$idx]['th_22_59'],'',$arr_th_tot[$idx],'');
			$tmp_dt_tb[]=number_format($arr_th[$idx]['th_60'],0,',','.');
			$tmp_dt_tb[]=$this->fprosen($arr_th[$idx]['th_60'],'',$arr_th_tot[$idx],'');
			$tmp_dt_tb[]=number_format($arr_th_tot[$idx],0,',','.');  
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
			$idx++;
		}	
		

		foreach ($dt_tb as $idx => $arr) {
			$dt_tb[$idx][]=$this->fprosen($arr_th_tot[$idx],'',$tot_th,'');              	
		}

		
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($tot_th_0_1,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_th_0_1,'',$tot_th,'');
		$tmp_footer[]=number_format($tot_th_1_5,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_th_1_5,'',$tot_th,'');
		$tmp_footer[]=number_format($tot_th_5_6,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_th_5_6,'',$tot_th,'');
		$tmp_footer[]=number_format($tot_th_7_15,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_th_7_15,'',$tot_th,'');
		$tmp_footer[]=number_format($tot_th_16_21,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_th_16_21,'',$tot_th,'');
		$tmp_footer[]=number_format($tot_th_22_59,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_th_22_59,'',$tot_th,'');
		$tmp_footer[]=number_format($tot_th_60,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_th_60,'',$tot_th,'');
		$tmp_footer[]=number_format($tot_th,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_th,'',$tot_th,'');
		

		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($pra_th_0_1,0,',','.').'<br>'.$this->fprosen($pra_th_0_1,'',$tot_pra,'');         
		$tmp_footer[]=$this->fprosen($pra_th_0_1,'',$tot_th_0_1,'');           
		$tmp_footer[]=number_format($pra_th_1_5,0,',','.').'<br>'.$this->fprosen($pra_th_1_5,'',$tot_pra,'');         
		$tmp_footer[]=$this->fprosen($pra_th_1_5,'',$tot_th_1_5,'');           
		$tmp_footer[]=number_format($pra_th_5_6,0,',','.').'<br>'.$this->fprosen($pra_th_5_6,'',$tot_pra,'');         
		$tmp_footer[]=$this->fprosen($pra_th_5_6,'',$tot_th_5_6,''); 
		$tmp_footer[]=number_format($pra_th_7_15,0,',','.').'<br>'.$this->fprosen($pra_th_7_15,'',$tot_pra,'');         
		$tmp_footer[]=$this->fprosen($pra_th_7_15,'',$tot_th_7_15,'');         
		$tmp_footer[]=number_format($pra_th_16_21,0,',','.').'<br>'.$this->fprosen($pra_th_16_21,'',$tot_pra,'');         
		$tmp_footer[]=$this->fprosen($pra_th_16_21,'',$tot_th_16_21,'');           
		$tmp_footer[]=number_format($pra_th_22_59,0,',','.').'<br>'.$this->fprosen($pra_th_22_59,'',$tot_pra,'');         
		$tmp_footer[]=$this->fprosen($pra_th_22_59,'',$tot_th_22_59,'');         
		$tmp_footer[]=number_format($pra_th_60,0,',','.').'<br>'.$this->fprosen($pra_th_60,'',$tot_pra,'');         
		$tmp_footer[]=$this->fprosen($pra_th_60,'',$tot_th_60,''); 
		$tmp_footer[]=number_format($tot_pra,0,',','.');           
		$tmp_footer[]='';           
		
		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($ks_th_0_1,0,',','.').'<br>'.$this->fprosen($ks_th_0_1,'',$tot_ks,'');         
		$tmp_footer[]=$this->fprosen($ks_th_0_1,'',$tot_th_0_1,'');           
		$tmp_footer[]=number_format($ks_th_1_5,0,',','.').'<br>'.$this->fprosen($ks_th_1_5,'',$tot_ks,'');         
		$tmp_footer[]=$this->fprosen($ks_th_1_5,'',$tot_th_1_5,'');           
		$tmp_footer[]=number_format($ks_th_5_6,0,',','.').'<br>'.$this->fprosen($ks_th_5_6,'',$tot_ks,'');         
		$tmp_footer[]=$this->fprosen($ks_th_5_6,'',$tot_th_5_6,''); 
		$tmp_footer[]=number_format($ks_th_7_15,0,',','.').'<br>'.$this->fprosen($ks_th_7_15,'',$tot_ks,'');         
		$tmp_footer[]=$this->fprosen($ks_th_7_15,'',$tot_th_7_15,'');         
		$tmp_footer[]=number_format($ks_th_16_21,0,',','.').'<br>'.$this->fprosen($ks_th_16_21,'',$tot_ks,'');         
		$tmp_footer[]=$this->fprosen($ks_th_16_21,'',$tot_th_16_21,'');           
		$tmp_footer[]=number_format($ks_th_22_59,0,',','.').'<br>'.$this->fprosen($ks_th_22_59,'',$tot_ks,'');         
		$tmp_footer[]=$this->fprosen($ks_th_22_59,'',$tot_th_22_59,'');         
		$tmp_footer[]=number_format($ks_th_60,0,',','.').'<br>'.$this->fprosen($ks_th_60,'',$tot_ks,'');         
		$tmp_footer[]=$this->fprosen($ks_th_60,'',$tot_th_60,''); 
		$tmp_footer[]=number_format($tot_ks,0,',','.');           
		$tmp_footer[]='';           
		
		$footer_tb[] = $tmp_footer;
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_temanggung_tabel_5($idkec,$iddesa,$iddusun,$idrt)
    {
    	$kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');   	          	         

		$data = $this->get_unit_detail($kd_wl);
		
		$sql_select = "SUM(Kd_gen=1 AND Kd_edu!=0) AS lk_skl,
								SUM(Kd_gen=2 AND Kd_edu!=0) AS pr_skl,
								SUM(Kd_gen=1 AND Kd_edu=0) AS lk_t_skl,
								SUM(Kd_gen=2 AND Kd_edu=0) AS pr_t_skl, ".
		"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND Kd_edu!=0 AND Kd_gen=1) AS pra_lk_skl,
								SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND Kd_edu!=0 AND Kd_gen=2) AS pra_pr_skl, ".
		"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND Kd_edu=0 AND Kd_gen=1) AS pra_lk_t_skl,
								SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND Kd_edu=0 AND Kd_gen=2) AS pra_pr_t_skl, ".
		"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND Kd_edu!=0 AND Kd_gen=1) AS ks_lk_skl,
								SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND Kd_edu!=0 AND Kd_gen=2) AS ks_pr_skl, ".
		"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND Kd_edu=0 AND Kd_gen=1) AS ks_lk_t_skl,
								SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND Kd_edu=0 AND Kd_gen=2) AS ks_pr_t_skl "; 
		
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();			  	
		$rent = $this->getrentang(7,15);  
        $pra_lk_skl=0;
        $pra_lk_t_skl=0;
        $pra_pr_skl=0;
        $pra_pr_t_skl=0;
        $ks_lk_skl=0;
        $ks_lk_t_skl=0;
        $ks_pr_skl=0;
        $ks_pr_t_skl=0;
        $jml_lk_skl=0;
        $jml_lk_t_skl=0;
        $jml_pr_skl=0;
        $jml_pr_t_skl=0;
        $tot_lk=0;
        $tot_pr=0;
        $tot_jd_i=0;
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');                 
						
			 $this->db->select($sql_select);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wlq);
			 $this->db->where("($rent)");
             $this->db->where("Kd_mutasi IS NULL");
             $data1 = $this->db->get();

			 $rc = $this->build_rc($data1);               
			
			$lk_skl=isset($rc['lk_skl']) ? $rc['lk_skl'] : 0; 
			$pr_skl=isset($rc['pr_skl']) ? $rc['pr_skl'] : 0; 
			$lk_t_skl=isset($rc['lk_t_skl']) ? $rc['lk_t_skl'] : 0; 
			$pr_t_skl=isset($rc['pr_t_skl']) ? $rc['pr_t_skl'] : 0;
			$pra_lk_skl=$this->fjml_ttl($rc,'pra_lk_skl',$pra_lk_skl,''); 
			$pra_lk_t_skl=$this->fjml_ttl($rc,'pra_lk_t_skl',$pra_lk_t_skl,'');
			$pra_pr_skl=$this->fjml_ttl($rc,'pra_pr_skl',$pra_pr_skl,''); 
			$pra_pr_t_skl=$this->fjml_ttl($rc,'pra_pr_t_skl',$pra_pr_t_skl,'');
			$ks_lk_skl=$this->fjml_ttl($rc,'ks_lk_skl',$ks_lk_skl,''); 
			$ks_lk_t_skl=$this->fjml_ttl($rc,'ks_lk_t_skl',$ks_lk_t_skl,'');
			$ks_pr_skl=$this->fjml_ttl($rc,'ks_pr_skl',$ks_pr_skl,''); 
			$ks_pr_t_skl=$this->fjml_ttl($rc,'ks_pr_t_skl',$ks_pr_t_skl,'');
			
			
			$jml_lk=$lk_skl+$lk_t_skl;
			$jml_pr=$pr_skl+$pr_t_skl;
			$jml_skl=$lk_skl+$pr_skl;
			$jml_t_skl=$lk_t_skl+$pr_t_skl;
			$jml_lk_skl=$jml_lk_skl+$lk_skl;
			$jml_pr_skl=$jml_pr_skl+$pr_skl;
			$jml_lk_t_skl=$jml_lk_t_skl+$lk_t_skl;
			$jml_pr_t_skl=$jml_pr_t_skl+$pr_t_skl;
			$tot_lk=$tot_lk+$jml_lk;
			$tot_pr=$tot_pr+$jml_pr;
			$jd_i=$jml_lk+$jml_pr;
			$tot_jd_i=$tot_jd_i+$jd_i;
			$tot_pra_lk=$pra_lk_skl+$pra_lk_t_skl;
			$tot_pra_pr=$pra_pr_skl+$pra_pr_t_skl;
			$tot_ks_lk=$ks_lk_skl+$ks_lk_t_skl;
			$tot_ks_pr=$ks_pr_skl+$ks_pr_t_skl;
			
			$tmp_dt_tb[]=number_format($lk_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($lk_skl,'',$jml_skl,''); 
			$tmp_dt_tb[]=number_format($pr_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($pr_skl,'',$jml_skl,'');
			$tmp_dt_tb[]=number_format($jml_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($jml_skl,'',$jd_i,'');
			$tmp_dt_tb[]=number_format($lk_t_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($lk_t_skl,'',$jml_t_skl,''); 
			$tmp_dt_tb[]=number_format($pr_t_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($pr_t_skl,'',$jml_t_skl,'');
			$tmp_dt_tb[]=number_format($jml_t_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($jml_t_skl,'',$jd_i,'');
			$tmp_dt_tb[]=number_format($jml_lk,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($jml_lk,'',$jd_i,''); 
			$tmp_dt_tb[]=number_format($jml_pr,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($jml_pr,'',$jd_i,'');
			$tmp_dt_tb[]=number_format($jd_i,0,',','.');
			
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
			//$idx++;
		}	                 
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($jml_lk_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_lk_skl,'',$jml_lk_skl+$jml_pr_skl,'');
		$tmp_footer[]=number_format($jml_pr_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_pr_skl,'',$jml_lk_skl+$jml_pr_skl,'');
		$tmp_footer[]=number_format($jml_lk_skl+$jml_pr_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_lk_skl+$jml_pr_skl,'',$tot_jd_i,'');
		$tmp_footer[]=number_format($jml_lk_t_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_lk_t_skl,'',$jml_lk_t_skl+$jml_pr_t_skl,'');
		$tmp_footer[]=number_format($jml_pr_t_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_pr_t_skl,'',$jml_lk_t_skl+$jml_pr_t_skl,'');
		$tmp_footer[]=number_format($jml_lk_t_skl+$jml_pr_t_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_lk_t_skl+$jml_pr_t_skl,'',$tot_jd_i,'');
		$tmp_footer[]=number_format($tot_lk,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_lk,'',$tot_jd_i,'');
		$tmp_footer[]=number_format($tot_pr,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_pr,'',$tot_jd_i,'');
		$tmp_footer[]=number_format($tot_jd_i,0,',','.');


		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($pra_lk_skl,0,',','.').'<br>'.$this->fprosen($pra_lk_skl,'',$jml_lk_skl,'');         
		$tmp_footer[]=$this->fprosen($pra_lk_skl,'',$pra_lk_skl+$pra_pr_skl,'');           
		$tmp_footer[]=number_format($pra_pr_skl,0,',','.').'<br>'.$this->fprosen($pra_pr_skl,'',$jml_pr_skl,'');         
		$tmp_footer[]=$this->fprosen($pra_pr_skl,'',$pra_lk_skl+$pra_pr_skl,'');  
		$tmp_footer[]=number_format($pra_lk_skl+$pra_pr_skl,0,',','.').'<br>'.$this->fprosen($pra_lk_skl+$pra_pr_skl,'',$jml_lk_skl+$jml_pr_skl,'');         
		$tmp_footer[]=$this->fprosen($pra_lk_skl+$pra_pr_skl,'',$tot_pra_lk+$tot_pra_pr,''); 
		$tmp_footer[]=number_format($pra_lk_t_skl,0,',','.').'<br>'.$this->fprosen($pra_lk_t_skl,'',$jml_lk_t_skl,'');         
		$tmp_footer[]=$this->fprosen($pra_lk_t_skl,'',$pra_lk_t_skl+$pra_pr_t_skl,'');           
		$tmp_footer[]=number_format($pra_pr_t_skl,0,',','.').'<br>'.$this->fprosen($pra_pr_t_skl,'',$jml_pr_t_skl,'');         
		$tmp_footer[]=$this->fprosen($pra_pr_t_skl,'',$pra_lk_t_skl+$pra_pr_t_skl,'');  
		$tmp_footer[]=number_format($pra_lk_t_skl+$pra_pr_t_skl,0,',','.').'<br>'.$this->fprosen($pra_lk_t_skl+$pra_pr_t_skl,'',$jml_lk_t_skl+$jml_pr_t_skl,'');         
		$tmp_footer[]=$this->fprosen($pra_lk_t_skl+$pra_pr_t_skl,'',$tot_pra_lk+$tot_pra_pr,''); 
		$tmp_footer[]=number_format($tot_pra_lk,0,',','.').'<br>'.$this->fprosen($tot_pra_lk,'',$tot_lk,'');         
		$tmp_footer[]=$this->fprosen($tot_pra_lk,'',$tot_pra_lk+$tot_pra_pr,'');           
		$tmp_footer[]=number_format($tot_pra_pr,0,',','.').'<br>'.$this->fprosen($tot_pra_pr,'',$tot_pr,'');         
		$tmp_footer[]=$this->fprosen($tot_pra_pr,'',$tot_pra_lk+$tot_pra_pr,'');   
		$tmp_footer[]=number_format($tot_pra_lk+$tot_pra_pr,0,',','.').'<br>'.$this->fprosen($tot_pra_lk+$tot_pra_pr,'',$tot_jd_i,'');             
		
		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($ks_lk_skl,0,',','.').'<br>'.$this->fprosen($ks_lk_skl,'',$jml_lk_skl,'');         
		$tmp_footer[]=$this->fprosen($ks_lk_skl,'',$ks_lk_skl+$ks_pr_skl,'');           
		$tmp_footer[]=number_format($ks_pr_skl,0,',','.').'<br>'.$this->fprosen($ks_pr_skl,'',$jml_pr_skl,'');         
		$tmp_footer[]=$this->fprosen($ks_pr_skl,'',$ks_lk_skl+$ks_pr_skl,'');  
		$tmp_footer[]=number_format($ks_lk_skl+$ks_pr_skl,0,',','.').'<br>'.$this->fprosen($ks_lk_skl+$ks_pr_skl,'',$jml_lk_skl+$jml_pr_skl,'');         
		$tmp_footer[]=$this->fprosen($ks_lk_skl+$ks_pr_skl,'',$tot_ks_lk+$tot_ks_pr,''); 
		$tmp_footer[]=number_format($ks_lk_t_skl,0,',','.').'<br>'.$this->fprosen($ks_lk_t_skl,'',$jml_lk_t_skl,'');         
		$tmp_footer[]=$this->fprosen($ks_lk_t_skl,'',$ks_lk_t_skl+$ks_pr_t_skl,'');           
		$tmp_footer[]=number_format($ks_pr_t_skl,0,',','.').'<br>'.$this->fprosen($ks_pr_t_skl,'',$jml_pr_t_skl,'');         
		$tmp_footer[]=$this->fprosen($ks_pr_t_skl,'',$ks_lk_t_skl+$ks_pr_t_skl,'');  
		$tmp_footer[]=number_format($ks_lk_t_skl+$ks_pr_t_skl,0,',','.').'<br>'.$this->fprosen($ks_lk_t_skl+$ks_pr_t_skl,'',$jml_lk_t_skl+$jml_pr_t_skl,'');         
		$tmp_footer[]=$this->fprosen($ks_lk_t_skl+$ks_pr_t_skl,'',$tot_ks_lk+$tot_ks_pr,''); 
		$tmp_footer[]=number_format($tot_ks_lk,0,',','.').'<br>'.$this->fprosen($tot_ks_lk,'',$tot_lk,'');         
		$tmp_footer[]=$this->fprosen($tot_ks_lk,'',$tot_ks_lk+$tot_ks_pr,'');           
		$tmp_footer[]=number_format($tot_ks_pr,0,',','.').'<br>'.$this->fprosen($tot_ks_pr,'',$tot_pr,'');         
		$tmp_footer[]=$this->fprosen($tot_ks_pr,'',$tot_ks_lk+$tot_ks_pr,'');   
		$tmp_footer[]=number_format($tot_ks_lk+$tot_ks_pr,0,',','.').'<br>'.$this->fprosen($tot_ks_lk+$tot_ks_pr,'',$tot_jd_i,'');                 
		
		$footer_tb[] = $tmp_footer;
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_temanggung_tabel_6($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');   	          	         
				
		$dtctyp = $this->get_contr_typ('');            

		$nmcontyp=array();
		$str_a = '';
		$str_pra_a = '';
		$str_ks_a ='';
		if(!empty($dtctyp)){
			foreach($dtctyp as $row)
			{ 	
				$str_a= (!empty($str_a) ? $str_a.',' :$str_a)."SUM(Kd_contyp=$row[Kd_contyp]) AS j_$row[Kd_contyp]";
				$str_pra_a=(!empty($str_pra_a) ? $str_pra_a.',' :$str_pra_a).
				"SUM((Kd_prosplvl=1 OR Kd_prosplvl=6) AND Kd_contyp=$row[Kd_contyp]) AS pra_j_$row[Kd_contyp]";
				$str_ks_a=(!empty($str_ks_a) ? $str_ks_a.',' :$str_ks_a).
				"SUM((Kd_prosplvl=2 OR Kd_prosplvl=7) AND Kd_contyp=$row[Kd_contyp]) AS ks_j_$row[Kd_contyp]";
			}	
			
		}        

		$data = $this->get_unit_detail($kd_wl);
		
		$sql_select = "COUNT(*) AS j_pus,
								SUM(Kd_contyp IS NOT NULL) AS j_typ,
								$str_a,
								SUM(Kd_prosplvl=1 OR Kd_prosplvl=6) AS pra_j_pus,
								SUM(Kd_prosplvl=2 OR Kd_prosplvl=7) AS ks_j_pus,
								$str_pra_a,
								$str_ks_a "; 
		
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();			  	
		$total_j_typ=0;
		$pra_pus=0;
		$ks_pus=0;
		$tot_j_pus=0;
		$total_pra_j_typ=0;
		$total_ks_j_typ=0;
		$tot_j_typ=array();
		$tot_pra_j_typ=array();
		$tot_ks_j_typ=array();
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'fam','');                 
			
			 $this->db->select($sql_select);
             $this->db->from('dbo_family fam');
			 $this->db->where($kd_wlq);
			 $this->db->where("pus='1'");
             $data1 = $this->db->get();

			$rc = $this->build_rc($data1);               
			
			$j_pus=isset($rc['j_pus']) ? $rc['j_pus'] : 0; 
			$total_j_typ=$this->fjml_ttl($rc,'j_typ',$total_j_typ,'');
			$pra_pus=$this->fjml_ttl($rc,'pra_j_pus',$pra_pus,'');
			$ks_pus=$this->fjml_ttl($rc,'ks_j_pus',$ks_pus,'');
			
			$tot_j_pus=$tot_j_pus+$j_pus;
			
			$tmp_dt_tb[]=number_format($j_pus,0,',','.');
			
			$idx=0;
			if(!empty($dtctyp)){
				foreach($dtctyp as $row)
				{
					$str="j_".$row['Kd_contyp']; 
					$str_pra="pra_j_".$row['Kd_contyp']; 
					$str_ks="ks_j_".$row['Kd_contyp']; 

					$tot_j_typ[$idx]=$this->fjml_ttl($tot_j_typ,$idx,$rc,$str); 
					$tot_pra_j_typ[$idx]=$this->fjml_ttl($tot_pra_j_typ,$idx,$rc,$str_pra); 
					$tot_ks_j_typ[$idx]=$this->fjml_ttl($tot_ks_j_typ,$idx,$rc,$str_ks);

					$total_pra_j_typ=$this->fjml_ttl($rc,$str_pra,$total_pra_j_typ,''); 
					$total_ks_j_typ=$this->fjml_ttl($rc,$str_ks,$total_ks_j_typ,'');

					$tmp_dt_tb[]=$this->frupiah($rc,$str);
					$tmp_dt_tb[]=$this->fprosen($rc,$str,$rc,'j_typ');
					$idx++;
				}
			}   
			
			$tmp_dt_tb[]=$this->frupiah($rc,'j_typ');
			$tmp_dt_tb[]=$this->fprosen($rc,'j_typ',$j_pus,'');
			
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
			$idx++;
		}	                 
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($tot_j_pus,0,',','.');
		
		foreach($tot_j_typ as $idx=>$isi)
		{
			$tmp_footer[]=number_format($isi,0,',','.');
			$tmp_footer[]=$this->fprosen($isi,'',$total_j_typ,'');              
		}              
		
		$tmp_footer[]=number_format($total_j_typ,0,',','.');
		$tmp_footer[]=$this->fprosen($total_j_typ,'',$tot_j_pus,'');

		$footer_tb[] = $tmp_footer;

		$tmp_footer=array(); 
		$tmp_footer[]=number_format($pra_pus,0,',','.').'<br>'.$this->fprosen($pra_pus,'',$tot_j_pus,'');         
		
		foreach($tot_pra_j_typ as $idx=>$isi)
		{
			$tmp_footer[]=number_format($isi,0,',','.').'<br>'.$this->fprosen($isi,'',$tot_j_typ[$idx],'');
			$tmp_footer[]=$this->fprosen($isi,'',$total_pra_j_typ,'');
		}                       
		
		$tmp_footer[]=number_format($total_pra_j_typ,0,',','.').'<br>'.$this->fprosen($total_pra_j_typ,'',$total_j_typ,'');         
		$tmp_footer[]=$this->fprosen($total_pra_j_typ,'',$pra_pus,'');   
		
		$footer_tb[] = $tmp_footer;

		$tmp_footer=array();           
		$tmp_footer[]=number_format($ks_pus,0,',','.').'<br>'.$this->fprosen($ks_pus,'',$tot_j_pus,'');         
		
		foreach($tot_ks_j_typ as $idx=>$isi)
		{
			$tmp_footer[]=number_format($isi,0,',','.').'<br>'.$this->fprosen($isi,'',$tot_j_typ[$idx],'');
			$tmp_footer[]=$this->fprosen($isi,'',$total_ks_j_typ,'');
		}                       
		
		$tmp_footer[]=number_format($total_ks_j_typ,0,',','.').'<br>'.$this->fprosen($total_ks_j_typ,'',$total_j_typ,'');         
		$tmp_footer[]=$this->fprosen($total_ks_j_typ,'',$ks_pus,'');
		
		$footer_tb[] = $tmp_footer;
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_temanggung_tabel_6_1($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');   	          	         
			
		$dt_kondks = $this->get_indikator_ks('id_ind_ks_idk=1'); 
		
		$str_qr_idk='';
		$str_qr='';
		if(!empty($dt_kondks)){
			foreach($dt_kondks as $row)
			{
				$ind[$row['id_ind_ks']]=0;
				$tot_j_ind[$row['id_ind_ks']]=0;
				$str_qr_idk=(!empty($str_qr_idk) ? $str_qr_idk.',' :$str_qr_idk).
				"SUM(fd.id_ind_ks=$row[id_ind_ks]) AS j_tks_$row[id_ind_ks]";
				$str_qr=(!empty($str_qr) ? $str_qr.' or ' :$str_qr)."fd.id_ind_ks=$row[id_ind_ks]";
				
			}
			
		}           

		$data = $this->get_unit_detail($kd_wl);
        $sql_select = "COUNT(DISTINCT fam.Kd_fam) AS jml_kk_pra,$str_qr_idk"; 
				
		$dt_tb=array();
		$i=1;
		$arrjml = array();			  	
		$tot_kk_pra=0;
		$tot_j_ind=array();
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'fam','');                 
			
			 $this->db->select($sql_select);
             $this->db->from('dbo_family fam inner join dbo_fam_ind_detail fd on fam.Kd_fam=fd.Kd_fam');
			 $this->db->where($kd_wlq);
			 $this->db->where('(fam.Kd_prosplvl=1 OR fam.Kd_prosplvl=6)');
			 $this->db->where("($str_qr)");
			 $this->db->where('(fd.kd_prospinstat=2 OR fd.kd_prospinstat=4)');
             $data1 = $this->db->get();


			$rc = $this->build_rc($data1);               
			
			$jml_kk_pra=isset($rc['jml_kk_pra']) ? $rc['jml_kk_pra']: 0;            
			$tot_kk_pra=$this->fjml_ttl($rc,'jml_kk_pra',$tot_kk_pra,'');
			
			$tmp_dt_tb[]=number_format($jml_kk_pra,0,',','.');
			
			foreach($ind as $idx=>$isi)
			{
				$str="j_tks_".$idx; 
				$tot_j_ind[$idx]=$this->fjml_ttl($tot_j_ind,$idx,$rc,$str);
				
				$tmp_dt_tb[]=$this->frupiah($rc,$str);
				$tmp_dt_tb[]=$this->fprosen($rc,$str,$jml_kk_pra,'');
			}
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
			$idx++;
		}	                 
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($tot_kk_pra,0,',','.');
		foreach($tot_j_ind as $idx=>$isi)
		{
			$tmp_footer[]=number_format($isi,0,',','.');
			$tmp_footer[]=$this->fprosen($isi,'',$tot_kk_pra,'');

		}      

		$footer_tb[] = $tmp_footer;

		
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_temanggung_ks_tabel_1($idkec,$iddesa,$iddusun,$idrt)
    {
    	$kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');
		$this->arrayks(); 

		$str='';
		foreach($this->arr_ks as $idx => $isi)	
		{
			$str=(!empty($str) ? $str.',' :$str).
			"SUM(($isi) AND Kd_fammbrtyp=1 AND Kd_gen=1) AS ".$this->arr_ks_bb[$idx]."_kk_lk,
							SUM(($isi) AND Kd_fammbrtyp=1 AND Kd_gen=2) AS ".$this->arr_ks_bb[$idx]."_kk_pr".
			",SUM(($isi) AND Kd_gen=1) AS ".$this->arr_ks_bb[$idx]."_lk,
							SUM(($isi) AND Kd_gen=2) AS ".$this->arr_ks_bb[$idx]."_pr";
			
		}

             $this->db->select($str);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wl);
			 $this->db->where('Kd_mutasi IS NULL');			 
             $data = $this->db->get();

		$rc = $this->build_rc($data);             
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();	
		$jmlh_kk_lk=0;
		$jmlh_kk_pr=0;
		$jmlh_lk=0;
		$jmlh_pr=0;		                
		foreach($this->arr_ks_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$this->arr_ks_b[$idx];                 

			$str_rc_kk_lk=$isi."_kk_lk"; 
			$str_rc_kk_pr=$isi."_kk_pr"; 
			$str_rc_lk=$isi."_lk"; 
			$str_rc_pr=$isi."_pr";
			$jmlh_kk_lk=$this->fjml_ttl($rc,$str_rc_kk_lk,$jmlh_kk_lk,''); 
			$jmlh_kk_pr=$this->fjml_ttl($rc,$str_rc_kk_pr,$jmlh_kk_pr,''); 
			$jmlh_lk=$this->fjml_ttl($rc,$str_rc_lk,$jmlh_lk,''); 
			$jmlh_pr=$this->fjml_ttl($rc,$str_rc_pr,$jmlh_pr,'');
			

			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_kk_lk);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_kk_lk,$this->fjml_ttl($rc,$str_rc_kk_lk,$rc,$str_rc_kk_pr),''); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_kk_pr); 
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_kk_pr,$this->fjml_ttl($rc,$str_rc_kk_lk,$rc,$str_rc_kk_pr),''); 
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc_kk_lk,$rc,$str_rc_kk_pr),'');
			

			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_lk);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_lk,$this->fjml_ttl($rc,$str_rc_lk,$rc,$str_rc_pr),''); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_pr); 
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_pr,$this->fjml_ttl($rc,$str_rc_lk,$rc,$str_rc_pr),''); 
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc_lk,$rc,$str_rc_pr),'');
			$tmp_dt_tb[]=$this->fprosen($this->fjml_ttl($rc,$str_rc_lk,$rc,$str_rc_pr),'',
			$this->fjml_ttl($rc,$str_rc_kk_lk,$rc,$str_rc_kk_pr),'',1); 
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($jmlh_kk_lk,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_kk_lk,'',$jmlh_kk_lk+$jmlh_kk_pr,'');
		$tmp_footer[]=number_format($jmlh_kk_pr,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_kk_pr,'',$jmlh_kk_lk+$jmlh_kk_pr,'');
		$tmp_footer[]=number_format($jmlh_kk_lk+$jmlh_kk_pr,0,',','.');
		$tmp_footer[]=number_format($jmlh_lk,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_lk,'',$jmlh_lk+$jmlh_pr,'');
		$tmp_footer[]=number_format($jmlh_pr,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_pr,'',$jmlh_lk+$jmlh_pr,'');
		$tmp_footer[]=number_format($jmlh_lk+$jmlh_pr,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_lk+$jmlh_pr,'',$jmlh_kk_lk+$jmlh_kk_pr,'',1);

		$footer_tb[] = $tmp_footer;

		
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function report_temanggung_ks_tabel_2($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');
		$rent = $this->getrentang(0,1);              
		$this->arrayks();

		$str='';
		foreach($this->arr_ks as $idx => $isi)	
		{
			$str=(!empty($str) ? $str.',' :$str).
			"SUM($isi) AS ".$this->arr_ks_bb[$idx]."_jiwa".
			",SUM(($isi) AND ($rent) AND Posyandu='1') AS ".$this->arr_ks_bb[$idx]."_py,
							SUM(($isi) AND ($rent) AND Posyandu='0') AS ".$this->arr_ks_bb[$idx]."_tpy";
			
		}
		
             $this->db->select($str);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wl);
			 $this->db->where('Kd_mutasi IS NULL');			 
             $data = $this->db->get();

		$rc = $this->build_rc($data); 

		
		$dt_tb=array();
		$i=1;
		$arrjml = array();	
		$jmlh_jiwa=0;
		$jmlh_py=0;
		$jmlh_tpy=0;	        
		foreach($this->arr_ks_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$this->arr_ks_b[$idx];                  
			
			$str_rc_jiwa=$isi."_jiwa"; 
			$str_rc_py=$isi."_py"; 
			$str_rc_tpy=$isi."_tpy";
			$jmlh_jiwa=$this->fjml_ttl($rc,$str_rc_jiwa,$jmlh_jiwa,''); 
			$jmlh_py=$this->fjml_ttl($rc,$str_rc_py,$jmlh_py,''); 
			$jmlh_tpy=$this->fjml_ttl($rc,$str_rc_tpy,$jmlh_tpy,'');             

			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_jiwa);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_py);  
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_py,$this->fjml_ttl($rc,$str_rc_py,$rc,$str_rc_tpy),'');	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_tpy);  
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_tpy,$this->fjml_ttl($rc,$str_rc_py,$rc,$str_rc_tpy),'');
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc_py,$rc,$str_rc_tpy),'');  
			$tmp_dt_tb[]=$this->fprosen($this->fjml_ttl($rc,$str_rc_py,$rc,$str_rc_tpy),'',$this->fjml_ttl($rc,$str_rc_jiwa,0,''),'');

			

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($jmlh_jiwa,0,',','.');
		$tmp_footer[]=number_format($jmlh_py,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_py,'',$jmlh_py+$jmlh_tpy,'');
		$tmp_footer[]=number_format($jmlh_tpy,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_tpy,'',$jmlh_py+$jmlh_tpy,'');
		$tmp_footer[]=number_format($jmlh_py+$jmlh_tpy,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_py+$jmlh_tpy,'',$jmlh_jiwa,'',1000);
		

		$footer_tb[] = $tmp_footer;

		
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_temanggung_ks_tabel_3($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');
		$rent1549 = $this->getrentang(15,49);
		$rent05 = $this->getrentang(0,4);
		$this->arrayks();

		$str='';
		foreach($this->arr_ks as $idx => $isi)	
		{
			$str=(!empty($str) ? $str.',' :$str).
			"SUM(($isi) AND ($rent1549)) AS ".$this->arr_ks_bb[$idx]."_jiwa".
			",SUM(($isi) AND ($rent05) AND Posyandu='1') AS ".$this->arr_ks_bb[$idx]."_py,
							SUM(($isi) AND ($rent05) AND Posyandu='0') AS ".$this->arr_ks_bb[$idx]."_tpy";
			
		}

		     $this->db->select($str);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wl);
			 $this->db->where("((($rent1549) AND Kd_gen=2) OR ($rent05))");	
			 $this->db->where('Kd_mutasi IS NULL');			 
             $data = $this->db->get();
		
		$rc = $this->build_rc($data);             
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();
		$jmlh_jiwa=0;
		$jmlh_py=0;
		$jmlh_tpy=0;		        
		foreach($this->arr_ks_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$this->arr_ks_b[$idx];

			$str_rc_jiwa=$isi."_jiwa"; 
			$str_rc_py=$isi."_py"; 
			$str_rc_tpy=$isi."_tpy";
			$jmlh_jiwa=$this->fjml_ttl($rc,$str_rc_jiwa,$jmlh_jiwa,''); 
			$jmlh_py=$this->fjml_ttl($rc,$str_rc_py,$jmlh_py,''); 
			$jmlh_tpy=$this->fjml_ttl($rc,$str_rc_tpy,$jmlh_tpy,'');

			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_jiwa);
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_py);  
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_py,$this->fjml_ttl($rc,$str_rc_py,$rc,$str_rc_tpy),'');	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_tpy);  
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_tpy,$this->fjml_ttl($rc,$str_rc_py,$rc,$str_rc_tpy),'');
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc_py,$rc,$str_rc_tpy),'');  
			$tmp_dt_tb[]=$this->fprosen($this->fjml_ttl($rc,$str_rc_py,$rc,$str_rc_tpy),'',$this->fjml_ttl($rc,$str_rc_jiwa,0,''),'',1000); 

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($jmlh_jiwa,0,',','.');
		$tmp_footer[]=number_format($jmlh_py,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_py,'',$jmlh_py+$jmlh_tpy,'');
		$tmp_footer[]=number_format($jmlh_tpy,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_tpy,'',$jmlh_py+$jmlh_tpy,'');
		$tmp_footer[]=number_format($jmlh_py+$jmlh_tpy,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_py+$jmlh_tpy,'',$jmlh_jiwa,'',1000);
		

		$footer_tb[] = $tmp_footer;   
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);        
    }

    public function report_temanggung_ks_tabel_4($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');

		$rent1 = $this->getrentang(0,1);
		$rent2 = $this->getrentang(1,4);
		$rent3 = $this->getrentang(5,6);
		$rent4 = $this->getrentang(7,15);
		$rent5 = $this->getrentang(16,21);
		$rent6 = $this->getrentang(22,59);
		$rent7 = $this->getrentang(60,0);

		$this->arrayks();

		$str='';
		foreach($this->arr_ks as $idx => $isi)	
		{
			$str=(!empty($str) ? $str.',' :$str).
			"SUM(($isi) AND ($rent1)) AS ".$this->arr_ks_bb[$idx]."_0_1".
			",SUM(($isi) AND ($rent2)) AS ".$this->arr_ks_bb[$idx]."_1_5".
			",SUM(($isi) AND ($rent3)) AS ".$this->arr_ks_bb[$idx]."_5_6".
			",SUM(($isi) AND ($rent4)) AS ".$this->arr_ks_bb[$idx]."_7_15".
			",SUM(($isi) AND ($rent5)) AS ".$this->arr_ks_bb[$idx]."_16_21".
			",SUM(($isi) AND ($rent6)) AS ".$this->arr_ks_bb[$idx]."_22_59
						,SUM(($isi) AND ($rent7)) AS ".$this->arr_ks_bb[$idx]."_60";
			
		}
             $this->db->select($str);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wl);
			 $this->db->where('Kd_mutasi IS NULL');			 
             $data = $this->db->get();

		$rc = $this->build_rc($data);              
		$jmlh_tot=0;
		foreach($this->arr_ks_bb as $idx=>$isi)
		{
			$str_rc_0_1=$isi."_0_1"; 
			$str_rc_1_5=$isi."_1_5"; 
			$str_rc_5_6=$isi."_5_6"; 
			$str_rc_7_15=$isi."_7_15"; 
			$str_rc_16_21=$isi."_16_21"; 
			$str_rc_22_59=$isi."_22_59"; 
			$str_rc_60=$isi."_60";
			$jmlh_tot=$this->fjml_ttl($rc,$str_rc_0_1,$jmlh_tot,'')+
			$this->fjml_ttl($rc,$str_rc_1_5,$rc,$str_rc_5_6)+
			$this->fjml_ttl($rc,$str_rc_7_15,$rc,$str_rc_16_21)+
			$this->fjml_ttl($rc,$str_rc_22_59,$rc,$str_rc_60);
		}	


		$dt_tb=array();
		$i=1;
		$arrjml = array();
		$idx=0;	
		$jmlh_0_1=0; 
		$jmlh_1_5=0; 
		$jmlh_5_6=0; 
		$jmlh_7_15=0; 
		$jmlh_16_21=0; 
		$jmlh_22_59=0; 
		$jmlh_60=0; 	        
		foreach($this->arr_ks_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$this->arr_ks_b[$idx];

			$str_rc_0_1=$isi."_0_1"; 
			$str_rc_1_5=$isi."_1_5"; 
			$str_rc_5_6=$isi."_5_6"; 
			$str_rc_7_15=$isi."_7_15"; 
			$str_rc_16_21=$isi."_16_21"; 
			$str_rc_22_59=$isi."_22_59"; 
			$str_rc_60=$isi."_60";
			
			$jmlh_0_1=$this->fjml_ttl($rc,$str_rc_0_1,$jmlh_0_1,''); 
			$jmlh_1_5=$this->fjml_ttl($rc,$str_rc_1_5,$jmlh_1_5,''); 
			$jmlh_5_6=$this->fjml_ttl($rc,$str_rc_5_6,$jmlh_5_6,''); 
			$jmlh_7_15=$this->fjml_ttl($rc,$str_rc_7_15,$jmlh_7_15,''); 
			$jmlh_16_21=$this->fjml_ttl($rc,$str_rc_16_21,$jmlh_16_21,''); 
			$jmlh_22_59=$this->fjml_ttl($rc,$str_rc_22_59,$jmlh_22_59,''); 
			$jmlh_60=$this->fjml_ttl($rc,$str_rc_60,$jmlh_60,''); 
			
			$jmlh=$this->fjml_ttl($rc,$str_rc_0_1,0,'')+	
			$this->fjml_ttl($rc,$str_rc_1_5,$rc,$str_rc_5_6)+
			$this->fjml_ttl($rc,$str_rc_7_15,$rc,$str_rc_16_21)+	
			$this->fjml_ttl($rc,$str_rc_22_59,$rc,$str_rc_60);
			
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_0_1);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_0_1,$jmlh,''); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_1_5);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_1_5,$jmlh,''); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_5_6);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_5_6,$jmlh,''); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_7_15);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_7_15,$jmlh,''); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_16_21);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_16_21,$jmlh,''); 	
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_22_59);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_22_59,$jmlh,''); 
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_60);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_60,$jmlh,''); 
			$tmp_dt_tb[]=number_format($jmlh,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($jmlh,'',$jmlh_tot,'');  
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
			$idx++;
		}	
				
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($jmlh_0_1,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_0_1,'',$jmlh_tot,'');
		$tmp_footer[]=number_format($jmlh_1_5,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_1_5,'',$jmlh_tot,'');
		$tmp_footer[]=number_format($jmlh_5_6,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_5_6,'',$jmlh_tot,'');
		$tmp_footer[]=number_format($jmlh_7_15,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_7_15,'',$jmlh_tot,'');
		$tmp_footer[]=number_format($jmlh_16_21,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_16_21,'',$jmlh_tot,'');
		$tmp_footer[]=number_format($jmlh_22_59,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_22_59,'',$jmlh_tot,'');
		$tmp_footer[]=number_format($jmlh_60,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_60,'',$jmlh_tot,'');
		$tmp_footer[]=number_format($jmlh_tot,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_tot,'',$jmlh_tot,'');
		

		$footer_tb[] = $tmp_footer;
		
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_temanggung_ks_tabel_5($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');       
		$this->arrayks();

		$str='';
		foreach($this->arr_ks as $idx => $isi)	
		{
			$str=(!empty($str) ? $str.',' :$str).
			"SUM(($isi) AND (Kd_edu!=0 AND Kd_gen=1)) AS ".$this->arr_ks_bb[$idx]."_lk_skl".
			",SUM(($isi) AND (Kd_edu!=0 AND Kd_gen=2)) AS ".$this->arr_ks_bb[$idx]."_pr_skl".
			",SUM(($isi) AND (Kd_edu=0 AND Kd_gen=1)) AS ".$this->arr_ks_bb[$idx]."_lk_t_skl".
			",SUM(($isi) AND (Kd_edu=0 AND Kd_gen=2)) AS ".$this->arr_ks_bb[$idx]."_pr_t_skl";					   
		}
		
		$rent = $this->getrentang(7,15);

             $this->db->select($str);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wl);
			 $this->db->where("($rent)");	
			 $this->db->where('Kd_mutasi IS NULL');			 
             $data = $this->db->get(); 


		$rc = $this->build_rc($data); 


		$dt_tb=array();
		$i=1;
		$arrjml = array();	
		$jml_lk=0;
		$jml_pr=0;
		$jml_skl=0;
		$jml_t_skl=0;
		$jml_lk_skl=0;
		$jml_pr_skl=0;
		$jml_lk_t_skl=0;
		$jml_pr_t_skl=0;
		$tot_lk=0;
		$tot_pr=0;
		$jd_i=0;
		$tot_jd_i=0;		  	
		
		foreach($this->arr_ks_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$this->arr_ks_b[$idx];                              
			
			$lk_skl=isset($rc[$this->arr_ks_bb[$idx].'_lk_skl']) ? $rc[$this->arr_ks_bb[$idx].'_lk_skl'] : 0; 
			$pr_skl=isset($rc[$this->arr_ks_bb[$idx].'_pr_skl']) ? $rc[$this->arr_ks_bb[$idx].'_pr_skl'] : 0; 
			$lk_t_skl=isset($rc[$this->arr_ks_bb[$idx].'_lk_t_skl']) ? $rc[$this->arr_ks_bb[$idx].'_lk_t_skl'] : 0; 
			$pr_t_skl=isset($rc[$this->arr_ks_bb[$idx].'_pr_t_skl']) ? $rc[$this->arr_ks_bb[$idx].'_pr_t_skl'] : 0;
			
			
			$jml_lk=$lk_skl+$lk_t_skl;
			$jml_pr=$pr_skl+$pr_t_skl;
			$jml_skl=$lk_skl+$pr_skl;
			$jml_t_skl=$lk_t_skl+$pr_t_skl;
			$jml_lk_skl=$jml_lk_skl+$lk_skl;
			$jml_pr_skl=$jml_pr_skl+$pr_skl;
			$jml_lk_t_skl=$jml_lk_t_skl+$lk_t_skl;
			$jml_pr_t_skl=$jml_pr_t_skl+$pr_t_skl;
			$tot_lk=$tot_lk+$jml_lk;
			$tot_pr=$tot_pr+$jml_pr;
			$jd_i=$jml_lk+$jml_pr;
			$tot_jd_i=$tot_jd_i+$jd_i;
			
			
			$tmp_dt_tb[]=number_format($lk_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($lk_skl,'',$jml_skl,''); 
			$tmp_dt_tb[]=number_format($pr_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($pr_skl,'',$jml_skl,'');
			$tmp_dt_tb[]=number_format($jml_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($jml_skl,'',$jd_i,'');
			$tmp_dt_tb[]=number_format($lk_t_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($lk_t_skl,'',$jml_t_skl,''); 
			$tmp_dt_tb[]=number_format($pr_t_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($pr_t_skl,'',$jml_t_skl,'');
			$tmp_dt_tb[]=number_format($jml_t_skl,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($jml_t_skl,'',$jd_i,'');
			$tmp_dt_tb[]=number_format($jml_lk,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($jml_lk,'',$jd_i,''); 
			$tmp_dt_tb[]=number_format($jml_pr,0,',','.');
			$tmp_dt_tb[]=$this->fprosen($jml_pr,'',$jd_i,'');
			$tmp_dt_tb[]=number_format($jd_i,0,',','.');
			
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
			$idx++;
		}	                 
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($jml_lk_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_lk_skl,'',$jml_lk_skl+$jml_pr_skl,'');
		$tmp_footer[]=number_format($jml_pr_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_pr_skl,'',$jml_lk_skl+$jml_pr_skl,'');
		$tmp_footer[]=number_format($jml_lk_skl+$jml_pr_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_lk_skl+$jml_pr_skl,'',$tot_jd_i,'');
		$tmp_footer[]=number_format($jml_lk_t_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_lk_t_skl,'',$jml_lk_t_skl+$jml_pr_t_skl,'');
		$tmp_footer[]=number_format($jml_pr_t_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_pr_t_skl,'',$jml_lk_t_skl+$jml_pr_t_skl,'');
		$tmp_footer[]=number_format($jml_lk_t_skl+$jml_pr_t_skl,0,',','.');
		$tmp_footer[]=$this->fprosen($jml_lk_t_skl+$jml_pr_t_skl,'',$tot_jd_i,'');
		$tmp_footer[]=number_format($tot_lk,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_lk,'',$tot_jd_i,'');
		$tmp_footer[]=number_format($tot_pr,0,',','.');
		$tmp_footer[]=$this->fprosen($tot_pr,'',$tot_jd_i,'');
		$tmp_footer[]=number_format($tot_jd_i,0,',','.');


		$footer_tb[] = $tmp_footer;

		
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_temanggung_ks_tabel_6($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');   	          	         
		$this->arrayks();

		
		$dtctyp = $this->get_contr_typ('');            

		$nmcontyp=array();
		$str ='';
		foreach($this->arr_ks as $idx => $isi)	
		{
			if(!empty($dtctyp)){
				foreach($dtctyp as $row)
				{ 	
					$str=(!empty($str) ? $str.',' :$str).
					"SUM(($isi) AND Kd_contyp=$row[Kd_contyp]) AS ".$this->arr_ks_bb[$idx]."_$row[singkatan],
										SUM($isi) AS ".$this->arr_ks_bb[$idx]."_pus,
										SUM(($isi) AND Kd_contyp IS NOT NULL) AS ".$this->arr_ks_bb[$idx]."_nonkb";
				}	
				
			}        
		}

		     $this->db->select($str);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wl);
			 $this->db->where("pus='1'");			 
             $data = $this->db->get(); 

		$rc = $this->build_rc($data);                     
		
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();
		$jmlh_typ=array();			  	
		$jmlh_pus=0;
		$jmlh_nonkb=0;
		foreach($this->arr_ks_bb as $idx=>$isi)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$this->arr_ks_b[$idx];                 
			
			
			$str_rc_pus=$isi."_pus"; 
			$str_rc_nonkb=$isi."_nonkb";
			$jmlh_pus=$this->fjml_ttl($rc,$str_rc_pus,$jmlh_pus,''); 
			$jmlh_nonkb=$this->fjml_ttl($rc,$str_rc_nonkb,$jmlh_nonkb,'');
			
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_pus);
			
			
			if(!empty($dtctyp)){
				foreach($dtctyp as $row)
				{
					$str=$this->arr_ks_bb[$idx]."_".$row['singkatan'];
					$jmlh_typ[$row['Kd_contyp']]=$this->fjml_ttl($jmlh_typ,$row['Kd_contyp'],$rc,$str);
					$tmp_dt_tb[]=$this->frupiah($rc,$str);
					$tmp_dt_tb[]=$this->fprosen($rc,$str,$rc,$str_rc_nonkb);
					
				}
			}   
			
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc_nonkb);
			$tmp_dt_tb[]=$this->fprosen($rc,$str_rc_nonkb,$rc,$str_rc_pus);
			
			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
			$idx++;
		}	                 
		
		$footer_tb=array();
		$tmp_footer=array(); 
		$tmp_footer[]=number_format($jmlh_pus,0,',','.');
		
		foreach($dtctyp as $row)
		{
			$tmp_footer[]=number_format($jmlh_typ[$row['Kd_contyp']],0,',','.');
			$tmp_footer[]=$this->fprosen($jmlh_typ[$row['Kd_contyp']],'',$jmlh_nonkb,'');              
		}              
		
		$tmp_footer[]=number_format($jmlh_nonkb,0,',','.');
		$tmp_footer[]=$this->fprosen($jmlh_nonkb,'',$jmlh_pus,'');

		$footer_tb[] = $tmp_footer;

		
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_ks_tabel_1($idkec,$iddesa,$iddusun,$ks)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,'','','','id_unit_detail_idk');
		$this->arrayks();                           
		
		$data = $this->get_empmnt_stat('');

		$str='';
		foreach($data as  $rc)
		{
			$arr_emp[]=$rc['Kd_emp'];		 	   
			$str= (!empty($str) ? $str.',':'') .
			"SUM(Kd_emp=$rc[Kd_emp]) AS j_$rc[Kd_emp]"; 
			
		}

		 
		$data = $this->get_unit_detail("$kd_wl");  
	
		$dt_tb=array();
		$i=1;
		$arrjml = array();
		
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];    
			

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			'','','');                 
			
			
			 $this->db->select($str);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wlq);
			 $this->db->where('Kd_fammbrtyp=1');
			 $this->db->where('Kd_mutasi IS NULL');
			 $this->db->where('('.$this->arr_ks[$ks].')');
             $data1 = $this->db->get();           
			
			$rc = $this->build_rc($data1);

			$jml=0;
			foreach($arr_emp as $idxx=>$isii)
			{
				if($isii!=6){
					$str_rc="j_".$isii; 
					$jml=$this->fjml_ttl($rc,$str_rc,$jml,''); 
					$arrjml[$isii]=$this->fjml_ttl($arrjml,$isii,$rc,$str_rc);
					$tmp_dt_tb[]=$this->frupiah($rc,$str_rc); 
				}
			}
			$tmp_dt_tb[]=number_format($jml,0,',','.');
			$arrjml['jml']=$this->fjml_ttl($arrjml,'jml',$jml,'');
			$str_rc="j_6";
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc);                    
			$arrjml[$str_rc]=$this->fjml_ttl($arrjml,$str_rc,$rc,$str_rc);
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc,$jml,''),'');
			$arrjml['jml_ttl']=$this->fjml_ttl($arrjml,'jml_ttl',$this->fjml_ttl($rc,$str_rc,$jml,''),'');
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array();
		foreach ($arrjml as $value) {
			$tmp_footer[]=number_format($value,0,',','.');     
		}           

		$footer_tb[] = $tmp_footer;          
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    function report_ks_tabel_3($idkec,$iddesa,$iddusun,$ks,$cmbumur,$dtumur,$edu){ 
		
		$kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,'','','','id_unit_detail_idk');
		$this->arrayks();                           
				
		$data = $this->get_empmnt_stat('');

		$str='';
		foreach($data as  $rc)
		{
			$arr_emp[]=$rc['Kd_emp'];         
			$str= (!empty($str) ? $str.',':'') .
			"SUM(Kd_emp=$rc[Kd_emp]) AS j_$rc[Kd_emp]"; 
			
		}

		switch($cmbumur)
		{
		case 1 : $umur = $this->getrentang($dtumur[0],$dtumur[0]);break;
		case 2 : $umur = $this->getrentang(0,$dtumur[0]-1);break;
		case 3 : $umur = $this->getrentang($dtumur[0]+1);break;
		case 4 : $umur = $this->getrentang($dtumur[0],$dtumur[1]);break; 
		}
		
		$nmedu='';		
		foreach($edu as $row)
		{
			$nmedu= (!empty($nmedu) ? $nmedu.' OR ':''). "kd_edu=$row";		
		}
		
		
		            
		$data = $this->get_unit_detail("$kd_wl");  

	
		$dt_tb=array();
		$i=1;
		$arrjml = array();
		
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];    
			

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),'','','');                 
			
		
			
             $this->db->select($str);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wlq);
			 $this->db->where('Kd_mutasi IS NULL');
			 $this->db->where('('.$this->arr_ks[$ks].')');
             $this->db->where("($umur)");
             $this->db->where("($nmedu)");
             $data1 = $this->db->get();     


			$rc = $this->build_rc($data1);

			$jml=0;
			foreach($arr_emp as $idxx=>$isii)
			{
				if($isii!=6){
					$str_rc="j_".$isii; 
					$jml=$this->fjml_ttl($rc,$str_rc,$jml,''); 
					$arrjml[$isii]=$this->fjml_ttl($arrjml,$isii,$rc,$str_rc);
					$tmp_dt_tb[]=$this->frupiah($rc,$str_rc); 
				}
			}
			$tmp_dt_tb[]=number_format($jml,0,',','.');
			$arrjml['jml']=$this->fjml_ttl($arrjml,'jml',$jml,'');
			$str_rc="j_6";
			$tmp_dt_tb[]=$this->frupiah($rc,$str_rc);                    
			$arrjml[$str_rc]=$this->fjml_ttl($arrjml,$str_rc,$rc,$str_rc);
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,$str_rc,$jml,''),'');
			$arrjml['jml_ttl']=$this->fjml_ttl($arrjml,'jml_ttl',$this->fjml_ttl($rc,$str_rc,$jml,''),'');
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		} 
		
		
		$footer_tb=array();
		$tmp_footer=array();
		foreach ($arrjml as $value) {
			$tmp_footer[]=number_format($value,0,',','.');     
		}           

		$footer_tb[] = $tmp_footer;          
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
	}

	public function report_ks_tabel_2($idkec,$iddesa,$iddusun,$ks)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,'','','','id_unit_detail_idk');
		$this->arrayks();                           
		
		
		$data = $this->get_non_acptr_reas('');

		$str='';
		foreach($data as  $rc)
		{
			$arr_emp[]=$rc['Kd_nonacptr'];		 	   
			$str= (!empty($str) ? $str.',':'') .
			"SUM(Kd_nonacptr=$rc[Kd_nonacptr]) AS j_$rc[Kd_nonacptr]"; 
			
		}

		     
		$data = $this->get_unit_detail("$kd_wl");  

		                   
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();
		
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];    
			

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),'','','');                 
			
			

             $this->db->select($str);
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wlq);
			 $this->db->where("pus='1'");
			 $this->db->where('Kd_fammbrtyp=2');
			 $this->db->where('Kd_nonacptr IS NOT NULL');
			 $this->db->where('Kd_mutasi IS NULL');
			 $this->db->where('('.$this->arr_ks[$ks].')');
             $data1 = $this->db->get(); 


			$rc = $this->build_rc($data1);

			$jml=0;
			foreach($arr_emp as $idxx=>$isii)
			{
				
				$str_rc="j_".$isii; 
				$jml=$this->fjml_ttl($rc,$str_rc,$jml,''); 
				$arrjml[$isii]=$this->fjml_ttl($arrjml,$isii,$rc,$str_rc);
				$tmp_dt_tb[]=$this->frupiah($rc,$str_rc); 
				
			}
			
			$tmp_dt_tb[]=number_format($jml,0,',','.');
			$arrjml['jlmttl']=$this->fjml_ttl($arrjml,'jlmttl',$jml,'');			
			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array();
		foreach ($arrjml as $value) {
			$tmp_footer[]=number_format($value,0,',','.');     
		}           

		$footer_tb[] = $tmp_footer;          
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    function report_kat_usia($idkec,$iddesa,$iddusun,$idrt){ 
        
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');
		$this->arrayks();                           
		
		$str='sum('.$this->getrentang(0,4).') as blt,';
		$str.='sum('.$this->getrentang(16,24).') as rmj,';
		$str.='sum('.$this->getrentang(60).') as mnl';

		          
		$data = $this->get_unit_detail("$kd_wl");  
		                         
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();
		
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];    
			

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');                 
			
			
			 $this->db->select("count(*) as jd,$str");
             $this->db->from('vw_fam_indv');
			 $this->db->where($kd_wlq);
			 $this->db->where('Kd_mutasi IS NULL');
             $data1 = $this->db->get();             
			
			$rc = $this->build_rc($data1);
			
			$tmp_dt_tb[]=$this->frupiah($rc,'jd');
			$arrjml['jd']=$this->fjml_ttl($arrjml,'jd',$rc,'jd');
			
			$tmp_dt_tb[]=$this->frupiah($rc,'blt');
			$arrjml['blt']=$this->fjml_ttl($arrjml,'blt',$rc,'blt');
			$tmp_dt_tb[]=$this->fprosen($rc,'blt',$rc,'jd');
			$arrjml['pblt']=0;
			
			$tmp_dt_tb[]=$this->frupiah($rc,'rmj');
			$arrjml['rmj']=$this->fjml_ttl($arrjml,'rmj',$rc,'rmj');
			$tmp_dt_tb[]=$this->fprosen($rc,'rmj',$rc,'jd');
			$arrjml['prmj']=0;

			$tmp_dt_tb[]=$this->frupiah($rc,'mnl');
			$arrjml['mnl']=$this->fjml_ttl($arrjml,'mnl',$rc,'mnl');
			$tmp_dt_tb[]=$this->fprosen($rc,'mnl',$rc,'jd');
			$arrjml['pmnl']=0;
			
			$tmp_dt_tb[]=$this->frupiah($this->fjml_ttl($rc,'blt',$this->fjml_ttl($rc,'rmj',$rc,'mnl'),''),'');
			$arrjml['ttl']=$this->fjml_ttl($arrjml,'ttl',$rc,'blt')+$this->fjml_ttl($rc,'rmj',$rc,'mnl');
			$tmp_dt_tb[]=$this->fprosen($this->fjml_ttl($rc,'blt',$this->fjml_ttl($rc,'rmj',$rc,'mnl'),''),'',$rc,'jd');    
			$arrjml['pttl']=0;			

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array();

		$arrjml['pblt']=$this->fprosen($arrjml,'blt',$arrjml,'jd');
		$arrjml['prmj']=$this->fprosen($arrjml,'rmj',$arrjml,'jd');
		$arrjml['pmnl']=$this->fprosen($arrjml,'mnl',$arrjml,'jd');
		$arrjml['pttl']=$this->fprosen($arrjml,'ttl',$arrjml,'jd');

		foreach ($arrjml as $key=>$value) {
			$tmp_footer[]= strpos($key,'p')!==false ? number_format($value,2,',','.') : number_format($value,0,',','.');     
		}           

		$footer_tb[] = $tmp_footer;          
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 

    }

    function report_temanggung_pus($idkec,$iddesa,$iddusun,$idrt){ 
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');
		$this->arrayks();

		$data = $this->get_unit_detail("$kd_wl");  
		
		$dt_tb=array();
		$i=1;
		$arrjml = array();
		
		foreach($data as $row)
		{
			$tmp_dt_tb=array();
			$tmp_dt_tb[]=$i;
			$tmp_dt_tb[]=$row['no_unit_detail'];    
			

			$kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
			( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
			( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
			((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');                 
			
			
			 $this->db->select("count(*) as jd,SUM(Kd_nonacptr IS NULL) AS pus");
             $this->db->from('dbo_family');
			 $this->db->where($kd_wlq);
			 $this->db->where("pus='1'");
             $data1 = $this->db->get();                  
			
			$rc = $this->build_rc($data1);
			
			$tmp_dt_tb[]=$this->frupiah($rc,'jd');
			$arrjml['jd']=$this->fjml_ttl($arrjml,'jd',$rc,'jd');
			
			$tmp_dt_tb[]=$this->frupiah($rc,'pus');
			$arrjml['pus']=$this->fjml_ttl($arrjml,'pus',$rc,'pus');
			$tmp_dt_tb[]=$this->fprosen($rc,'pus',$rc,'jd');
			$arrjml['prpus']=0;
			
			$tmp_dt_tb[]=$this->frupiah($this->fkurangi($rc,'jd',$rc,'pus'),'');
			$arrjml['bpus']=$this->fjml_ttl($arrjml,'bpus',$this->fkurangi($rc,'jd',$rc,'pus'),'');
			$tmp_dt_tb[]=$this->fprosen($this->fkurangi($rc,'jd',$rc,'pus'),'',$rc,'jd');
			$arrjml['prbpus']=0;

			

			$dt_tb[] = $tmp_dt_tb;
			$i++;
		}	
		
		
		$footer_tb=array();
		$tmp_footer=array();

		$arrjml['prpus']=$this->fprosen($arrjml,'pus',$arrjml,'jd');
		$arrjml['prbpus']=$this->fprosen($arrjml,'bpus',$arrjml,'jd');
		

		foreach ($arrjml as $key=>$value) {
			$tmp_footer[]= strpos($key,'pr')!==false ? number_format($value,2,',','.') : number_format($value,0,',','.');     
		}           

		$footer_tb[] = $tmp_footer;          
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

}