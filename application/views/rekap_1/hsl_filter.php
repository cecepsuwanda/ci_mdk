 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                      $html_txt = "<center><bold>DAFTAR KEPALA KELUARGA</bold><br>$subjudul</center>";
                                            
                      $dt_tb = null;
                      $txt = ''; 
                      
                      
                      $tb_hslfilter = new mytable(array('id'=>'tbserverside','width'=>'100%'),
                                                  array(
                                                          array(array('NO. KKI',array()),
                                                                array("KEPALA KELUARGA",array()),
                                                                array('JUMLAH JIWA',array())
                                                              )
                                                        ),
                                                 $dt_tb,$txt);

                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         