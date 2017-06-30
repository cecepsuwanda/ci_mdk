<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <style type="text/css">

  #tbhslfilter thead,
  #tbhslfilter th {text-align: center; vertical-align:middle;}

  

  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php $this->load->view('header');  ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php 
        $data['menu_active'] = array(219,220,224); 
        $this->load->view('sidebar',$data); 
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        
        <small>4. JUMLAH PESERTA KB MENURUT KELOMPOK UMUR METODE KONTRSEPSI DAN JUMLAH ANAK </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Tabel Kependudukan</a></li>
        <li class="active">Kelompok Umur</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Filter</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>            
          </div>
        </div>
        <div class="box-body">

          <?php 
             $data['cmb_filter'] = $cmb_filter;
             $data['kec_select'] = $kec_select;
             $data['desa_select'] = $desa_select;
             $data['dusun_select'] = $dusun_select;
             $data['rt_select'] = $rt_select;

             $this->load->view('filter_v1',$data);  
          ?>       
        
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
         
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Hasil Filter</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>            
          </div>
        </div>
        <div class="box-body">
          <div id="hslfilter">

          <div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.3.6
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>
  </div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>assets/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/app.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo base_url();?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap 
<script src="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>-->
<!-- Select2 -->
<script src="<?php echo base_url();?>assets/plugins/select2/select2.full.min.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo base_url();?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) 
<script src="<?php echo base_url();?>assets/dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes 
<script src="<?php echo base_url();?>assets/dist/js/demo.js"></script>
<!-- Page script -->
<script>  
   var folder='<?php  echo basename(dirname(dirname($_SERVER['PHP_SELF']))); ?>';
   var subfolder = '<?php  echo basename(dirname($_SERVER['PHP_SELF'])); ?>';
   var page = '<?php echo basename($_SERVER['PHP_SELF']); ?>';

   function afterajax(data)
   {
      $("#tbhslfilter").DataTable({
        "paging": false, 
        "searching": false,
        "ordering": false,
         "info": false});
   }

   function myajax(id,data,url,fbefore=null,fafter=null) {
        
        if(fbefore != null){
            if(typeof fbefore==='function'){
               fbefore();
            }
        }
        
        $.ajax({
            "type" : "post",
            "url" : url,
            "cache" : false,
            "data" : data,
            success : function (data) {
                if(id!=''){                  
                  $('#'+id).html(data);
                }
                
                if(fafter != null){
                    if(typeof fafter==='function'){
                       fafter(data);
                    }
                }
            }
        });
     }

  $(function () {
     //Initialize Select2 Elements
     $(".select2").select2();

     $("#kec").change(function () {
       var idkec = $('#kec option:selected').val();
       data = "idkec=" + idkec; 
        
       if(idkec==0)
       {
        $('#desa').html("<option value='0' selected='selected' >-- Semua Desa/Kelurahan --</option>");
        $('#dusun').html("<option value='0' selected='selected' >-- Semua Dusun/RW --</option>");
        $('#rt').html("<option value='0' selected='selected' >-- Semua RT --</option>");
       }else{
         myajax('desa',data,"<?php echo base_url();?>index.php/dashboard/get_unit_detail");
         $('#dusun').html("<option value='0' selected='selected' >-- Semua Dusun/RW --</option>");
         $('#rt').html("<option value='0' selected='selected' >-- Semua RT --</option>");
       }
      });

      $("#desa").change(function () {
         var idkec = $('#kec option:selected').val();
         var iddesa = $('#desa option:selected').val();
         data = "idkec=" + idkec + "&iddesa=" + iddesa;
         if(iddesa==0)
         {
           $('#dusun').html("<option value='0' selected='selected' >-- Semua Dusun/RW --</option>");
           $('#rt').html("<option value='0' selected='selected' >-- Semua RT --</option>");
         }else{
          myajax('dusun',data,"<?php echo base_url();?>index.php/dashboard/get_unit_detail");
          $('#rt').html("<option value='0' selected='selected' >-- Semua RT --</option>");
         }
      }); 

      $("#dusun").change(function () {
         var idkec = $('#kec option:selected').val();
         var iddesa = $('#desa option:selected').val();
         var iddusun = $('#dusun option:selected').val();         
         data = "idkec=" + idkec + "&iddesa=" + iddesa + "&iddusun=" + iddusun;
         if(iddusun==0){
          $('#rt').html("<option value='0' selected='selected' >-- Semua RT --</option>"); 
         }else{
           myajax('rt',data,"<?php echo base_url();?>index.php/dashboard/get_unit_detail");
         }
       }); 

       $("#filter_kec").click(function () {
          var idkec = $('#kec option:selected').val();
          data ="idkec=" + idkec + '&folder='+folder+'&subfolder='+subfolder+'&page='+page;
          $('#hslfilter').html("<font size='5' color='red'>Silahkan Tunggu, Sedang Proses ....<\/font> <img src='<?php echo base_url();?>assets/img/ajax-loader.gif' />");
          myajax('hslfilter',data,"<?php echo base_url();?>index.php/dashboard/pelaporan_filter",null,afterajax);
       });

      $("#filter_desa").click(function () {
         var idkec = $('#kec option:selected').val();
         var iddesa = $('#desa option:selected').val();
         data = "idkec=" + idkec + "&iddesa=" + iddesa + '&folder='+folder+'&subfolder='+subfolder+'&page='+page;
         $('#hslfilter').html("<font size='5' color='red'>Silahkan Tunggu, Sedang Proses ....<\/font> <img src='<?php echo base_url();?>assets/img/ajax-loader.gif' />");
         myajax('hslfilter',data,"<?php echo base_url();?>index.php/dashboard/pelaporan_filter",null,afterajax);
      });

 
      $("#filter_dusun").click(function () {
          var idkec = $('#kec option:selected').val();
          var iddesa = $('#desa option:selected').val();
          var iddusun = $('#dusun option:selected').val();        
          data = "idkec=" + idkec + "&iddesa=" + iddesa + "&iddusun=" + iddusun+ '&folder='+folder+'&subfolder='+subfolder+'&page='+page;
          $('#hslfilter').html("<font size='5' color='red'>Silahkan Tunggu, Sedang Proses ....<\/font> <img src='<?php echo base_url();?>assets/img/ajax-loader.gif' />");
          myajax('hslfilter',data,"<?php echo base_url();?>index.php/dashboard/pelaporan_filter",null,afterajax);
      });

  

      $("#filter_rt").click(function () {
          var idkec = $('#kec option:selected').val();
          var iddesa = $('#desa option:selected').val();
          var iddusun = $('#dusun option:selected').val();
          var idrt = $('#rt option:selected').val();
          data = "idkec=" + idkec + "&iddesa=" + iddesa + "&iddusun=" + iddusun + '&idrt='+idrt+ '&folder='+folder+'&subfolder='+subfolder+'&page='+page;
          $('#hslfilter').html("<font size='5' color='red'>Silahkan Tunggu, Sedang Proses ....<\/font> <img src='<?php echo base_url();?>assets/img/ajax-loader.gif' />");
          myajax('hslfilter',data,"<?php echo base_url();?>index.php/dashboard/pelaporan_filter",null,afterajax);
       }); 


       

         
  });
</script>
</body>
</html>
