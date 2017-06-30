 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH JIWA MENURUT KELOMPOK UMUR DAN PENDIDIKAN</bold><br>$subjudul<br><br>";
                       
                       $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                       $dt_tb = $tmp['dt_tb'];
                       $txt = $tmp['footer_tb'];
                        
                       $nmedulvl=$this->Edu_lvl_model->getnm('Kd_edu != 0'); 
                       $numrows=$this->Edu_lvl_model->getnumrows();   

                      $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                     $tb_hslfilter = new mytable($tb_header,
                                array(
                       array(
                            array('NO. KODE',array('rowspan'=>'2')),
                                              array("KELOMPOK UMUR",array('rowspan'=>'2')),
                                              array('TIDAK SEKOLAH',array('rowspan'=>'2')),
                                              array('BERSEKOLAH',array('colspan'=>($numrows+1))),
                                              array('JUMLAH',array('rowspan'=>'2'))
                                            ),
                                       $nmedulvl
                                     ),
                                $dt_tb,$txt);

                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         