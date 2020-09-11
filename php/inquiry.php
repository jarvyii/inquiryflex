<?php 
require_once 'class/DataAccess.php';
require_once 'Views/viewProduction.php';
require_once 'Views/viewInquiry.php';
require_once 'Views/ViewTracking.php';
require_once 'Views/viewTrackingDisplay.php';
require_once 'Views/viewTrackingInquiry.php'; 
require_once 'Views/viewTrackingInformation.php';
//require_once '/PHP/Library/ToolkitService.php';

/*************************************************
       getLocHistory($Order)
    Return the historic of one order from the table FMLOCHIST
**************************************************/
function getLocHistory($OrderNumber, $Line ){
   
   $db_conn = new DataAccess(); 
   $tracksLoc = $db_conn ->getTrackLocHistory($OrderNumber, $Line);
   //echo "Line:". $Line;
   echo json_encode($tracksLoc);
   //return $tracksLoc; 

}
/********************************************
 Access to DB2 Files to get the Machines Info
    Id & Description
/********************************************/
function Machines(){
   $db_conn = new DataAccess(); 
   $Machines = $db_conn ->getMachines();
   //echo "Line:". $Line;
   echo json_encode($Machines);
}


function checkPanel($EIFGD, $EIBGD) {
    if ( trim($EIFGD) == trim($EIBGD) ) {
        return "N";
      } else {
        return "Y";
    }

}
/********************************************
     checkOrder($Order)
    Return if the one specific order exist the Database.
*********************************************/
function checkOrder($Order, $Line){
   $db_conn = new DataAccess(); 
   $tracksLoc = $db_conn ->checkOrder($Order, $Line);
   // To get Item Number
   $headOI = $db_conn ->getOrderItem($Order, $Line);
   $codeItem = $headOI['EIPN'];

   $product =  $db_conn->getItemDesc(trim($codeItem)); 
   $pmClas = $product['PMCLAS']; 
   // To know if the product is a Panel ( Face & Back)
   if (trim($pmClas) == '08' or trim($pmClas) == '09') {

        $tracksLoc['PANEL'] = checkPanel( $headOI['EIFGD'], $headOI['EIBGD']);
       } else {
         $tracksLoc['PANEL'] = "N";
       }


   if( $tracksLoc) {
      echo json_encode($tracksLoc);
   } else {
     echo "";
   }
}
/****************************************************
 Print Traveler Print Queue R3
/*****************************************************/
function callCLTraveler( $Order, $Line ) {
	 require_once '/PHP/Library/ToolkitService.php';
    // Setup Database Connection to Call RPG/CLLE
    $db = db2_connect( '*LOCAL', '', '', array( 'i5_naming' => '1' ) );
        // Connect to toolkit using existing DB2 conn
    $tkitConn = ToolkitService::getInstance( $db );
    // Toolkit will share job with DB2
    $tkitConn->setOptions( array('stateless' => true) );
   // Call commandline to ENABLE User profile
    $Para = " PARM(  '$Order'  '$Line' 'R' )";
    //$rBoolean =  $tkitConn->CLCommand( 'CALL PGM(FLEXWEB/TRAVSHCL )'.$Para );  
    $rBoolean =  $tkitConn->CLCommand( 'CALL PGM(FLEXLIB/TRAVSHCL )'.$Para );  
    // CLCommand( 'CALL PGM(FLEXWEB/TRAVSHCL )  PARM( '678695' ' 1' 'R' ) );
    // CLCommand( 'CALL PGM(FLEXLIB/TRAVSHCL )  PARM( '678695' ' 1' 'R' ) );
    $tkitConn->setToolkitServiceParams( array( 'InternalKey'=>"/PHP/bin/ZendUser",
                                               // 'debug' => true,
                                               'plug'  => "iPLUG1M" ) ); 
  // unset connection
    unset( $tkitConn ); 
}
function callPrintTraveler( $Order, $Line ) { 
    callCLTraveler( $Order, $Line );
    echo "Sent";
 }
/***********************************************
Print Shortage Ticket in R4 Printer Queue
/************************************************/
function callCL($Order, $Line, $Machine )  {
  //////////////////////////////////////////////////////////////////////////////////////////////

    /*
    if ( !$this->user && $this->status !== '*ENABLED' ) {
         $this->debug['ERROR'] = "User Id is not correctly set or User profile is already *ENABLED. Please set User ID first from method::setUserId() before calling ::callCL(); User Id value is FALSE."; 
         return false;
      }

      */



    require_once '/PHP/Library/ToolkitService.php';
    // Setup Database Connection to Call RPG/CLLE
    $db = db2_connect( '*LOCAL', '', '', array( 'i5_naming' => '1' ) ); /* array( 'i5_commit' => DB2_I5_NAMING_ON ) */
        // Connect to toolkit using existing DB2 conn
    // $tkitConn = ToolkitService::getInstance( $db, DB2_I5_NAMING_ON );
    $tkitConn = ToolkitService::getInstance( $db );
  // Toolkit will share job with DB2
    $tkitConn->setOptions( array('stateless' => true) );
  // Call commandline to ENABLE User profile
    // $tkitConn->CLCommand( 'CHGUSRPRF USRPRF('. $this->user .') STATUS(*ENABLED) ' );
    $Para = " PARM(  '$Order'  '$Line' 'S' '$Machine' )"; //
    // Example of command Line $Para = " PARM( '678695' ' 1' 'MACH02')" ;
   $rBoolean =  $tkitConn->CLCommand( 'CALL PGM(FLEXLIB/TRAVSHRTCL )'.$Para );  
   //$rBoolean =  $tkitConn->CLCommand( 'CALL PGM(FLEXWEB/TRAVSHRTC1 )'.$Para ); 
   	//PARM( '678695' ' 1' 'MACH02') );

     //TRAVSHRTC1
     // CLCommand( 'CALL PGM(FLEXWEB/TRSHSHRTPR )  PARM( '678695' ' 1' 'MACH02' ) );
    // CLCommand( 'CALL PGM(FLEXLIB/TRAVSHRTC1 )  PARM( '679388' ' 1' 'MACH03' ) );



     
     $tkitConn->setToolkitServiceParams( array( 'InternalKey'=>"/PHP/bin/ZendUser",
                                               // 'debug' => true,
                                               'plug'  => "iPLUG1M" ) ); 
  // unset connection
    unset( $tkitConn );
    /*
    $this->updated = true;
    $this->Results = "User Profile $this->user was flagged as *DISABLED by MIA Systems and Triggered through the Message Montior in WRKMSG QSYSMSG Outq. Program has successfully re-enabled ". $this->name . "'s Profile.";
    return $this->Results; */
  //////////////////////////////////////////////////////////////////////////////////////////////
  }



/****************************************
        checkOverrideCode($Code)
 Access the Database to validate the Code to override the production
 Its called from ControllerInquiry.php
******************************************/
function checkOverrideCode($Code, $Order, $Line, $Machine){
   $db_conn = new DataAccess(); 
   $Supervisor = $db_conn ->checkOverrideCode($Code); // Return True if the name of supervisor.
   if( $Supervisor) {

         
        // Start call RPG
        callCL( $Order, $Line, $Machine ); //Calling  RPG Program

       //  include("FLEXLIB");
 
       //   $query = "CALL TRSHSHRTPR('658666'  '  1'  'MACH01')";
         // $result =  $db_conn -> CLCommand(CALL PGM( TRSHSHRTPR('658666'  '  1'  'MACH01') ) ); // odbc_exec($connection, $query);
       // 	$result =  $db_conn -> CLCommand($query);
          /*
          if ($result) {
            if (odbc_fetch_into($result,$row)!=FALSE)
              echo $row[0];
          }
          */

     // End call RPG




      echo json_encode($Supervisor);
   } else {
     echo "";
   }
}

/******************************************************
Display all info using the Variable:
$BarCode, $OrderCode, $LineNumber
*******************************************************/
function TrackingDisplay($OrderNumber, $LineNumber, $Customer, $orderDate, $orderQtty, $Item, $Operator) {

    viewTrackingDisplay($OrderNumber, $LineNumber);
   // $objData = new DataAccess(); 
    //$headOrder = $Order -> getOrderHeader($OrderNumber, $LineNumber, $Machine);
   // $headOI = $Order ->getOrderItem($OrderNumber, $LineNumber);
   // $tracksLoc = $objData ->getTrackLocHistory($OrderNumber);
    viewHead( $OrderNumber, $LineNumber, $Customer, $orderDate, $orderQtty, $Item, $Operator);//$headOI);//$headOrder, $headOI);
   
//testing
  //     $objData = new DataAccess(); 
  //    $objData -> testFMLOCHIST($OrderNumber, $LineNumber);


} //TrackingDisplay

/**************************************
Display the Tracking Information
***************************************/

function TrackingInformation ($OrderNumber, $Operator) {
   $Pos = strpos($OrderNumber, "/");;//strpos($Barcode, "/");
   if ( ($Pos >= 0) and !empty($Pos) ){
       $Order = substr($OrderNumber,0, $Pos);
       $LineNumber =  substr($OrderNumber, $Pos+1);

       if (!empty($LineNumber)){ 
           $objData = new DataAccess(); 
           if ( !empty($objData->checkOrder($Order, $LineNumber))) {  
              
          
             $headOrder = $objData -> getOrderHeader($Order, $LineNumber);
             //Order Item info.
             $headOI = $objData ->getOrderItem($Order, $LineNumber);
             $codeItem = $headOI['EIPN'];
             $headDesc = $objData ->getItemDesc( $codeItem);
             $itemDesc =  $headDesc['PMDESC'];//$objData ->getItemDesc( $codeItem);
             $orderQty = (int) $headOI['EICCQ'];
             $orderQty = calculateOrderQty( $headDesc['PMCLAS'], $headOI['EIFGD'], $headOI['EIBGD'], $orderQty);

             viewTrackingInformation($Order, $LineNumber, $Operator,$orderQty, $headOrder, $headOI);
             return;
          }
        }
     }
   TrackingInquiry($Operator);
   
      
}//TrackingInformation ()


/********************************************
 Asign a Locaction to an specific order
*********************************************/
function assignLoc(  $Order, $Line, $Location ){
  
  $dDate = date("Y-m-d-00.00.00");
  $objData = new DataAccess();
  //echo "L:".$line;
  $updateLoc = $objData -> assignLocation($Order, $Line, $Location, $dDate);   
  echo  $updateLoc;

}


/*********************************************
 return all production for an specific Order in one Shifts
**********************************************/
function shiftsOrder( $Order, $Line){

 $dDate = date("Y-m-d-00.00.00");
 $objData = new DataAccess();
 $shiftsOrder = $objData -> shiftsOrder($Order, $Line, $dDate); 

  echo json_encode($shiftsOrder);



}
 /**************************************
 call a PHP Function (DataAccess ), to acces DB2 Database, and return Total of Orders, Total Machine Time and Total Qty Produced 
 per Machine 
 ***************************************/ 

function getMachProd() {
 
 $dDate = date("Y-m-d-00.00.00");
 $objData = new DataAccess();
 $MachProd = $objData -> getMachProd($dDate); 

  echo json_encode($MachProd); 

}

/*******************************************************
Get this Variable  $BarCode, $Machine, $Operator from Operator to be use
*******************************************************/

function TrackingInquiry( $Operator, $Order ){
   //( $BarCode, $Machine, $Operator)
 
  viewTrackingInquiry( $Operator, $Order); //($BarCode, $Machine, $Operator); 
}


/**************************************
       function Tracking($UserName)
***************************************/
function Tracking($UserName, $selectIndex ) {
  //  $objData = new DataAccess();
  // $Production = dailyProduction( $objData,  $selectIndex);
   viewTracking($UserName, $selectIndex, 0);
  
}
/* *****************************************
    Calculo the quantity to produce checking if this is a Panel
 ********************************************/
function calculateOrderQty( $PMCLAS, $EIFGD, $EIBGD, $orderQty ){
    if (( $PMCLAS == '08') or ( $PMCLAS == '09')) {
          if ( trim($EIFGD) == trim($EIBGD) ) {
             $orderQty *= 2;
          }
        }
    return $orderQty;
}

function dailyProduction( $objData, $idMachine ) {
 
  $Orders = $objData -> dailyOrders( $idMachine );
  if ($Orders == ""){
    return 0;
  }
  $shiftsProduction = 0;
  
  foreach ( $Orders as $Index=> $Order) {
       $shiftsProduction += $Order['LHQTY'] * $objData -> qttyPMSLU( $Order['LHPN']) ;
     }
 
 return $shiftsProduction;
}
function getdailyOrders( $idMachine ) {
   $objData = new DataAccess();
    
  return dailyProduction( $objData, $idMachine );
}

/**************************************
    Return Daily Production per MAchine
****************************************/
function getdailyProd() {
  $objData = new DataAccess();
  $listMachines = $objData -> getMachines();
  foreach( $listMachines as $Index => $Row ) {
    $listMachines[$Index]['PRODUCTION'] =  dailyProduction( $objData, $Row['MACHINEID'] );
  }

   echo json_encode($listMachines);

}

function Production($BarCode, $idMachine, $Operator, $selectindex, $Panel) {
  
   $Pos = strpos($BarCode, "/"); 
   if ( ($Pos >= 0) and !empty($Pos) ) {
        $Order= substr($BarCode,0, $Pos);
        $LineNumber =  substr($BarCode, $Pos+1);
        $isFlitch =  strtoupper(substr($idMachine, 0, 1));
        $idMachine = substr($idMachine, 1);
      if (!empty($LineNumber)) {  
        
          $objData = new DataAccess();


          if ( !empty($objData->checkOrder($Order, $LineNumber))) {  
             switch ($Panel) {
               case "F": $typePart = "Panel Face";
                          break;
               case "B": $typePart = "Panel Back";
                          break;
               default : $typePart ="";
             }

             $Machine = $objData->getMachineDesc($idMachine);
             $descMachine =  $Machine['MACHDESC'];

             // Total quantity per Machine in the same group
             $qtyCmpted =  $objData->qtyCompleted($Order, $LineNumber, $idMachine,  $Machine['MACHGRPID'], $Panel);
             $qtyCmpted = empty( $qtyCmpted) ? 0:  $qtyCmpted;
             $headOrder = $objData->getOrderHeader($Order, $LineNumber);
             $d =$headOrder['EHORDT'];
             $Date = substr($d, 3,2)."/".substr($d, 5,2)."/".substr($d, 1,2);
             //Order Item info.
             $headOI = $objData ->getOrderItem($Order, $LineNumber);
             $codeItem = $headOI['EIPN'];

             $headDesc = $objData ->getItemDesc( $codeItem);
             $itemDesc = $headDesc['PMDESC'];//$objData ->getItemDesc( $codeItem);
    
             $orderQty = (int)$headOI['EICCQ'];
             // To trace and test this Vars.
            // echo " PMCLAS:". $headDesc['PMCLAS']. " EIFGD:".$headOI['EIFGD']."EIBGD: ".$headOI['EIBGD']. "Qtty:".$orderQty;
             $totalQty = calculateOrderQty( $headDesc['PMCLAS'], $headOI['EIFGD'], $headOI['EIBGD'], $orderQty);
             $neededQty = $totalQty - $qtyCmpted;
          
             $Production =  dailyProduction( $objData, $idMachine);

            viewProduction($BarCode,$Order, $LineNumber,$Date,$idMachine, $descMachine, $isFlitch, $Operator, $qtyCmpted, $headOrder['EHCT#'], $orderQty, $neededQty, $selectindex, $codeItem, $itemDesc, $typePart, $Panel, $Production );
 
             return;
         }  
      }
    }   
   viewTracking( $Operator, $selectindex, 0);
  
}
/*$Operator, $Barcode, $Machine, $startTime, $stopTime, $Qtty*/
function endProduction($Param ){
   $Pos = strpos($Param["barcode"], "/");;//strpos($Barcode, "/");
   $Order = substr($Param["barcode"],0, $Pos);
   $LineNumber =  substr($Param["barcode"], $Pos+1);
   $Param["order"] =  $Order;
   $Param["line"] = $LineNumber;
   $objData  = new DataAccess();
   if ( $Param["flitch"] <> "" ) {
    //$objData -> TestFlitch($Param["order"],  $Param["line"]);
    $objData -> insertFlitch($Param);
   } 
   
   $objData -> insertHistoric($Param);
  }
?>
