 
 <?php
                      $pelaporan = new pelaporan;
                      
                      
                      $nm_kecamatan = $this->Unit_detail_model->getnm("id_unit_detail='$idkec'");
                      $nm_kelurahan = $this->Unit_detail_model->getnm("id_unit_detail='$iddesa'");

                      
                                $sub = array(
                                             array('MENGIKUTI POSYANDU',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('TIDAK MENGIKUTI POSYANDU',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('MENGIKUTI POSYANDU',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('TIDAK MENGIKUTI POSYANDU',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('SEKOLAH',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('TIDAK SEKOLAH',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('SEKOLAH',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('TIDAK SEKOLAH',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('NAMA',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KELOMPOK UMUR',array('colspan'=>'3','bgcolor'=>'#99CCFF'))                           
                                             );
                                            
                      
                      $dt_contr_src = $this->Contr_src_model->getData('');

                      if(!empty($dt_contr_src))
                      {
                        foreach ($dt_contr_src as $row) {
                          $sub[]=array(strtoupper($row['Nm_consrc_ind']),array('rowspan'=>'2','bgcolor'=>'#99CCFF'));
                        }
                      }

                      $sub[]=array('PESERTA KB YANG IMPLANNYA AKAN DICABUT TAHUN DEPAN',array('rowspan'=>'2','bgcolor'=>'#99CCFF'));

                      
                      $sub = $this->Non_acptr_reas_model->getnm('',0,$sub,array('rowspan'=>'2','bgcolor'=>'#99CCFF')); 


                      $nocol = $pelaporan->build_nocol(48);

                      $jdltbl =  array(
                                        array(
                                             array('NO URUT RUMAH TANGGA',array('rowspan'=>'5','bgcolor'=>'#99CCFF')),
                                             array('NO URUT KEPALA KELUARGA',array('rowspan'=>'5','bgcolor'=>'#99CCFF')),
                                             array('KELUARGA',array('colspan'=>'46','bgcolor'=>'#99CCFF'))
                                             ),
                                        array(
                                             array('NAMA KEPALA KELUARGA',array('rowspan'=>'4','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT JENIS KELAMIN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT STATUS PEKERJAAN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT STATUS PERKAWINAN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT TINGKAT PENDIDIKAN',array('colspan'=>'4','bgcolor'=>'#99CCFF')),
                                             array('KELUARGA MENDAPATKAN KREDIT MIKRO/BANTUAN MODAL',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH JIWA DALAM KELUARGA',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH WANITA USIA SUBUR (15-49) TAHUN',array('rowspan'=>'4','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH KEMATIAN 1 TAHUN TERAKHIR',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH ANGGOTA KELUARGA MENURUT KELOMPOK UMUR',array('colspan'=>'16','bgcolor'=>'#99CCFF')),
                                             array('PASANGAN USIA SUBUR',array('colspan'=>'12','bgcolor'=>'#99CCFF'))
                                             ),
                                       array(
                                             array('LAKI-LAKI',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('PEREMPUAN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('BEKERJA',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('TIDAK BEKERJA',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('KAWIN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('DUDA/JANDA/BELUM KAWIN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('TIDAK TAMAT SD',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('TAMAT SD - SLTP',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('TAMAT SLTA',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('TAMAT AK/PT',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('YA',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('TIDAK',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('LAKI-LAKI',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('PEREMPUAN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('IBU HAMIL/MELAHIRKAN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('UMUR < 1',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('BAYI < 1 TAHUN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('BALITA 1 - < 5 TAHUN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('5 - 6 TAHUN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('7 - 12 TAHUN',array('colspan'=>'4','bgcolor'=>'#99CCFF')),
                                             array('13 - 15 TAHUN',array('colspan'=>'4','bgcolor'=>'#99CCFF')),
                                             array('16 - 21 TAHUN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('22 - 59 TAHUN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('60 TAHUN KEATAS',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('NO URUT PUS',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('ISTERI',array('colspan'=>'4','bgcolor'=>'#99CCFF')),
                                             array('PESERTA KB',array('colspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('BUKAN PESERTA KB',array('colspan'=>'4','bgcolor'=>'#99CCFF')),
                                             ),
                                       $sub,
                                       array(
                                             array('LAKI-LAKI',array('bgcolor'=>'#99CCFF')),
                                             array('PEREMPUAN',array('bgcolor'=>'#99CCFF')),
                                             array('LAKI-LAKI',array('bgcolor'=>'#99CCFF')),
                                             array('PEREMPUAN',array('bgcolor'=>'#99CCFF')),
                                             array('LAKI-LAKI',array('bgcolor'=>'#99CCFF')),
                                             array('PEREMPUAN',array('bgcolor'=>'#99CCFF')),
                                             array('LAKI-LAKI',array('bgcolor'=>'#99CCFF')),
                                             array('PEREMPUAN',array('bgcolor'=>'#99CCFF')),
                                             array('< 20 TAHUN',array('bgcolor'=>'#99CCFF')),
                                             array('20 - 29 TAHUN',array('bgcolor'=>'#99CCFF')),
                                             array('30 - 49 TAHUN',array('bgcolor'=>'#99CCFF'))
                                             ),
                                       $nocol
                                       );
                      
                      $html_tab1 = "<center><bold>REGISTER PENDATAAN KELUARGA</bold><br><br></center>";
                      
                      if(empty($iddusun)){
                       $data_dusun = $this->Unit_detail_model->getdata("id_unit=13 AND id_unit_detail_idk='$iddesa'");;    
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
                      
                                $html_tab1 .='<center>'.$tb_judul->display('tbjudul').'</center><br><br>';
                                $html_tab1 .='A. DATA DEMOGRAFI DAN KB<br>';

                                 $tmp_dt_tb = $this->Rekap_register_data_keluarga_model->$jns_rpt($idkec,$iddesa,$row['id_unit_detail'],$row1['id_unit_detail']); 

                                 

                                 
                                 $txt = '<tr>';
                                 $txt .= "<th colspan='2' bgcolor='#000000'></th><th>JUMLAH</th>";
                                 if(isset($tmp_dt_tb['footer_tb']['row1'])){ 
                                 $j=1;               
                                 foreach ($tmp_dt_tb['footer_tb']['row1'] as $key => $value) {
                                    if($j==35){
                                      $txt .= "<th align='center' bgcolor='#000000' ></th>";
                                    }else{ 
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                    $j++;
                                  }
                                 }

                                 $txt .= '</tr>';
                                 $txt .= '<tr>';
                                 $txt .= "<th colspan='48'><B>KELUARGA PRA SEJAHTERA DAN ANGGOTA KELUARGA</B></th>";
                                 $txt .= '</tr>';
                                 $txt .= '<tr>';
                                 $txt .= "<th colspan='2' bgcolor='#000000'></th><th>JUMLAH</th>";
                                 if(isset($tmp_dt_tb['footer_tb']['row2'])){                
                                 $j=1;
                                 foreach ($tmp_dt_tb['footer_tb']['row2'] as $key => $value) {
                                    if($j==35){
                                      $txt .= "<th align='center' bgcolor='#000000' ></th>";
                                    }else{ 
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                    $j++;
                                  }
                                 }                            
                                 $txt .= '</tr>';
                                 $txt .= '<tr>';
                                 $txt .= "<th colspan='48'><B>KELUARGA SEJAHTERA I DAN ANGGOTA KELUARGA</B></th>";
                                 $txt .= '</tr>';
                                 $txt .= '<tr>';
                                 $txt .= "<th colspan='2'bgcolor='#000000'></th><th>JUMLAH</th>";
                                 if(isset($tmp_dt_tb['footer_tb']['row3'])){                
                                 $j=1;
                                 foreach ($tmp_dt_tb['footer_tb']['row3'] as $key => $value) {
                                    if($j==35){
                                      $txt .= "<th align='center' bgcolor='#000000' ></th>";
                                    }else{ 
                                      $txt .= "<th align='center'>$value</th>";
                                    }
                                    $j++;
                                  }
                                 }
                                 $txt .= '</tr>';
                      
                                 $tmp_dt_tb['footer_tb']=array();

                                 $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                                 $dt_tb = $tmp['dt_tb'];
                                                             

                                 $tb_tab1 = new mytable(array('id'=>'tbhslfilter'.$i++,'width'=>'100%','border'=>'2','cellpadding'=>'8','cellspacing'=>'0','style'=>'border-collapse: collapse'),
                                                            $jdltbl,
                                                            $dt_tb,$txt);

                                 $html_tab1 .= $tb_tab1->display('tbhslfilter').'<br><br>';

                               }
                             }
                         }
                      }

                      echo $html_tab1;
 ?>              
         