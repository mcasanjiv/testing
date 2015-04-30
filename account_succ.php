<?php 
	require_once("includes/header.php");
	include_once("includes/header_menu.php");
        
           
        if(!empty($_SESSION['Paymet_Failure']))
        {
            $_SESSION['SUCC_TITLE']   = PAYMENT;
            $_SESSION['mess_account'] = PAYMENT_DECLINED;
            $RedirectUrl = 'index.php';
        }
	

	if (empty($_SESSION['Email']) && empty($_SESSION['Cid'])) { 
		$RedirectUrl = 'login.php';
	}
        
        if (!empty($_SESSION['Email']) && !empty($_SESSION['Cid']) && empty($_SESSION['Paymet_Failure'])) { 
		header("location:account.php");
	}
        
      

	
	//require_once("includes/html/".$ThisPage);
	require_once("includes/footer.php");
 ?>


