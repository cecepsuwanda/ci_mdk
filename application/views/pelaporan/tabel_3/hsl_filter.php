 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH JIWA PEMILIH MENURUT KELOMPOK UMUR JENIS KELAMIN DAN STATUS DALAM KELUARGA</bold><br>$subjudul</center><br><br>";
                       
                       $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                       $dt_tb = $tmp['dt_tb'];
                       $txt = $tmp['footer_tb'];

                      $dt_ulang = $pelaporan->dt_berulang(2,array('LAKI-LAKI','PEREMPUAN'));

                      $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                     $tb_hslfilter = new mytable($tb_header,
                                array(
                          array(
                              array('NO. KODE',array('rowspan'=>'2')),
                                            array("KELOMPOK UMUR",array('rowspan'=>'2')),
                                            array('KEPALA KELUARGA',array('rowspan'=>'2')),
                                            array('ISTRI',array('rowspan'=>'2')),
                                            array('ANAK',array('colspan'=>'2')),
                                            array('LAINNYA',array('colspan'=>'2')),
                                            array('JUMLAH',array('rowspan'=>'2'))
                                        ),
                                        $dt_ulang
                                    ),
                                $dt_tb,$txt);

                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         