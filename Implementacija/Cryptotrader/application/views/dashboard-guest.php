<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body class="home-body">
        <div class="container-fluid">
			
            <?php include('header-guest.php'); ?>
            
            <div class="main-content boxed-content">
                
                <?php include('flashdata.php'); ?>
                
                <div class="row">
                    
                    <?php include('chart.php'); ?>
                    
                    <div class="news-content col-md-4">
                        <div class="news-container white-background">
                            <script type="text/javascript" src="https://static.cryptorival.com/js/newswidget.js"></script>
                            <a id="cr-copyright" href="https://cryptorival.com/" target="_blank" rel="nofollow">Powered by CryptoRival</a>
                            <script type="text/javascript">
                            showNews('320', false, '0', '022B4B', '1576d0', '1576d0', '777777', '500');
                            </script>
                        </div>
                    </div>
                    <div class="row carbon-top-ad">
                    <div class="carbon-container">
                    <div class="azure-square">
                        <svg version="1.1" id="svg8159" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="-99 280.8 297.6 232.2" style="enable-background:new -99 280.8 297.6 232.2; width:23px; height:19px;" xml:space="preserve">
                            <style type="text/css">
                                .azure{fill:#ffffff;}
                            </style>
                            <path id="path7291" inkscape:connector-curvature="0" class="azure" d="M85.8,315.8l-44,88.7l77.5,89.8l-143.9,16.3l223.2,2.3
                                   L85.8,315.8L85.8,315.8z"/>
                            <path id="path7293" inkscape:connector-curvature="0" class="azure" d="M77.1,280.8L-24,365.4L-99,492l63.9-6.4L77.1,280.8z"/>
                        </svg>
                    </div>
                    <span class="carbon-top-title">MICROSOFT AZURE</span>
                    <span class="carbon-top-text">Your Next Great App Awaits - Finish Signing Up for Azure</span>
                    <div class="sponsored-box">
                        <span class="sponsored-txt">SPONSORED</span>
                    </div>
                    </div>
                    </div>
                </div>
                
            </div>
            
            <?php include('footer.php'); ?>
            
        </div>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/Chart.min.js"></script>
        <script src="js/custom.js"></script>
        <script>
                createChart(<?php echo json_encode($cryptodata['chartdata']); ?>);
                closeLogin();
        </script>
    </body>
</html>