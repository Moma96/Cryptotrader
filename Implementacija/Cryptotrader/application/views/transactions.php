<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/cryptotrader/css/style.css">
    </head>
    <body class="home-body">
        <div class="container-fluid">
            
            <?php include('header.php'); ?>
            
            <div class="main-content boxed-content col-md-12">
                
                <?php include('flashdata.php'); ?>
                
                <h2 class="page-title">
                    My Transactions
                </h2>
                <div class="transactions-container white-background">
                    <table class="transactions-table">
                        <tr>
                            <th>Type</th>
                            <th>Currency</th>
                            <th>Price per unit</th>
                            <th>Amount</th>
                            <th>Total value</th>
                            <th>Opened</th>
                            <th>Closed</th>
                        </tr>
                        <script>
                            var active_asks = <?php echo json_encode($transactiondata['active_asks']); ?>;
                            if (active_asks !== null) {
                                for(var i = 0; i < active_asks.length; i++) {
                                    document.write("<tr>",
                                                        "<td class=\"transaction-ask\">",active_asks[i].type,"</td>",
                                                        "<td class=\"transaction-currency\">",active_asks[i].cryptoId,"</td>",
                                                        "<td class=\"transaction-price\">",active_asks[i].pricePU,"</td>",
                                                        "<td class=\"transaction-amount\">",active_asks[i].initAmount,"</td>",
                                                        "<td class=\"transaction-value\">",active_asks[i].pricePU*active_asks[i].initAmount,"</td>",
                                                        "<td class=\"transaction-opened\">",active_asks[i].timeOpened,"</td>",
                                                        "<td class=\"transaction-closed\">Pending...</td>",
                                                    "</tr>");
                                }
                            }
                        </script>
                        <script>
                            var active_bids = <?php echo json_encode($transactiondata['active_bids']); ?>;
                            if (active_bids !== null) {
                                for(var i = 0; i < active_bids.length; i++) {
                                    document.write("<tr>",
                                                        "<td class=\"transaction-bid\">",active_bids[i].type,"</td>",
                                                        "<td class=\"transaction-currency\">",active_bids[i].cryptoId,"</td>",
                                                        "<td class=\"transaction-price\">",active_bids[i].pricePU,"</td>",
                                                        "<td class=\"transaction-amount\">",active_bids[i].initAmount,"</td>",
                                                        "<td class=\"transaction-value\">",active_bids[i].pricePU*active_bids[i].initAmount,"</td>",
                                                        "<td class=\"transaction-opened\">",active_bids[i].timeOpened,"</td>",
                                                        "<td class=\"transaction-closed\">Pending...</td>",
                                                    "</tr>");
                                }
                            }
                        </script>
                        <script>
                            var completed = <?php echo json_encode($transactiondata['completed_transactions']); ?>;
                            if (completed !== null) {
                                for(var i = 0; i < completed.length; i++) {
                                    document.write("<tr>",
                                                        "<td class=\"transaction-",completed[i].type,"\">",completed[i].type,"</td>",
                                                        "<td class=\"transaction-currency\">",completed[i].cryptoId,"</td>",
                                                        "<td class=\"transaction-price\">",completed[i].pricePU,"</td>",
                                                        "<td class=\"transaction-amount\">",completed[i].initAmount,"</td>",
                                                        "<td class=\"transaction-value\">",completed[i].pricePU*completed[i].initAmount,"</td>",
                                                        "<td class=\"transaction-opened\">",completed[i].timeOpened,"</td>",
                                                        "<td class=\"transaction-closed\">",completed[i].timeClosed,"</td>",
                                                    "</tr>");
                                }
                            }
                        </script>
                    </table>
                </div>
            </div>
            
            <?php include('footer.php'); ?>
            
        </div>
        <script src="/cryptotrader/js/bootstrap.min.js"></script>
        <script src="/cryptotrader/js/Chart.min.js"></script>
        <script src="/cryptotrader/js/custom.js"></script>
    </body>
</html>