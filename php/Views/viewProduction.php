<?php
require_once 'class/DataAccess.php';
//require_once 'Views/viewInquiry.php';
/**************************************************************
    function  viewProduction($UserName)
**************************************************************/
function  viewProduction($BarCode, $Order, $LineNumber,$Date,$idMachine, $descMachine, $isFlitch, $Operator, $qtyCmpted, $Customer, $orderQty, $neededQty, $selectIndex, $codeItem, $itemDesc, $typePart,$panelFacBck,  $Production){ 
  
    $processTime = 5; 
   
  
  $sBody = '
  <form name="production"  id="productionform" action="ControllerInquiry.php" method="post">
        <input type="hidden" name="inquiry" value="Production"/>
        <input type="hidden" name="operator" id = "operator" value="'. $Operator. '"/>
        <input type="hidden" name="barcode" id = "barcode" value="'. $BarCode. '"/>
        <input type="hidden" name="order" id = "order" value="'. $Order. '"/>
        <input type="hidden" name="idmachine" id = "idmachine" value="'. $idMachine. '"/>
        <input type="hidden" name="isflitch" id = "isflitch" value="'. $isFlitch. '"/>
        <input type="hidden" name="panelfacbck" id = "panelfacbck" value="'. $panelFacBck. '"/>
         <input type="hidden" name="line" id = "line" value="'. $LineNumber. '"/>
        <input type="hidden" name="selectindex" id = "selectindex" value="'. $selectIndex. '"/>
        <input type="hidden" name="orderqty" id = "orderqty" value="'. $orderQty. '"/>
        <input type="hidden" name="qtycmpted" id = "qtycmpted" value="'. $qtyCmpted. '"/>
        <input type="hidden" name="itemnumber" id = "itemnumber" value="'. $codeItem. '"/>
        <input type="hidden" name="code" id = "code"/>
        <input type="hidden" name="supervisor" id = "supervisor"/>
        <input type="hidden" id="typeuser" name="typeuser" value="operator"/>
        <input type="hidden" name="starttime" id = "starttime"/>
        <input type="hidden" name="endtime" id = "endtime"/>
        <div class ="info row container">
              <div class="production-info">
                <h5 class="showuser">Operator: '. $Operator. '</h5>
                <h3 class= "titlecenter">Production Process</h3>
                <!--  Bar Code -->
                <label class="label-tracking" for="barcode">Order No.: <span class="label-content">'. $BarCode. '</span></label>
                <label class="label-tracking">Machine: <span class="label-content">'. $descMachine. '</span></label>
                <label class="label-tracking">Customer: <span class="label-content">'. $Customer. '</span></label>
                <label class="label-tracking">Order date: <span class="label-content">'. $Date. '</span></label>
                <label class="label-tracking">Order Qty: <span class="label-content">'. $orderQty. '</span></label>
                <label class="label-tracking">Qty Needed: <span class="label-content">'. $neededQty. '</span></label>
                <label class="label-tracking">Qty Completed: <span class="label-content">'. $qtyCmpted. '  '. $typePart. '</span></label>
                
                <label class="label-tracking">Item: <span class="label-content">'. $codeItem. '</span></label>
                <label class="label-tracking">Item Desc.: <span class="label-content">'. $itemDesc. '</span></label><br><br>
              </div>
        </div>
        <div class="processing container justify-content-center">
            <div class="producecontent col">
              <label  for="processtime">Processing:</label>
              <input class="processing_color titlecenter blinking" name="processtime" id="processedtime" size="10" type="text" disabled value=
              "00h:00m:00s"><br>
              <label  for="qtyproduced">Qty Produced:</label>
              <input class="quantityproduced" type="number" name="qtyproduced" id="qtyproduced"  min="0" value="0" >
              <br>
              <label  id ="flitchlabel" for="flitch"> Flitch #:
                <input   type="text" name="flitch" size= "7" maxlength = "5" id="flitch" placeholder="Number"></label>
               
              <label  for="comment">Comment: 
                <input  class="comment" type="text" name="comment" size= "50" id="comment" placeholder="Write comments if order incomplete."></label>
              <br>
            </div>
          <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
              <button id="startprod" class="btn_lg startbutton"  type="button">Start</button>
              <button id="stopprod" class="btn_lg button-reset " type="submit">Stop</button>
            </div>
            <div class="col-4"></div>
          </div> 
        </div>
  
</form>
<!-- The Modal -->
  <div  id="dialog-confirm" title="Warning">
    <p><span class="ui-icon ui-icon-alert"></span> Do you want to print a shortage ticket?</p>
  </div>
<form>
<!-- The Modal -->
  <div  id="myOverride" title="Supervisor Override Code">
    <p>  The quantity produced is less than the quantity in the order.</p>
             <label>Supervisor CODE:
                <input  class="comment" name="override" id="override" autofocus type="password"  required placeholder="Type the code."></label>
    </div>

</form>';

  
  $sColum = '
    <div class="produced  p-3 my-3 border rounded mx-auto mw-100 shadow text-success">
         <h4 class ="button-reset" >Total SQ Feet</h4>
         <div class="align-middle text-success font-weight-bold">
              <h1 class = "text-body ">'. $Production. '</h1>
         </div>
    </div>';

   Head($sBody, $sColum);
   $newScript = '<script src="../js/production.js"></script>';
  Foot($newScript);
}