         <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Kecamatan</label>
                <?php
                   $mycmb = new mycmb;
                   //$data = array(1=>'Alaska',2=>'California',3=>'Delaware',4=>'Tennessee',5=>'Texas',6=>'Washington');
                   echo $mycmb->display('kec',$cmb_filter['kec'],'-- Semua Kecamatan --',$kec_select,$kec_select!='');
                  if($desa_select==''){
                ?>
                     <button type="submit" id='filter_kec' class="btn btn-info pull-right">Filter</button>
                 <?php
                    } 
                 ?>
                  
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <label>Desa/Kelurahan</label>
                <?php 
                   echo $mycmb->display('desa',$cmb_filter['desa'],'-- Semua Desa/Kelurahan --',$desa_select,$desa_select!=''); 
                 if($dusun_select==''){  
                ?>                                
                <button type="submit" id='filter_desa' class="btn btn-info pull-right">Filter</button>
                <?php  } ?>                               
                
               
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Dusun/RW</label>
                <?php 
                    echo $mycmb->display('dusun',$cmb_filter['dusun'],'-- Semua Dusun/RW --',$dusun_select,$dusun_select!=''); 
                    if($rt_select=='')
                    {
                ?>               
                <button type="submit" id='filter_dusun' class="btn btn-info pull-right">Filter</button>
                <?php
                    }
                ?>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <label>Jenis Report</label>
                 <select id="jns_rpt" class="form-control select2" style="width: 100%;">
                  <option value="jns_rpt1" selected="selected">R/I/K S/07 Hal 1</option>
                  <option value="jns_rpt2" >R/I/K S/07 Hal 2</option>
                  <option value="jns_rpt3" >R/I/K S/Peserta KB/09</option>
                  <option value="jns_rpt4" >R/I/Pra S/Peserta KB/09</option>
                  <option value="jns_rpt5" >R/I/K S I/Peserta KB/09</option>
                  <option value="jns_rpt6" >R/I/Kel. Pra S/07 Hal 1</option>
                  <option value="jns_rpt7" >R/I/Kel. Pra S/07 Hal 2</option>
                  <option value="jns_rpt8" >Indikator Tahapan Keluarga PRA S</option>
                  <option value="jns_rpt9" >R/I/Kel. K S I/07 Hal 1</option>
                  <option value="jns_rpt10" >R/I/Kel. K S I/07 Hal 2</option>
                  <option value="jns_rpt11" >Indikator Tahapan Keluarga K S I</option>
                </select>                  
                </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
          