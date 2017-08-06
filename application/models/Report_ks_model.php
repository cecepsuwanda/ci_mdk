<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_ks_model extends CI_Model {

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


	public function report_ks($idkec,$iddesa,$iddusun,$idrt)
    {
       if(empty($idrt)){
		 $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');
        }else{
         $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'',''); 	
        }
		
		$this->arrayks();

		$arrjml = array();
        if(empty($idrt)){		             
		  $data = $this->get_unit_detail("$kd_wl");	 	                         
		}else{
          
		  $this->db->select("kd_fam,COUNT(*) AS jml_jiwa,Kd_prosplvl");
          $this->db->from('vw_fam_indv');
          $this->db->group_by('kd_fam');
          $this->db->order_by('kd_fam'); 
          $this->db->where($kd_wl);
          $this->db->where("(".$this->arr_ks['pra_s']." OR ".$this->arr_ks['ks_1'].")");
          $this->db->where('kd_mutasi IS NULL');

          $data = $this->db->get();
          $data =$data->result_array(); 
		  
		  $arrjml['jml_jiwa']=0;
		  $arrjml['pra']=0;
		  $arrjml['ks']=0;
		}

		$dt_tb=array();
		$i=1;
		
		$jml_pra=1;
		$jml_ks=1;

        if(!empty($data)){
			foreach($data as $row)
			{
				$tmp_dt_tb=array();			

				if(empty($idrt))
				{
				  $tmp_dt_tb[]=$i;
				  $tmp_dt_tb[]=$row['no_unit_detail'];    
				

				  $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
				  ( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
				  ( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
				  ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');          
				
				  
				  $this->db->select("SUM(Kd_fammbrtyp=1) AS j_kk,
				                   COUNT(*) AS j_jiwa,
				                   SUM(Kd_fammbrtyp=1 AND (".$this->arr_ks['pra_s'].")) AS j_pra_s,
				                   SUM(Kd_fammbrtyp=1 AND (".$this->arr_ks['ks_1'].")) AS j_ks");
		          $this->db->from('vw_fam_indv');
		          $this->db->where($kd_wlq);
		          $this->db->where("(".$this->arr_ks['pra_s']." OR ".$this->arr_ks['ks_1'].")");
		          $this->db->where('kd_mutasi IS NULL');

		          $data1 = $this->db->get();

				  $rc = $this->build_rc($data1);
	            
	              $tmp_dt_tb[]=number_format($rc['j_kk'],0,',','.');
	              $arrjml['j_kk']=$this->fjml_ttl($arrjml,'j_kk',$rc,'j_kk');

	              $tmp_dt_tb[]=number_format($rc['j_jiwa'],0,',','.');
	              $arrjml['j_jiwa']=$this->fjml_ttl($arrjml,'j_jiwa',$rc,'j_jiwa');
	            
	              $tmp_dt_tb[]=number_format($rc['j_pra_s'],0,',','.');
	              $arrjml['j_pra_s']=$this->fjml_ttl($arrjml,'j_pra_s',$rc,'j_pra_s');            
	            
	              $tmp_dt_tb[]=number_format($rc['j_ks'],0,',','.');
	              $arrjml['j_ks']=$this->fjml_ttl($arrjml,'j_ks',$rc,'j_ks'); 
	            } else{
	            	$tmp_dt_tb[]=$row['Kd_fam'];
	                  

	              $this->db->select('Nama');
		          $this->db->from('dbo_individu');
		          $this->db->where("Kd_fam='$row[Kd_fam]'");
		          $this->db->where("Kd_fammbrtyp=1");
		          
		          $data1 = $this->db->get();			
				  
				    $rc = $this->build_rc($data1);     

				    $tmp_dt_tb[]=$rc['Nama'];
				    $tmp_dt_tb[]=$row['jml_jiwa'];
				    $arrjml['jml_jiwa']=$this->fjml_ttl($arrjml,'jml_jiwa',$row,'jml_jiwa');
				    
				    if(($row['Kd_prosplvl']==1) or ($row['Kd_prosplvl']==6)){
				      $tmp_dt_tb[]='V';
				      $arrjml['pra'] = $this->fjml_ttl($arrjml,'pra',$jml_pra,'');
				    }else{
				      $tmp_dt_tb[]=' ';
				    }

				    if(($row['Kd_prosplvl']==2) or ($row['Kd_prosplvl']==7)){
				      $tmp_dt_tb[]='V';
				      $arrjml['ks'] = $this->fjml_ttl($arrjml,'ks',$jml_ks,'');
				    }else{
				      $tmp_dt_tb[]=' ';
				    } 
	            }           

				$dt_tb[] = $tmp_dt_tb;
				$i++;
			}	
		}
		
		$footer_tb=array();
		$tmp_footer=array();       

		foreach ($arrjml as $key=>$value) {
			$tmp_footer[]= number_format($value,0,',','.');     
		}           

		$footer_tb[] = $tmp_footer;          
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

    public function report_ks_alek($idkec,$iddesa,$iddusun,$idrt)
    {
      if(empty($idrt)){
		 $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');
        }else{
         $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'',''); 	
        }
		
		$this->arrayks();

		$arrjml = array();
        if(empty($idrt)){
		  $data = $this->get_unit_detail("$kd_wl");                           
		}else{
          

          $this->db->select("kd_fam,COUNT(*) AS jml_jiwa,Kd_prosplvl");
          $this->db->from('vw_fam_indv');
          $this->db->group_by('kd_fam');
          $this->db->order_by('kd_fam'); 
          $this->db->where($kd_wl);
          $this->db->where("(".$this->arr_ks['pra_s']." OR ".$this->arr_ks['ks_1'].")");
          $this->db->where('kd_mutasi IS NULL');

          $data = $this->db->get();
          $data =$data->result_array();  

		  
		  $arrjml['jml_jiwa']=0;
		  $arrjml['pra_alek']=0;
		  $arrjml['pra_non_alek']=0;
		  $arrjml['ks_alek']=0;
		  $arrjml['ks_non_alek']=0;
		}

		$dt_tb=array();
		$i=1;
		
		$jml_pra_alek=1;
		$jml_pra_non_alek=1;
		$jml_ks_alek=1;
		$jml_ks_non_alek=1;

        if(!empty($data)){
			foreach($data as $row)
			{
				$tmp_dt_tb=array();			

				if(empty($idrt))
				{
				  $tmp_dt_tb[]=$i;
				  $tmp_dt_tb[]=$row['no_unit_detail'];    
				

				  $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
				  ( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
				  ( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
				  ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');          
				
				  
                  $this->db->select("SUM(Kd_fammbrtyp=1) AS j_kk,
		                     COUNT(*) AS j_jiwa,
		                     SUM(Kd_fammbrtyp=1 AND Kd_prosplvl=6) AS j_pra_s_alek,
		                     SUM(Kd_fammbrtyp=1 AND Kd_prosplvl=1) AS j_pra_s_non_alek,
		                     SUM(Kd_fammbrtyp=1 AND Kd_prosplvl=7) AS j_ks_alek,
		                     SUM(Kd_fammbrtyp=1 AND Kd_prosplvl=2) AS j_ks_non_alek ");
		          $this->db->from('vw_fam_indv');
		          $this->db->where($kd_wlq);
		          $this->db->where("(".$this->arr_ks['pra_s']." OR ".$this->arr_ks['ks_1'].")");
		          $this->db->where('kd_mutasi IS NULL');

		          $data1 = $this->db->get();
          		  $rc = $this->build_rc($data1);
	            
	              $tmp_dt_tb[]=$this->frupiah($rc,'j_kk');
	              $arrjml['j_kk']=$this->fjml_ttl($arrjml,'j_kk',$rc,'j_kk');

	              $tmp_dt_tb[]=$this->frupiah($rc,'j_jiwa');
	              $arrjml['j_jiwa']=$this->fjml_ttl($arrjml,'j_jiwa',$rc,'j_jiwa');
	            
	              $tmp_dt_tb[]=$this->frupiah($rc,'j_pra_s_alek');
	              $arrjml['j_pra_s_alek']=$this->fjml_ttl($arrjml,'j_pra_s_alek',$rc,'j_pra_s_alek'); 

	              $tmp_dt_tb[]=$this->frupiah($rc,'j_pra_s_non_alek');
	              $arrjml['j_pra_s_non_alek']=$this->fjml_ttl($arrjml,'j_pra_s_non_alek',$rc,'j_pra_s_non_alek');            
	            
	              $tmp_dt_tb[]=$this->frupiah($rc,'j_ks_alek');
	              $arrjml['j_ks_alek']=$this->fjml_ttl($arrjml,'j_ks_alek',$rc,'j_ks_alek');

	              $tmp_dt_tb[]=$this->frupiah($rc,'j_ks_non_alek');
	              $arrjml['j_ks_non_alek']=$this->fjml_ttl($arrjml,'j_ks_non_alek',$rc,'j_ks_non_alek');

	            } else{
	            	$tmp_dt_tb[]=$row['Kd_fam'];

	            	$this->db->select('Nama');
		            $this->db->from('dbo_individu');
		            $this->db->where("Kd_fam='$row[Kd_fam]'");
		            $this->db->where("Kd_fammbrtyp=1");
		          
		            $data1 = $this->db->get();
			        $rc = $this->build_rc($data1);     

				    $tmp_dt_tb[]=$rc['Nama'];
				    $tmp_dt_tb[]=$row['jml_jiwa'];
				    $arrjml['jml_jiwa']=$this->fjml_ttl($arrjml,'jml_jiwa',$row,'jml_jiwa');
				    
				    if(($row['Kd_prosplvl']==6)){
				      $tmp_dt_tb[]='V';
				      $arrjml['pra_alek'] = $this->fjml_ttl($arrjml,'pra_alek',$jml_pra_alek,'');
				    }else{
				      $tmp_dt_tb[]=' ';
				    }

				    if(($row['Kd_prosplvl']==1)){
				      $tmp_dt_tb[]='V';
				      $arrjml['pra_non_alek'] = $this->fjml_ttl($arrjml,'pra_non_alek',$jml_pra_non_alek,'');
				    }else{
				      $tmp_dt_tb[]=' ';
				    }


				    if(($row['Kd_prosplvl']==7)){
				      $tmp_dt_tb[]='V';
				      $arrjml['ks_alek'] = $this->fjml_ttl($arrjml,'ks_alek',$jml_ks_alek,'');
				    }else{
				      $tmp_dt_tb[]=' ';
				    } 

				    if(($row['Kd_prosplvl']==2)){
				      $tmp_dt_tb[]='V';
				      $arrjml['ks_non_alek'] = $this->fjml_ttl($arrjml,'ks_non_alek',$jml_ks_non_alek,'');
				    }else{
				      $tmp_dt_tb[]=' ';
				    } 



	            }           

				$dt_tb[] = $tmp_dt_tb;
				$i++;
			}	
		}
		
		$footer_tb=array();
		$tmp_footer=array();       

		foreach ($arrjml as $key=>$value) {
			$tmp_footer[]= number_format($value,0,',','.');     
		}           

		$footer_tb[] = $tmp_footer;          
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb);     
    }

    public function report_ks_kb($idkec,$iddesa,$iddusun,$idrt,$kb)
    {
       if(empty($idrt)){
		 $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','','id_unit_detail_idk');
        }else{
         $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'',''); 	
        }
		
		$str_kb = "Kd_nonacptr IS ".($kb==1 ? 'NULL' : 'NOT NULL')." AND pus='1'";

		$this->arrayks();

		$arrjml = array();
        if(empty($idrt)){
		   $data = $this->get_unit_detail("$kd_wl");                         
		}else{
         
          $this->db->select("kd_fam,COUNT(*) AS jml_jiwa,Kd_prosplvl");
          $this->db->from('vw_fam_indv');
          $this->db->group_by('kd_fam');
          $this->db->order_by('kd_fam'); 
          $this->db->where($kd_wl);
          $this->db->where("($str_kb) AND (".$this->arr_ks['pra_s']." OR ".$this->arr_ks['ks_1'].")");
          $this->db->where('kd_mutasi IS NULL');

          $data = $this->db->get();
          $data =$data->result_array();

		 
		  $arrjml['jml_jiwa']=0;
		  $arrjml['pra']=0;
		  $arrjml['ks']=0;
		}

		$dt_tb=array();
		$i=1;
		
		$jml_pra=1;
		$jml_ks=1;

        if(!empty($data)){
			foreach($data as $row)
			{
				$tmp_dt_tb=array();			

				if(empty($idrt))
				{
				  $tmp_dt_tb[]=$i;
				  $tmp_dt_tb[]=$row['no_unit_detail'];    
				

				  $kd_wlq=$this->getkdwl(($idkec==0 ? $row['id_unit_detail'] : $idkec ),
				  ( (($idkec!=0) and ($iddesa == 0)) ? $row['id_unit_detail'] : $iddesa),
				  ( (($idkec!=0) and ($iddesa != 0) and ($iddusun==0)) ? $row['id_unit_detail']  : $iddusun),
				  ((($idkec!=0) and ($iddesa != 0) and ($iddusun!=0) and ($idrt==0)) ? $row['id_unit_detail']  : $idrt),'','');          
				
				  $this->db->select("SUM(Kd_fammbrtyp=1) AS j_kk,
		                   COUNT(*) AS j_jiwa,
		                   SUM(Kd_fammbrtyp=1 AND (".$this->arr_ks['pra_s'].")) AS j_pra_s,
		                   SUM(Kd_fammbrtyp=1 AND (".$this->arr_ks['ks_1'].")) AS j_ks");
		          $this->db->from('vw_fam_indv');
		          $this->db->where($kd_wlq);
		          $this->db->where("($str_kb) AND (".$this->arr_ks['pra_s']." OR ".$this->arr_ks['ks_1'].")");
		          $this->db->where('kd_mutasi IS NULL');
                  
	 	  		  $data1 = $this->db->get();	
				  $rc = $this->build_rc($data1);
	            
	              $tmp_dt_tb[]=$this->frupiah($rc,'j_kk');
	              $arrjml['j_kk']=$this->fjml_ttl($arrjml,'j_kk',$rc,'j_kk');

	              $tmp_dt_tb[]=$this->frupiah($rc,'j_jiwa');
	              $arrjml['j_jiwa']=$this->fjml_ttl($arrjml,'j_jiwa',$rc,'j_jiwa');
	            
	              $tmp_dt_tb[]=$this->frupiah($rc,'j_pra_s');
	              $arrjml['j_pra_s']=$this->fjml_ttl($arrjml,'j_pra_s',$rc,'j_pra_s');            
	            
	              $tmp_dt_tb[]=$this->frupiah($rc,'j_ks');
	              $arrjml['j_ks']=$this->fjml_ttl($arrjml,'j_ks',$rc,'j_ks'); 
	            } else{
	            	$tmp_dt_tb[]=$row['Kd_fam'];
	               
	                $this->db->select('Nama');
		            $this->db->from('dbo_individu');
		            $this->db->where("Kd_fam='$row[Kd_fam]'");
		            $this->db->where("Kd_fammbrtyp=1");
		          
		            $data1 = $this->db->get();
				    $rc = $this->build_rc($data1);     

				    $tmp_dt_tb[]=$rc['Nama'];
				    $tmp_dt_tb[]=$row['jml_jiwa'];
				    $arrjml['jml_jiwa']=$this->fjml_ttl($arrjml,'jml_jiwa',$row,'jml_jiwa');
				    
				    if(($row['Kd_prosplvl']==1) or ($row['Kd_prosplvl']==6)){
				      $tmp_dt_tb[]='V';
				      $arrjml['pra'] = $this->fjml_ttl($arrjml,'pra',$jml_pra,'');
				    }else{
				      $tmp_dt_tb[]=' ';
				    }

				    if(($row['Kd_prosplvl']==2) or ($row['Kd_prosplvl']==7)){
				      $tmp_dt_tb[]='V';
				      $arrjml['ks'] = $this->fjml_ttl($arrjml,'ks',$jml_ks,'');
				    }else{
				      $tmp_dt_tb[]=' ';
				    } 
	            }           

				$dt_tb[] = $tmp_dt_tb;
				$i++;
			}	
		}
		
		$footer_tb=array();
		$tmp_footer=array();       

		foreach ($arrjml as $key=>$value) {
			$tmp_footer[]= number_format($value,0,',','.');     
		}           

		$footer_tb[] = $tmp_footer;          
		
		return array('isi_tb'=>$dt_tb,'footer_tb'=>$footer_tb); 
    }

}