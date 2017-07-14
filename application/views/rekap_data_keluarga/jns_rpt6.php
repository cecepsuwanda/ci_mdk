 
 <?php
                      $pelaporan = new pelaporan;
                      
                      
                     $html_tab6="<center><bold>REKAPITULASI HASIL PEMUTAKHIRAN DATA KELUARGA PRA SEJAHTERA TINGKAT ".$pelaporan->getjudul($idkec,$iddesa,$iddusun,$idrt)."</bold><br><br></center>"; 
   
                        
                     $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                     $dt_tb = $tmp['dt_tb'];
                     $txt = $tmp['footer_tb'];


                     $jdl=$pelaporan->getjdltab($idkec,$iddesa,$iddusun,$idrt);               
                     $tb_judul = new mytable(array('id'=>'tbjudul','width'=>'600px'),
                                                  array(),$jdl,'');    
                      
                     $html_tab6 .='<center>'.$tb_judul->display('').'</center><br><br>';

                      $jnocol = 20;

                      $nocol = $pelaporan->build_nocol($jnocol);

                      $jdltbl =  array(
                                        array(
                                             array('NO URUT',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array($pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt),array('rowspan'=>'3','bgcolor'=>'#99CCFF')),                           
                                             array('STATUS KELUARGA PRA SEJAHTERA',array('rowspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH KEPALA KELUARGA',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KELUARGA',array('colspan'=>'16','bgcolor'=>'#99CCFF'))
                                             ),
                                        array(                          
                                             array('KEPALA KELUARGA MENURUT JENIS KELAMIN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT STATUS PEKERJAAN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT STATUS PERKAWINAN',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('KEPALA KELUARGA MENURUT TINGKAT PENDIDIKAN',array('colspan'=>'4','bgcolor'=>'#99CCFF')),
                                             array('KELUARGA MENDAPATKAN KREDIT MIKRO/BANTUAN MODAL',array('colspan'=>'2','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH JIWA DALAM KELUARGA',array('colspan'=>'3','bgcolor'=>'#99CCFF')),
                                             array('JUMLAH WANITA USIA SUBUR (15-49) TAHUN',array('rowspan'=>'2','bgcolor'=>'#99CCFF'))
                                             ),
                                       array(
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
                                  
                                  


                                  $tb_tab6 = new mytable(array('id'=>'tbtab6','width'=>'100%','border'=>'2','cellpadding'=>'8','cellspacing'=>'0','style'=>'border-collapse: collapse'),
                                                            $jdltbl,
                                                            $dt_tb,$txt);

                                 $html_tab6 .= $tb_tab6->display('table table-bordered table-hover').'<br><br>';

                     echo $html_tab6;    
 ?>              
         