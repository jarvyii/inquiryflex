<?php
/*******************************************************
   View
   Get this Variable  $BarCode, $Machine, $Operator from Operator to be use
********************************************************/
function viewTrackingInquiry( $Operator, $Order ){
   // arguments ( $BarCode, $Machine, $Operator)
  $sColumn ='
          <div class="container text-center">
              <span class = "prodcolumn"><br>SQ Feet per Machine</span>
              <div class="row">
                   <div id="prodlist" class="prodlist col-12"></div>
              </div>
          </div>
  ';
  $sBody =' <form name="trackinginquiry"  action="ControllerInquiry.php" method="post" autocomplete="on">
        <input type="hidden" name="inquiry" value="TrackingInquiry"/>
        <!-- <input type="hidden" name="machine" id = "machine" value="<?php echo $Machine?>">
        -->
        <input type="hidden" name="operator" id = "operator" value= "' . $Operator . '"/>
        <input type="hidden" id="typeuser" name="typeuser" value="supervisor">
        <div class="trackinginquiry">
         <br><br><h3>Tracking Inquiry</h3><br>
          <!-- Order Number--> <!--
          <label class="label-inquiry" for="ordernumber">Order Number:</label>
          <input class="input-tracking" type="text" name= "ordernumber"  id="ordernumber"  value = "' . $Order . '" placeholder="Order Number/Line" autofocus><br> -->

          <!--  Bar Code -->
          <div class = " container row">
              <label class="label-inquiry" for="barcode">Order Number:</label>
              <input class="input-tracking" type="text" name= "barcode"  id="barcode" value = "' . $Order . '"  size = "20" placeholder="Bar Code/Line number" autofocus required >
             <label  for="assignment"> <input type="checkbox" id="assignment" name="assignment" value="N"> Assign location?</label>
             <label class = "location" for="location"><input type="text" id="location" name="location" size= "10" maxlength = "10" placeholder="Type Location" disabled ></label>
              
          </div>
          <br><br>
          <!-- Line Number--> <!--
          <label class="label-inquiry" for="linenumber">Line Number:</label>
          <input class="input-tracking" type="number" name = "linenumber" id="linenumber"  placeholder="Enter Line Number" required><br><br> -->
          <!-- Buttons-->
          <div class="button-trackinginquiry row">
              <div class ="col">
                <button type="reset" id ="assignlocation" class="btn-inquiry btn button-reset">Assign Location</button>
              </div>
             <div class ="col">
                <button id="search"  type="submit" class="btn-inquiry btn button-next">Search order ...</button>
             </div>
              <div class ="col">
                <button  id = "reset" type="reset" class="btn-inquiry btn button-reset">Reset</button>
              </div>
          </div>
        </div>
  </form>
  <br>
 <div class="container row text-center">
       <div class="col-md-2"></div>
       <div class="col-md-3">
         <div class="row">
            <div  id="startdate" class="col"></div>
         </div>
       </div>
       <div class="col-md-3">
         <div class="row">
            <div id="qty" class="col-4"></div>
            <div id="machine" class="col-8"></div>
         </div>
        </div>
       <div class="col-md-2">
        <div class="row">
           <div id="columnlocation" class="col-6"></div>
           <div id="grade"    class="col-6"></div>
        </div>
      </div>
      <div class="col-md-2"></div>
    </div>

  '  ;
  Head( $sBody, $sColumn );
  
  $newScript = '<script src="../js/trackinginquiry.js"></script>
                <script src="../js/showproduction.js"></script>
  ';
  Foot($newScript);
}