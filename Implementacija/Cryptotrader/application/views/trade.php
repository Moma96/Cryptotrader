<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto|Montserrat" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body class="trade-body">
        <div class="container-fluid">
        
            <?php include('header.php'); ?>
            
            <div class="boxed-content">
                
                <?php include('flashdata.php'); ?>
                
                <div class="row currency-info-row">
                    <div class="col-md-12">
                    <div class="currency-info-container white-background">
                    <div class="row currency-row">
                        <div class="trade-logo-container">
                            <img class="trade-logo" src="images/<?php echo $cryptodata['cryptoId1']; ?>.png">
                        </div>
                        <div class="trade-currency">
                            <?php echo $cryptodata['name']; ?> - <?php echo strtoupper($cryptodata['cryptoId1']); ?>
                        </div>
                        <div class="currency-switch-box-first">
                            <button onclick="firstCurrency()" class="currency-switch">
                                <svg class="arr-down-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 9.602 5.922" style="enable-background:new 0 0 9.602 5.922;" xml:space="preserve">
                                    <g>
                                        <path class="arr-down-icon" d="M9.602,1.121L8.481,0l-3.68,3.68L1.122,0L0,1.121l4.801,4.801L9.602,1.121z M9.602,1.121"/>
                                    </g>
                                </svg>
                            </button>
                            <div id="currency-search-first" class="currency-search-1">
                                <form method="post" action="<?php echo base_url(); ?>trade">
                                    <input type="text" name="cryptoId1" class="currency-search-input-first" placeholder="Search...">
                                    <input type="submit" style="height: 0px; width: 0px; border: none; padding: 0px;" hidefocus="true" />
                                </form>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="changes-container">
                            <div class="change">
                                <div class="stats-text">
                                    CURRENT VALUE
                                </div>
                                <div class="value">
                                    <span class="change-value-text value-text current-value-text"><?php echo strtoupper($cryptodata['price']); ?></span>
                                </div>
                            </div>
                            <div class="change">
                                <div class="stats-text">
                                    <?php echo strtoupper($cryptodata['type']); ?> CHANGE
                                </div>
                                <div class="value">
                                    <?php $change = $cryptodata['chartdata']['change'];
                                        if ($cryptodata['chartdata']['change'] >= 0) {
                                            echo "<span class=\"change-value-positive value-text\">+$change%</span>".
                                                 "<img id=\"change-value-arr\" src=\"images/arrow-up.png\">";
                                        } else {
                                            echo "<span class=\"change-value-negative value-text\">$change%</span>".
                                                 "<img id=\"change-value-arr\" src=\"images/arrow-down.png\">";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="change">
                                <div class="stats-text">
                                    <?php echo strtoupper($cryptodata['type']); ?> VOLUME
                                </div>
                                <div class="value">
                                    <span class="value-text">295.722... USDT</span>
                                </div>
                            </div>
                            <div class="change">
                                <div class="stats-text">
                                    <?php echo strtoupper($cryptodata['type']); ?> LOW
                                </div>
                                <div class="value">
                                    <span class="value-text"><?php echo $cryptodata['chartdata']['low']; ?></span>
                                    <img id="low-value-arr" src="images/arrow-down.png">
                                </div>
                            </div>
                            <div class="change">
                                <div class="stats-text">
                                    <?php echo strtoupper($cryptodata['type']); ?> HIGH
                                </div>
                                <div class="value">
                                    <span class="value-text"><?php echo $cryptodata['chartdata']['high']; ?></span>
                                    <img id="change-value-arr" src="images/arrow-up.png">
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-8">
                    <div class="buy-box white-background">
                        <form method="post" action="<?php echo base_url(); ?>trade/buy">
                            <div class="buy-title">
                                Buy <span class="buy-currency-name" style="text-transform:uppercase" ><?php echo $cryptodata['cryptoId1']; ?></span>
                            </div>
                            <div class="price-box">
                                <input class="sell-input" type="text" id="buy-price" name="buy-price" placeholder="Price">
                                <span class="buy-currency-name-price">USD</span>
                            </div>
                             <div class="quantity-box">
                                <input class="buy-input" type="text" id="buy-quantity" name="buy-quantity" placeholder="Quantity">
                                <span class="buy-currency-name-price">BTC</span>
                            </div>
                            <div class="available-text">
                                Available <span class="available-amount">$<?php echo $userdata['availableUSDT']; ?></span>
                            </div>
                            <button class="buy-btn" type="submit" formmethod="post">
                                BUY <span class="buy-currency-name" style="text-transform:uppercase" ><?php echo $cryptodata['cryptoId1']; ?></span>
                            </button>
                        </form>
                    </div>
                    <div class="sell-box white-background">
                        <form method="post" action="<?php echo base_url(); ?>trade/sell">
                            <div class="sell-title">
                                Sell <span class="buy-currency-name" style="text-transform:uppercase" ><?php echo $cryptodata['cryptoId1']; ?></span>
                            </div>
                            <div class="price-box">
                                <input class="sell-input" type="text" id="sell-price" name="sell-price" placeholder="Price">
                                <span class="buy-currency-name-price">USD</span>
                            </div>
                             <div class="quantity-box">
                                <input class="sell-input" type="text" id="sell-quantity" name="sell-quantity" placeholder="Quantity">
                                <span class="buy-currency-name-price">BTC</span>
                            </div>
                            <div class="available-text">
                                Available <span class="available-amount"><?php echo $userdata['availableCurr']." ".strtoupper($cryptodata['cryptoId1']); ?></span>
                            </div>
                            <button class="sell-btn" type="submit" formmethod="post">
                                SELL <span class="buy-currency-name" style="text-transform:uppercase" ><?php echo $cryptodata['cryptoId1']; ?></span>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="white-background ask-bid-background">
                    <div class="ask-bid-container ask-container">
                        <div class="ask-bid-title">
                            Asks
                        </div>
                        <div class="price-quantity-header">
                            <div class="price-header">
                                Price
                            </div>
                            <div class="quantity-header">
                                Quantity
                            </div>
                        </div>
                        <script>
                            var asks = <?php echo json_encode($transactiondata['asks']); ?>;
                            for(i = 0; i < asks.length; i++) {
                                document.write("<div class=\"price-quantity-values\" onclick=\"clickedTransaction('ask',",asks[i].pricePU,",", asks[i].availAmount,")\">",
                                                "<span class=\"price-val\">$",asks[i].pricePU,"</span>",
                                                "<span class=\"quantity-val\">",asks[i].availAmount,"</span></div>");
                            }
                        </script>
                        </div>
                     <div class="ask-bid-container bid-container">
                        <div class="ask-bid-title">
                            Bids
                        </div>
                        <div class="price-quantity-header">
                            <div class="price-header">
                                Price
                            </div>
                            <div class="quantity-header">
                                Quantity
                            </div>
                        </div>
                        <script>
                            var bids = <?php echo json_encode($transactiondata['bids']); ?>;
                            for(i = 0; i < bids.length; i++) {
                                document.write("<div class=\"price-quantity-values\" onclick=\"clickedTransaction('bid',",bids[i].pricePU,",", bids[i].availAmount,")\">",
                                                "<span class=\"price-val\">$",bids[i].pricePU,"</span>",
                                                "<span class=\"quantity-val\">",bids[i].availAmount,"</span></div>");
                            }
                        </script>
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
            function clickedTransaction(type, price, quantity) {
                document.getElementById('buy-price').value = (type === 'ask' ? price : '');
                document.getElementById('buy-quantity').value = (type === 'ask' ? quantity : '');
                document.getElementById('sell-price').value = (type === 'bid' ? price : '');
                document.getElementById('sell-quantity').value = (type === 'bid' ? quantity : '');
            }
        </script>
    </body>
</html>