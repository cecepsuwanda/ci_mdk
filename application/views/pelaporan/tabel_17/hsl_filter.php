 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH PUS BUKAN PESERTA KB MENURUT KELOMPOK UMUR, ALASAN TIDAK BER-KB DAN TAHAPAN KELUARGA SEJAHTERA</bold><br>$subjudul</center>";
    
    
    
                      $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                      $dt_tb = $tmp['dt_tb'];
                      $txt = $tmp['footer_tb']; 

                      
                      $nmnon=$this->Non_acptr_reas_model->getnm(''); 
                      $nmnon[]=array('PRA S',array());
                      $nmnon[]=array('KS I',array());
                      $nmnon[]=array('KS II',array());
                      $nmnon[]=array('KS III',array());
                      $nmnon[]=array('KS III+',array());
                      $numrows=$this->Non_acptr_reas_model->getnumrows();

                        $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                      $tb_hslfilter = new mytable($tb_header,
                                array(
                       array(
                            array('NO. KODE',array('rowspan'=>'2')),
                                              array("KELOMPOK UMUR",array('rowspan'=>'2')),
                                              array("ALASAN TIDAK BER-KB",array('colspan'=>($numrows+1))),
                                              array('TAHAPAN KELUARGA SEJAHTERA',array('colspan'=>'5'))
                                            ),                                          
                                       $nmnon
                                     ),
                                $dt_tb,$txt);


                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         