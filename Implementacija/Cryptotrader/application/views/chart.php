<div class="chart-content col-md-8">
    <div class="white-background chart-background">
    <div class="currency-container">
        <img class="currency-img" src="images/<?php echo $cryptodata['cryptoId1']; ?>.png">
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
                <form method="post" action="<?php echo base_url(); ?>cryptotrader">
                    <input type="text" name="cryptoId1" class="currency-search-input-first" placeholder="Search...">
                    <input type="submit" style="height: 0px; width: 0px; border: none; padding: 0px;" hidefocus="true" />
                </form>
            </div>
        </div>
        <div class="currency-switch-box-second">
            <span class="currency-text" id="currency-2" style="text-transform:uppercase"><?php echo $cryptodata['cryptoId2']; ?></span>
            <button onclick="secondCurrency()" class="currency-switch">
                <svg class="arr-down-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 9.602 5.922" style="enable-background:new 0 0 9.602 5.922;" xml:space="preserve">
                    <g>
                        <path class="arr-down-icon" d="M9.602,1.121L8.481,0l-3.68,3.68L1.122,0L0,1.121l4.801,4.801L9.602,1.121z M9.602,1.121"/>
                    </g>
                </svg>
            </button>
            <div id="currency-search-second" class="currency-search-2">
                <form method="post" action="<?php echo base_url(); ?>cryptotrader">
                    <input type="text" name="cryptoId2" class="currency-search-input-first" placeholder="Search...">
                <input type="submit" style="height: 0px; width: 0px; border: none; padding: 0px;" hidefocus="true" />
                </form>
            </div>
        </div>
        <svg class="arr-right-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 14.398 9.602" style="enable-background:new 0 0 14.398 9.602;" xml:space="preserve">
            <g>
                <path class="arr-right-icon" d="M0,5.602h11.359L8.476,8.481l1.121,1.121l4.801-4.801L9.597,0L8.476,1.122L11.359,4H0V5.602z M0,5.602"/>
            </g>
        </svg>
        <span class="currency-text" id="currency-result"><?php echo $cryptodata['price']; ?></span>
    </div>
    <div class="changes-wrapper">
        <div class="changes-container">
            <div class="change">
                <div class="stats-text">
                    24 HOUR CHANGE
                </div>
                <div class="value">
                    <span class="change-value-text value-text">+22.25%</span>
                    <img id="change-value-arr" src="images/arrow-up.png">
                </div>
            </div>
            <div class="change">
                <div class="stats-text">
                    24 HOUR VOLUME
                </div>
                <div class="value">
                    <span class="value-text">295.722... BTC</span>
                </div>
            </div>
            <div class="change">
                <div class="stats-text">
                    <?php echo strtoupper($cryptodata['type']); ?> LOW
                </div>
                <div class="value">
                    <span class="value-text"><?php echo strtoupper($cryptodata['chartdata']['low']); ?></span>
                    <img id="low-value-arr" src="images/arrow-down.png">
                </div>
            </div>
            <div class="change">
                <div class="stats-text">
                    <?php echo strtoupper($cryptodata['type']); ?> HIGH
                </div>
                <div class="value">
                    <span class="value-text"><?php echo strtoupper($cryptodata['chartdata']['high']); ?></span>
                    <img id="change-value-arr" src="images/arrow-up.png">
                </div>
            </div>
        </div>
        <form method="post" action="<?php echo base_url(); ?>cryptotrader">
            <div class="time-span">
                <select name="timespan" id="timespan" onchange="this.form.submit();">
                    <option value="1d" <?php if($this->input->post('timespan') == "1d") echo "selected" ?>>1 day</option> <!--ULEPSAJ MALO OVO!!!!!!!!!!!!-->
                    <option value="1w" <?php if($this->input->post('timespan') == "1w") echo "selected" ?>>1 week</option>
                    <option value="1m" <?php if($this->input->post('timespan') == "1m") echo "selected" ?>>1 month</option>
                    <option value="6m" <?php if($this->input->post('timespan') == "6m") echo "selected" ?>>6 months</option>
                    <option value="1y" <?php if($this->input->post('timespan') == "1y" || !$this->input->post('timespan')) echo "selected" ?>>1 year</option>
                </select>
            </div>
        </form>
    </div>
    <div class="chart">
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>
    </div>
</div>
