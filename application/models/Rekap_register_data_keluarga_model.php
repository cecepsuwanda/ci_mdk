<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap_register_data_keluarga_model extends CI_Model {

    private $arr_ks;
    private $arr_ks_b;
    private $arr_ks_bb;

    private function get_contr_src($where)
    {
      $this->db->select('*');
      $this->db->from('dbo_contr_src');
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

    private function get_indikator_ks($where)
    {
      $this->db->select('*');
      $this->db->from('dbo_indikator_ks');
      if(!empty($where)){ 
        $this->db->where($where);
      }
      $this->db->order_by('id_ind_ks_idk,no_ind_ks');
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
           $op=$jml[$idx_jml];
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

    public function jns_rpt1($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');
       
        $u1549 = $this->getrentang(15,49);
        $u01 = $this->getrentang(0,1);
        $u15 = $this->getrentang(1,5);
        $u56 = $this->getrentang(5,6);
        $u712 = $this->getrentang(7,12);
        $u1315 = $this->getrentang(13,15);
        $u1621 = $this->getrentang(16,21);
        $u2259 = $this->getrentang(22,59);
        $u60 = $this->getrentang(60);

        $u020 = $this->getrentang(0,20);
        $u2029 = $this->getrentang(20,29);
        $u3049 = $this->getrentang(30,49);
       
        
        $dt_contr_src = $this->get_contr_src('');

       $str='';
       if(!empty($dt_contr_src))
       {
          foreach ($dt_contr_src as $row) {
            $str.=  (!empty($str) ? ',' : '')."if((pus='1' and Kd_nonacptr is null and  Kd_consrc=$row[Kd_consrc]),singkatan,'') as consrc$row[Kd_consrc]";
          }
       }

       $str.=",if((pus='1' and Kd_nonacptr is null and a.Kd_contyp=4),1,0) as consrc";
          
        
        $data_non = $this->get_non_acptr_reas('');
        
        $str1='';
        if(!empty($data_non))
        {
            foreach ($data_non as $row) {
              $str1.=  (!empty($str1) ? ',' : '')."if(Kd_nonacptr=$row[Kd_nonacptr],1,0) as nonacptr$row[Kd_nonacptr]"; 
            }
        }

        $this->db->select('kd_fam,
                          Nama,
                          IF(kd_gen=1,1,0) AS jkl,
                          IF(kd_gen=2,1,0) AS jkp,
                          IF(kd_emp<>6,1,0) AS krj,
                          IF(kd_emp=6,1,0) AS tkrj,
                          IF(kd_martl=2,1,0) AS stat,
                          IF(kd_martl<>2,1,0) AS tsat,
                          IF(kd_edu=2,1,0)AS edu1,
                          IF((kd_edu=3 OR kd_edu=5),1,0) AS edu2,
                          IF(kd_edu=7,1,0) AS edu3,
                          IF(kd_edu=9,1,0) AS edu4,
                            Kd_prosplvl');
          $this->db->from('vw_fam_indv');
          $this->db->group_by('kd_fam');
          $this->db->order_by('kd_fam'); 
          $this->db->where($kd_wl);
          $this->db->where('kd_fammbrtyp=1');
          $this->db->where('kd_mutasi IS NULL');

          $data = $this->db->get(); 

          
  
        $tmp_dt_tb=array();
        $arrjml = array();
        if(!empty($data))
        {
           $nombrs=1;
           foreach ($data->result_array() as $row) {
            $tmp=array();
            $tmp[] = $nombrs;
            $Kd_prosplvl = $row['Kd_prosplvl'];
            $i=1;               
            foreach ($row as $key => $value) {
              if($i>2 and $i<13)
              {
                $tmp[] = $value==1 ? 'V' : '';
                
                $arrjml['row1'][$key] = $this->fjml_ttl($arrjml,array('row1',$key),$value==1 ? 1 : 0,'');

                if($Kd_prosplvl==1 or $Kd_prosplvl==6){
                    $arrjml['row2'][$key] = $this->fjml_ttl($arrjml,array('row2',$key),$value==1 ? 1 : 0,'');
                }elseif($Kd_prosplvl==2 or $Kd_prosplvl==7)
                {
                    $arrjml['row3'][$key] = $this->fjml_ttl($arrjml,array('row3',$key),$value==1 ? 1 : 0,'');  
                }
                  $arrjml['row2'][$key] = $this->fjml_ttl($arrjml,array('row2',$key),0,'');
                  $arrjml['row3'][$key] = $this->fjml_ttl($arrjml,array('row3',$key),0,'');                 
                

              } else{
                  if($key=='nama'){
                    $tmp[] = $value==''  ? 'TIDAK ADA NAMA' : $value;
                  }elseif($i<13){                       
                     $tmp[] = $value;
                  }
                }
              
               $i++;
            }

            $this->db->select("IF(Bantuan_modal='1',1,0) AS modal1,
                            IF(Bantuan_modal='0',1,0) AS modal0, 
                            SUM(kd_gen=1) AS jl,
                            SUM(kd_gen=2) AS jp,
                            sum((kd_gen=2) and ($u1549)) AS usbr,
                            0 as one,0 as two,
                            sum((posyandu=1) and ($u01)) as u01pos,
                            sum((posyandu=0) and ($u01)) as u01npos,
                            sum((posyandu=1) and ($u15)) as u15pos,
                            sum((posyandu=0) and ($u15)) as u15npos,
                            sum($u56) as u56,
                            sum((kd_edu<>0) and (kd_gen=1) and ($u712)) as u712edul,
                            sum((kd_edu<>0) and (kd_gen=2) and ($u712)) as u712edup,
                            sum((kd_edu=0) and (kd_gen=1) and ($u712)) as u712nonedul,
                            sum((kd_edu=0) and (kd_gen=2) and ($u712)) as u712nonedup,
                            sum((kd_edu<>0) and (kd_gen=1) and ($u1315)) as u1315edul,
                            sum((kd_edu<>0) and (kd_gen=2) and ($u1315)) as u1315edup,
                            sum((kd_edu=0) and (kd_gen=1) and ($u1315)) as u1315nonedul,
                            sum((kd_edu=0) and (kd_gen=2) and ($u1315)) as u1315nonedup,
                            sum($u1621) as u1621,
                            sum($u2259) as u2259,
                            sum($u60) as u60,
                            0 as three");
              $this->db->from('vw_fam_indv');
              $this->db->group_by('kd_fam');
              $this->db->where($kd_wl);
              $this->db->where("kd_fam=$row[Kd_fam]");
              $this->db->where('kd_mutasi IS NULL');
              $data1 = $this->db->get();

              


              if(!empty($data1))
              {
                foreach ($data1->result_array() as $row1) {
                   $j=1;
                   foreach ($row1 as $key1 => $value1) {
                              if($j>2){
                                 $tmp[] = $value1==0 ? '' : $value1;
                                 $arrjml['row1'][$key1] = $this->fjml_ttl($arrjml,array('row1',$key1),$value1,'');
                                
                                 if($Kd_prosplvl==1 or $Kd_prosplvl==6){
                                     $arrjml['row2'][$key1] = $this->fjml_ttl($arrjml,array('row2',$key1),$value1,'');
                                 }elseif($Kd_prosplvl==2 or $Kd_prosplvl==7)
                                 {
                                     $arrjml['row3'][$key1] = $this->fjml_ttl($arrjml,array('row3',$key1),$value1,'');  
                                 }
                                   $arrjml['row2'][$key1] = $this->fjml_ttl($arrjml,array('row2',$key1),0,'');
                                   $arrjml['row3'][$key1] = $this->fjml_ttl($arrjml,array('row3',$key1),0,'');                  
                                 

                              } else{
                                 $tmp[] = $value1==1 ? 'V' : ''; 
                                 $arrjml['row1'][$key1] = $this->fjml_ttl($arrjml,array('row1',$key1),$value1==1 ? 1 : 0,'');

                                 if($Kd_prosplvl==1 or $Kd_prosplvl==6){
                                       $arrjml['row2'][$key1] = $this->fjml_ttl($arrjml,array('row2',$key1),$value1==1 ? 1 : 0,'');
                                 }elseif($Kd_prosplvl==2 or $Kd_prosplvl==7)
                                 {
                                       $arrjml['row3'][$key1] = $this->fjml_ttl($arrjml,array('row3',$key1),$value1==1 ? 1 : 0,'');  
                                 }
                                   $arrjml['row2'][$key1] = $this->fjml_ttl($arrjml,array('row2',$key1),0,'');
                                   $arrjml['row3'][$key1] = $this->fjml_ttl($arrjml,array('row3',$key1),0,'');                  
                                    
                              } 
                              $j++;         
                            }           
                   
                }
              }

            

          $this->db->select("nama, if($u020,YEAR(NOW())-YEAR(Tgl_lahir),'') as u1,
                                     if($u2029,YEAR(NOW())-YEAR(Tgl_lahir),'') as u2,
                                     if($u3049,YEAR(NOW())-YEAR(Tgl_lahir),'') as u3,
                                     $str,$str1");
          $this->db->from('vw_fam_indv a left join dbo_contr_typ b on a.Kd_contyp=b.Kd_contyp');
          $this->db->group_by('kd_fam');
          $this->db->order_by('kd_fam'); 
          $this->db->where($kd_wl);
          $this->db->where('Kd_fammbrtyp=2');
          $this->db->where("kd_fam=$row[Kd_fam]");
          $this->db->where('kd_mutasi IS NULL');

          $data2 = $this->db->get();

            
              
            if(!empty($data2))
              {
                foreach ($data2->result_array() as $row2) {
                   $j=1;
                   foreach ($row2 as $key2 => $value2) {
                              if($key2=='nama'){
                                     $tmp[] = $value2==''  ? 'TIDAK ADA NAMA' : $value2;
                                     $arrjml['row1'][$key2]='';
                                     $arrjml['row2'][$key2]='';
                                     $arrjml['row3'][$key2]='';
                              }elseif($j>1 && $j<=6 )
                              {
                                 $tmp[] = $value2;
                                 $arrjml['row1'][$key2] = $this->fjml_ttl($arrjml,array('row1',$key2),$value2!='' ? 1 : 0,'');

                                 if($Kd_prosplvl==1 or $Kd_prosplvl==6){
                                        $arrjml['row2'][$key2] = $this->fjml_ttl($arrjml,array('row2',$key2),$value2!='' ? 1 : 0,'');
                                 }elseif($Kd_prosplvl==2 or $Kd_prosplvl==7)
                                 {
                                        $arrjml['row3'][$key2] = $this->fjml_ttl($arrjml,array('row3',$key2),$value2!='' ? 1 : 0,'');  
                                 }
                                   $arrjml['row2'][$key2] = $this->fjml_ttl($arrjml,array('row2',$key2),0,'');
                                   $arrjml['row3'][$key2] = $this->fjml_ttl($arrjml,array('row3',$key2),0,'');                  
                                 


                              } else
                              {
                                 $tmp[] = $value2==1 ? 'V' : '';  
                                 $arrjml['row1'][$key2] = $this->fjml_ttl($arrjml,array('row1',$key2),$value2==1 ? 1 : 0,'');

                                 if($Kd_prosplvl==1 or $Kd_prosplvl==6){
                                     $arrjml['row2'][$key2] = $this->fjml_ttl($arrjml,array('row2',$key2),$value2==1 ? 1 : 0,'');
                                 }elseif($Kd_prosplvl==2 or $Kd_prosplvl==7)
                                 {
                                     $arrjml['row3'][$key2] = $this->fjml_ttl($arrjml,array('row3',$key2),$value2==1 ? 1 : 0,'');  
                                 }
                                   $arrjml['row2'][$key2] = $this->fjml_ttl($arrjml,array('row2',$key2),0,'');
                                   $arrjml['row3'][$key2] = $this->fjml_ttl($arrjml,array('row3',$key2),0,'');                  
                                  
                              }         
    
                              $j++;         
                            }           
                   
                }
              }else{
                 for($j=1;$j<=11;$j++)
                 {
                    $tmp[]='';
                 }



              }


            $tmp_dt_tb[]=$tmp;
            $nombrs++;
           }
        }
        $footer_tb=array();
        $tmp_footer=array();

        foreach ($arrjml as $key=>$value) {
          foreach ($value as $key1 => $value1) {
            $tmp_footer[$key][$key1]=  is_float($value1)  ? number_format($value1,0,',','.') : $value1;
          }
                 
        } 

        $footer_tb = $tmp_footer;

              

        return array('isi_tb'=>$tmp_dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt2($idkec,$iddesa,$iddusun,$idrt)
    {
        $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'','');
        
        $this->arrayks();
        
        $dt = $this->get_indikator_ks('id_ind_ks>=6 and id_ind_ks<=36');  
        
        $ind_ks=array();
        if(!empty($dt))
        {
          foreach ($dt as $row) {
            $ind_ks[]=$row['id_ind_ks'];
          }
        }  
       
        $selct_txt='';        
        foreach($this->arr_ks as $key=>$value)
        {
          $selct_txt .= (!empty($selct_txt) ? ',':''). "if($value,1,0) as ks_$key";            
        }  


          $this->db->select("a.kd_fam,a.Kd_prosplvl,CONCAT('array(',GROUP_CONCAT(id_ind_ks,'=>','".'"'."',R_prosPinStat,'".'"'."'),')') AS array_ks_val,$selct_txt");
          $this->db->from('((dbo_family a INNER JOIN dbo_fam_ind_detail b ON a.kd_fam=b.kd_fam) LEFT JOIN dbo_prosp_ind_stat c ON c.kd_prospinstat= b.kd_prospinstat)');
          $this->db->group_by('a.kd_fam');
          $this->db->order_by('a.kd_fam'); 
          $this->db->where($kd_wl);
          $data = $this->db->get();          
        
        $tmp_dt_tb=array();
        $arrjml = array();
        $i=1;
        if(!empty($data))
        {
           foreach ($data->result_array() as $row) {
             $tmp=array();
             $tmp[] = $i;
             $tmp[] = $row['kd_fam'];

             eval("\$array_ks_val = $row[array_ks_val];");               

             foreach ($ind_ks as $row1) {
               $tmp[] = isset($array_ks_val[$row1]) ? $array_ks_val[$row1] : '';               
               $arrjml['row2']['V'.$row1] = $this->fjml_ttl($arrjml,array('row2','V'.$row1),
                                                      isset($array_ks_val[$row1]) ? $array_ks_val[$row1]=='V ' : 0,'');
               $arrjml['row3']['X'.$row1] = $this->fjml_ttl($arrjml,array('row3','X'.$row1),
                                                      isset($array_ks_val[$row1]) ? $array_ks_val[$row1]=='X ' : 0,'');
               $arrjml['row4']['-'.$row1] = $this->fjml_ttl($arrjml,array('row4','-'.$row1),
                                                      isset($array_ks_val[$row1]) ? $array_ks_val[$row1]=='- ' : 0,'');
               $arrjml['row5']['X*'.$row1] = $this->fjml_ttl($arrjml,array('row5','X*'.$row1),
                                                      isset($array_ks_val[$row1]) ? $array_ks_val[$row1]=='X*' : 0,'');
               $arrjml['row6']['praks'.$row1] = $this->fjml_ttl($arrjml,array('row6','praks'.$row1),
                                                      isset($array_ks_val[$row1]) ? (($row['Kd_prosplvl']==1 or $row['Kd_prosplvl']==6) and ($array_ks_val[$row1]=='X ' or  $array_ks_val[$row1]=='X*')) : 0,'');
               $arrjml['row7']['ks'.$row1] = $this->fjml_ttl($arrjml,array('row7','ks'.$row1),
                                                      isset($array_ks_val[$row1]) ? (($row['Kd_prosplvl']==2 or $row['Kd_prosplvl']==7) and ($array_ks_val[$row1]=='X ' or  $array_ks_val[$row1]=='X*')) : 0,'');
             }

             foreach($this->arr_ks as $key=>$value)
             {
               $txt ="ks_$key";
               $tmp[] = $row[$txt] == 1 ? 'V' : '';
               $arrjml['row1'][$txt] = $this->fjml_ttl($arrjml,array('row1',$txt),$row[$txt] == 1 ? 1 : 0,'');             
             }

             $tmp_dt_tb[]=$tmp;
             $i++;
            
           }
        }

        $footer_tb=array();
        $tmp_footer=array();

        foreach ($arrjml as $key=>$value) {
          foreach ($value as $key1 => $value1) {
            $tmp_footer[$key][$key1]= number_format($value1,0,',','.');
          }
        } 

        $footer_tb = $tmp_footer;

        return array('isi_tb'=>$tmp_dt_tb,'footer_tb'=>$footer_tb);
    }

    public function jns_rpt3($idkec,$iddesa,$iddusun,$idrt)
    {
       $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt,'idv','');
        
      
          $this->db->from('dbo_individu idv 
                       LEFT JOIN dbo_edu_lvl edu ON idv.Kd_edu=edu.Kd_edu
                           LEFT JOIN dbo_empmnt_stat emp ON idv.Kd_emp=emp.Kd_emp
                   LEFT JOIN dbo_gender gen ON idv.Kd_gen=gen.Kd_gen
                   LEFT JOIN dbo_martl_stat ms ON idv.Kd_martl=ms.Kd_martl
                   LEFT JOIN dbo_mutasi mts ON idv.Kd_mutasi=mts.Kd_mutasi
                   LEFT JOIN dbo_fam_mbr_typ fmt ON idv.Kd_fammbrtyp=fmt.Kd_fammbrtyp');
          $this->db->order_by('idv.kd_fam,idv.Kd_mutasi,idv.Kd_fammbrtyp'); 
          $this->db->where($kd_wl);
          $data = $this->db->get();  
        
        $tmp_dt_db=array();
        if(!empty($data))
        {
           foreach ($data->result_array() as $row) {
            $tgl=explode("-",$row['Tgl_lahir']);
            $tmp_dt_db[$row['Kd_fam']]['jml'] = isset($tmp_dt_db[$row['Kd_fam']]) ? $tmp_dt_db[$row['Kd_fam']]['jml']+1 : 1; 
            $tmp_dt_db[$row['Kd_fam']]['data'][]=array($row['Kd_fam'],
                                                     $row['Kd_indv'],
                                                     $row['Nama'],
                                                     $row['Nm_fammbrtyp_ind'],
                                                     $row['Nm_gen_ind'],
                                                     $tgl[2]."/".$tgl[1]."/".$tgl[0],
                                                     $row['Nm_emp_ind'],
                                                     $row['Nm_edu_ind'],
                                                     $row['Nm_martl_ind'],
                                                     $row['Nm_mutasi']);
           }
        }

        return $tmp_dt_db;
    }

    public function jns_rpt4($idkec,$iddesa,$iddusun,$idrt)
    {
         $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt);
            
         $this->db->select("Kd_fam,Nama,
              (SELECT p.Nm_prosplvl_ind FROM dbo_prosp_lvl p WHERE f.Kd_prosplvl=p.Kd_prosplvl) AS prosp,
              (SELECT ii.Nama FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=2 AND ii.Kd_mutasi IS NULL ORDER BY ii.Tgl_lahir LIMIT 0,1) AS istr,
              (SELECT ii.Kd_indv FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=2 AND ii.Kd_mutasi IS NULL ORDER BY ii.Tgl_lahir LIMIT 0,1) AS id_istr,
              (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Tgl_lahir)),'%Y')+0 FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=2 AND ii.Kd_mutasi IS NULL ORDER BY ii.Tgl_lahir LIMIT 0,1) AS istr_u,
              (SELECT COUNT(*) FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=3 AND ii.Kd_mutasi IS NULL) AS ank,
              (SELECT MIN(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Tgl_lahir)),'%Y')+0) FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=3 AND ii.Kd_mutasi IS NULL GROUP BY ii.Kd_fam) AS ank_kecil,
              (SELECT ii.Kd_indv FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=3 AND ii.Kd_mutasi IS NULL ORDER BY ii.Tgl_lahir DESC LIMIT 0,1) AS id_ank_kecil ");
          $this->db->from('vw_fam_indv f');          
          $this->db->order_by('Kd_fam'); 
          $this->db->where("$kd_wl Kd_contyp IS NOT NULL AND 
                                   pus='1' AND 
                                   Kd_martl=2 AND 
                                   Kd_fammbrtyp=1 AND 
                                   Kd_mutasi IS NULL AND 
                                   (SELECT COUNT(*) AS j_istr FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=2 AND ii.Kd_mutasi IS NULL HAVING j_istr > 0) ");
          $data = $this->db->get();         



        $tmp_dt_db=array();
        if(!empty($data))
        {
           foreach ($data->result_array() as $row) {
              $tmp = array(($row['istr']!='') ? $row['istr'] : 'TIDAK ADA NAMA',
                           ($row['nama']!='')  ? $row['nama'] : 'TIDAK ADA NAMA',
                            $row['istr_u'],($row['ank'] > 0) ? $row['ank'] : '-',
                           (isset($row['ank_kecil'])) ? $row['ank_kecil'] : '-',
                           $row['prosp']);
              $tmp_dt_db['data'][]=$tmp;
           }
        }

        return $tmp_dt_db;
    }

    public function jns_rpt5($idkec,$iddesa,$iddusun,$idrt)
    {
         $kd_wl=$this->getkdwl($idkec,$iddesa,$iddusun,$idrt);
        
        $tb = new tb_gen('tbgen');    
    $tb->sql_from= 'vw_fam_indv f';
    $tb->sql_select = "Kd_fam,Nama,
              (SELECT p.Nm_prosplvl_ind FROM dbo_prosp_lvl p WHERE f.Kd_prosplvl=p.Kd_prosplvl) AS prosp,
              (SELECT ii.Nama FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=2 AND ii.Kd_mutasi IS NULL ORDER BY ii.Tgl_lahir LIMIT 0,1) AS istr,
              (SELECT ii.Kd_indv FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=2 AND ii.Kd_mutasi IS NULL ORDER BY ii.Tgl_lahir LIMIT 0,1) AS id_istr,
              (SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Tgl_lahir)),'%Y')+0 FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=2 AND ii.Kd_mutasi IS NULL ORDER BY ii.Tgl_lahir LIMIT 0,1) AS istr_u,
              (SELECT COUNT(*) FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=3 AND ii.Kd_mutasi IS NULL) AS ank,
              (SELECT MIN(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Tgl_lahir)),'%Y')+0) FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=3 AND ii.Kd_mutasi IS NULL GROUP BY ii.Kd_fam) AS ank_kecil,
              (SELECT ii.Kd_indv FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=3 AND ii.Kd_mutasi IS NULL ORDER BY ii.Tgl_lahir DESC LIMIT 0,1) AS id_ank_kecil ";
        $tb->sql_orderby = 'Kd_fam';
        $data = $tb->getData("$kd_wl (Kd_prosplvl=1 OR Kd_prosplvl=2 OR Kd_prosplvl=6 OR Kd_prosplvl=7) AND 
                                    Kd_contyp IS NOT NULL AND 
                                    pus='1' AND 
                                    Kd_martl=2 AND Kd_fammbrtyp=1 AND Kd_mutasi IS NULL AND 
                                    (SELECT COUNT(*) AS j_istr FROM dbo_individu ii WHERE f.Kd_fam=ii.Kd_fam AND ii.Kd_fammbrtyp=2 AND ii.Kd_mutasi IS NULL HAVING j_istr > 0)");
        
        $tmp_dt_db=array();
        if(!empty($data))
        {
           foreach ($data as $row) {
              $tmp = array(($row['istr']!='') ? $row['istr'] : 'TIDAK ADA NAMA',
                           ($row['nama']!='')  ? $row['nama'] : 'TIDAK ADA NAMA',
                            $row['istr_u'],($row['ank'] > 0) ? $row['ank'] : '-',
                           (isset($row['ank_kecil'])) ? $row['ank_kecil'] : '-',
                           $row['prosp']);
              $tmp_dt_db['data'][]=$tmp;
           }
        }

        return $tmp_dt_db;
    }


}


