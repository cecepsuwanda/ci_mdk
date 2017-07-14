 
 <?php
                       $pelaporan = new pelaporan;
                       
                      
                      $nm_kecamatan = $this->Unit_detail_model->getnm("id_unit_detail='$idkec'");
                      $nm_kelurahan = $this->Unit_detail_model->getnm("id_unit_detail='$iddesa'"); 

                      
                      $nm = $this->Indikator_ks_model->getnm('id_ind_ks>=6 and id_ind_ks<=36',0,0,0,array('bgcolor'=>'#99CCFF'));
                      $jml = $this->Indikator_ks_model->getnumrows();

                      $nm[]=array('KELUARGA PRA SEJAHTERA',array('bgcolor'=>'#99CCFF'));
                      $nm[]=array('KELUARGA SEJAHTERA I',array('bgcolor'=>'#99CCFF'));
                      $nm[]=array('KELUARGA SEJAHTERA II',array('bgcolor'=>'#99CCFF'));
                      $nm[]=array('KELUARGA SEJAHTERA III',array('bgcolor'=>'#99CCFF'));
                      $nm[]=array('KELUARGA SEJAHTERA III PLUS',array('bgcolor'=>'#99CCFF'));

                      
                      $jdltbl =  array(array(
                                          array('NO URUT RUMAH TANGGA',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                          array('NO URUT KEPALA KELUARGA',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                          array('INDIKATOR KELUARGA SEJAHTERA',array('colspan'=>$jml,'bgcolor'=>'#99CCFF')),
                                          array('TAHAPAN KELUARGA SEJAHTEA',array('colspan'=>'5','bgcolor'=>'#99CCFF'))
                                        ),
                                        $nm                 
                                       );
                      
                      $html_tab2 = "";
                      
                      if(empty($iddusun)){
                       $data_dusun = $this->Unit_detail_model->getdata("id_unit=13 AND id_unit_detail_idk='$iddesa'");   
                      }else{      
                        $data_dusun = $this->Unit_detail_model->getdata("id_unit=13 AND id_unit_detail_idk='$iddesa' AND id_unit_detail='$iddusun'");
                      }    
                      
                      $i=1;
                      if(!empty($data_dusun))
                      {
                        
                        foreach($data_dusun as $row)
                         {  
                            if(empty($idrt))
                            {   
                               $data_rt = $this->Unit_detail_model->getdata("id_unit=14 AND id_unit_detail_idk='$row[id_unit_detail]'"); 
                            }else{
                               $data_rt = $this->Unit_detail_model->getdata("id_unit=14 AND id_unit_detail_idk='$row[id_unit_detail]' and id_unit_detail='$idrt'"); 
                            }
                             if(!empty($data_rt))
                             {
                               foreach ($data_rt as $row1) {
                                
                               
                                $tb_judul = new mytable(array('id'=>'tbjudul','width'=>'300px'),
                                                  array(),
                                                  array(array(array('JUMLAH KELUARGA YANG ADA',array()),array(' : ',array()),array('',array())),
                                                        array(array('RT',array()),array(' : ',array()),array($row1['no_unit_detail'],array())),
                                                        array(array('DUSUN/RW',array()),array(' : ',array()),array($row['no_unit_detail'],array())),
                                                        array(array('DESA/KELURAHAN',array()),array(' : ',array()),array($nm_kelurahan,array())),
                                                        array(array('KECAMATAN',array()),array(' : ',array()),array($nm_kecamatan,array())),
                                                        array(array('KABUPATEN/KOTA',array()),array(' : ',array()),array(' KABUPATEN BANDUNG BARAT',array())),
                                                        array(array('PROVINSI',array()),array(' : ',array()),array(' JAWA BARAT',array())) 
                                                       ),'');    
                      
                                $html_tab2 .='<center>'.$tb_judul->display('tbjudul').'</center><br><br>';
                                $html_tab2 .='B. TAHAPAN KELUARGA SEJAHTERA<br>';

                                 $tmp_dt_tb = $this->Rekap_register_data_keluarga_model->$jns_rpt($idkec,$iddesa,$row['id_unit_detail'],$row1['id_unit_detail']);
                                 
                                 $txt = "<tr><th align='center' bgcolor='#000000' colspan='27' >";
                                 
                                 if(isset($tmp_dt_tb['footer_tb']['row1'])){
                                    foreach ($tmp_dt_tb['footer_tb']['row1'] as $key => $value) {
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                 }     
                                 
                                 $txt .="</tr>";
                                 $txt .= "<tr>
                                             <th align='center' bgcolor='#000000'></th>
                                             <th align='center' >V</th>";
                                 
                                 if(isset($tmp_dt_tb['footer_tb']['row2'])){
                                    foreach ($tmp_dt_tb['footer_tb']['row2'] as $key => $value) {
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                 }
                                 $txt .= "<th align='center' bgcolor='#000000' colspan='5'></th>"; 
                                 $txt .= "</tr>";
                                 $txt .= "<tr>
                                             <th align='center' bgcolor='#000000'></th>
                                             <th align='center' >X</th>";               
                                 if(isset($tmp_dt_tb['footer_tb']['row3'])){
                                    foreach ($tmp_dt_tb['footer_tb']['row3'] as $key => $value) {
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                 }
                                 $txt .= "<th align='center' bgcolor='#000000' colspan='5'></th>"; 
                                 $txt .= "</tr>";
                                 $txt .= "<tr>
                                             <th align='center' bgcolor='#000000'></th>
                                             <th align='center' >-</th>";               
                                 if(isset($tmp_dt_tb['footer_tb']['row4'])){
                                    foreach ($tmp_dt_tb['footer_tb']['row4'] as $key => $value) {
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                 }
                                 $txt .= "<th align='center' bgcolor='#000000' colspan='5'></th>"; 
                                 $txt .= "</tr>";
                                 $txt .= "<tr>
                                             <th align='center' bgcolor='#000000'></th>
                                             <th align='center' >X*</th>";               
                                 if(isset($tmp_dt_tb['footer_tb']['row5'])){
                                    foreach ($tmp_dt_tb['footer_tb']['row5'] as $key => $value) {
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                 }
                                 $txt .= "<th align='center' bgcolor='#000000' colspan='5'></th>"; 
                                 $txt .= "</tr>";
                                 $txt .= "<tr><th align='center' colspan='32'>KELUARGA MENURUT INDIKATOR PRA SEJAHTERA</th></tr>";
                                 $txt .= "<tr>
                                              <th align='center' bgcolor='#000000'></th>
                                              <th align='center' >X & X*</th>";               
                                 if(isset($tmp_dt_tb['footer_tb']['row6'])){
                                    foreach ($tmp_dt_tb['footer_tb']['row6'] as $key => $value) {
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                 }
                                 $txt .= "<th align='center' bgcolor='#000000' colspan='5'></th>"; 
                                 $txt .= "</tr>";
                                 $txt .= "<tr><th align='center' colspan='32'>KELUARGA MENURUT INDIKATOR SEJAHTERA I</th></tr>";
                                 $txt .= "<tr>
                                              <th align='center' bgcolor='#000000'></th>
                                              <th align='center' >X & X*</th>";               
                                 if(isset($tmp_dt_tb['footer_tb']['row7'])){
                                    foreach ($tmp_dt_tb['footer_tb']['row7'] as $key => $value) {
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                 }
                                 $txt .= "<th align='center' bgcolor='#000000' colspan='5'></th>"; 
                                 $txt .= "</tr>";

                      
                                 $tmp_dt_tb['footer_tb']=array();

                                 $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                                 $dt_tb = $tmp['dt_tb'];
                                   
                      
                                 $tb_tab2 = new mytable(array('id'=>'tbhslfilter'.$i++,'width'=>'100%','border'=>'2','cellpadding'=>'8','cellspacing'=>'0','style'=>'border-collapse: collapse'),
                                                            $jdltbl,
                                                            $dt_tb,$txt);

                                 $html_tab2 .= $tb_tab2->display('table table-bordered table-hover').'<br><br>';

                               }
                             }
                         }
                      }

                      echo $html_tab2;
 ?>              
         