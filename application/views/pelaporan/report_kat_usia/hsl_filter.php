 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                      $html_txt = "<center><bold>JUMLAH JIWA MENURUT KELOMPOK BALITA, REMAJA DAN LANSIA</bold><br>$subjudul</center>";
                      
                      $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);

                     
                      
                      $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                      $dt_tb = $tmp['dt_tb'];
                      $txt = $tmp['footer_tb']; 

                      $nocol=$pelaporan->build_nocol(11);

                      $dt_ulang = $pelaporan->dt_berulang(4,array('JUMLAH','%'));

                       $tb_header = array('id'=>'tbhslfilter','width'=>'100%');
                           
                      $tb_hslfilter = new mytable($tb_header,
                                array(
                                     array(
                                            array('NO. KODE',array('rowspan'=>'2')),
                                              array($nmklm,array('rowspan'=>'2')),
                                              array('JUMLAH JIWA',array('rowspan'=>'2')),
                                              array('BALITA (0 - < 5 TAHUN)',array('colspan'=>'2')),
                                              array('REMAJA (16 - 24 TAHUN)',array('colspan'=>'2')),
                                              array('LANSIA (60+ TAHUN)',array('colspan'=>'2')),
                                              array('BALITA+REMAJA+LANSIA',array('colspan'=>'2'))
                                            ),                                          
                                       $dt_ulang,
                                       $nocol
                                ),
                                $dt_tb,$txt);
                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         