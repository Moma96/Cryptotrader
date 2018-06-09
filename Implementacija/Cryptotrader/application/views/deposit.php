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
                
                <?php include('flashdata.php'); ?>
                
                <div class="col-md-12 page-title">
                    <h2>Deposit</h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="deposit-container white-background">
                            <div class="deposit-fields-row">
                                <span class="deposit-field-label">Select currency: </span>
                                <img class="deposit-currency-img" src="/cryptotrader/images/<?php echo $cryptodata['cryptoId1']; ?>.png">
                                <div class="currency-switch-box-first">
                                    <span class="currency-text" id="currency-1" style="text-transform:uppercase"><?php echo $cryptodata['cryptoId1']; ?></span>
                                    <button onclick="firstCurrency()" class="currency-switch">
                                        <svg class="arr-down-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                         viewBox="0 0 9.602 5.922" style="enable-background:new 0 0 9.602 5.922;" xml:space="preserve">
                                            <g>
                                                <path class="arr-down-icon" d="M9.602,1.121L8.481,0l-3.68,3.68L1.122,0L0,1.121l4.801,4.801L9.602,1.121z M9.602,1.121"/>
                                            </g>
                                        </svg>
                                    </button>
                                    <div id="currency-search-first" class="currency-search-1">
                                        <form method="post" action="<?php echo base_url(); ?>transfering/deposit">
                                            <input type="text" name="cryptoId1" class="currency-search-input-first" placeholder="Search...">
                                            <input type="submit" style="height: 0px; width: 0px; border: none; padding: 0px;" hidefocus="true" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="deposit-fields-row">
                                <form method="post" action="<?php echo base_url(); ?>transfering/deposit">
                                    <span class="deposit-field-label">Amount: </span>
                                    <input class="deposit-amount-input" type="text" name="amount" placeholder="0.00">
                                    <input class="submit-btn" type="submit" value="Submit">
                                </form>
                            </div>
                        </div>
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