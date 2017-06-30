 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH KEPALA KELUARGA DAN JUMLAH JIWA DALAM KELUARGA MENURUT JENIS KELAMIN</bold><br>$subjudul</center>";
    
                       $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);   
    
                       $dt = $this->Unit_detail_model;
                       $nm = $pelaporan->getfooternmklm($idkec,$iddesa,$iddusun,$idrt,$dt);

                       $tmp = $pelaporan->build_dt_tb($tmp_dt_tb,array($nm,'KEL. PRASEJAHTERA<br>% THD. TOTAL KEL.','KEL. SEJAHTERA I<br>% THD. TOTAL KEL.'));
                       $dt_tb = $tmp['dt_tb'];
                       $txt = $tmp['footer_tb']; 

                       $nocol=$pelaporan->build_nocol(13);

                       $dt_ulang = $pelaporan->dt_berulang(2,array('LAKI-LAKI','%','PEREMPUAN','%','TOTAL')); 

                       $tb_header = array('id'=>'tbhslfilter','width'=>'100%');
    
                       $tb_hslfilter = new mytable($tb_header,
                                array(
                       array(
                            array('NO',array('rowspan'=>'2')),
                                              array($nmklm,array('rowspan'=>'2')),
                                              array("JUMLAH KEPALA KELUARGA",array('colspan'=>'5')),
                                              array("JUMLAH JIWA DALAM KELUARGA",array('colspan'=>'5')),
                                              array('RATA-2 JUMLAH JIWA PERKELUARGA',array('rowspan'=>'2'))
                                            ),                                          
                                        $dt_ulang,
                                        $nocol,
                                     ),
                                $dt_tb,$txt);

                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         