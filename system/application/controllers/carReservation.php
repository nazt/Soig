<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * carReservation Controller
 *
 * @package default
 * @author /bin/bash: niutil: command not found
 **/
class CarReservation extends Controller {
 			public $car; //=array('colour'=>array(),'gear'=>array(),'fuel'=>array(),'brand'=>array(),'engine'=>array());
			private $types=array("getColourList"=>array("Colour","colour") ,"getEngineList"=>array("Engine","engine") ,"getCarBrandList"=>array("CarBrand","brand") ,"getFuelList"=>array("Fuel","fuel") ,"getGearList"=>array("Gear","gear"),"getCityList"=>array("City","city"),"getCarTypeList"=>array("CarType","type"));
	/**
	 * 
	 *
	 * @return void
	 * @author /bin/bash: niutil: command not found
	 **/
 
	function __construct()
    {
		parent::Controller();
		$this->load->library('session');
		// echo "Hello , I'm Constructor.";
		$this->load->library('jquery');
		$this->load->helper('form');
		$this->load->library('nusoap');
		$this->load->helper('url');
		$this->soapclient = new nusoap_client("http://localhost:9000/CarReservation/services/carReservation?wsdl","wsdl");
    }
	function destroyCarDetail()
	{
		$array_items['commit']['car']='';
		$array_items['commit']='';
		$array_items['car']='';
		$array_items['hotel']='';		
		$array_items['flight']='';				
		$array_items['user']='';	
		$array_items['payment']='';					

		$this->session->unset_userdata($array_items);
	}
	function showSession()
	{
		print_r($this->session->userdata);
	}
	function confirm()
	{
		// print_r($_POST);
		
		if($_POST)
		{
			$newdata = array(
			                   'car'  => $_POST,
//			                   'car'     =>	$this->session->userdata('car'),
			               );
			// print_r($this->session->userdata('car'));
			$this->session->set_userdata($newdata);
		}
		else
		{
			$array_items['car']='';
			echo "CAR ELFE";
			$this->session->unset_userdata($array_items);
		}
		redirect('http://localhost/nusoap/index/');		
		//print_r($this->session->userdata);
	}
	function checkStatus()
	{
		// print_r($this->session->userdata);
		$services=array("car","hotel",'flight','user','payment');
		foreach ($services as $key => $service) {
			if(!empty($this->session->userdata[$service])  )
			{
				printf("%s defined!  with<br>\n",$service);
				echo " <pre> ";
				print_r($this->session->userdata[$service]);
				echo "</pre>";
			}
			else
			{
				printf("%s not defined! <br>\n",$service);
			}
		}
	}
	function addCarDetail()
	{
		if(empty($_POST['to_reserv']))
		{
			printf("choose car to reserv first");
			exit(0);	
		}
		$SUM_RATE=0;
		foreach ($_POST['to_reserv'] as $key => $value) {
			$id=$value;
			$param = array('in0' => $id);
			$result = $this->soapclient->call('getRateFromId', array('parameters' => $param), '', '', false, true);					
			 $SUM_RATE+=$result["out"];		
			$data["erate"][]=$result["out"];	 
		}
		// echo "SUM" .$SUM_RATE;
		$data["rate"]=$SUM_RATE;
		$data["detail"]=$this->session->userdata["temp"]["car"]["detail"];	
		$data["id"]=$_POST['to_reserv'];
	//	$data["city"];//=$this->session->userdata["temp"]["city"]["City"];	
		foreach ($this->session->userdata["temp"]["city"]["City"] as $key => $value) {
			// print_r($value);
			$data["city"][$value['id']]=$value['city'];
		}
		// print_r($data);
		$this->load->view('addDetailview',$data);
	//	$data["detail"]=$this->session->userdata["temp"];		
	//	echo "SUM = " . array_sum($_POST['rate']);

	
		// print_r($_POST);
		// print_r($this->session->userdata);
	}
	function keepReserveId()
	{
		$newdata = array('car'=> $_POST);

		// $this->session->set_userdata($newdata);
		// 
		// print_r($this->session->userdata);
		// print_r($_POST['to_reserv']);
	}
	function ajaxnazt()
	{
	//  
			// print_r($_POST);
	 	$myStr="";
		foreach ($_POST as $key => $value) {
		//	printf("%s : %s\n",$key ,$value);
			$myStr.= "/".$value;
		}	
		// echo $myStr;	
	 	redirect('CarReservation/carOnDemandAjax'.$myStr);
	}
	function carOnDemandAjax()
	{
		// print_r($this->uri);
	//	echo  $this->uri->segment(3);
		$param;
		for($i=3;$i<=8;$i++)
		{
			// echo  $this->uri->segment($i);
			$temp=$i-3;
			$param["in$temp"]= $this->uri->segment($i);
		}
 
		$this->car_stock=array();
		$this->detail=array();
		// echo "test";
		// print_r($_POST);
		if(!$this->soapclient->fault)
		{
			$_POST['carType']='ANY';
	
			$Stockresult	 = $this->soapclient->call('searchCar', array('parameters' => $param), '', '', false, true);
	
	 		if(empty($Stockresult["out"]["CarStock"]))
			{
				echo "NOT FOUND!";
			}
			else if(isset($Stockresult["out"]["CarStock"][0]))
 			{
					foreach ($Stockresult["out"]  as $result_value) 
					{					

							foreach ($result_value as $value) 
							{
								$id=$value['id'];
					 			$param = array('in0' => $id);
								$result = $this->soapclient->call('getColourFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['colour']=$result["out"];

								$result = $this->soapclient->call('getEngineFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['engine']=$result["out"];

								$result = $this->soapclient->call('getGearFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['gear']=$result["out"];

								$result = $this->soapclient->call('getCarBrandFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['brand']=$result["out"];

								$result = $this->soapclient->call('getFuelFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['fuel']=$result["out"];		
								
								$result = $this->soapclient->call('getPickupCityFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['city']=$result["out"];		
								
				
								$result = $this->soapclient->call('getCarTypeFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['type']=$result["out"];																		
							}
					}			
				$this->car_stock=($Stockresult["out"]);
				$this->car_stock["detail"]=$this->detail;
				$this->load->view('viewStock',$this->car_stock);
			}
			else
			 {
			//	echo "Not Do! id =" .;
			 	$id=$Stockresult["out"]["CarStock"]['id'];
				$param = array('in0' => $id);
				$result = $this->soapclient->call('getColourFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['colour']=$result["out"];

				$result = $this->soapclient->call('getEngineFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['engine']=$result["out"];

				$result = $this->soapclient->call('getGearFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['gear']=$result["out"];

				$result = $this->soapclient->call('getCarBrandFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['brand']=$result["out"];

				$result = $this->soapclient->call('getFuelFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['fuel']=$result["out"];
				
				$result = $this->soapclient->call('getPickupCityFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['city']=$result["out"];
				
				$result = $this->soapclient->call('getCarTypeFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['type']=$result["out"];				
 				
				
				$this->car_stock["CarStock"]=($Stockresult["out"]);
				$this->car_stock["detail"]=$this->detail;
				$this->load->view('viewStock',$this->car_stock);
			}
				$result = $this->soapclient->call('getCityList');	
				// print_r($result["out"]);
				// $this->car_stock["detail"];
				// print_r($this->detail);
				$data["temp"]["car"]["detail"]=$this->detail;
				$data["temp"]["city"]=$result["out"];
				@$this->session->set_userdata($data);

			
		}
		
		

	}
	function carOnDemand()
	{
		$this->car_stock=array();
		$this->detail=array();
		// echo "test";
		// print_r($_POST);
		if(!$this->soapclient->fault)
		{
			$_POST['carType']='ANY';
			$param = array('in0' =>  $_POST['brand'],'in1' =>  $_POST['gear'],'in2' =>  $_POST['colour'],'in3' => $_POST['carType'],'in4' =>  $_POST['fuel'],'in5' =>  $_POST['city'] );
			$Stockresult	 = $this->soapclient->call('searchCar', array('parameters' => $param), '', '', false, true);
	
	 		if(empty($Stockresult["out"]["CarStock"]))
			{
				echo "NOT FOUND!";
			}
			else if(isset($Stockresult["out"]["CarStock"][0]))
 			{
					foreach ($Stockresult["out"]  as $result_value) 
					{					

							foreach ($result_value as $value) 
							{
								$id=$value['id'];
					 			$param = array('in0' => $id);
								$result = $this->soapclient->call('getColourFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['colour']=$result["out"];

								$result = $this->soapclient->call('getEngineFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['engine']=$result["out"];

								$result = $this->soapclient->call('getGearFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['gear']=$result["out"];

								$result = $this->soapclient->call('getCarBrandFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['brand']=$result["out"];

								$result = $this->soapclient->call('getFuelFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['fuel']=$result["out"];		
								
								$result = $this->soapclient->call('getPickupCityFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['city']=$result["out"];		
								
				
								// $result = $this->soapclient->call('getCarTypeFromId', array('parameters' => $param), '', '', false, true);					
								$this->detail[$id]['type']=$result["out"];																		
							}
					}			
				$this->car_stock=($Stockresult["out"]);
				$this->car_stock["detail"]=$this->detail;
				$this->load->view('viewStock',$this->car_stock);
			}
			else
			 {
				echo "Not Do! id =" . $id=$Stockresult["out"]["CarStock"]['id'];
				$param = array('in0' => $id);
				$result = $this->soapclient->call('getColourFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['colour']=$result["out"];

				$result = $this->soapclient->call('getEngineFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['engine']=$result["out"];

				$result = $this->soapclient->call('getGearFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['gear']=$result["out"];

				$result = $this->soapclient->call('getCarBrandFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['brand']=$result["out"];

				$result = $this->soapclient->call('getFuelFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['fuel']=$result["out"];
				
				$result = $this->soapclient->call('getPickupCityFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['city']=$result["out"];
				
				$result = $this->soapclient->call('getCarTypeFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['type']=$result["out"];				
 				
				
				$this->car_stock["CarStock"]=($Stockresult["out"]);
				$this->car_stock["detail"]=$this->detail;
				$this->load->view('viewStock',$this->car_stock);
			}
			
		}
	}
	function Index()
	{
		echo "Hello , World.";
		print_r($_POST);
	}
 
	function searchCar()
	{

		if(!$this->soapclient->fault)
		{
			foreach ($this->types as $tkey => $typeVal) 
			{
				$result = $this->soapclient->call($tkey);
				foreach ($result['out'][$typeVal[0]] as $key => $value) 
				{
					$vType=$typeVal[1]; // TYPE OF Car , Brand Gear 
					$vValue= $value[$typeVal[1]]; // VALUE OF TYPE -> car ,brand
					$this->car[$vType]['ANY']='ANY';
					// $this->car[$vType][$value['id']]=$vValue;
					$this->car[$vType][$vValue]=$vValue;
				
				} 	
			}
//				print_r($this->car);
				$this->load->view('searchCar',$this->car);
		}
	}
	function viewStock()
	{
		$this->car_stock=array();
		$this->detail=array();
		if(!$this->soapclient->fault)
		{
	 
			$param = array('in0' =>  'ANY','in1' =>  'ANY','in2' =>  'ANY','in3' => 'ANY','in4' =>  'ANY','in5' =>  'ANY' );
			$Stockresult	 = $this->soapclient->call('searchCar', array('parameters' => $param), '', '', false, true);
	
	 		if(empty($Stockresult["out"]["CarStock"]))
			{
				echo "NOT FOUND!";
			}
			else if(isset($Stockresult["out"]["CarStock"][0]))
 			{
				foreach ($Stockresult["out"]  as $result_value) 
				{					
					foreach ($result_value as $value) 
					{
						$id=$value['id'];
			 			$param = array('in0' => $id);
						$result = $this->soapclient->call('getColourFromId', array('parameters' => $param), '', '', false, true);					
						$this->detail[$id]['colour']=$result["out"];

						$result = $this->soapclient->call('getEngineFromId', array('parameters' => $param), '', '', false, true);					
						$this->detail[$id]['engine']=$result["out"];

						$result = $this->soapclient->call('getGearFromId', array('parameters' => $param), '', '', false, true);					
						$this->detail[$id]['gear']=$result["out"];

						$result = $this->soapclient->call('getCarBrandFromId', array('parameters' => $param), '', '', false, true);					
						$this->detail[$id]['brand']=$result["out"];

						$result = $this->soapclient->call('getFuelFromId', array('parameters' => $param), '', '', false, true);					
						$this->detail[$id]['fuel']=$result["out"];														
					}
				}			
				$this->car_stock=($Stockresult["out"]);
				$this->car_stock["detail"]=$this->detail;
				$this->load->view('viewStock',$this->car_stock);
			}
			else
			 {
				echo "Not Do! id =" . $id=$Stockresult["out"]["CarStock"]['id'];
				$param = array('in0' => $id);
				$result = $this->soapclient->call('getColourFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['colour']=$result["out"];

				$result = $this->soapclient->call('getEngineFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['engine']=$result["out"];

				$result = $this->soapclient->call('getGearFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['gear']=$result["out"];

				$result = $this->soapclient->call('getCarBrandFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['brand']=$result["out"];

				$result = $this->soapclient->call('getFuelFromId', array('parameters' => $param), '', '', false, true);					
				$this->detail[$id]['fuel']=$result["out"];
				print_r($Stockresult["out"]);
				
				$this->car_stock["CarStock"]=($Stockresult["out"]);
				$this->car_stock["detail"]=$this->detail;
				$this->load->view('viewStock',$this->car_stock);
			}
		}
	}
}
?>