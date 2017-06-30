 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH JIWA MENURUT KELOMPOK UMUR DAN JENIS KELAMIN</bold><br>$subjudul</center><br><br>";
                       
                       $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                       $dt_tb = $tmp['dt_tb'];
                       $txt = $tmp['footer_tb'];
                                                          
                      
                      $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                     $tb_hslfilter = new mytable($tb_header,
                                array(
                       array(
                           array('NO. KODE',array()),
                                             array("KELOMPOK UMUR",array()),
                                             array('LAKI-LAKI',array()),
                                             array('PEREMPUAN',array()),
                                             array('JUMLAH',array())
                                            )
                                     ),
                                $dt_tb,$txt);

                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         