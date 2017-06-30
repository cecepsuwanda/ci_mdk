         <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Kecamatan</label>
                <?php
                   $mycmb = new mycmb;
                   //$data = array(1=>'Alaska',2=>'California',3=>'Delaware',4=>'Tennessee',5=>'Texas',6=>'Washington');
                   echo $mycmb->display('kec',$cmb_filter['kec'],'-- Semua Kecamatan --',$kec_select,$kec_select!='');
                   
                ?>
                  
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <label>Desa/Kelurahan</label>
                <?php 
                   echo $mycmb->display('desa',$cmb_filter['desa'],'-- Semua Desa/Kelurahan --',$desa_select,$desa_select!=''); 
                   
                ?>                                
                
               
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
                <label>RT</label>
                <?php echo $mycmb->display('rt',$cmb_filter['rt'],'-- Semua RT --',$rt_select,$rt_select!=''); ?>
                <button type="submit" id='filter_rt' class="btn btn-info pull-right">Filter</button>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Jenis Report</label>
                 <select id="jns_rpt" class="form-control select2" style="width: 100%;">
                  <option value="jns_rpt1" selected="selected">A. Data Demografi dan KB</option>
                  <option value="jns_rpt2" >B. Tahapan Keluarga Sejahtera</option>
                  <option value="jns_rpt3" >C. Data Anggota Keluarga</option>
                  <option value="jns_rpt4" >Register Kelompok KB Bagi Seluruh Keluarga</option>
                  <option value="jns_rpt5" >Register Kelompok KB Bagi Keluarga PRA S & KS I</option>
                </select>                  
                </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->