 $(document).ready(function(){ 

/*******************************
Every 3 minutes call myProduction(); to update the INFO in the Bar Chart Diagram.
******************************/  

    function  updatemyProduction() {

      setInterval(function() {
        
           myProduction();   
          }, 120000); // update SQ Feet Production every 2 Minutes

    }


//updatemyProduction();


   

function  updateGraphInfo( myObj ) {
     if ( (myObj == "") || ( myObj == null)) {
          return;
      }
   // Main chart  Graph
   
      var ctxB = document.getElementById("barChart").getContext('2d');
      var myBarChart = new Chart(ctxB, {
                        type: 'bar',
                        data: {
                               // labels: ["Testing", "Mach02", "Mach03", "Mach04", "Mach05", "Mac06", "Mach07"],

                                
                                  labels : [ ],
                                datasets: [{
                                            label: "SQ Feet Production",
                                          //  fillColor: "#fff",
                                            backgroundColor : [],
                                            //backgroundColor: 'rgba(255, 255, 255, .3)',
                                            borderColor : [],
                                            // borderColor: 'rgba(255, 255, 255)',
                                            data: [0, 10, 5, 2, 20, 30, 45],
                                            }]
                                },  
                                options:   {
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}

/*

                        options: {
                                    legend: {
                                              labels: {
                                                          fontColor: "#fff",
                                                      }
                                            },
                                    scales: {
                                              xAxes: [{
                                                        gridLines: {
                                                                        display: true,
                                                                       // color: "rgba(255,255,255,.25)"
                                                                    },
                                                        ticks: {
                                                                    fontColor: "#fff",
                                                                },
                                                    }],
                                              yAxes: [{
                                                        display: true,
                                                        gridLines: {
                                                                        display: true,
                                                                     //   color: "rgba(255,255,255,.25)"
                                                                    },
                                                        ticks: {
                                                                    fontColor: "#fff",
                                                                    beginAtZero: true
                                                                },
                                                    }],
                                            }
                                    } */
                        });


/*
var ctxB = document.getElementById("barChart").getContext('2d');
var myBarChart = new Chart(ctxB, {
type: 'bar',
data: {
labels: [],
datasets: [{
label: '# of Votes',
data: [12, 19, 3, 5, 2, 3],
backgroundColor: [
'rgba(255, 99, 132, 0.2)',
'rgba(54, 162, 235, 0.2)',
'rgba(255, 206, 86, 0.2)',
'rgba(75, 192, 192, 0.2)',
'rgba(153, 102, 255, 0.2)',
'rgba(255, 159, 64, 0.2)'
],
borderColor: [
'rgba(255,99,132,1)',
'rgba(54, 162, 235, 1)',
'rgba(255, 206, 86, 1)',
'rgba(75, 192, 192, 1)',
'rgba(153, 102, 255, 1)',
'rgba(255, 159, 64, 1)'
],
borderWidth: 1
}]
},
options: {
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});
*/
 // var myObj =  myProduction();
 
  var i = 0;
var Color = [
'rgba(255, 99, 132, 0.2)',
'rgba(54, 162, 235, 0.2)',
'rgba(255, 206, 86, 0.2)',
'rgba(0, 0, 102, 0.2)',
'rgba(153, 102, 255, 0.2)',
'rgba(0, 51, 0, 0.2)',
'rgba(112, 219, 112, 0.2)',
'rgba(255, 255, 77, 0.2)',
'rgba(122, 31, 31, 0.2)',
'rgba(75, 192, 192, 0.2)',
'rgba(153, 102, 255, 0.2)',
'rgba(255, 159, 64, 0.2)'
]

 for (x in myObj) {
     
     myBarChart.config.data.labels[i] =  myObj[x].MACHDESC;
     myBarChart.config.data.datasets[0].data[i] =  myObj[x].PRODUCTION;
     myBarChart.config.data.datasets[0].backgroundColor[i] = Color[i];
     myBarChart.config.data.datasets[0].borderColor[i]= Color[i];
      i++;
   }
  
   alert( myBarChart.config.data.datasets[0].data);

   /*
  alert ( myBarChart.config.data.labels[3]);
 alert ( myBarChart.config.data.datasets[0].data[0]);
 */
  //document.getElementById("prodlist").innerHTML =  fullList;
}


/*************************************************************
  Update the Value of the Square Feet Production 
  per Machine per Shifts 
************************************************************/
function myProduction() {
   
   if (window.XMLHttpRequest) {
               xmlhttp = new XMLHttpRequest();
      }else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
           
             if (this.responseText === "") {
                  //document.getElementById("prod").innerHTML = "0";
               } else {
                    myObj = JSON.parse(this.responseText);
                    updateGraphInfo( myObj );
                 
              }
          }
      }
    
      // var idMachine = document.getElementById("machine").value.substr(1);
     
      para = "Dailyprod"; // To know the Daily Production per machine
      xmlhttp.open("GET","../php/ControllerInquiry.php?q="+para,true);
      xmlhttp.send();  
  
}




    // Tooltip Initialization
    $(function () {
    $('[data-toggle="tooltip"]').tooltip()
    })

/***********************************************
 Add new row to the information Table on the Dashboard,
 from the historic table.
 ***********************************************/
 function addRow( myObj ){
  var newRow = ""; // "<tr><th scope='row'>Machine</th><td>555</td><td>888</td><td>999</td></tr>";
  for( x in myObj ) {
   
    newRow += "<tr><th scope='row'>"+ myObj[x].MACHDESC + "</th><td>"+ myObj[x].ORDERS + "</td><td>"+ "  "+"</td><td>"+ myObj[x].QTY+ "</td></tr>";

  }
  document.getElementById("dashboardrow").innerHTML += newRow;
  } 
 /**************************************
 call a PHP Function and return Total of Orders, Total Machine Time and Total Qty Produced 
 per Machine 
 ***************************************/ 
function updateTable() {
   
   if (window.XMLHttpRequest) {
               xmlhttp = new XMLHttpRequest();
      }else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
             
             if (this.responseText === "") {
                  //document.getElementById("prod").innerHTML = "0";
               } else {
                    myObj = JSON.parse(this.responseText);
                    addRow(myObj);
                 
              }
          }
      }
    
      // var idMachine = document.getElementById("machine").value.substr(1);
    
      para = "Machprod"; // To know Total of Orders, Total Machine Time and Total Qty Produced  per Machine 
      xmlhttp.open("GET","../php/ControllerInquiry.php?q="+para,true);
      xmlhttp.send();  
  
} 

 myProduction();
  
 updateTable();
 //  document.getElementById("prodlist").innerHTML =  fullList;

})
 
 