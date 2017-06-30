 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH WANITA USIA SUBUR, BALITA USIA 0 - < 5 TH  DAN PROXY CWR</bold><br>$subjudul</center>";
    
                        $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);
                        
                        $dt = $this->Unit_detail_model;
                        $nm = $pelaporan->getfooternmklm($idkec,$iddesa,$iddusun,$idrt,$dt);
                        $tmp = $pelaporan->build_dt_tb($tmp_dt_tb,array($nm,'KEL. PRASEJAHTERA<br>% THD. TOTAL KEL.','KEL. SEJAHTERA I<br>% THD. TOTAL KEL.'));
                        $dt_tb = $tmp['dt_tb'];
                        $txt = $tmp['footer_tb']; 

                        $nocol=$pelaporan->build_nocol(9);

                       $tb_header = array('id'=>'tbhslfilter','width'=>'100%');
    
                       $tb_hslfilter = new mytable($tb_header,
                                array(
                      array(
                          array('NO',array('rowspan'=>'2')),
                                            array($nmklm,array('rowspan'=>'2')),
                                            array("JUMLAH WANITA USIA SUBUR (WUS 15-49)",array('rowspan'=>'2')),
                                            array("JUMLAH ANAK BALITA 0 - < 5 TAHUN",array('colspan'=>'5')),
                                            array('JUMLAH BAYI 0 - < 5 TH PER WUS (PROXY CWR)',array('rowspan'=>'2'))
                                           ),                                          
                                      array(
                          array('IKUT POSYANDU',array()),
                                            array("%",array()),
                                            array("TIDAK IKUT POSYANDU",array()),
                                            array('%',array()),
                                            array('TOTAL',array())
                                           ),
                                      $nocol,
                                ),
                                $dt_tb,$txt);

                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         