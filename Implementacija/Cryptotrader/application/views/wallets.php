<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/cryptotrader/css/style.css">
    </head>
    <body>
        <div class="container-fluid">
            
            <?php include('header.php'); ?>
            
            <div class="main-content boxed-content">
                <div class="col-md-6">
                <h2>My Wallets</h2>
                <div class="wallet-container white-background">
                    <table class="wallet-table">
                        <tr>
                            <th>Currency</th>
                            <th>Total</th>
                            <th>Reserved</th>
                            <th>Available</th>
                        </tr>
                        <script>
                            var wallets = <?php echo json_encode($wallets); ?>;
                            for(var i = 0; i < wallets.length; i++) {
                                document.write("<tr>",
                                                    "<td>",wallets[i].cryptoId,"</td>",
                                                    "<td>",wallets[i].amount,"</td>",
                                                    "<td>",wallets[i].amount - wallets[i].avail,"</td>",
                                                    "<td>",wallets[i].avail,"</td>",
                                                "</tr>");
                            }
                        </script>
                    </table>
                </div>
            </div>
            </div>
            
            <?php include('footer.php'); ?>
            
        </div>
        <script src="/cryptotrader/js/bootstrap.min.js"></script>
        <script src="/cryptotrader/js/Chart.min.js"></script>
        <script src="/cryptotrader/js/custom.js"></script>
    </body>
</html>