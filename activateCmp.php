<?php   
	//ini_set("display_errors","1"); error_reporting(5);	
	require_once("includes/function.php");
	//require_once("includes/config.php");
	/**************************/
	//echo '<pre>';
	/**************************/	
	//Get Company Info from Drupal
	/*
	$Config['DbHost']	= '54.86.106.56';
	$Config['DbUser']	= 'test2_user';
	$Config['DbPassword']	= 'crldLLTU4S';
	$Config['DbName']	= 'db_erpt2_crm';
	

	$link=mysql_connect ($Config['DbHost'],$Config['DbUser'],$Config['DbPassword'],TRUE);
	if(!$link){die("Could not connect to MySQL");}
	mysql_select_db($Config['DbName'],$link) or die ("Could not open db".mysql_error());	

	CleanGet(); 

	//$_GET['pay'] = 14; echo md5($_GET['pay']);exit;
	$payment_id = $_GET['pay']; //'aab3238922bcc25a6f606eb525ffdc56';  
	if(!empty($payment_id)){
		$sql = "select * from payment_pro_table where MD5(payment_id)='".$payment_id."'";	
		$q = mysql_query($sql,$link) or die (mysql_error());
		$arryCmp = mysql_fetch_array($q);
	}
	
	if(empty($arryCmp['payment_id'])){
		echo 'Invalid Data.';
		exit;
	}*/

	//echo strtotime("2015-08-15");

     $XmlContent = '<?xml version="1.0" encoding="ISO-8859-1"?>
		<subscriptiondetail>
		<userid>111</userid>
		<usename>raj</usename>
		<email>raj@sakshay.in</email>		
		<companyname>Sakshay Web</companyname>
		<startdate></startdate>
		<enddate>1439577000</enddate>
		<subs_type>standard</subs_type>
		<order_id>43545</order_id>
		<payment_id>1111</payment_id>
		<number_user>10</number_user>
		<first_name>Parwez</first_name>
		<last_name>Khan</last_name>
		<address>Sec-82</address>
		<city>New Delhi</city>
		<state>Delhi</state>
		<country>India</country>
		<zip>110022</zip>
		<se>ae1d5aaa4ec1799dcb800d398a717d0c</se>
		</subscriptiondetail>';

	$XmlContent = $_POST['xmldata'];
	$arryXml = xml2array($XmlContent);
	$Email = $arryXml['subscriptiondetail']['email']['value'];

	if(!empty($Email)){
		$_GET['se'] = $arryXml['subscriptiondetail']['se']['value'];
		$PaymentPlan = $arryXml['subscriptiondetail']['subs_type']['value'];
		$DisplayName = $arryXml['subscriptiondetail']['usename']['value'];
		$Email =  $arryXml['subscriptiondetail']['email']['value'];		
		$CompanyName =  $arryXml['subscriptiondetail']['companyname']['value'];
		$ContactPerson =  $arryXml['subscriptiondetail']['first_name']['value'].' '.$arryXml['subscriptiondetail']['last_name']['value'];
		$Address = $arryXml['subscriptiondetail']['address']['value'];
		$City = $arryXml['subscriptiondetail']['city']['value'];
		$State = $arryXml['subscriptiondetail']['state']['value'];
		$Country = $arryXml['subscriptiondetail']['country']['value']; //pending
		$ZipCode = $arryXml['subscriptiondetail']['zip']['value'];
		$MaxUser = $arryXml['subscriptiondetail']['number_user']['value'];
		$enddate = $arryXml['subscriptiondetail']['enddate']['value'];
		$ValidXml=1;
	}else{	/*
		// Static Data for Testing
		$PaymentPlan = 'standard'; 
		$DisplayName = 'raj';    
		$Email =  'raj@sakshay.in'; 
		
		$CompanyName =  'Sakshay Web'; 
		$ContactPerson =  'Parwez Khan'; 
		$Address = 'Sector-18'; 
		$City = 'San Jose'; 
		$State = 'CA'; 
		$Country = 'GB'; 
		$ZipCode = '201301';
		$MaxUser = 100;
		*/
		$ValidXml=0;
	}

	$Password = substr(md5(rand(100,10000)),0,8);

	$arryCompany['Email'] = $Email;
	$arryCompany['Password'] = $Password;
	$arryCompany['PaymentPlan'] = $PaymentPlan;

	#print $ValidXml; exit;


	/**************************/
	//Use Erp Database
	require_once("includes/config.php");	
	require_once("classes/dbClass.php");
	require_once("classes/MyMailer.php");	
	require_once("classes/admin.class.php");	
	require_once("classes/company.class.php");
	require_once("classes/region.class.php");
	require_once("superadmin/language/english.php");	
	$objConfig=new admin();	
	$objRegion=new region();
	/**************************/


 	
	//$_GET['se'] = 'ae1d5aaa4ec1799dcb800d398a717d0c';
	//$_GET['sp'] = 'd7c6c07a0a04ba4e65921e2f90726384';  //No Use
	
	if(!empty($_GET['se'])){

		$arrayAdmin = $objConfig->ValidateSecureAdmin($_GET['se'], $_GET['sp']);
		if($arrayAdmin[0]['AdminID']>0){
			$ValidLogin=1;
			$arrayConfig = $objConfig->GetSiteSettings(1);	
			
			$Config['SiteName']  = stripslashes($arrayConfig[0]['SiteName']);	
			$Config['SiteTitle'] = stripslashes($arrayConfig[0]['SiteTitle']);
			$Config['AdminEmail'] = $arrayAdmin[0]['AdminEmail'];
		}



		
	}
	
	if($ValidLogin!=1){
		echo 'Invalid';
		exit;
	}


	//print_r($arrayConfig);
	
	/*************************/
	/*************************/
	//$RedirectURL = "http://54.86.106.56/crm/thankyou";
	$objCompany=new company();
	
	 if(!empty($arryCompany['Email'])) {
		//print_r($arryCompany);exit;			
		if($objConfig->isCmpEmailExists($arryCompany['Email'],'')){
			$_SESSION['mess_company'] = COMPANY_ACTIVATED;
			$ReturnFlag = $objCompany->ActivateCompany($arryCompany); 
		}else{				
			$_SESSION['mess_company'] = 'Invalid Email';			
		}				

		/************************/
  		print $ValidXml; exit;				
		//echo $_SESSION['mess_company'];
		//header("Location:".$RedirectURL);
		exit;
			
			
	}
		



?>


