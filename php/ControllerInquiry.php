<?php
require_once 'inquiry.php';

if (isset($_POST['inquiry']) && ! isset($_GET['q'])) {
  switch($_POST['inquiry']){
    case 'Login': tracking($_POST['username'], 0);
                  break;
    case 'Tracking':
                  	  if ( isset($_POST['panel']) ) {
                  	  	$Panel = $_POST['panel'];
                  	  } else {
                  	  	$Panel ="";
                  	  }
                  	
                     Production($_POST['barcode'], $_POST['machine'],$_POST['operator'], $_POST['selectindex'], $Panel);
                            
                      //TrackingInquiry($_POST['barcode'], $_POST['machine'],$_POST['operator']);
                     break;
    case 'TrackingInquiry': if(isset($_POST['barcode'])){
                              $OrderNumber = $_POST['barcode'];
                              //$Machine = $_POST['machine'];
                              $Operator = $_POST['operator'];
                              TrackingInformation( $OrderNumber,$Operator);
                              //TrackingDisplay( $OrderNumber,$LineNumber);
                            }
                            break;
    case 'TrackingInformation':
                              $OrderNumber = htmlspecialchars($_POST['ordernumber']);
                              $LineNumber = htmlspecialchars( $_POST['linenumber']);
                              $Operator = htmlspecialchars($_POST['operator']);
                              $Link = htmlspecialchars( $_POST['linkbutton'] ) ;
                              $Order =  $OrderNumber."/". $LineNumber;
                              if ( $Link == 'home') {
                                  TrackingInquiry(  $Operator,  $Order);
                              } else {
                                    if(isset($OrderNumber) and isset($LineNumber)){
                                       $Customer = $_POST['customer'];
                                        $orderDate = $_POST['orderdate'];
                                        $orderQtty = $_POST['orderqtty'];
                                        $Item =  $_POST['item'];
                                         
                                     
                                        TrackingDisplay( $OrderNumber,$LineNumber, $Customer, $orderDate, $orderQtty, $Item, $Operator);
                                      }
                              }
                              
                            break;
    case 'TrackingDisplay'  :
                              $Order = htmlspecialchars( $_POST['order'] ) ;
                              $Operator = htmlspecialchars($_POST['operator']) ;
                              $Link = htmlspecialchars( $_POST['linkbutton'] ) ;
                              if ( $Link == 'home') {
                                 TrackingInquiry( $_POST['operator'], $_POST['order']);
                              } else {
                                  TrackingInformation( $Order,$Operator);
                              }
                             
                             break;                        
    case 'Display': return ( getLocHistory());  
    case 'Checkorder': return(checkOrder());  
    case 'Production': if(isset($_POST['operator'])) {
    	 				             if (isset($_POST['flitch'])){
    	 				  	                $Flitch = $_POST['flitch'];
    	 				              } else {
    	 				  	         $Flitch = "";
    	 				          }
    	  				   if(isset($_POST[' '])) {

    	  				   }
                          $Param = array("operator" => $_POST['operator'],
                                         "barcode"  => $_POST['barcode'],
                                         "idmachine"  => $_POST['idmachine'],
                                         "starttime"=> $_POST['starttime'],
                                         "endtime"  => $_POST['endtime'],
                                         "qty"      =>(int)$_POST['qtyproduced'],
                                         "flitch"   => $Flitch,
                                         "panelfacbck" => $_POST['panelfacbck'],
                                         "comment"  => $_POST['comment'],
                                         "override" => $_POST['supervisor'],
                                         "itemnumber" => $_POST['itemnumber']
                                       );
                          // if (($_POST['qtyproduced']+$_POST['qtycmpted'])>$_POST['orderqty']) {

                            // }
                           endProduction( $Param);
                           tracking($_POST['operator'], $_POST['selectindex']);
                        }
                        
                       break;
                      

     }
  } else {
        switch($_GET['q']) {
          
          case 'Machprod'  :
                            getMachProd(); // return all Production info per machine;
                            break;
                           
          case  	'Home'	:
              						TrackingInquiry($_GET['operator'], $_GET['order'] );
              						break; 
          case 'assignloc'	: 
                              return assignLoc(  $_GET['order'], $_GET['line'], $_GET['loc'] );
          					      break;
          case 'Shiftsorder':  
                  					  return shiftsOrder(  $_GET['order'], $_GET['line'] );

                   					  break ;	
          case 'Dailyprod': // To know the Daily Production per machine
                           
                             $P =  getdailyProd();
                             echo  $P;
                             return $P;
                      
          case 'Production': 

                              $P =  getdailyOrders( $_GET['idmachine'] );
                             echo  $P;
                             return $P;
          case 'Machines'  :
                            return Machines();
                            break;
          case 'Traveler'  :
                            
                            callPrintTraveler( $_GET['order'], $_GET['line']);
                            break;
          case 'Display'   :  
                              return getLocHistory($_GET['order'], $_GET['line']);
          case 'Checkorder':  if (isset($_GET['barcode'])) {
                                 $Barcode = $_GET['barcode'];
                                 $Pos = strpos($Barcode, "/");
                                 $Order= substr($Barcode,0, $Pos);
                                 $LineNumber =  substr($Barcode, $Pos+1);
                                 return checkOrder($Order, $LineNumber);
                                }
                                break;
           case 'Override' : if (! isset($_GET['code'])) {
                                 return "";
                              }
                             //   echo "$". $_GET['order']. $_GET['line']. $_GET['machine']."$";
                             return checkOverrideCode($_GET['code'], $_GET['order'], $_GET['line'], $_GET['machine']);                   
          }
        
      }



?>