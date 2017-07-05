 
 <?php
                       $pelaporan = new pelaporan;
                       
                      
                        $nm_kecamatan = $this->Unit_detail_model->getnm("id_unit_detail='$idkec'");
                        $nm_kelurahan = $this->Unit_detail_model->getnm("id_unit_detail='$iddesa'"); 

                        $jdltbl =  array(
                                          array(
                                               array('NO URUT',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('INDIVIDU ANGGOTA KELUARGA',array('colspan'=>'10','bgcolor'=>'#99CCFF'))
                                               ),
                                          array(
                                               array('NOMOR KODE KELUARGA INDONESIA',array('bgcolor'=>'#99CCFF')),
                                               array('NOMOR KODE ANGGOTA KELUARGA',array('bgcolor'=>'#99CCFF')),
                                               array('NAMA',array('bgcolor'=>'#99CCFF')),
                                               array('HUBUNGAN DENGAN KK',array('bgcolor'=>'#99CCFF')),
                                               array('JENIS KELAMIN',array('bgcolor'=>'#99CCFF')),
                                               array('TANGGAL BULAN DAN TAHUN',array('bgcolor'=>'#99CCFF')),
                                               array('PEKERJAAN',array('bgcolor'=>'#99CCFF')),
                                               array('PENDIDIKAN TERAKHIR',array('bgcolor'=>'#99CCFF')),
                                               array('STATUS PERKAWINAN',array('bgcolor'=>'#99CCFF')),
                                               array('PERUBAHAN MUTASI',array('bgcolor'=>'#99CCFF'))
                                               )
                                         );
                        
                        $html_tab3 = "";
                        
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
                              $nmdusun = $row['no_unit_detail'];
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
                                                          array(array('DUSUN/RW',array()),array(' : ',array()),array($nmdusun,array())),
                                                          array(array('DESA/KELURAHAN',array()),array(' : ',array()),array($nm_kelurahan,array())),
                                                          array(array('KECAMATAN',array()),array(' : ',array()),array($nm_kecamatan,array())),
                                                          array(array('KABUPATEN/KOTA',array()),array(' : ',array()),array(' KABUPATEN BANDUNG BARAT',array())),
                                                          array(array('PROVINSI',array()),array(' : ',array()),array(' JAWA BARAT',array())) 
                                                         ),'');    
                        
                                  $html_tab3 .='<center>'.$tb_judul->display('tbjudul').'</center><br><br>';
                                  $html_tab3 .='C. DATA ANGGOTA KELUARGA<br>';

                                   $tmp_dt_tb = $this->Rekap_register_data_keluarga_model->$jns_rpt($idkec,$iddesa,$row['id_unit_detail'],$row1['id_unit_detail']); 
                                   //$tmp = $this->build_dt_tb($tmp_dt_tb);
                                   $dt_tb = array();//$tmp['dt_tb'];

                                   if(!empty($tmp_dt_tb))
                                   {
                                     $tmp = array();
                                     $i=1;
                                     foreach($tmp_dt_tb as $kdfam=>$row2)
                                     {
                                        
                                        $tmp[]=array($i++,array('rowspan'=>$row2['jml'],'align'=>'center'));                 
                                        
                                        foreach($row2['data'] as $data_row)
                                        {                                            
                                          foreach ($data_row as $row3) {
                                            $tmp[]=array($row3,array('align'=>'center'));
                                          }                    
                                          $dt_tb[]=$tmp;
                                          $tmp = array();                  
                                        }
                                         unset($tmp_dt_tb[$kdfam]['jml']);
                                         unset($tmp_dt_tb[$kdfam]['data']);
                                         unset($tmp_dt_tb[$kdfam]);
                                          
                                     }
                                      unset($tmp_dt_tb);
                                   }               

                                   $txt = '';//$tmp['footer_tb']; 
                        
                        
                                   $tb_tab3 = new mytable(array('id'=>'tbhslfilter'.$i++,'width'=>'100%','border'=>'2','cellpadding'=>'8','cellspacing'=>'0','style'=>'border-collapse: collapse'),
                                                              $jdltbl,
                                                              $dt_tb,$txt);

                                   $html_tab3 .= $tb_tab3->display('tbhslfilter').'<br><br>';

                                 }
                               }
                           }
                        }

                        echo $html_tab3;
 ?>              
         