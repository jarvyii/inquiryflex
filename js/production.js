$(document).ready(function(){

$('#qtyproduced').keypress(function (event){
  var keycode = (event.keyCode ? event.keyCode: event.wich);
  if( keycode == '13'){
     checkProduction();
  } 
})

$('#qtyproduced').click(function (){
   document.getElementById("stopprod").disabled =false;
   //document.getElementById("travelerbutton").disabled =false;

})
/***********************************
   Stop the Production process
***********************************/
$('#stopprod').click(function (){
    /*
     if (checkProduction() == false) {
      document.getElementById("stopprod").disabled = true;
      return;
     }
     if (document.getElementById("stopprod").disabled == true) {
      return;
     } */
     var nDate = new Date();
      document.getElementById("endtime").value = nDate.format("Y-m-d H:i:s.u");
      document.getElementById("startprod").style.display = "block";
      document.getElementById("stopprod").style.display = "none";
})

$('#stopprod').mousedown(function (){
  
   checkProduction();
   
})
/*************************************************
         overrideCode()
   To check the Supervisor Overrride Code. Its used in the view_production(), modal form.
**************************************************/
function overrideCode(){
     if ( document.getElementById("override").value === ""){
       return;
     }
     if (window.XMLHttpRequest) {
               xmlhttp = new XMLHttpRequest();
      }else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // document.getElementById("output").innerHTML = xmlhttp.responseText;
              // myObj = //JSON.parse(this.responseText);
              if (this.responseText === "") {
                 alert("Sorry. Wrong CODE");
                document.getElementById("stopprod").disabled = true;
              } else {
                 myObj = JSON.parse(this.responseText);
                 document.getElementById("code").value = myObj['CODE'];
                 document.getElementById("supervisor").value = myObj['SUPERVISOR'];
                 document.getElementById("stopprod").disabled = false;
                 $('#stopprod').click();
              }
          }
      }
      para = "&order="+document.getElementById("order").value ;
      para +="&line="+document.getElementById("line").value;
      para += "&machine="+document.getElementById("idmachine").value;
      
      str = "Override&code="+document.getElementById("override").value;
      str += para;

      xmlhttp.open("GET","../php/ControllerInquiry.php?q="+str,true);
      xmlhttp.send();  

}
//Modal form to Override
dialogoOverride =  $( "#myOverride" ).dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
          "Submit":  function() {
            $( this ).dialog( "close" );
          }
        }
      });

//  Modal form to Warning;
 dialogoWarning =  $( "#dialog-confirm" ).dialog({
          autoOpen: false,
          resizable: false,
          height: "auto",
          width: 400,
          modal: true,
          buttons: {
            "Yes":
            function() {
              dialogoOverride.dialog( "open" );
              $( this ).dialog( "close" );
            },
            "No": function() {
            //  document.getElementById("stopprod").disabled = false;
            $('#stopprod').click();
              $( this ).dialog( "close" );
            }
          }
        });
 function checkProduction(){
    var qtyProduced = parseInt(document.getElementById("qtyproduced").value);
    var qtycmpted = parseInt(document.getElementById("qtycmpted").value);
    var qtyOrder = parseInt(document.getElementById("orderqty").value);
    if (((qtyProduced + qtycmpted) > qtyOrder) && (document.getElementById("comment").value == "") ){
        alert("You have to write a comment when the quantity produced is more  than the quantity in the order.");
        document.getElementById("stopprod").disabled = true;
       return ;
     }
    if ((qtyProduced + qtycmpted) < qtyOrder){
        dialogoWarning.dialog( "open" ); 
        return;     
      }
   }


	//Begining of the JavaScript body
	//document.getElementById("barcode").onblur=checkOrder;
	// document.getElementById("qtyproduced").onblur=checkProduction;
  document.getElementById("override").onblur= overrideCode;
  // Check if qty Produced is equal or  greater than qty Ordered
  if ( parseInt(document.getElementById("qtycmpted").value) >= parseInt(document.getElementById("orderqty").value)) {
    // document.getElementById("startprod").disabled = true;
    alert("ALERT: The quantity completed is greater than or equal to the quantity ordered.");
  }
  //document.getElementById("stopprod").disabled = true;
  document.getElementById("qtyproduced").disabled = true;
  if ( document.getElementById("isflitch").value.trim() == "Y"){
      document.getElementById("flitchlabel").style.display = "block";
     } else{
        document.getElementById("flitchlabel").style.display = "none";
    }
})