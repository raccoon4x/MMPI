<?php
require_once 'include/autoload.php';
$debug = isset($_GET['debug']) ? true : false;
$Poll = new Polls(377, $debug);
try {
    mmpi::getMMPI()->validate();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Опросник MMPI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <script>
        $(function(){
            'use strict';
            var ctx3 = document.getElementById('chartLine1');
            var myChart3 = new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: ['L', 'F', 'K', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'],
                    datasets: [{
                        label: '',
                        data: [<?$Poll->showDataset1();?>],
                        borderColor: '#27AAC8',
                        borderWidth: 1,
                        fill: false,
                        lineTension: 0
                    },{
                        label: '',
                        data: [<?$Poll->showDataset2();?>],
                        borderColor: '#27AAC8',
                        borderWidth: 1,
                        fill: false,
                        lineTension: 0
                    }]
                },
                options: {
                    legend: {
                        display: false,
                        labels: {
                            display: false
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                                fontSize: 10,
                                max: 130,
                                stepSize: 10
                            },
                            gridLines: {
                                // display:false,
                                color: ['','','','','','','','','#cc0000']
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                beginAtZero:true,
                                fontSize: 11
                            }
                        }]
                    }
                }
            });

            $('#savePDF').click(function () {
                var opt = {
                    margin:       0,
                    filename:     'results.pdf',
                    image:        { type: 'png', quality: 1 },
                    html2canvas:  { },
                    jsPDF:        { format: 'a3', orientation: 'portrait' }
                };
                var element = document.getElementById('pdf');
                html2pdf(element,opt);
            });
        });
    </script>
</head>
<body>
<div class="container" id="pdf">
    <div class="row pt-2">
        <div class="col-md-8">
        <h4 class="mb-3"><?$Poll->showPollTitle();?> | Результаты</h4>
        </div>
        <div class="col-4">
            <button id="savePDF" type="button" class="btn btn-primary ml-3">Сохранить в PDF</button>
        </div>
    </div>

    <p class="h6"><?$Poll->showName()?> <small class="text-muted">(<?$Poll->showSex();?>)</small></p>

    <div class="row">
        <div class="col-md-5">
            <div class="bd pd-t-30 pd-b-20 pd-x-20">
                <canvas id="chartLine1" height="250"></canvas>
            </div>
        </div>
        <div class="col-md-7">
            <?$Poll->showTableScales();?>
        </div>
    </div>

    <!--    <div class="col-md-2 order-md-1">-->
    <!--        --><?//$Poll->showTableResults();?>
    <!--    </div>-->
</div>
<script src="assets/js/Chart.js"></script>
<script src="assets/js/html2pdf.bundle.min.js"></script>
</body>
</html>
<?
} catch (Exception $e) {
    echo $e->getMessage();
}
?>