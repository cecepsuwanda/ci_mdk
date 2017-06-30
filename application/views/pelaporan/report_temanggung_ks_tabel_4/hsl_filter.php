 
 <?php
                       $pelaporan = new pelaporan;
                                              
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                        $html_txt = "<center><bold>JUMLAH JIWA ANGGOTA KELUARGA MENURUT KELOMPOK UMUR</bold><br>$subjudul</center>";
                        
                        $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);
                       
                        $dt = $this->Unit_detail_model;
                        $nm = $pelaporan->getfooternmklm($idkec,$iddesa,$iddusun,$idrt,$dt);
                        $tmp = $pelaporan->build_dt_tb($tmp_dt_tb,array($nm));
                        $dt_tb = $tmp['dt_tb'];
                        $txt = $tmp['footer_tb']; 

                        $nocol=$pelaporan->build_nocol(18);
                        

                       $tb_header = array('id'=>'tbhslfilter','width'=>'100%');
    
                     $tb_hslfilter = new mytable($tb_header,
                                array(
                                     array(
                                            array('NO',array('rowspan'=>'2')),
                                              array($nmklm,array('rowspan'=>'2')),
                                              array("JUMLAH JIWA ANGGOTA KELUARGA MENURUT KELOMPOK UMUR",array('colspan'=>'15')),
                                              array("% PERSEBARAN PER TAHAPAN KS - ",array('rowspan'=>'2'))
                                            ),                                          
                                       array(
                                               array('0 - < 1 TAHUN',array()),
                                               array("%",array()),
                                               array("1 - < 5 TAHUN",array()),
                                               array('%',array()),
                                               array("5 - < 6 TAHUN",array()),
                                               array('%',array()),
                                               array("7 - < 15 TAHUN",array()),
                                               array('%',array()),
                                               array("16 - < 21 TAHUN",array()),
                                               array('%',array()),
                                               array("22 - < 59 TAHUN",array()),
                                               array('%',array()),
                                               array("60 TAHUN+",array()),
                                               array('%',array()),
                                               array('TOTAL',array())
                                             ),
                                       $nocol,
                               ),
                              $dt_tb,$txt);

                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         