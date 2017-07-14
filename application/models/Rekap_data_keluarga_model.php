<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_data_keluarga_model extends CI_Model {

	 private $arr_ks;
     private $arr_ks_b;
     private $arr_ks_bb;

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


      private function get_unit_detail_jml($idunit,$kd)
	  {
	      
	      $sql = "select * from dbo_unit_detail ";
	      $sql.="where id_unit_detail LIKE '$idunit%' AND id_unit_detail_idk LIKE '$idunit%' AND id_unit=$kd ";
	      $sql.="order by no_unit_detail";
	            
	      $query = $this->db->query($sql);
	      return $query->num_rows(); 
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


   private function getkdwl($idkec,$iddesa,$iddusun,$idrt,$pref='',$opt='AND',$field='')
	{
		$kd_wl='';
		if($idrt)
		{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_neigh='$idrt' $opt";
			$kd=$idrt; 
		}
		elseif($iddusun)
		{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_subvill='$iddusun' $opt"; 
			$kd=$iddusun; 
		}
		elseif($iddesa)
		{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_vill='$iddesa' $opt"; 
			$kd=$iddesa;
		}
		elseif($idkec)
		{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_subdist='$idkec' $opt";
			$kd=$idkec; 
		}else{
			$kd_wl=($pref!=''? $pref.'.':'')."Kd_dist='1017' $opt";
			$kd=1017;	
		}

		if(!empty($field))
		{
			$kd_wl=($pref!=''? $pref.'.':'')."$field='$kd' $opt";
		}


		return $kd_wl;		
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

	   private function build_rc($data)
		{
			$rc=array();
			if(!empty($data))
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

   private function check_var($jml,$idx_jml)
  {
    $op=0;
      if(is_array($jml))
      {         
         if(is_array($idx_jml))
         {
            $tmp=$jml;
            $idx_ada = true;
            foreach ($idx_jml as $idx) {
              if($idx_ada){
                if(isset($tmp[$idx])){   
                  $tmp=$tmp[$idx];
                 }else{
                   $idx_ada=false;
                 }
              }    
            }
            if($idx_ada){
             $op=$tmp;
            }
         }else{
           $op=isset($jml[$idx_jml]) ? $jml[$idx_jml] : 0;
         }
      }else{
        if(!empty($jml)){
         $op=$jml;
        }
      }

      return $op;
  }


  private function fjml_ttl($jml,$idx_jml,$dt,$idx_dt)
	{
  		$op1=$this->check_var($jml,$idx_jml);
      $op2=$this->check_var($dt,$idx_dt);
      $tmp_jml = $op1+$op2;
    return $tmp_jml;		
	}

	private function fprosen($dt1,$idx_dt1,$dt2,$idx_dt2,$kali=100)
  {
    
    $op1=$this->check_var($dt1,$idx_dt1);
    $op2=$this->check_var($dt2,$idx_dt2);
    $tmp_prosen = ($op2 !=0 ? round((($op1/$op2)*$kali),2) : 0 );

    return $tmp_prosen;
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

	public function jns_rpt1($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');

	    $u1549 = $this->getrentang(15,49);
	    
	                
	    $data = $this->get_unit_detail($kd_wl);

	    $this->arrayks();

	    $dt_tb=array();
	    $arrjml = array();
	    $i=1;
	    foreach($data as $row)
	    {
	      $tmp_dt_tb=array();
	      $tmp_dt_tb[]=$i;
	      $tmp_dt_tb[]=$row['no_unit_detail'];
	      
	      if($idkec==0){
	         $jml=$this->get_unit_detail_jml($row['id_unit_detail'],12);
	         $tmp_dt_tb[]=number_format($jml,0,',','.');
	         $arrjml['row1']['jml1'] = $this->fjml_ttl($arrjml,array('row1','jml1'),$jml,'');
	         $tmp_dt_tb[]=number_format($jml,0,',','.');
	         $arrjml['row1']['jml2'] = $this->fjml_ttl($arrjml,array('row1','jml2'),'',$jml,'');
	      }
	      
	      if($iddesa==0){         
	         $jml=$this->get_unit_detail_jml($row['id_unit_detail'],13);
	         $tmp_dt_tb[]=number_format($jml,0,',','.');
	         $arrjml['row1']['jml3'] = $this->fjml_ttl($arrjml,array('row1','jml3'),$jml,'');
	         $tmp_dt_tb[]=number_format($jml,0,',','.');
	         $arrjml['row1']['jml4'] = $this->fjml_ttl($arrjml,array('row1','jml4'),$jml,'');
	      }

	      if($iddusun==0)
	      {
	         $jml=$this->get_unit_detail_jml($row['id_unit_detail'],14);
	         $tmp_dt_tb[]=number_format($jml,0,',','.');
	         $arrjml['row1']['jml5'] = $this->fjml_ttl($arrjml,array('row1','jml5'),$jml,'');
	         $tmp_dt_tb[]=number_format($jml,0,',','.');
	         $arrjml['row1']['jml6'] = $this->fjml_ttl($arrjml,array('row1','jml6'),$jml,'');
	      }

	      $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
	      ((($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
	      ((($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
	      ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');  

          $this->db->select("SUM( Kd_fammbrtyp=1) AS j_rt,".
	                          "SUM( Kd_fammbrtyp=1 AND  Kd_gen=1) AS j_lk_kk,".
	                  "SUM( Kd_fammbrtyp=1 AND  Kd_gen=2) AS j_pr_kk,".
	                  "SUM( Kd_fammbrtyp=1 AND  Kd_emp!=6) AS j_krj_kk,".
	                  "SUM( Kd_fammbrtyp=1 AND  Kd_emp=6) AS j_tkrj_kk,".
	                  "SUM( Kd_fammbrtyp=1 AND  Kd_martl=2) AS j_kwn_kk,".
	                  "SUM( Kd_fammbrtyp=1 AND  Kd_martl!=2) AS j_tkwn_kk,".
	                  "SUM( Kd_fammbrtyp=1 AND  Kd_edu=2) AS j_ttmt_sd_kk,".
	                  "SUM( Kd_fammbrtyp=1 AND ( Kd_edu=3 OR  Kd_edu=5)) AS j_tmt_sd_sltp_kk,".
	                  "SUM( Kd_fammbrtyp=1 AND  Kd_edu=7) AS j_tmt_slta_kk,".
	                  "SUM( Kd_fammbrtyp=1 AND  Kd_edu=9) AS j_tmt_ak_pt_kk,".
	                  "SUM( Bantuan_modal='1' AND  Kd_fammbrtyp=1) AS j_btm_kk,".
	                  "SUM( Bantuan_modal='0' AND  Kd_fammbrtyp=1) AS j_tbtm_kk,".
	                  "SUM( Kd_gen=1) AS j_lk_jiwa,".
	                  "SUM( Kd_gen=2) AS j_pr_jiwa,".
	                  "SUM( Kd_gen=2 AND ($u1549)) AS j_wus,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1) AS pra_s_j_rt,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND  Kd_gen=1) AS pra_s_j_lk_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND  Kd_gen=2) AS pra_s_j_pr_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND  Kd_emp!=6) AS pra_s_j_krj_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND  Kd_emp=6) AS pra_s_j_tkrj_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND  Kd_martl=2) AS pra_s_j_kwn_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND  Kd_martl!=2) AS pra_s_j_tkwn_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND  Kd_edu=2) AS pra_s_j_ttmt_sd_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND ( Kd_edu=3 OR  Kd_edu=5)) AS pra_s_j_tmt_sd_sltp_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND  Kd_edu=7) AS pra_s_j_tmt_slta_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1 AND  Kd_edu=9) AS pra_s_j_tmt_ak_pt_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Bantuan_modal='1' AND  Kd_fammbrtyp=1) AS pra_s_j_btm_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Bantuan_modal='0' AND  Kd_fammbrtyp=1) AS pra_s_j_tbtm_kk,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_gen=1) AS pra_s_j_lk_jiwa,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_gen=2) AS pra_s_j_pr_jiwa,".
	                  "SUM((".$this->arr_ks['pra_s'].") AND  Kd_gen=2 AND ($u1549)) AS pra_s_j_wus,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1) AS ks_j_rt,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND  Kd_gen=1) AS ks_j_lk_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND  Kd_gen=2) AS ks_j_pr_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND  Kd_emp!=6) AS ks_j_krj_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND  Kd_emp=6) AS ks_j_tkrj_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND  Kd_martl=2) AS ks_j_kwn_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND  Kd_martl!=2) AS ks_j_tkwn_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND  Kd_edu=2) AS ks_j_ttmt_sd_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND ( Kd_edu=3 OR  Kd_edu=5)) AS ks_j_tmt_sd_sltp_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND  Kd_edu=7) AS ks_j_tmt_slta_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1 AND  Kd_edu=9) AS ks_j_tmt_ak_pt_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Bantuan_modal='1' AND  Kd_fammbrtyp=1) AS ks_j_btm_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Bantuan_modal='0' AND  Kd_fammbrtyp=1) AS ks_j_tbtm_kk,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_gen=1) AS ks_j_lk_jiwa,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_gen=2) AS ks_j_pr_jiwa,".
	                  "SUM((".$this->arr_ks['ks_1'].") AND  Kd_gen=2 AND ($u1549)) AS ks_j_wus ");
          $this->db->from('vw_fam_indv');
          $this->db->where($kd_wlq);
          $this->db->where('kd_mutasi IS NULL');

          $data1 = $this->db->get();
	      $rc = $this->build_rc($data1);

	      $tmp_dt_tb[]=$this->frupiah($rc,'j_rt');
	      $arrjml['row1']['jml7'] = $this->fjml_ttl($arrjml,array('row1','jml7'),$rc,'j_rt');
	      $tmp_dt_tb[]=$this->frupiah($rc,'j_rt');
	      $arrjml['row1']['jml8'] = $this->fjml_ttl($arrjml,array('row1','jml8'),$rc,'j_rt');
	      $tmp_dt_tb[]=$this->frupiah($rc,'j_rt');
	      $arrjml['row1']['jml9'] = $this->fjml_ttl($arrjml,array('row1','jml9'),$rc,'j_rt');
	      $tmp_dt_tb[]=$this->frupiah($rc,'j_rt');
	      $arrjml['row1']['jml10'] = $this->fjml_ttl($arrjml,array('row1','jml10'),$rc,'j_rt');
	      $arrjml['row2']['jml10'] = $this->fjml_ttl($arrjml,array('row2','jml10'),$rc,'pra_s_j_rt');
	      $arrjml['row3']['jml10'] = $this->fjml_ttl($arrjml,array('row3','jml10'),$rc,'ks_j_rt');

	      $j=1;
	      if(!empty($rc)){  
	        foreach ($rc as $key => $value) {
	          if($j>1 and $j<=15){
	            $tmp_dt_tb[]=number_format($value,0,',','.'); 
	            $arrjml['row1'][$key] = $this->fjml_ttl($arrjml,array('row1',$key),$value,'');
	          }

	          if($j>16 and $j<=30){            
	            $arrjml['row2'][$key] = $this->fjml_ttl($arrjml,array('row2',$key),$value,'');
	          }

	          if($j>31 and $j<=45){             
	            $arrjml['row3'][$key] = $this->fjml_ttl($arrjml,array('row3',$key),$value,'');
	          }

	          $j++;
	        }
	        $tmp_dt_tb[]=number_format($rc['j_lk_jiwa']+$rc['j_pr_jiwa'],0,',','.');
	        $arrjml['row1']['jml11'] = $this->fjml_ttl($arrjml,array('row1','jml11'),$rc['j_lk_jiwa']+$rc['j_pr_jiwa'],'');
	        $tmp_dt_tb[]=number_format($rc['j_wus'],0,',','.');
	        $arrjml['row1']['jml12'] = $this->fjml_ttl($arrjml,array('row1','jml12'),$rc,'j_wus');

	        $arrjml['row2']['jml11'] = $this->fjml_ttl($arrjml,array('row2','jml11'),$rc['pra_s_j_lk_jiwa']+$rc['pra_s_j_pr_jiwa'],'');
	        $arrjml['row2']['jml12'] = $this->fjml_ttl($arrjml,array('row2','jml12'),$rc,'pra_s_j_wus'); 

	        $arrjml['row3']['jml11'] = $this->fjml_ttl($arrjml,array('row3','jml11'),$rc['ks_j_lk_jiwa']+$rc['ks_j_pr_jiwa'],'');
	        $arrjml['row3']['jml12'] = $this->fjml_ttl($arrjml,array('row3','jml12'),$rc,'ks_j_wus'); 

	      }else{
	          for($j=1;$j<17;$j++)
	          {
	            $tmp_dt_tb[]=0;
	          }
	          
	        }
	      


	      $dt_tb[] = $tmp_dt_tb;
	      $i++;
	    } 

	       
	    $footer_tb=array();
	        $tmp_footer=array();

	        foreach ($arrjml as $key=>$value) {
	          foreach ($value as $key1 => $value1) {
	            $tmp_footer[$key][$key1]= number_format($value1,0,',','.');
	          }
	        } 

	        $footer_tb = $tmp_footer;


	    return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt2($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');    
    
               
    $data = $this->get_unit_detail($kd_wl);

    $this->arrayks();
        
        $u01 = $this->getrentang(0,1);
        $u15 = $this->getrentang(1,4);
        $u56 = $this->getrentang(5,6);
        $u715 = $this->getrentang(7,15);
        
        $u1621 = $this->getrentang(16,21);
        $u2259 = $this->getrentang(22,59);
        $u60 = $this->getrentang(60);

        $u020 = $this->getrentang(0,20);
        $u2029 = $this->getrentang(20,29);
        $u3049 = $this->getrentang(30,49);

        
        $tmp_data = $this->get_non_acptr_reas('');
        
        $str_reas = '';
        $str_reas_pra_s = '';
        $str_reas_ks = '';

        if(!empty($tmp_data))
        {
          foreach ($tmp_data as $rc_reas) {         
            $str_reas.= (!empty($str_reas) ? ',' : '')."SUM(Kd_nonacptr=".$rc_reas['Kd_nonacptr']." AND Kd_fammbrtyp=1) AS j_reas_".$rc_reas['Kd_nonacptr'];
            $str_reas_pra_s.= (!empty($str_reas_pra_s) ? ',' : '')."SUM(((".$this->arr_ks['pra_s'].") AND Kd_nonacptr=".$rc_reas['Kd_nonacptr'].") AND Kd_fammbrtyp=1) AS pra_s_j_reas_".$rc_reas['Kd_nonacptr'];
            $str_reas_ks.= (!empty($str_reas_ks) ? ',' : '')."SUM(((".$this->arr_ks['ks_1'].") AND Kd_nonacptr=".$rc_reas['Kd_nonacptr'].") AND Kd_fammbrtyp=1) AS ks_j_reas_".$rc_reas['Kd_nonacptr'];
          } 
        }



    $dt_tb=array();
    $arrjml = array();
    $i=1;
    foreach($data as $row)
    {
      $tmp_dt_tb=array();
      //$tmp_dt_tb[]=$i;
      //$tmp_dt_tb[]=$row['no_unit_detail'];
      
      $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
      ((($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');  

                $this->db->select("SUM(($u01) AND  Posyandu='1') AS j_blt_0_1_pos,".
                      "SUM(($u01) AND  Posyandu='0') AS j_blt_0_1_tpos,".
                      "SUM(($u15) AND  Posyandu='1') AS j_blt_1_5_pos,".
                      "SUM(($u15) AND  Posyandu='0') AS j_blt_1_5_tpos,".
                      "SUM($u56) AS j_5_6_th,".                                   
                      "SUM(($u715) AND  Kd_edu!=0 AND  Kd_gen=1) AS j_lk_sklh_7_15,".
                      "SUM(($u715) AND  Kd_edu!=0 AND  Kd_gen=2) AS j_pr_sklh_7_15,".
                      "SUM(($u715) AND  Kd_edu=0 AND  Kd_gen=1) AS j_lk_tsklh_7_15,".
                      "SUM(($u715) AND  Kd_edu=0 AND  Kd_gen=2) AS j_pr_tsklh_7_15,".
                      "SUM($u1621) AS j_16_21_th,".
                      "SUM($u2259) AS j_22_59_th,".
                      "SUM($u60) AS j_60_th,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=1) AS j_pus,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=2 AND ($u020)) AS j_20_th_pus,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=2 AND ($u2029)) AS j_20_29_th_pus,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=2 AND ($u3049)) AS j_30_49_th_pus,".
                      "SUM( Kd_consrc=1 AND  Kd_fammbrtyp=1) AS j_pmt_kb,".
                      "SUM( Kd_consrc=2 AND  Kd_fammbrtyp=1) AS j_swst_kb,".
                      "SUM(( Kd_implan=0 AND  Kd_implan IS NOT NULL AND  Kd_contyp=4 AND  Kd_consrc IS NOT NULL AND  Kd_consrc!=0) AND  Kd_fammbrtyp=1) AS j_impl,".
                      $str_reas.",".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u01) AND  Posyandu='1') AS pra_s_j_blt_0_1_pos,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u01) AND  Posyandu='0') AS pra_s_j_blt_0_1_tpos,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u15) AND  Posyandu='1') AS pra_s_j_blt_1_5_pos,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u15) AND  Posyandu='0') AS pra_s_j_blt_1_5_tpos,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u56)) AS pra_s_j_5_6_th,".                                    
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u715) AND  Kd_edu!=0 AND  Kd_gen=1) AS pra_s_j_lk_sklh_7_15,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u715) AND  Kd_edu!=0 AND  Kd_gen=2) AS pra_s_j_pr_sklh_7_15,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u715) AND  Kd_edu=0 AND  Kd_gen=1) AS pra_s_j_lk_tsklh_7_15,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u715) AND  Kd_edu=0 AND  Kd_gen=2) AS pra_s_j_pr_tsklh_7_15,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u1621)) AS pra_s_j_16_21_th,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u2259)) AS pra_s_j_22_59_th,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ($u60)) AS pra_s_j_60_th,".
                      "SUM((".$this->arr_ks['pra_s'].") AND  pus='1' AND  Kd_fammbrtyp=1) AS pra_s_j_pus,".
                      "SUM((".$this->arr_ks['pra_s'].") AND  pus='1' AND  Kd_fammbrtyp=2 AND ($u020)) AS pra_s_j_20_th_pus,".
                      "SUM((".$this->arr_ks['pra_s'].") AND  pus='1' AND  Kd_fammbrtyp=2 AND ($u2029)) AS pra_s_j_20_29_th_pus,".
                      "SUM((".$this->arr_ks['pra_s'].") AND  pus='1' AND  Kd_fammbrtyp=2 AND ($u3049)) AS pra_s_j_30_49_th_pus,".
                      "SUM((".$this->arr_ks['pra_s'].") AND  Kd_consrc=1 AND  Kd_fammbrtyp=1) AS pra_s_j_pmt_kb,".
                      "SUM((".$this->arr_ks['pra_s'].") AND  Kd_consrc=2 AND  Kd_fammbrtyp=1) AS pra_s_j_swst_kb,".
                      "SUM((".$this->arr_ks['pra_s'].") AND ( Kd_implan=0 AND  Kd_implan IS NOT NULL AND  Kd_contyp=4 AND  Kd_consrc IS NOT NULL AND  Kd_consrc!=0 AND  Kd_fammbrtyp=1)) AS pra_s_j_impl,".
                      $str_reas_pra_s.",".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u01) AND  Posyandu='1') AS ks_j_blt_0_1_pos,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u01) AND  Posyandu='0') AS ks_j_blt_0_1_tpos,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u15) AND  Posyandu='1') AS ks_j_blt_1_5_pos,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u15) AND  Posyandu='0') AS ks_j_blt_1_5_tpos,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u56)) AS ks_j_5_6_th,".                                   
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u715) AND  Kd_edu!=0 AND  Kd_gen=1) AS ks_j_lk_sklh_7_15,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u715) AND  Kd_edu!=0 AND  Kd_gen=2) AS ks_j_pr_sklh_7_15,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u715) AND  Kd_edu=0 AND  Kd_gen=1) AS ks_j_lk_tsklh_7_15,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u715) AND  Kd_edu=0 AND  Kd_gen=2) AS ks_j_pr_tsklh_7_15,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u1621)) AS ks_j_16_21_th,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u2259)) AS ks_j_22_59_th,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ($u60)) AS ks_j_60_th,".
                      "SUM((".$this->arr_ks['ks_1'].") AND  pus='1' AND  Kd_fammbrtyp=1) AS ks_j_pus,".
                      "SUM((".$this->arr_ks['ks_1'].") AND  pus='1' AND  Kd_fammbrtyp=2 AND ($u020)) AS ks_j_20_th_pus,".
                      "SUM((".$this->arr_ks['ks_1'].") AND  pus='1' AND  Kd_fammbrtyp=2 AND ($u2029)) AS ks_j_20_29_th_pus,".
                      "SUM((".$this->arr_ks['ks_1'].") AND  pus='1' AND  Kd_fammbrtyp=2 AND ($u3049)) AS ks_j_30_49_th_pus,".
                      "SUM((".$this->arr_ks['ks_1'].") AND  Kd_consrc=1 AND  Kd_fammbrtyp=1) AS ks_j_pmt_kb,".
                      "SUM((".$this->arr_ks['ks_1'].") AND  Kd_consrc=2 AND  Kd_fammbrtyp=1) AS ks_j_swst_kb,".
                      "SUM((".$this->arr_ks['ks_1'].") AND ( Kd_implan=0 AND  Kd_implan IS NOT NULL AND  Kd_contyp=4 AND  Kd_consrc IS NOT NULL AND  Kd_consrc!=0 AND  Kd_fammbrtyp=1)) AS ks_j_impl,".
                      $str_reas_ks.",".
                      "SUM((".$this->arr_ks['pra_s'].") AND  Kd_fammbrtyp=1) AS j_pra_s,".
                      "SUM((".$this->arr_ks['ks_1'].") AND  Kd_fammbrtyp=1) AS j_ks_1,".
                      "SUM(".$this->arr_ks['ks_2']." AND  Kd_fammbrtyp=1) AS j_ks_2,".
                      "SUM(".$this->arr_ks['ks_3']." AND  Kd_fammbrtyp=1) AS j_ks_3,".
                      "SUM(".$this->arr_ks['ks_3_p']." AND  Kd_fammbrtyp=1) AS j_ks_3_p");
          $this->db->from('vw_fam_indv');
          $this->db->where($kd_wlq);
          $this->db->where('kd_mutasi IS NULL');

          $data1 = $this->db->get();
      

      $rc = $this->build_rc($data1);

      
      $j=1;
      if(!empty($rc)){  
        foreach ($rc as $key => $value) {
          if($j<=23){
            $tmp_dt_tb[]=number_format($value,0,',','.'); 
            $arrjml['row1'][$key] = $this->fjml_ttl($arrjml,array('row1',$key),$value,'');
          }

          if($j>23 and $j<=46){            
            $arrjml['row2'][$key] = $this->fjml_ttl($arrjml,array('row2',$key),$value,'');
          }

          if($j>46 and $j<=69){             
            $arrjml['row3'][$key] = $this->fjml_ttl($arrjml,array('row3',$key),$value,'');
            $jml=0;
          }

          if($j>69){
            $tmp_dt_tb[]=number_format($value,0,',','.');
            $jml=$jml+$value; 
            $arrjml['row1'][$key] = $this->fjml_ttl($arrjml,array('row1',$key),$value,'');
          }

          $j++;
        }

        $tmp_dt_tb[]=number_format($jml,0,',','.');
        $arrjml['row1']['jml'] = $this->fjml_ttl($arrjml,array('row1','jml'),$jml,'');
        
      }else{
          for($j=1;$j<30;$j++)
          {
            $tmp_dt_tb[]=0;
          }
          
        }
      


      $dt_tb[] = $tmp_dt_tb;
      $i++;
    } 

       
    $footer_tb=array();
        $tmp_footer=array();

        foreach ($arrjml as $key=>$value) {
          foreach ($value as $key1 => $value1) {
            $tmp_footer[$key][$key1]= number_format($value1,0,',','.');
          }
        } 

        $footer_tb = $tmp_footer;

    return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt3($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');    
    
               
    $data = $this->get_unit_detail("$kd_wl");

    $this->arrayks();
                
        
        $tmp_data = $this->get_contr_typ('');

        $str_qr_idk = '';
        $pra_s_str_qr_idk = '';
        $ks_str_qr_idk = '';

        if(!empty($tmp_data))
        {
          foreach ($tmp_data as $rc_typ) {         
            $str_qr_idk.= (!empty($str_qr_idk) ? ',' : '')."SUM(Kd_contyp=$rc_typ[Kd_contyp]) AS j_typ_$rc_typ[Kd_contyp]";
            $pra_s_str_qr_idk.= (!empty($pra_s_str_qr_idk) ? ',' : '')."SUM((".$this->arr_ks['pra_s'].") AND Kd_contyp=$rc_typ[Kd_contyp]) AS pra_s_j_typ_$rc_typ[Kd_contyp]";
            $ks_str_qr_idk.= (!empty($ks_str_qr_idk) ? ',' : '')."SUM((".$this->arr_ks['ks_1'].") AND Kd_contyp=$rc_typ[Kd_contyp]) AS ks_j_typ_$rc_typ[Kd_contyp]";
          } 
        }

    

    $dt_tb=array();
    $arrjml = array();
    $i=1;
    foreach($data as $row)
    {
      $tmp_dt_tb=array();
      $tmp_dt_tb[]=$i;
      $tmp_dt_tb[]=$row['no_unit_detail'];
      
      $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
      ((($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');  

      $this->db->select("COUNT(*) AS jd,
                       $str_qr_idk,
                       SUM(".$this->arr_ks['pra_s'].") pra_s_j_pus,
                       $pra_s_str_qr_idk,
                       SUM(".$this->arr_ks['ks_1'].") ks_j_pus,                       
                       $ks_str_qr_idk");
          $this->db->from('dbo_family');
          $this->db->where($kd_wlq);
          $this->db->where("pus='1'");

          $data1 = $this->db->get();

      $rc = $this->build_rc($data1);

      
      $j=1;
      $jml=0;
      $jml1=0;
      $jml2=0;
      if(!empty($rc)){  
        foreach ($rc as $key => $value) {
          if($j<=9){
            $tmp_dt_tb[]=number_format($value,0,',','.'); 
            if($j>1){ 
              $jml=$jml+$value;
            }

            $arrjml['row1'][$key] = $this->fjml_ttl($arrjml,array('row1',$key),$value,'');
          }

          if($j>9 and $j<=18){
            if($j>10){ 
              $jml1=$jml1+$value;
            }            
            $arrjml['row2'][$key] = $this->fjml_ttl($arrjml,array('row2',$key),$value,'');
          }

          if($j>18 and $j<=32){ 
          if($j>19){ 
              $jml2=$jml2+$value;
            }               
            $arrjml['row3'][$key] = $this->fjml_ttl($arrjml,array('row3',$key),$value,'');            
          }
          
          $j++;
        }

        $tmp_dt_tb[]=number_format($jml,0,',','.');
        $tmp_dt_tb[]=number_format($this->fprosen($jml,'',$rc,'jd'),2,',','.').'%';
        $arrjml['row1']['jml'] = $this->fjml_ttl($arrjml,array('row1','jml'),$jml,''); 
        $arrjml['row1']['prosen'] =  $this->fprosen($arrjml,array('row1','jml'),$arrjml,array('row1','jd')) ;
        $arrjml['row2']['jml1'] = $this->fjml_ttl($arrjml,array('row2','jml1'),$jml1,'');
        $arrjml['row2']['prosen'] =  $this->fprosen($arrjml,array('row2','jml1'),$arrjml,array('row2','pra_s_j_pus')) ;
        $arrjml['row3']['jml2'] = $this->fjml_ttl($arrjml,array('row3','jml2'),$jml2,'');
        $arrjml['row3']['prosen'] =  $this->fprosen($arrjml,array('row3','jml2'),$arrjml,array('row3','ks_j_pus')) ;
      }else{
          for($j=1;$j<11;$j++)
          {
            $tmp_dt_tb[]=0;
          }
          $tmp_dt_tb[]='0%';
        }

      $dt_tb[] = $tmp_dt_tb;
      $i++;
    } 

       
    $footer_tb=array();
        $tmp_footer=array();

        foreach ($arrjml as $key=>$value) {
          foreach ($value as $key1 => $value1) {
            if($key1!='prosen'){ 
             $tmp_footer[$key][$key1]= number_format($value1,0,',','.');
            }else{
             $tmp_footer[$key][$key1]= number_format($value1,2,',','.').'%';
            } 
          }
        } 

        $footer_tb = $tmp_footer;
    return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);   
    }

    public function jns_rpt4($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');    

    
                
    $data = $this->get_unit_detail("$kd_wl");

    $this->arrayks();
                
        $tmp_data = $this->get_contr_typ('');

        $str_qr_idk = '';
        $pra_s_str_qr_idk = '';
        $ks_str_qr_idk = '';

        if(!empty($tmp_data))
        {
          foreach ($tmp_data as $rc_typ) {         
            $str_qr_idk.= (!empty($str_qr_idk) ? ',' : '')."SUM(Kd_contyp=$rc_typ[Kd_contyp]) AS j_typ_$rc_typ[Kd_contyp]";            
          } 
        }

    
    $dt_tb=array();
    $arrjml = array();
    $i=1;
    foreach($data as $row)
    {
      $tmp_dt_tb=array();
      $tmp_dt_tb[]=$i;
      $tmp_dt_tb[]=$row['no_unit_detail'];
      $tmp_dt_tb[]='PRA SEJAHTERA';
      
      $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
      ((($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');  
      
          $this->db->select("COUNT(*) AS jd,$str_qr_idk");
          $this->db->from('dbo_family');
          $this->db->where($kd_wlq);
          $this->db->where("pus='1'");
          $this->db->where("(".$this->arr_ks['pra_s'].")");

          $data1 = $this->db->get();



      $rc = $this->build_rc($data1);      
     
      $jml=0;
      $j=1;
      if(!empty($rc)){  
        foreach ($rc as $key => $value) {
          
            $tmp_dt_tb[]=number_format($value,0,',','.'); 
            if($j>1){ 
              $jml=$jml+$value;
            }

            $arrjml[$key] = $this->fjml_ttl($arrjml,$key,$value,'');
         $j++;
        }

        $tmp_dt_tb[]=number_format($jml,0,',','.');
        $tmp_dt_tb[]=number_format($this->fprosen($jml,'',$rc,'jd'),2,',','.').'%';
        $arrjml['jml'] = $this->fjml_ttl($arrjml,'jml',$jml,''); 
        $arrjml['prosen'] =  $this->fprosen($arrjml,'jml',$arrjml,'jd') ;
        
      }else{
          for($j=1;$j<11;$j++)
          {
            $tmp_dt_tb[]=0;
          }
          $tmp_dt_tb[]='0%';
        }     


      $dt_tb[] = $tmp_dt_tb;
      $i++;
    } 

       
    $footer_tb=array();
        $tmp_footer=array();
        $tmp_footer[]='PRA SEJAHTERA';
        foreach ($arrjml as $key=>$value) {
          
            if($key!='prosen'){ 
             $tmp_footer[$key]= number_format($value,0,',','.');
            }else{
             $tmp_footer[$key]= number_format($value,2,',','.').'%';
            } 
         
        } 

        $footer_tb = $tmp_footer;
    return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt5($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');    
    
                  
    $data = $this->get_unit_detail("$kd_wl");

    $this->arrayks();
                
       $tmp_data = $this->get_contr_typ('');

        $str_qr_idk = '';
        $pra_s_str_qr_idk = '';
        $ks_str_qr_idk = '';

        if(!empty($tmp_data))
        {
          foreach ($tmp_data as $rc_typ) {         
            $str_qr_idk.= (!empty($str_qr_idk) ? ',' : '')."SUM(Kd_contyp=$rc_typ[Kd_contyp]) AS j_typ_$rc_typ[Kd_contyp]";            
          } 
        }

    
    $dt_tb=array();
    $arrjml = array();
    $i=1;
    foreach($data as $row)
    {
      $tmp_dt_tb=array();
      $tmp_dt_tb[]=$i;
      $tmp_dt_tb[]=$row['no_unit_detail'];
      $tmp_dt_tb[]='KS I';
      
      $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
      ((($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');  
    
      
          $this->db->select("COUNT(*) AS jd,$str_qr_idk");
          $this->db->from('dbo_family');
          $this->db->where($kd_wlq);
          $this->db->where("pus='1'");
          $this->db->where("(".$this->arr_ks['ks_1'].")");

          $data1 = $this->db->get();

      $rc = $this->build_rc($data1);      
     
      $jml=0;
      $j=1;
      if(!empty($rc)){  
        foreach ($rc as $key => $value) {
          
            $tmp_dt_tb[]=number_format($value,0,',','.'); 
            if($j>1){ 
              $jml=$jml+$value;
            }

            $arrjml[$key] = $this->fjml_ttl($arrjml,$key,$value,'');
         $j++;
        }

        $tmp_dt_tb[]=number_format($jml,0,',','.');
        $tmp_dt_tb[]=number_format($this->fprosen($jml,'',$rc,'jd'),2,',','.').'%';
        $arrjml['jml'] = $this->fjml_ttl($arrjml,'jml',$jml,''); 
        $arrjml['prosen'] =  $this->fprosen($arrjml,'jml',$arrjml,'jd') ;
        
      }else{
          for($j=1;$j<11;$j++)
          {
            $tmp_dt_tb[]=0;
          }
          $tmp_dt_tb[]='0%';
        }     


      $dt_tb[] = $tmp_dt_tb;
      $i++;
    } 

       
    $footer_tb=array();
        $tmp_footer=array();
        $tmp_footer[]='KS I';
        foreach ($arrjml as $key=>$value) {
          
            if($key!='prosen'){ 
             $tmp_footer[$key]= number_format($value,0,',','.');
            }else{
             $tmp_footer[$key]= number_format($value,2,',','.').'%';
            } 
         
        } 

        $footer_tb = $tmp_footer;
    return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt6($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');

    $u1549 = $this->getrentang(15,49);
    
    $data = $this->get_unit_detail("$kd_wl");

    $this->arrayks();

    $dt_tb=array();
    $arrjml = array();
    $i=1;
    foreach($data as $row)
    {
      $tmp_dt_tb=array();
      $tmp_dt_tb[]=$i;
      $tmp_dt_tb[]=$row['no_unit_detail'];
      $tmp_dt_tb[]='PRA SEJAHTERA';
      
      $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
      ((($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');  

     

          $this->db->select("SUM( Kd_fammbrtyp=1) AS j_rt,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_gen=1) AS j_lk_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_gen=2) AS j_pr_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_emp!=6) AS j_krj_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_emp=6) AS j_tkrj_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_martl=2) AS j_kwn_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_martl!=2) AS j_tkwn_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_edu=2) AS j_ttmt_sd_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  (Kd_edu=3 OR  Kd_edu=5)) AS j_tmt_sd_sltp_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_edu=7) AS j_tmt_slta_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_edu=9) AS j_tmt_ak_pt_kk,".
                      "SUM( Bantuan_modal='1' AND  Kd_fammbrtyp=1) AS j_btm_kk,".
                      "SUM( Bantuan_modal='0' AND  Kd_fammbrtyp=1) AS j_tbtm_kk,".
                      "SUM( Kd_gen=1) AS j_lk_jiwa,".
                      "SUM( Kd_gen=2) AS j_pr_jiwa,".
                      "SUM( Kd_gen=2 AND ($u1549)) AS j_wus");
          $this->db->from('vw_fam_indv');
          $this->db->where($kd_wlq);
          $this->db->where("Kd_mutasi IS NULL");
          $this->db->where("(".$this->arr_ks['pra_s'].")");

          $data1 = $this->db->get();

      $rc = $this->build_rc($data1);
      
      $tmp_dt_tb[]=$this->frupiah($rc,'j_rt');
      $arrjml['jml10'] = $this->fjml_ttl($arrjml,'jml10',$rc,'j_rt');
      

      $j=1;
      if(!empty($rc)){  
        foreach ($rc as $key => $value) {
          if($j>1 and $j<=15){
            $tmp_dt_tb[]=number_format($value,0,',','.'); 
            $arrjml[$key] = $this->fjml_ttl($arrjml,$key,$value,'');
          }      

          $j++;
        }
        $tmp_dt_tb[]=$this->frupiah($rc['j_lk_jiwa']+$rc['j_pr_jiwa'],'');
        $arrjml['jml11'] = $this->fjml_ttl($arrjml,'jml11',$rc['j_lk_jiwa']+$rc['j_pr_jiwa'],'');
        $tmp_dt_tb[]=$this->frupiah($rc,'j_wus');
        $arrjml['jml12'] = $this->fjml_ttl($arrjml,'jml12',$rc,'j_wus');
      }else{
          for($j=1;$j<17;$j++)
          {
            $tmp_dt_tb[]=0;
          }
          
        } 
      $dt_tb[] = $tmp_dt_tb;
      $i++;
    } 

       
    $footer_tb=array();
        $tmp_footer=array();
        $tmp_footer[]='PRA SEJAHTERA';
        foreach ($arrjml as $key=>$value) {          
            $tmp_footer[$key]= number_format($value,0,',','.');          
        } 

        $footer_tb = $tmp_footer;
    return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt7($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');    
    
    $data = $this->get_unit_detail("$kd_wl");

    $this->arrayks();
        
        $u01 = $this->getrentang(0,1);
        $u15 = $this->getrentang(1,4);
        $u56 = $this->getrentang(5,6);
        $u715 = $this->getrentang(7,15);
        
        $u1621 = $this->getrentang(16,21);
        $u2259 = $this->getrentang(22,59);
        $u60 = $this->getrentang(60);

        $u020 = $this->getrentang(0,19);
        $u2029 = $this->getrentang(20,29);
        $u3049 = $this->getrentang(30,49);

        $tmp_data = $this->get_non_acptr_reas('');
        
        $str_reas = '';
        $str_reas_pra_s = '';
        $str_reas_ks = '';

        if(!empty($tmp_data))
        {
          foreach ($tmp_data as $rc_reas) {         
            $str_reas.= (!empty($str_reas) ? ',' : '')."SUM(Kd_nonacptr=".$rc_reas['Kd_nonacptr']." AND Kd_fammbrtyp=1) AS j_reas_".$rc_reas['Kd_nonacptr'];
          } 
        }

   

    $dt_tb=array();
    $arrjml = array();
    $i=1;
    foreach($data as $row)
    {
      $tmp_dt_tb=array();
      $tmp_dt_tb[]=$i;
      $tmp_dt_tb[]=$row['no_unit_detail'];
      $tmp_dt_tb[]='PRA SEJAHTERA';
      
      $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
      ((($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');  

      $this->db->select("SUM(($u01) AND  Posyandu='1') AS j_blt_0_1_pos,".
                      "SUM(($u01) AND  Posyandu='0') AS j_blt_0_1_tpos,".
                      "SUM(($u15) AND  Posyandu='1') AS j_blt_1_5_pos,".
                      "SUM(($u15) AND  Posyandu='0') AS j_blt_1_5_tpos,".
                      "SUM($u56) AS j_5_6_th,".                                   
                      "SUM(($u715) AND  Kd_edu!=0 AND  Kd_gen=1) AS j_lk_sklh_7_15,".
                      "SUM(($u715) AND  Kd_edu!=0 AND  Kd_gen=2) AS j_pr_sklh_7_15,".
                      "SUM(($u715) AND  Kd_edu=0 AND  Kd_gen=1) AS j_lk_tsklh_7_15,".
                      "SUM(($u715) AND  Kd_edu=0 AND  Kd_gen=2) AS j_pr_tsklh_7_15,".
                      "SUM($u1621) AS j_16_21_th,".
                      "SUM($u2259) AS j_22_59_th,".
                      "SUM($u60) AS j_60_th,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=1) AS j_pus,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=2 AND ($u020)) AS j_20_th_pus,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=2 AND ($u2029)) AS j_20_29_th_pus,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=2 AND ($u3049)) AS j_30_49_th_pus,".
                      "SUM( Kd_consrc=1 AND  Kd_fammbrtyp=1) AS j_pmt_kb,".
                      "SUM( Kd_consrc=2 AND  Kd_fammbrtyp=1) AS j_swst_kb,".
                      "SUM(( Kd_implan=0 AND  Kd_implan IS NOT NULL AND  Kd_contyp=4 AND  Kd_consrc IS NOT NULL AND  Kd_consrc!=0) AND  Kd_fammbrtyp=1) AS j_impl,".
                      $str_reas);
          $this->db->from('vw_fam_indv');
          $this->db->where($kd_wlq);
          $this->db->where("Kd_mutasi IS NULL");
          $this->db->where("(".$this->arr_ks['pra_s'].")");

          $data1 = $this->db->get();

      $rc = $this->build_rc($data1);      
     
      if(!empty($rc)){  
        foreach ($rc as $key => $value) {          
            $tmp_dt_tb[]=number_format($value,0,',','.'); 
            $arrjml[$key] = $this->fjml_ttl($arrjml,$key,$value,'');            
        }       
      }else{
          for($j=1;$j<30;$j++)
          {
            $tmp_dt_tb[]=0;
          }          
        }      


      $dt_tb[] = $tmp_dt_tb;
      $i++;
    } 

       
    $footer_tb=array();
        $tmp_footer=array();
        $tmp_footer[]='PRA SEJAHTERA';
        foreach ($arrjml as $key=>$value) {          
            $tmp_footer[$key]= number_format($value,0,',','.');          
        } 
        $footer_tb = $tmp_footer;
    return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt8($idkec,$iddesa,$iddusun,$idrt)
    {
      $dt_tb=array();
      $footer_tb=array();
      return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt9($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');

    $u1549 = $this->getrentang(15,49);
    
    $data = $this->get_unit_detail("$kd_wl");

    $this->arrayks();

   
    $dt_tb=array();
    $arrjml = array();
    $i=1;
    foreach($data as $row)
    {
      $tmp_dt_tb=array();
      $tmp_dt_tb[]=$i;
      $tmp_dt_tb[]=$row['no_unit_detail'];
      $tmp_dt_tb[]='KS I';
      
      $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
      ((($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');  

      $this->db->select("SUM( Kd_fammbrtyp=1) AS j_rt,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_gen=1) AS j_lk_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_gen=2) AS j_pr_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_emp!=6) AS j_krj_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_emp=6) AS j_tkrj_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_martl=2) AS j_kwn_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_martl!=2) AS j_tkwn_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_edu=2) AS j_ttmt_sd_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  (Kd_edu=3 OR  Kd_edu=5)) AS j_tmt_sd_sltp_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_edu=7) AS j_tmt_slta_kk,".
                      "SUM( Kd_fammbrtyp=1 AND  Kd_edu=9) AS j_tmt_ak_pt_kk,".
                      "SUM( Bantuan_modal='1' AND  Kd_fammbrtyp=1) AS j_btm_kk,".
                      "SUM( Bantuan_modal='0' AND  Kd_fammbrtyp=1) AS j_tbtm_kk,".
                      "SUM( Kd_gen=1) AS j_lk_jiwa,".
                      "SUM( Kd_gen=2) AS j_pr_jiwa,".
                      "SUM( Kd_gen=2 AND ($u1549)) AS j_wus");
          $this->db->from('vw_fam_indv');
          $this->db->where($kd_wlq);
          $this->db->where("Kd_mutasi IS NULL");
          $this->db->where("(".$this->arr_ks['ks_1'].")");

          $data1 = $this->db->get();

      $rc = $this->build_rc($data1);
      
      $tmp_dt_tb[]=$this->frupiah($rc,'j_rt');
      $arrjml['jml10'] = $this->fjml_ttl($arrjml,'jml10',$rc,'j_rt');
      

      $j=1;
      if(!empty($rc)){  
        foreach ($rc as $key => $value) {
          if($j>1 and $j<=15){
            $tmp_dt_tb[]=number_format($value,0,',','.'); 
            $arrjml[$key] = $this->fjml_ttl($arrjml,$key,$value,'');
          }      

          $j++;
        }
        $tmp_dt_tb[]=$this->frupiah($rc['j_lk_jiwa']+$rc['j_pr_jiwa'],'');
        $arrjml['jml11'] = $this->fjml_ttl($arrjml,'jml11',$rc['j_lk_jiwa']+$rc['j_pr_jiwa'],'');
        $tmp_dt_tb[]=$this->frupiah($rc,'j_wus');
        $arrjml['jml12'] = $this->fjml_ttl($arrjml,'jml12',$rc,'j_wus');
      }else{
          for($j=1;$j<17;$j++)
          {
            $tmp_dt_tb[]=0;
          }
          
        } 
      $dt_tb[] = $tmp_dt_tb;
      $i++;
    } 

       
    $footer_tb=array();
        $tmp_footer=array();
        $tmp_footer[]='KS I';
        foreach ($arrjml as $key=>$value) {          
            $tmp_footer[$key]= number_format($value,0,',','.');          
        } 

        $footer_tb = $tmp_footer;
    return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt10($idkec,$iddesa,$iddusun,$idrt)
    {
      $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');    
    
    $data = $this->get_unit_detail("$kd_wl");

    $this->arrayks();
        
        $u01 = $this->getrentang(0,1);
        $u15 = $this->getrentang(1,4);
        $u56 = $this->getrentang(5,6);
        $u715 = $this->getrentang(7,15);
        
        $u1621 = $this->getrentang(16,21);
        $u2259 = $this->getrentang(22,59);
        $u60 = $this->getrentang(60);

        $u020 = $this->getrentang(0,19);
        $u2029 = $this->getrentang(20,29);
        $u3049 = $this->getrentang(30,49);

        $tmp_data = $this->get_non_acptr_reas('');
        
        $str_reas = '';
        $str_reas_pra_s = '';
        $str_reas_ks = '';

        if(!empty($tmp_data))
        {
          foreach ($tmp_data as $rc_reas) {         
            $str_reas.= (!empty($str_reas) ? ',' : '')."SUM(Kd_nonacptr=".$rc_reas['Kd_nonacptr']." AND Kd_fammbrtyp=1) AS j_reas_".$rc_reas['Kd_nonacptr'];
          } 
        }

    $dt_tb=array();
    $arrjml = array();
    $i=1;
    foreach($data as $row)
    {
      $tmp_dt_tb=array();
      $tmp_dt_tb[]=$i;
      $tmp_dt_tb[]=$row['no_unit_detail'];
      $tmp_dt_tb[]='KS I';
      
      $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
      ((($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
      ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');  

       $this->db->select("SUM(($u01) AND  Posyandu='1') AS j_blt_0_1_pos,".
                      "SUM(($u01) AND  Posyandu='0') AS j_blt_0_1_tpos,".
                      "SUM(($u15) AND  Posyandu='1') AS j_blt_1_5_pos,".
                      "SUM(($u15) AND  Posyandu='0') AS j_blt_1_5_tpos,".
                      "SUM($u56) AS j_5_6_th,".                                   
                      "SUM(($u715) AND  Kd_edu!=0 AND  Kd_gen=1) AS j_lk_sklh_7_15,".
                      "SUM(($u715) AND  Kd_edu!=0 AND  Kd_gen=2) AS j_pr_sklh_7_15,".
                      "SUM(($u715) AND  Kd_edu=0 AND  Kd_gen=1) AS j_lk_tsklh_7_15,".
                      "SUM(($u715) AND  Kd_edu=0 AND  Kd_gen=2) AS j_pr_tsklh_7_15,".
                      "SUM($u1621) AS j_16_21_th,".
                      "SUM($u2259) AS j_22_59_th,".
                      "SUM($u60) AS j_60_th,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=1) AS j_pus,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=2 AND ($u020)) AS j_20_th_pus,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=2 AND ($u2029)) AS j_20_29_th_pus,".
                      "SUM( pus='1' AND  Kd_fammbrtyp=2 AND ($u3049)) AS j_30_49_th_pus,".
                      "SUM( Kd_consrc=1 AND  Kd_fammbrtyp=1) AS j_pmt_kb,".
                      "SUM( Kd_consrc=2 AND  Kd_fammbrtyp=1) AS j_swst_kb,".
                      "SUM(( Kd_implan=0 AND  Kd_implan IS NOT NULL AND  Kd_contyp=4 AND  Kd_consrc IS NOT NULL AND  Kd_consrc!=0) AND  Kd_fammbrtyp=1) AS j_impl,".
                      $str_reas);
          $this->db->from('vw_fam_indv');
          $this->db->where($kd_wlq);
          $this->db->where("Kd_mutasi IS NULL");
          $this->db->where("(".$this->arr_ks['ks_1'].")");

          $data1 = $this->db->get();



      $rc = $this->build_rc($data1);

      
     
      if(!empty($rc)){  
        foreach ($rc as $key => $value) {          
            $tmp_dt_tb[]=number_format($value,0,',','.'); 
            $arrjml[$key] = $this->fjml_ttl($arrjml,$key,$value,'');            
        }       
      }else{
          for($j=1;$j<30;$j++)
          {
            $tmp_dt_tb[]=0;
          }          
        }      


      $dt_tb[] = $tmp_dt_tb;
      $i++;
    } 

       
    $footer_tb=array();
        $tmp_footer=array();
        $tmp_footer[]='KS I';
        foreach ($arrjml as $key=>$value) {          
            $tmp_footer[$key]= number_format($value,0,',','.');          
        } 
        $footer_tb = $tmp_footer;
    return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt11($idkec,$iddesa,$iddusun,$idrt)
    {

    }
}