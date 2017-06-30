 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH PUS MENURUT KELOMPOK UMUR, KESERTAAN BER-KB, TEMPAT PELAYANAN DAN TAHAPAN KELUARGA SEJAHTERA</bold><br>$subjudul</center>";
    
                                                
                        $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                        $dt_tb = $tmp['dt_tb'];
                        $txt = $tmp['footer_tb'];   

                        $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                      $tb_hslfilter = new mytable($tb_header,
                                array(
                      array(
                          array('NO. KODE',array('rowspan'=>'3')),
                                            array("KELOMPOK UMUR",array('rowspan'=>'3')),
                                            array("JUMLAH PUS",array('rowspan'=>'3')),
                                            array("PESERTA BER-KB",array('colspan'=>'4')),
                                            array('TAHAPAN KELUARGA SEJAHTERA',array('colspan'=>'5'))
                                          ),                                          
                                      array( 
                          array('PESERTA KB',array('colspan'=>3)), 
                                            array('BUKAN PESERTA KB',array('rowspan'=>'2')),
                                            array('PRA S',array('rowspan'=>'2')), 
                                            array('KS I',array('rowspan'=>'2')),
                                            array('KS II',array('rowspan'=>'2')),
                                            array('KS III',array('rowspan'=>'2')),
                                            array('KS III+',array('rowspan'=>'2'))
                                           ),
                                     array(
                                           array('KLINIK PEMERINTAH',array()),
                                           array('KLINIK SWASTA',array()),
                                           array('JUMLAH',array())
                                          )
                                   ),
                                $dt_tb,$txt);


                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         