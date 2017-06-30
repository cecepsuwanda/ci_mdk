 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH JIWA USIA KERJA MENURUT KELOMPOK UMUR, KELAMIN, STATUS BEKERJA DAN TAHAPAN KELUARGA SEJAHTERA</bold><br>$subjudul</center>";
    
        
                        $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                        $dt_tb = $tmp['dt_tb'];
                        $txt = $tmp['footer_tb'];   

                        $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                        $tb_hslfilter = new mytable($tb_header,
                                array(
                       array(
                            array('NO. KODE',array('rowspan'=>'2')),
                                              array("KELOMPOK UMUR",array('rowspan'=>'2')),
                                              array('JENIS KELAMIN',array('colspan'=>'2')), 
                                              array('STATUS BEKERJA',array('colspan'=>'2')),
                                              array('TAHAPAN KELUARGA SEJAHTERA',array('colspan'=>'5')),
                                              array('JUMLAH',array('rowspan'=>'2'))
                        ),                                          
                                       array(
                            array('LAKI-LAKI',array()),
                                              array("PEREMPUAN",array()),
                                              array('BEKERJA',array()),
                                              array("TIDAK BEKERJA",array()),
                                              array('PRA S',array()), 
                                              array('KS I',array()),
                                              array('KS II',array()),
                                              array('KS III',array()),
                                              array('KS III+',array())
                                             )
                                     ),
                                $dt_tb,$txt);


                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         