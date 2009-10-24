 
<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * carReservation Controller
 *
 * @package default
 * @author /bin/bash: niutil: command not found
 **/
class CarReservation extends Controller {
 			public $car; //=array('colour'=>array(),'gear'=>array(),'fuel'=>array(),'brand'=>array(),'engine'=>array());
			private $types=array("getColourList"=>array("Colour","colour") ,"getEngineList"=>array("Engine","engine") ,"getCarBrandList"=>array("CarBrand","brand") ,"getFuelList"=>array("Fuel","fuel") ,"getGearList"=>array("Gear","gear"));
	/**
	 * 
	 *
	 * @return void
	 * @author /bin/bash: niutil: command not found
	 **/
 
	function __construct()
    {
		parent::Controller();
		echo "Hello , I'm Constructor.";
		$this->load->library('jquery');
		$this->load->helper('form');
		$this->load->library('nusoap');
		echo"\n";
		$this->soapclient = new nusoap_client("http://localhost:9000/CarReservation/services/carReservation?wsdl","wsdl");
    }

	function Index()
	{
		echo "Hello , World.";
		print_r($_POST);
	}
	function Test()
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
				
					$this->car[$vType][$value['id']]=$vValue;
				} 	
			}
				//	print_r($this->car);
				$this->load->view('Testview',$this->car);
		}
	}
	function viewStock()
	{
		$this->car_stock=array();
		$this->detail=array();
		echo "test";
		if(!$this->soapclient->fault)
		{
			$Stockresult = $this->soapclient->call("getCarStock");
			// print_r($result);
			foreach ($Stockresult["out"]  as $result_value) {
				foreach ($result_value as $value) {
					{
						$Stockresult = $this->soapclient->call("getCarStock");
						{
							$id=$value['id'];
							$param = array('in0' =>  $id);
							$result = $this->soapclient->call('getColourFromId', array('parameters' => $param), '', '', false, true);					
							$this->detail[$id]['colour']=$result["out"];
						}
						{
							$id=$value['id'];
							$param = array('in0' =>  $id);
							$result = $this->soapclient->call('getEngineFromId', array('parameters' => $param), '', '', false, true);					
							$this->detail[$id]['engine']=$result["out"];
						}
						{
							$id=$value['id'];
							$param = array('in0' =>  $id);
							$result = $this->soapclient->call('getGearFromId', array('parameters' => $param), '', '', false, true);					
							$this->detail[$id]['gear']=$result["out"];
						}
						{
							$id=$value['id'];
							$param = array('in0' =>  $id);
							$result = $this->soapclient->call('getCarBrandFromId', array('parameters' => $param), '', '', false, true);					
							$this->detail[$id]['brand']=$result["out"];
						}									
										
											
					}
				}
			}
			// print_r($this->detail);
			$this->car_stock=($Stockresult["out"]);
			$this->car_stock["detail"]=$this->detail;
			
				$this->load->view('viewStock',$this->car_stock);
		}		
	}

}
?>
 