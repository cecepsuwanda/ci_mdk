 
 <?php
                       $pelaporan = new pelaporan;
                       
                  $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                  $html_txt = "<center><bold>JUMLAH PUS, PESERTA KB DAN BUKAN PESERTA KB</bold><br>$subjudul</center>";
                  
                  $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);

            
                  $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                  $dt_tb = $tmp['dt_tb'];
                  $txt = $tmp['footer_tb']; 

                  $nocol=$pelaporan->build_nocol(7);

                  $dt_ulang = $pelaporan->dt_berulang(2,array('JUMLAH','%'));

                       $tb_header = array('id'=>'tbhslfilter','width'=>'100%');
                           
                     $tb_hslfilter = new mytable($tb_header,
                                array(
                                     array(
                                            array('NO. KODE',array('rowspan'=>'2')),
                                              array($nmklm,array('rowspan'=>'2')),
                                              array('JUMLAH PUS',array('rowspan'=>'2')),
                                              array('PESERTA KB',array('colspan'=>'2')),
                                              array('BUKAN PESERTA KB',array('colspan'=>'2'))
                                            ),                                          
                                       $dt_ulang,
                                       $nocol
                                 ),
                                $dt_tb,$txt);
                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         