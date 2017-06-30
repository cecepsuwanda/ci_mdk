 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH KELUARGA DENGAN ANAK BALITA MENURUT KELOMPOK UMUR, JUMLAH BALITA, KEGIATAN POSYANDU DAN STATUS TAHAPAN KELUARGA SEJAHTERA</bold><br>$subjudul</center>";
    
       
                       $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                       $dt_tb = $tmp['dt_tb'];
                       $txt = $tmp['footer_tb'];   

                        $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                      $tb_hslfilter = new mytable($tb_header,
                                array(
                      array(
                          array('NO. KODE',array('rowspan'=>'2')),
                                            array("KELOMPOK UMUR",array('rowspan'=>'2')),
                                            array("JUMLAH KEPALA KELUARGA",array('rowspan'=>'2')),
                                            array("JUMLAH BALITA",array('rowspan'=>'2')),
                                            array('POSYANDU',array('colspan'=>'2')),
                                            array('TAHAPAN KELUARGA SEJAHTERA',array('colspan'=>'5'))
                                           ),                                          
                                      array( 
                          array('IKUT',array()), 
                                            array('TIDAK',array()),
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
         