function createChart(chartdata){
    var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartdata['times'],
        datasets: [{
            data: chartdata['prices'],
            backgroundColor: [
                'rgba(243, 247, 250, 1)',
                'rgba(0, 0, 0, 0)',
                'rgba(0, 0, 0, 0)',
                'rgba(0, 0, 0, 0)',
                'rgba(0, 0, 0, 0)',
                'rgba(0, 0, 0, 0)'
            ],
            borderColor: [
                'rgba(42,118,221,1)',
            ],
            borderWidth: 3, 
            radius: 0, 
            tension: 0.1, 
            fill: true,
            pointHoverBackgroundColor: 'rgba(63, 67, 70, 1)',
            pointHoverBorderColor: 'rgba(63, 67, 70, 1)'
            
        }]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true, 
                    max: chartdata['max'],
                    min: chartdata['min'],
                    stepSize: chartdata['stepSize']
                }, 
            }], 
            xAxes:[{
                ticks: {
                    
                }
            }]
        },
        legend: {
        display: false
    },
        tooltips: {
            callbacks: {
                label: function(tooltipItem) {
                    return tooltipItem.yLabel;
                }
            },
            backgroundColor: 'rgba(63, 67, 70, 1)',
            displayColors: false,
            bodyFontColor: '#fff',
            bodyFontSize: 14,
            xPadding: 10,
            yPadding: 5,
            yAlign: 'bottom'
        }, 
        hover: {
         onHover: function(e) {
            var point = this.getElementAtEvent(e);
            if (point.length) e.target.style.cursor = 'pointer';
            else e.target.style.cursor = 'default';
         }
      }
    }
    });

}

function showLogin(){
    document.getElementById('login-window').style.display="block";
}


function closeLogin(){
    var modal = document.getElementById("login-window");
    window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
}

function toggleCurrencies(){
    var dropdown = document.getElementById('deposit-dropdown-menu');
    
    if(dropdown.style.display!="block"){
        dropdown.style.display="block";
    }
    else{
        dropdown.style.display="none";
    }
}

function firstCurrency(){
    var dropdown = document.getElementById('currency-search-first');
    if(dropdown.style.display != "block"){
        dropdown.style.display="block";
    }
    else{
        dropdown.style.display="none";
    }
   
}

function secondCurrency(){
    var dropdown = document.getElementById('currency-search-second');
    if(dropdown.style.display != "block"){
        dropdown.style.display="block";
    }
    else{
        dropdown.style.display="none";
    }
}