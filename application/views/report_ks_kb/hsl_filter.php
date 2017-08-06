 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>".(empty($idrt) ? 'PUS ' : 'DAFTAR KELUARGA ').($kb==1 ? "KB" : "NON KB").", PRA S DAN KS I</bold><br>$subjudul</center>";
                     
                     $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);

                     
                     
                     $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                     $dt_tb = $tmp['dt_tb'];
                     $txt = $tmp['footer_tb']; 

                     $row_1 = array(array('NO. KODE',array('rowspan'=>'2')),
                                                             array($nmklm,array('rowspan'=>'2')),
                                                             array('JUMLAH KK',array('rowspan'=>'2')),
                                                             array('JUMLAH JIWA',array('rowspan'=>'2')),
                                                             array('TAHAPAN KS',array('colspan'=>'2'))
                                                             );

                     if(!empty($idrt)){
                        $row_1 = array(array('NO. KKI',array('rowspan'=>'2')),
                                      array('KEPALA KELUARGA',array('rowspan'=>'2')),
                                      array('JUMLAH JIWA',array('rowspan'=>'2')),
                                      array('TAHAPAN KS',array('colspan'=>'2'))
                                      );
                     }

                     $jdl_tb = array($row_1,                                          
                                     array(array('PRA S',array()),array('KS I',array()))
                                    );
                     
                     $tb_hslfilter = new mytable(array('id'=>'tbhslfilter','width'=>'100%'),
                                                 $jdl_tb,
                                                 $dt_tb,$txt);

                    $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                    echo $html_txt;


                      
 ?>              
         