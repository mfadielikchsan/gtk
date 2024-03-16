  
<!DOCTYPE html>
<?php
include "config.inc.php";
include "koneksi.php";

$csPostgre = koneksiPostgre($namaUserPostgre, $userPassPostgre, $databasePostgre, $msgGagalKoneksi);
$cs = koneksiOracle($oraUserMfg, $oraPassMfg, $db, $msgGagalKoneksi);

$rejectToday = 0;
$totalReject = 0;
$d14Reject = 0;
$d17Reject = 0;
$d40Reject = 0;
$proses = $_GET['PROSES'];

function shift(){
  date_default_timezone_set('Asia/Jakarta');
  $shift = "";
  $jam = date('H:i');
  if($jam > '07:30' && $jam <= '21:00'){
    $shift = 2;
  } else if($jam > '00:00' && $jam <= '07:30'){
    $shift = 1;
  } else {
    $shift = 3;
  }
  return $shift;
}

$shift = shift();
$dataX = array();
$dataPos = array();
    // array untuk nomor pos 
$query = "select jam_mulai, eff from prodv_dashboard_eff where tgl = current_date";
$stmts = pg_query($csPostgre,$query);
while($row = pg_fetch_array($stmts)){
  array_push($dataPos, $row);
}

$queryD = "select avg(eff) avg_eff, avg(ct) avg_ct, current_date-'2019-01-01' zero_accident, 
current_date-'2019-01-01' zero_claim
from (
select eff, ct from prodv_dashboard_eff where tgl = current_date and current_time > jam_selesai
and kd_proses = '".$proses."' and shift = ".$shift."
union all
select eff, ct from prodv_dashboard_eff where tgl = current_date and pointer = 1
and kd_proses = '".$proses."' and shift = ".$shift."
) as a";
$stD = pg_query($csPostgre,$queryD);
$dataD = pg_fetch_array($stD);
if(!$stD){
  echo pg_last_error($csPostgre);
}
$avgEff = number_format($dataD['avg_eff'],1);
$avgCt = number_format($dataD['avg_ct'],2);
$zeroAccident = $dataD['zero_accident'];
$zeroClaim = $dataD['zero_claim'];
$tgl = date('d-m-Y');

$query = "select * from prodv_dashboard_eff where tgl = current_date and kd_proses = '".$proses."' and shift = ".$shift."";
$stmts = pg_query($csPostgre,$query);
$dataX = array();
while($row = pg_fetch_array($stmts)){
  array_push($dataX, $row);
}

$queryVolume = "select sum(fix_po) order_volume, (395+440)*60*FGET_HK_BLN(to_char(sysdate, 'YYYY'), 
to_char(sysdate, 'MM'))/50 prod_volume
from VW_FTO_IGP 
where thn = to_char(sysdate, 'YYYY') and bln = to_char(sysdate, 'MM')
and kd_cust = '02' and substr(part_no, 1,3) = '421'";
$stVolume = ociparse($cs,$queryVolume);
ociexecute($stVolume);
$dataV = oci_fetch_array($stVolume);
$prodVol = number_format($dataV['PROD_VOLUME']);
$ordVol = number_format($dataV['ORDER_VOLUME']);

$queryMhu = "select * from prodv_mhu where proses = '".$proses."' and shift = ".$shift."";
$stMhu = ociparse($cs,$queryMhu);
ociexecute($stMhu);
$dataMhu = oci_fetch_array($stMhu);

$queryReject = "select * from prodv_reject where kd_proses = '".$proses."' and shift = ".$shift."";
$stReject = pg_query($csPostgre, $queryReject);
$dataReject = pg_fetch_array($stReject);

$queryDt = "select kategori, sum(jml) jml from (
select fnm_ls(t4.kd_ls) kategori, round(sum(fjml_ls_lhp('', t4.ls_mulai, t4.ls_selesai))/fjml_ls_lhp('', t1.awal_kerja, sysdate), 2) jml
from tlhpn01 t1, tlhpn04 t4 
where t1.no_doc = t4.no_doc
and t1.tgl_doc = trunc(sysdate) and t1.shift = 2 and t1.proses = '".$proses."'
and t4.kd_ls <> 'MLS07'
group by fnm_ls(t4.kd_ls), fjml_ls_lhp('', t4.ls_mulai, t4.ls_selesai), t1.awal_kerja
) group by kategori";
$stDt = ociparse($cs,$queryDt);
ociexecute($stDt);
$dataDt = array();
while($row = oci_fetch_array($stDt)){
  array_push($dataDt, $row);
}

$queryMhuAvg = "SELECT AVG(QTY) AVG_MHU FROM (
SELECT T1.SHIFT, SUM(T2.JML_MAT) JML,
ROUND((CASE WHEN T1.SHIFT = 2 AND T1.JML_MP_OT = 0 THEN (T1.JML_MP*440/60)/SUM(T2.JML_MAT) 
WHEN T1.SHIFT = 2 AND T1.JML_MP_OT = 1 THEN (T1.JML_MP*500/60)/SUM(T2.JML_MAT)
WHEN T1.SHIFT = 2 AND T1.JML_MP_OT = 2 THEN (T1.JML_MP*545/60)/SUM(T2.JML_MAT)
WHEN T1.SHIFT = 2 AND T1.JML_MP_OT = 3 THEN (T1.JML_MP*605/60)/SUM(T2.JML_MAT) 
WHEN T1.SHIFT = 2 AND T1.JML_MP_OT = 0 THEN (T1.JML_MP*665/60)/SUM(T2.JML_MAT)
WHEN T1.SHIFT = 1 THEN (T1.JML_MP*395/60)/SUM(T2.JML_MAT)
WHEN T1.SHIFT = 3 THEN (T1.JML_MP*440/60)/SUM(T2.JML_MAT) ELSE 0 END), 2) QTY
FROM TLHPN01 T1, TLHPN02 T2 
WHERE T1.NO_DOC = T2.NO_DOC
AND T1.TGL_DOC <> TRUNC(SYSDATE)
AND TO_CHAR(T1.TGL_DOC, 'YYYYMM') = TO_CHAR(SYSDATE, 'YYYYMM')
AND T1.PROSES = '".$proses."' AND T1.SHIFT = ".$shift."
GROUP BY T1.TGL_DOC, T1.JML_MP, T1.JML_MP_OT, T1.PROSES, T1.SHIFT
)";
$stMhuAvg = ociparse($cs,$queryMhuAvg);
ociexecute($stMhuAvg);
$dataMhuAvg = oci_fetch_array($stMhuAvg);
$mhuAvg = number_format($dataMhuAvg['AVG_MHU'],2);

$queryMhuToday = "SELECT ROUND((T1.JML_MP*FJML_LS_LHP('', T1.AWAL_KERJA, SYSDATE))/60/SUM(T2.JML_MAT), 2) MHU_TD
FROM TLHPN01 T1, TLHPN02 T2 
WHERE T1.NO_DOC = T2.NO_DOC
AND T1.TGL_DOC = TRUNC(SYSDATE)
AND T1.PROSES = 'WC04' AND T1.SHIFT = 2
GROUP BY T1.JML_MP, T1.SHIFT, T1.AWAL_KERJA";
$stMhuToday = ociparse($cs,$queryMhuToday);
ociexecute($stMhuToday);
$dataMhuToday = oci_fetch_array($stMhuToday);
$mhuToday = number_format($dataMhuToday['MHU_TD'],2);
?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Production | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
   folder instead of downloading all of them to reduce the load. -->
   <link rel="stylesheet" href="dist/css/skins/skin-midnight.min.css">
   <link rel="stylesheet" href="dist/css/skins/skin-midnight.css">

   <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style type="text/css">
  .donut-inner {
   margin-top: -130px;
   color: #FFF;
   font-size: 36pt;
   text-align: center;
   margin-left: -130px;
 }
</style>
</head>
<body class="hold-transition skin-midnight" style = "background-color: #000;">
  <!-- Content Wrapper. Contains page content -->
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Smart Production Dashboard RA B IGP 3
        <small><?php echo $tgl;?></small>
      </h1>
      <ol class="breadcrumb">
        <li style ="font-size: 20px; list-style-type: none !important;"><i class="fa fa-heart"></i> Zero Claim <a style ="font-size: 25px"><?php echo $zeroClaim;?></a></li>
        days
        <li style ="font-size: 20px; list-style-type: none !important;"><i class="fa fa-medkit"></i> Zero Accident <a style ="font-size: 25px"><?php echo $zeroAccident;?></a></li>
        days
      </ol>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Production Performance</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-2">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><h3><?php echo $prodVol;?></h3></span>
                    <span class="description-text" style="font-size: 12px"><strong>Production Volume</strong></span>
                  </div>
                </div>
                <div class="col-md-8">
                  <p class="text-center">
                    <strong>Efficiency</strong>
                  </p>
                  <canvas id="effChart" style="max-height:100px !important"> </canvas> 
                </div>
                <div class="col-md-2">
                  <div class="description-block border-left">
                    <span class="description-percentage text-green"><h1><?php echo $avgEff;?><small>%</small></h1></span>
                    <span class="description-text"><strong>Efficiency</strong></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><h3><?php echo $ordVol;?></h3></span>
                    <span class="description-text" style="font-size: 12px"><strong>Order Volume</strong></span>
                  </div>
                </div>
                <div class="col-md-8">
                  <p class="text-center">
                    <strong>Cycle Time</strong>
                  </p>
                  <canvas id="cycleChart" style="max-height:100px !important"> </canvas> 
                </div>
                <div class="col-md-2">
                  <div class="description-block border-left">
                    <span class="description-percentage text-green"><h1><?php echo $avgCt;?></h1></span>
                    <span class="description-text"><strong>Cycle Time</strong></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Production Output</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <?php 
                $_GET['SHIFT'] = $shift;
                $_GET['WC'] = $proses;
                include('listProdOutput.php');
                    //echo $shift;
                ?>
                

                <!-- <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-none"><img src="images/.png"></span>

                    <div class="info-box-content">
                      <span class="info-box-text">D17</span>
                      <span class="info-box-number">90<small>%</small></span>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Non Conforming Part</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-sm-3 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-red">
                      <h2 class="description-header"><?php echo $rejectToday;?></h2>
                    </span>
                    <span class="description-text"><strong>Reject Today</strong></span>
                  </div>
                </div>

                <div class="col-sm-2 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-red">
                      <h2 class="description-header"><?php echo $totalReject;?></h2>
                    </span>
                    <span class="description-text"><strong>Total</strong></span>
                  </div>
                </div>

                <div class="col-sm-2 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-red">
                      <h2 class="description-header"><?php echo $totalReject;?></h2>
                    </span>
                    <span class="description-text"><strong>D17</strong></span>
                  </div>
                </div>

                <div class="col-sm-2 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-red">
                      <h2 class="description-header"><?php echo $totalReject;?></h2>
                    </span>
                    <span class="description-text"><strong>D14</strong></span>
                  </div>
                </div>

                <div class="col-sm-2 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-red">
                      <h2 class="description-header"><?php echo $totalReject;?></h2>
                    </span>
                    <span class="description-text"><strong>D40</strong></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main row -->
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Man Hour Unit</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-2">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><h3><?php echo $mhuToday;?></h3></span>
                    <span class="description-text" style="font-size: 12px"><strong>MHU Today</strong></span>
                    <span class="description-percentage text-green"><h3><?php echo $mhuAvg;?></h3></span>
                    <span class="description-text" style="font-size: 12px"><strong>MHU Average</strong></span>
                  </div>
                </div>
                <div class="col-md-10">
                  <p class="text-center">
                    <strong>Man Hour Unit</strong>
                  </p>
                  <canvas id="manhourChart" style="max-height:200px !important"> </canvas> 
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Downtime</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <p class="text-center">
                    <strong>Line Stop</strong>
                  </p>
                  <canvas id="downtimeChart" style="max-height:200px !important"> </canvas>
                  <div class="donut-inner">97%
                    <br/><br/><br/>
                  </div> 
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Reject Ratio</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-2">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><h3><?php echo "0";?></h3></span>
                    <span class="description-text" style="font-size: 12px"><strong>Reject Today</strong></span>
                    <span class="description-percentage text-green"><h3><?php echo "0";?></h3></span>
                    <span class="description-text" style="font-size: 12px"><strong>Reject Acc.</strong></span>
                  </div>
                </div>
                <div class="col-md-10">
                  <p class="text-center">
                    <strong>Reject Ratio</strong>
                  </p>
                  <canvas id="rejectChart" style="max-height:200px !important"> </canvas> 
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">List Downtime</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="max-height: 200px; overflow-y: scroll;">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                    <tr>
                      <th colspan="2">Time</th>
                      <th>Category</th>
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $_GET['SHIFT'] = $shift;
                    $_GET['WC'] = $proses;
                    include('listDowntime.php');
                    //echo $shift;
                    ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- Sparkline -->
  <script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
  <!-- jvectormap  -->
  <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
  <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <!-- SlimScroll -->
  <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- ChartJS -->
  <script src="bower_components/chart.js/stackedbar/Chart.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>

  <script type="text/javascript">
    $(document).ready(function () {
      var dataX = <?php echo json_encode($dataX); ?>;
      var eff = [];
      var std = [];
      var ct = [];
      var stdct = [];

      for (i = 0; i < dataX.length; i++){
        eff.push(dataX[i]['eff']);
        std.push(98);
        ct.push(dataX[i]['ct']);
        stdct.push(50);
      }          

      //EFFICIENCY
      var ctx = document.getElementById("effChart").getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: [<?php 
            for($i = 0 ; $i < count($dataX); $i++)
            {
              $waktu = $dataX[$i]['waktu'];
              echo '"'.$waktu.'",';
            }                    
            ?>],
            datasets: [{
              label: 'Efficiency',
              data: eff,
              pointHoverRadius: 20,
              fill: false,
              borderColor: '#05d5f5',
              borderWidth: 2,
            }, {
              label: 'Standart',
              data: std,
              pointHoverRadius: 20,
              fill: false,
              borderColor: '#f00736',
              borderWidth: 2,
            }]
          },
          options: {
            title: {
              display: false,
              text: 'Efficiency'
            },
            elements: {
              line: {
                tension: 0.000001
              }
            },
            responsive: true,           
            tooltips: {
              titleFontSize: 10,
              bodyFontSize: 12
            },
            legend: {
                    position: 'right', // place legend on the right side of chart
                    labels:{
                      fontSize:18
                      ,fontColor:"#000"
                    },
                    display:false
                  },
                  scales: {
                    xAxes: [{                  
                      display: false,
                      ticks: {
                        fontSize: 8,
                        fontColor: "#000",
                      },
                      gridLines: {
                        zeroLineColor: "#000",
                        lineWidth: 1,
                      }
                    }],
                    yAxes: [{
                      //stacked: true, // this also..   
                      display: false,              
                      ticks: {
                        stepSize: 20,
                        max: 100,
                        fontSize: 14,
                        fontColor: "#000",
                        callback: function (value) {
                              return value + '%'; // convert it to percentage
                            }
                          },
                          scaleLabel: {
                            display: true,
                            labelString: '(%)',
                            fontSize: 14,
                            fontColor: "#FFF",
                          },
                          gridLines: {
                            zeroLineColor: "#000",
                            lineWidth: 1,
                          }
                        }]
                      }
                    }
                  });

      //CYCLE TIME
      var ctx = document.getElementById("cycleChart").getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [<?php 
            for($i = 0 ; $i < count($dataX); $i++)
            {
              $waktu = $dataX[$i]['waktu'];
              echo '"'.$waktu.'",';
            }                    
            ?>],
            datasets: [{
              type: 'line',
              label: 'Standart CT',
              data: stdct,
              pointHoverRadius: 20,
              fill: false,
              borderColor: '#f00736',
              borderWidth: 2,
            },
            {
              label: 'Cycle Time',
              data: ct,
              pointHoverRadius: 20,
              fill: true,
              backgroundColor: '#05d5f5',
              borderColor: '#05d5f5',
              borderWidth: 2,
            }]
          },
          options: {
            title: {
              display: false,
              text: 'Cycle Time'
            },
            elements: {
              line: {
                tension: 0.000001
              }
            },
            responsive: true,           
            tooltips: {
              titleFontSize: 10,
              bodyFontSize: 12
            },
            legend: {
                    position: 'right', // place legend on the right side of chart
                    labels:{
                      fontSize:18
                      ,fontColor:"#000"
                    },
                    display:false
                  },
                  scales: {
                    xAxes: [{                  
                      display: false,
                      ticks: {
                        fontSize: 8,
                        fontColor: "#000",
                      },
                      gridLines: {
                        zeroLineColor: "#000",
                        lineWidth: 1,
                      }
                    }],
                    yAxes: [{
                      display: false,          
                      ticks: {
                        stepSize: 20,
                        max: 100,
                        fontSize: 14,
                        fontColor: "#000",
                        callback: function (value) {
                              return value + '%'; // convert it to percentage
                            }
                          },
                          scaleLabel: {
                            display: true,
                            labelString: '(%)',
                            fontSize: 14,
                            fontColor: "#FFF",
                          },
                          gridLines: {
                            zeroLineColor: "#000",
                            lineWidth: 1,
                          }
                        }]
                      }
                    }
                  });

      //MANHOUR
      var dataMhu = <?php echo json_encode($dataMhu); ?>;
      var mhu = [];
      var stdMhu = [];
      for (i = 0; i < 31; i++){
       stdMhu.push(dataMhu['STD']);
     }

     mhu.push(dataMhu['TGL1']);
     mhu.push(dataMhu['TGL2']);
     mhu.push(dataMhu['TGL3']);
     mhu.push(dataMhu['TGL4']);
     mhu.push(dataMhu['TGL5']);
     mhu.push(dataMhu['TGL6']);
     mhu.push(dataMhu['TGL7']);
     mhu.push(dataMhu['TGL8']);
     mhu.push(dataMhu['TGL9']);
     mhu.push(dataMhu['TGL10']);
     mhu.push(dataMhu['TGL11']);
     mhu.push(dataMhu['TGL12']);
     mhu.push(dataMhu['TGL13']);
     mhu.push(dataMhu['TGL14']);
     mhu.push(dataMhu['TGL15']);
     mhu.push(dataMhu['TGL16']);
     mhu.push(dataMhu['TGL17']);
     mhu.push(dataMhu['TGL18']);
     mhu.push(dataMhu['TGL19']);
     mhu.push(dataMhu['TGL20']);
     mhu.push(dataMhu['TGL21']);
     mhu.push(dataMhu['TGL22']);
     mhu.push(dataMhu['TGL23']);
     mhu.push(dataMhu['TGL24']);
     mhu.push(dataMhu['TGL25']);
     mhu.push(dataMhu['TGL26']);
     mhu.push(dataMhu['TGL27']);
     mhu.push(dataMhu['TGL28']);
     mhu.push(dataMhu['TGL29']);
     mhu.push(dataMhu['TGL30']);
     mhu.push(dataMhu['TGL31']);

     var ctx = document.getElementById("manhourChart").getContext('2d');
     var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [<?php 
          for($i = 1 ; $i < 32; $i++)
          {
            echo '"tgl '.$i.'",';
          }                    
          ?>],
          datasets: [{
            type: 'line',
            label: 'Standart MHU',
            data: stdMhu,
            pointHoverRadius: 20,
            fill: false,
            borderColor: '#f00736',
            borderWidth: 2,
          },
          {
            label: 'Man Hour',
            data: mhu,
            pointHoverRadius: 20,
            fill: true,
            backgroundColor: '#05d5f5',
            borderColor: '#05d5f5',
            borderWidth: 2,
          }]
        },
        options: {
          title: {
            display: false,
            text: 'Man Hour'
          },
          elements: {
            line: {
              tension: 0.000001
            }
          },
          responsive: true,           
          tooltips: {
            titleFontSize: 10,
            bodyFontSize: 12
          },
          legend: {
                    position: 'right', // place legend on the right side of chart
                    labels:{
                      fontSize:18
                      ,fontColor:"#fff"
                    },
                    display:false
                  },
                  scales: {
                    xAxes: [{                  
                      display: false,
                      ticks: {
                        fontSize: 8,
                        fontColor: "#fff",
                      },
                      gridLines: {
                        zeroLineColor: "#fff",
                        lineWidth: 1,
                      }
                    }],
                    yAxes: [{
                      display: false,          
                      ticks: {
                        stepSize: 0.1,
                        max: 0.3,
                        min: 0,
                        fontSize: 14,
                        fontColor: "#fff",
                        callback: function (value) {
                              return value + ''; // convert it to percentage
                            }
                          },
                          scaleLabel: {
                            display: true,
                            labelString: '',
                            fontSize: 14,
                            fontColor: "#FFF",
                          },
                          gridLines: {
                            zeroLineColor: "#fff",
                            lineWidth: 1,
                          }
                        }]
                      }
                    }
                  });

     //MANHOUR
     var dataReject = <?php echo json_encode($dataReject); ?>;
     var reject = [];
     var stdReject = [];
     for (i = 0; i < 31; i++){
       stdReject.push('1');
     }

     reject.push(dataReject['tgl1']);
     reject.push(dataReject['tgl2']);
     reject.push(dataReject['tgl3']);
     reject.push(dataReject['tgl4']);
     reject.push(dataReject['tgl5']);
     reject.push(dataReject['tgl6']);
     reject.push(dataReject['tgl7']);
     reject.push(dataReject['tgl8']);
     reject.push(dataReject['tgl9']);
     reject.push(dataReject['tgl10']);
     reject.push(dataReject['tgl11']);
     reject.push(dataReject['tgl12']);
     reject.push(dataReject['tgl13']);
     reject.push(dataReject['tgl14']);
     reject.push(dataReject['tgl15']);
     reject.push(dataReject['tgl16']);
     reject.push(dataReject['tgl17']);
     reject.push(dataReject['tgl18']);
     reject.push(dataReject['tgl19']);
     reject.push(dataReject['tgl20']);
     reject.push(dataReject['tgl21']);
     reject.push(dataReject['tgl22']);
     reject.push(dataReject['tgl23']);
     reject.push(dataReject['tgl24']);
     reject.push(dataReject['tgl25']);
     reject.push(dataReject['tgl26']);
     reject.push(dataReject['tgl27']);
     reject.push(dataReject['tgl28']);
     reject.push(dataReject['tgl29']);
     reject.push(dataReject['tgl30']);
     reject.push(dataReject['tgl31']);

     var ctx = document.getElementById("rejectChart").getContext('2d');
     var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [<?php 
            for($i = 1 ; $i < 32; $i++)
            {
              echo '"tgl '.$i.'",';
            }                    
            ?>],
            datasets: [{
              type: 'line',
              label: 'Standart Reject',
              data: stdReject,
              pointHoverRadius: 20,
              fill: false,
              borderColor: '#f00736',
              borderWidth: 2,
            },
            {
              label: 'Reject Ratio',
              data: reject,
              pointHoverRadius: 20,
              fill: true,
              backgroundColor: '#05d5f5',
              borderColor: '#05d5f5',
              borderWidth: 2,
            }]
          },
          options: {
            title: {
              display: false,
              text: 'Reject Ratio'
            },
            elements: {
              line: {
                tension: 0.000001
              }
            },
            responsive: true,           
            tooltips: {
              titleFontSize: 10,
              bodyFontSize: 12
            },
            legend: {
                      position: 'right', // place legend on the right side of chart
                      labels:{
                        fontSize:18
                        ,fontColor:"#fff"
                      },
                      display:false
                    },
                    scales: {
                      xAxes: [{                  
                        display: false,
                        ticks: {
                          fontSize: 8,
                          fontColor: "#fff",
                        },
                        gridLines: {
                          zeroLineColor: "#fff",
                          lineWidth: 1,
                        }
                      }],
                      yAxes: [{
                        display: false,          
                        ticks: {
                          stepSize: 1,
                          max: 10,
                          min: 0,
                          fontSize: 14,
                          fontColor: "#fff",
                          callback: function (value) {
                                return value + ''; // convert it to percentage
                              }
                            },
                            scaleLabel: {
                              display: true,
                              labelString: '',
                              fontSize: 14,
                              fontColor: "#FFF",
                            },
                            gridLines: {
                              zeroLineColor: "#fff",
                              lineWidth: 1,
                            }
                          }]
                        }
                      }
                    });
     
     //DOWNTIME
     var dataDt = <?php echo json_encode($dataDt); ?>;
     var dt = [];
     for (i = 0; i < dataDt.length; i++){
       dt.push(dataDt[i]['JML']);
     }

     var ctx = document.getElementById("downtimeChart").getContext('2d');
     var myChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: [<?php 
          for($i = 0 ; $i < count($dataDt); $i++)
          {
            $kategori = $dataDt[$i]['KATEGORI'];
            echo '"'.$kategori.'",';
          }                    
          ?>],
          datasets: [
          {
            label: 'Kategori',
            data: dt,
            pointHoverRadius: 20,
            fill: true,
            backgroundColor: ["#05d5f5",  "#e6f542", "#34eb52", "#eb7c0e", "#7c0eeb", "#0eeba9"],
            borderColor: '#fff',
            borderWidth: 2,
          }]
        },
        options: {
          title: {
            display: false,
            text: 'Line Stop'
          },
          responsive: true,           
          tooltips: {
            titleFontSize: 16,
            bodyFontSize: 18
          },
          legend: {
                    position: 'right', // place legend on the right side of chart
                    labels:{
                      fontSize:18
                      ,fontColor:"#fff"
                    },
                    display:true
                  },
                  scales: {
                    xAxes: [{                  
                      display: false,
                      ticks: {
                        fontSize: 8,
                        fontColor: "#fff",
                      },
                      gridLines: {
                        zeroLineColor: "#fff",
                        lineWidth: 1,
                      }
                    }],
                    yAxes: [{
                      display: false,          
                      ticks: {
                        stepSize: 0.1,
                        max: 0.3,
                        min: 0,
                        fontSize: 14,
                        fontColor: "#fff",
                        callback: function (value) {
                                return value + ''; // convert it to percentage
                              }
                            },
                            scaleLabel: {
                              display: true,
                              labelString: '',
                              fontSize: 14,
                              fontColor: "#FFF",
                            },
                            gridLines: {
                              zeroLineColor: "#fff",
                              lineWidth: 1,
                            }
                          }]
                        }
                      }
                    });
   });
 </script>  
</body>
</html>
