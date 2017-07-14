 
 <?php
                       $pelaporan = new pelaporan;
                       
                      
                                      $txt = '<tr>';
                               if(isset($tmp_dt_tb['footer_tb']['row1'])){ 
                                 foreach ($tmp_dt_tb['footer_tb']['row1'] as $key => $value) {                   
                                    $txt .= "<th align='center'>$value</th>";                  
                                }
                               }

                               $txt .= '</tr>';
                               $txt .= '<tr>';
                               $txt .= "<th colspan='30'><B>KELUARGA PRA SEJAHTERA DAN ANGGOTA KELUARGA</B></th>";
                               $txt .= '</tr>';
                               $txt .= '<tr>';
                               if(isset($tmp_dt_tb['footer_tb']['row2'])){             
                               foreach ($tmp_dt_tb['footer_tb']['row2'] as $key => $value) {
                                     $txt .= "<th align='center'>$value</th>"; 
                                }
                               }
                               $txt .="<th colspan='6' bgcolor='#000000'></th>";                           
                               $txt .= '</tr>';
                               $txt .= '<tr>';
                               $txt .= "<th colspan='30'><B>KELUARGA SEJAHTERA I DAN ANGGOTA KELUARGA</B></th>";
                               $txt .= '</tr>';
                               $txt .= '<tr>';
                               if(isset($tmp_dt_tb['footer_tb']['row3'])){                
                               
                               foreach ($tmp_dt_tb['footer_tb']['row3'] as $key => $value) {
                                   
                                    $txt .= "<th align='center'>$value</th>";
                                  
                                }
                               }
                               $txt.="<th colspan='6' bgcolor='#000000'></th>";
                               $txt.='</tr>';

                   $tmp_dt_tb['footer_tb']=array();
                   $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                   $dt_tb = $tmp['dt_tb'];
                   //$txt = $tmp['footer_tb'];

                   $html_tab2='';

                          $sub = array(
                                           array('IKUT',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                           array('TIDAK IKUT',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                           array('IKUT',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                           array('TIDAK IKUT',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                           array('SEKOLAH',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                           array('TIDAK SEKOLAH',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                           array('PASANGAN USIA SUBUR',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
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


                    $nocol = $pelaporan->build_nocol(51,23);

                    $jdltbl =  array(
                                      array(                           
                                           array('KELUARGA',array('colspan'=>'23','bgcolor'=>'#99CCFF')),
                                           array('HASIL PENTAHAPAN KELUARGA SEJAHTERA',array('colspan'=>'6','bgcolor'=>'#99CCFF'))
                                           ),
                                      array(                           
                                           array('JUMLAH JIWA KELOMPOK UMUR',array('colspan'=>'16','bgcolor'=>'#99CCFF')),
                                           array('PASANGAN USIA SUBUR',array('colspan'=>'7','bgcolor'=>'#99CCFF')),
                                           array('KELUARGA PRA SEJAHTERA',array('rowspan'=>'4','bgcolor'=>'#99CCFF')),
                                           array('KELUARGA SEJAHTERA I',array('rowspan'=>'4','bgcolor'=>'#99CCFF')),
                                           array('KELUARGA SEJAHTERA II',array('rowspan'=>'4','bgcolor'=>'#99CCFF')),
                                           array('KELUARGA SEJAHTERA III',array('rowspan'=>'4','bgcolor'=>'#99CCFF')),
                                           array('KELUARGA SEJAHTERA III PLUS',array('rowspan'=>'4','bgcolor'=>'#99CCFF')),
                                           array('JUMLAH',array('rowspan'=>'4','bgcolor'=>'#99CCFF'))

                                           ),
                                     array(                           
                                           array('BAYI 0 < 1 TAHUN MENGIKUTI KEGIATAN POSYANDU',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                           array('BALITA 1 - < 5 TAHUN  MENGIKUTI KEGIATAN POSYANDU',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                           array('5 - 6 TAHUN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                           array('7 - 15 TAHUN',array('colspan'=>'4','bgcolor'=>'#99CCFF')),                           
                                           array('16 - 21 TAHUN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                           array('22 - 59 TAHUN',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                           array('60 TAHUN KEATAS',array('rowspan'=>'3','bgcolor'=>'#99CCFF')), 
                                           array('JUMLAH',array('colspan'=>'4','bgcolor'=>'#99CCFF')),                          
                                           array('PESERTA KB',array('colspan'=>'3','bgcolor'=>'#99CCFF')),
                                           array('BUKAN PESERTA KB',array('colspan'=>'4','bgcolor'=>'#99CCFF')),
                                           ),
                                     $sub,
                                     array(
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
                               

                               $tb_tab2 = new mytable(array('id'=>'tbhslfilter','width'=>'100%','border'=>'2','cellpadding'=>'8','cellspacing'=>'0','style'=>'border-collapse: collapse'),
                                                          $jdltbl,
                                                          $dt_tb,$txt);

                               $html_tab2 .= $tb_tab2->display('table table-bordered table-hover').'<br><br>';
                 
                   echo $html_tab2;  
 ?>              
         