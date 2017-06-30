 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $dt_unit = new dt_unit_detail;
                        $nm_kecamatan = $dt_unit->getnm("id_unit_detail='$idkec'");
                        $nm_kelurahan = $dt_unit->getnm("id_unit_detail='$iddesa'"); 

                        $nocol = $this->build_nocol(19);

                            $jdltbl =  array(
                                          array(
                                               array('NO',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                               array('PASANGAN USIA SUBUR',array('colspan'=>'6','bgcolor'=>'#99CCFF')),
                                               array('HASIL PEMBINAAN KESERTAAN BER KB',array('colspan'=>'12','bgcolor'=>'#99CCFF'))
                                               ),
                                          array(
                                               array('NAMA',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('UMUR ISTRI',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('JUMLAH ANAK',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('UMUR ANAK TERKECIL',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('TAHAPAN KS',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('JANUARI',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('FEBRUARI',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('MARET',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('APRIL',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('MEI',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('JUNI',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('JULI',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('AGUSTUS',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('SEPTEMBER',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('OKTOBER',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('NOVEMBER',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                               array('DESEMBER',array('rowspan'=>'2','bgcolor'=>'#99CCFF'))
                                               ),
                                         array(
                                               array('ISTRI',array('bgcolor'=>'#99CCFF')),
                                               array('SUAMI',array('bgcolor'=>'#99CCFF')),
                                               ),
                                         $nocol
                                         );
                        
                        
                        
                        if(empty($iddusun)){
                         $data_dusun = $dt_unit->getdusun($iddesa);    
                        }else{      
                          $data_dusun = $dt_unit->getdata("id_unit=13 AND id_unit_detail_idk='$iddesa' AND id_unit_detail='$iddusun'");
                        }    
                        
                        $html_tab5='';
                        $i=1;
                        if(!empty($data_dusun))
                        {
                          $dt_report = new dt_report;
                          foreach($data_dusun as $row)
                           {  
                              $nmdusun = $row['no_unit_detail'];
                              if(empty($idrt))
                              {   
                                 $data_rt = $dt_unit->getrt($row['id_unit_detail']);
                              }else{
                                 $data_rt = $dt_unit->getdata("id_unit=14 AND id_unit_detail_idk='$row[id_unit_detail]' and id_unit_detail='$idrt'"); 
                              }

                               if(!empty($data_rt))
                               {
                                 foreach ($data_rt as $row1) {
                                             
                                  $html_tab5 .= "<center><bold>REGISTER KELOMPOK KB BAGI KELUARGA PRA SEJAHTERA DAN KELUARGA SEJAHTERA I </bold><br><br></center>";
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
                        
                                  $html_tab5 .='<center>'.$tb_judul->display('').'</center><br><br>';
                                  

                                   $tmp_dt_tb = $dt_report->getdtmenu02_tab5($idkec,$iddesa,isset($row['id_unit_detail']) ? $row['id_unit_detail'] :'',isset($row1['id_unit_detail']) ? $row1['id_unit_detail'] : ''); 
                                   //$tmp = $this->build_dt_tb($tmp_dt_tb);
                                   $dt_tb = null;//$tmp['dt_tb'];
                                   $txt = '';//$tmp['footer_tb']; 

                                   if(!empty($tmp_dt_tb['data']))
                                   {
                                     
                                     $j=1;
                                     foreach ($tmp_dt_tb['data'] as $row2) {
                                       $tmp = array();
                                       $tmp[]=array($j++,array());  
                                       
                                       foreach ($row2 as $row3) {
                                        $tmp[]=array($row3,array('align'=>'center'));
                                       }

                                       for($k=1;$k<=12;$k++) {
                                            $tmp[]=array('',array('align'=>'center'));
                                          }


                                       $dt_tb[]=$tmp;
                                          
                                     }
                                   }

                                   unset($tmp_dt_tb);

                                   $klmjdl=array();
                                   $klmjdl[1]=array(array('1. Jumlah Pasangan Usia Subur (PUS)',array('align'=>'left','colspan'=>'2')));
                                   $klmjdl[2]=array(array('2. Jumlah Peserta KB Aktif',array('align'=>'left','colspan'=>'2')));
                                   $klmjdl[3]=array(array('ALKON',array('align'=>'center')),array('KODE',array('align'=>'center')));
                                   
                                   $i=4;
                                   $dt = new dt_contr_typ;
                                   $data = $dt->getdata('');

                                   foreach ($data as $row) {
                                     $klmjdl[$i][]=array(chr(ord('a')+($i-4)).'. '.$row['Nm_contyp_ind'],array('align'=>'left','style'=>'border-right-style: none;'));
                                     $klmjdl[$i][]=array('('.$row['singkatan'].')',array('align'=>'center','style'=>'border-left-style: none;'));

                                     $i++;
                                   }

                                   $klmjdl[$i++]=array(array('3. Jumlah Peserta KB Aktif Menurut Tempat Pelayanan',array('align'=>'left','colspan'=>'2')));
                                   
                                   $tb = new tb_gen('dbo_contr_src');
                                   $data = $tb->getData('');
                                   
                                   foreach ($data as $row) {
                                     $klmjdl[$i][]=array($row['Nm_consrc_ind'],array('align'=>'left','colspan'=>'2'));
                                     $i++;
                                   }

                                   $klmjdl[$i++]=array(array('4. Jumlah Pasangan Usia Subur Bukan Peserta KB',array('align'=>'left','colspan'=>'2')));
                                   
                                   $klmjdl[$i][]=array('a. Hamil',array('align'=>'left','style'=>'border-right-style: none;'));
                                   $klmjdl[$i++][]=array('(H)',array('align'=>'center','style'=>'border-left-style: none;'));

                                   $klmjdl[$i][]=array('b. Ingin Anak Segera',array('align'=>'left','style'=>'border-right-style: none;'));
                                   $klmjdl[$i++][]=array('(IAS)',array('align'=>'center','style'=>'border-left-style: none;'));
                     
                                   $klmjdl[$i][]=array('c. Ingin Anak Ditunda',array('align'=>'left','style'=>'border-right-style: none;'));
                                   $klmjdl[$i++][]=array('(IAT)',array('align'=>'center','style'=>'border-left-style: none;'));
                                   
                                   $klmjdl[$i][]=array('d. Tidak Ingin Anak Lagi',array('align'=>'left','style'=>'border-right-style: none;'));
                                   $klmjdl[$i++][]=array('(TIAL)',array('align'=>'center','style'=>'border-left-style: none;'));

                                   $klmjdl[$i++]=array(array('<I>PARAF KETUA KELOMPOK KB</I>',array('align'=>'center','colspan'=>'3')));
                                   $klmjdl[$i++]=array(array('<I>PARAF Sub PPKBD</I>',array('align'=>'center','colspan'=>'3')));

                                   $txt='';
                                   for($i=1;$i<=21;$i++){  
                                     $txt .='<tr>';
                                       if($i==1){
                                        $txt .='<th rowspan="19" bgcolor="#333333" ></th>';
                                       }
                                     
                                       foreach($klmjdl[$i] as $row){                      
                                            $txt .='<th';
                                            foreach ($row[1] as $key => $value) {
                                              $txt .=" $key='$value'";
                                            }
                                            $txt .='>'.$row[0].'</th>';
                                       }
                                     

                                     $txt .='<th colspan="4" bgcolor="#333333" ></th>';
                                          for($k=1;$k<=12;$k++) {
                                            $txt .='<th></th>';
                                          }                     

                                     $txt .='</tr>';
                                    }
                        
                                   $tb_tab5 = new mytable(array('id'=>'tbtab5'.$i++,'width'=>'100%','border'=>'2','cellpadding'=>'8','cellspacing'=>'0','style'=>'border-collapse: collapse'),
                                                              $jdltbl,
                                                              $dt_tb,$txt);

                                   $html_tab5 .= $tb_tab5->display().'<br><br>';

                                 }
                               }
                           }
                        }

                        return $html_tab5;
 ?>              
         