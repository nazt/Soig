<html>
<head>
<title>Test</title>
<script language="javascript" src="http://localhost/nusoap/jquery.js"></script>
 
<script language="javascript">
function queryCar (argument) {
	$(document).ready(function() {

	        var brand = $('#brand').val();
			var gear=  $('#gear').val();
			var colour=  $('#colour').val();
			var type=  $('#type').val();  
			var fuel=  $('#fuel').val();  
			var city=  $('#city').val();  				     
	        $.post("<?= site_url('CarReservation/ajaxnazt') ?>",    { brand: brand, gear: gear, colour: colour, type: type,fuel:fuel,city:city }, function() {
	            $('#content').load("carOnDemandAjax/"+brand+"/"+gear+"/"+colour+"/"+type+"/"+fuel+"/"+city+"/", function() {
				     $(this).fadeIn(4000);
					
					;});
					 
				
	        });
	});

}
$(document).ready(function() {
    $('#submit').click(function() {

        var brand = $('#brand').val();
		var gear=  $('#gear').val();
		var colour=  $('#colour').val();
		var type=  $('#type').val();  
		var fuel=  $('#fuel').val();  
		var city=  $('#city').val();  				     
        $.post("<?= site_url('CarReservation/ajaxnazt') ?>",    { brand: brand, gear: gear, colour: colour, type: type,fuel:fuel,city:city }, function() {
            $('#content').load("carOnDemandAjax/"+brand+"/"+gear+"/"+colour+"/"+type+"/"+fuel+"/"+city+"/", function() {
			     $(this).fadeIn(4000);
				
				;});
        });
    });
});
</script>
 
</head>
<body onLoad="queryCar();">
 
 
<div id="form">
	

 
<? 
echo form_label('Brand', 'brand');

$js = ' onChange="queryCar();" id="brand"';
echo form_dropdown('brand',$brand,'',$js); 

?>

<?echo form_label('Gear', 'gear');?>
<? 
	$js = ' onChange="queryCar();" id="gear" '; 
	echo form_dropdown('gear',$gear,'',$js); 
?>
	
	<?echo form_label('Colour', 'colour');?>
	<?
		$js = ' onChange="queryCar();" id="colour"'; 
		 echo form_dropdown('colour',$colour,'',$js); 
	?>
	
	
	<?echo form_label('CarType', 'type');?>
	<? 
	$js = ' onChange="queryCar();" id="type"'; 
	echo form_dropdown('type',$type,'',$js);
	 ?>	

<?echo form_label('Fuel', 'fuel');?>
	<? 
		$js = ' onChange="queryCar();" id="fuel"'; 
			echo form_dropdown('fuel',$fuel,'',$js); 
	?>
	


	<?echo form_label('pickup City', 'city');?>	
	<? 	
		$js = ' onChange="queryCar();" id="city"'; 
		echo form_dropdown('city',$city,'',$js);
	 ?>		
			
 <input type="submit" id="submit" name="submit" value="submit" />
</div>
 
	<div id="content">
</body>
</html>
