 
 <?php
                      $pelaporan = new pelaporan;
                      
                      
                      $html_tab1 = "<center><bold>REKAPITULASI HASIL PENDATAAN KELUARGA TINGKAT ".$pelaporan->getjudul($idkec,$iddesa,$iddusun,$idrt)."</bold><br><br></center>"; 
                           
                     
                     $footerspan = 9;
                     $footerspan -= $idkec>0 ? 2 : 0;
                     $footerspan -= $iddesa>0 ? 2 : 0;
                     $footerspan -= $iddusun>0 ? 2 : 0;

                      $jcolspan = 28;
                      $jcolspan -= $idkec>0 ? 2 : 0;
                      $jcolspan -= $iddesa>0 ? 2 : 0;
                      $jcolspan -= $iddusun>0 ? 2 : 0; 

                                 $txt = '<tr>';
                                 $txt .= "<th colspan='2'>JUMLAH</th>";
                                 if(isset($tmp_dt_tb['footer_tb']['row1'])){ 
                                                
                                 foreach ($tmp_dt_tb['footer_tb']['row1'] as $key => $value) {                   
                                      $txt .= "<th align='center'>$value</th>";                  
                                  }
                                 }

                                 $txt .= '</tr>';
                                 $txt .= '<tr>';
                                 $txt .= "<th colspan='$jcolspan'><B>KELUARGA PRA SEJAHTERA DAN ANGGOTA KELUARGA</B></th>";
                                 $txt .= '</tr>';
                                 $txt .= '<tr>';
                                 $txt .= "<th colspan='2'>JUMLAH</th><th colspan='$footerspan' bgcolor='#000000'></th>";
                                 if(isset($tmp_dt_tb['footer_tb']['row2'])){                
                                
                                 foreach ($tmp_dt_tb['footer_tb']['row2'] as $key => $value) {
                                       $txt .= "<th align='center'>$value</th>"; 
                                  }
                                 }                            
                                 $txt .= '</tr>';
                                 $txt .= '<tr>';
                                 $txt .= "<th colspan='$jcolspan'><B>KELUARGA SEJAHTERA I DAN ANGGOTA KELUARGA</B></th>";
                                 $txt .= '</tr>';
                                 $txt .= '<tr>';
                                 $txt .= "<th colspan='2'>JUMLAH</th><th colspan='$footerspan' bgcolor='#000000'></th>";
                                 if(isset($tmp_dt_tb['footer_tb']['row3'])){                
                                 
                                 foreach ($tmp_dt_tb['footer_tb']['row3'] as $key => $value) {
                                     
                                      $txt .= "<th align='center'>$value</th>";
                                    
                                  }
                                 }
                                 $txt .= '</tr>';

                     $tmp_dt_tb['footer_tb']=array();
                     $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                     $dt_tb = $tmp['dt_tb'];
                     //$txt = $tmp['footer_tb'];


                     $jdl=$pelaporan->getjdltab($idkec,$iddesa,$iddusun,$idrt);               
                     $tb_judul = new mytable(array('id'=>'tbjudul','width'=>'600px'),
                                                  array(),$jdl,'');    
                      
                      $html_tab1 .='<center>'.$tb_judul->display('').'</center><br><br>';

                      $jnocol = 28;
                      $jnocol -= $idkec>0 ? 2 : 0;
                      $jnocol -= $iddesa>0 ? 2 : 0;
                      $jnocol -= $iddusun>0 ? 2 : 0;

                      $jcolspan = 6;
                      $jcolspan -= $idkec>0 ? 2 : 0;
                      $jcolspan -= $iddesa>0 ? 2 : 0;
                      $jcolspan -= $iddusun>0 ? 2 : 0;


                      $nocol = $pelaporan->build_nocol($jnocol);

                      $jdltbl =  array(
                                        array(
                                             array('NO URUT',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array($pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt),array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('CAKUPAN WILAYAH',array('colspan'=>$jcolspan,'bgcolor'=>'#99CCFF')),
                                             array('CAKUPAN RUMAH TANGGA DAN KELUARGA',array('colspan'=>'4','bgcolor'=>'#99CCFF')),
                                             array('KELUARGA',array('colspan'=>'16','bgcolor'=>'#99CCFF'))
                                             ),
                                        array(
                                             array('JUMLAH DESA/KELURAHAN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH DUSUN/RW',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH RUKUN TETANGGA',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH RUMAH TANGGA',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH KEPALA KELUARGA',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT JENIS KELAMIN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT STATUS PEKERJAAN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT STATUS PERKAWINAN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT TINGKAT PENDIDIKAN',array('colspan'=>'4','bgcolor'=>'#99CCFF')),
                                             array('KELUARGA MENDAPATKAN KREDIT MIKRO/BANTUAN MODAL',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH JIWA DALAM KELUARGA',array('colspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH WANITA USIA SUBUR (15-49) TAHUN',array('rowspan'=>'2','bgcolor'=>'#99CCFF'))
                                             ),
                                       array(
                                             array('YANG ADA',array('bgcolor'=>'#99CCFF')),
                                             array('YANG DI DATA',array('bgcolor'=>'#99CCFF')),
                                             array('YANG ADA',array('bgcolor'=>'#99CCFF')),
                                             array('YANG DI DATA',array('bgcolor'=>'#99CCFF')),
                                             array('YANG ADA',array('bgcolor'=>'#99CCFF')),
                                             array('YANG DI DATA',array('bgcolor'=>'#99CCFF')),
                                             array('YANG ADA',array('bgcolor'=>'#99CCFF')),
                                             array('YANG DI DATA',array('bgcolor'=>'#99CCFF')),
                                             array('YANG ADA',array('bgcolor'=>'#99CCFF')),
                                             array('YANG DI DATA',array('bgcolor'=>'#99CCFF')),
                                             array('LAKI-LAKI',array('bgcolor'=>'#99CCFF')),
                                             array('PEREMPUAN',array('bgcolor'=>'#99CCFF')),
                                             array('BEKERJA',array('bgcolor'=>'#99CCFF')),
                                             array('TIDAK BEKERJA',array('bgcolor'=>'#99CCFF')),
                                             array('KAWIN',array('bgcolor'=>'#99CCFF')),
                                             array('DUDA/JANDA/BELUM KAWIN',array('bgcolor'=>'#99CCFF')),
                                             array('TIDAK TAMAT SD',array('bgcolor'=>'#99CCFF')),
                                             array('TAMAT SD - SLTP',array('bgcolor'=>'#99CCFF')),
                                             array('TAMAT SLTA',array('bgcolor'=>'#99CCFF')),
                                             array('TAMAT AK/PT',array('bgcolor'=>'#99CCFF')),
                                             array('YA',array('bgcolor'=>'#99CCFF')),
                                             array('TIDAK',array('bgcolor'=>'#99CCFF')),
                                             array('LAKI-LAKI',array('bgcolor'=>'#99CCFF')),
                                             array('PEREMPUAN',array('bgcolor'=>'#99CCFF')),
                                             array('JUMLAH',array('bgcolor'=>'#99CCFF'))                           
                                             ),                     
                                       $nocol
                                       );
                                  
                                  if($idkec>0)
                                  {
                                   unset($jdltbl[1][0]);
                                   unset($jdltbl[2][0]);
                                   unset($jdltbl[2][1]);
                                  }

                                  if($iddesa>0)
                                  {
                                   unset($jdltbl[1][1]);
                                   unset($jdltbl[2][2]);
                                   unset($jdltbl[2][3]);
                                  }

                                  if($iddusun>0)
                                  {
                                   unset($jdltbl[0][2]); 
                                   unset($jdltbl[1][2]);
                                   unset($jdltbl[2][4]);
                                   unset($jdltbl[2][5]);
                                  }

                                  

                                  $tb_tab1 = new mytable(array('id'=>'tbhslfilter','width'=>'100%','border'=>'2','cellpadding'=>'8','cellspacing'=>'0','style'=>'border-collapse: collapse'),
                                                            $jdltbl,
                                                            $dt_tb,$txt);

                                 $html_tab1 .= $tb_tab1->display('table table-bordered table-hover').'<br><br>';
                     
                     echo $html_tab1;     
 ?>              
         