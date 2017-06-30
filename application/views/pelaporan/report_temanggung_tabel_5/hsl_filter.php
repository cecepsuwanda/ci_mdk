 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH USIA ANAK SEKOLAH, YANG SEKOLAH DAN TIDAK SEKOLAH MENURUT JENIS KELAMIN</bold><br>$subjudul</center>";
    
                      $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);
                     
                      
                       $dt = $this->Unit_detail_model;
                      $nm = $pelaporan->getfooternmklm($idkec,$iddesa,$iddusun,$idrt,$dt);
                      $tmp = $pelaporan->build_dt_tb($tmp_dt_tb,array($nm,'KEL. PRASEJAHTERA<br>% THD. TOTAL KEL.','KEL. SEJAHTERA I<br>% THD. TOTAL KEL.'));
                      $dt_tb = $tmp['dt_tb'];
                      $txt = $tmp['footer_tb']; 

                      $nocol=$pelaporan->build_nocol(19);

                      $dt_ulang = $pelaporan->dt_berulang(2,array('LAKI-LAKI','%','PEREMPUAN','%','TOTAL','%'));
                      $dt_ulang[] = array('LAKI-LAKI',array());
                      $dt_ulang[] = array("%",array());
                          $dt_ulang[] = array("PEREMPUAN",array());
                      $dt_ulang[] = array("%",array());
                      $dt_ulang[] = array("TOTAL",array());

                       $tb_header = array('id'=>'tbhslfilter','width'=>'100%');
    
                     $tb_hslfilter = new mytable($tb_header,
                                array(
                                     array(
                                           array('NO',array('rowspan'=>'3')),
                                             array($nmklm,array('rowspan'=>'3')),
                                             array("JUMLAH JIWA MENURUT KELOMPOK UMUR 7-15 TAHUN",array('colspan'=>'17'))
                                             ),                                          
                                       array(
                                             array('SEKOLAH',array('colspan'=>'6')),
                                             array("TIDAK SEKOLAH",array('colspan'=>'6')),
                                             array("TOTAL",array('colspan'=>'6'))
                                             ),
                                        $dt_ulang,
                                        $nocol,
                                    ),
                                $dt_tb,$txt);


                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         