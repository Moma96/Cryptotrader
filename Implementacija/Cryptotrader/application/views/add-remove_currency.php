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
            
            <div class="row main-content boxed-content">
                
                <?php include('flashdata.php'); ?>
                
                <div class="col-md-6">
                    <div class="add-currency-box white-background">
                        <form class="add-currency-form" method="post" action="<?php echo base_url(); ?>admin/add_currency">
                            <div class="add-remove-title">
                                Add Currency
                            </div>
                            <div class="add-remove-input-box">
                                <label class="add-remove-label">Currency Name: </label>
                                <input name="add-currency" type="text" class="add-currency-input">
                            </div>
                            <div class="add-remove-input-box">
                                <label class="add-remove-label">Currency ID: </label>
                                <input name="add-id" type="text" class="add-currency-input">
                            </div>
                                <input class="submit-btn currency-btn" type="submit" value="Submit">
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="add-currency-box white-background">
                        <form class="remove-currency-form" method="post" action="<?php echo base_url(); ?>admin/remove_currency">
                            <div class="add-remove-title">
                                Remove Currency
                            </div>
                            <div class="add-remove-input-box">
                                <label class="add-remove-label">Currency Name: </label>
                                <input name="remove-currency" type="text" class="remove-currency-input">
                            </div>
                            <div class="add-remove-input-box">
                                <label class="add-remove-label">Currency ID: </label>
                                <input name="remove-id" type="text" class="remove-currency-input">
                            </div>
                                <input class="submit-btn currency-btn" type="submit" value="Submit">
                        </form>
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