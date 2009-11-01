<html>
<head>
<title>Test</title>

<LINK REL=StyleSheet HREF="http://localhost/nusoap/smodal.css" TYPE="text/css" MEDIA=screen>
 
 
</head>
<body>
<?
// print_r($this->detail);



	// print_r($CarStock);
	// print_r($detail);
	$attributes = array('class' => 'email', 'id' => 'myform');

	echo form_open('CarReservation/addCarDetail', $attributes);
?>
<table border="1" cellspacing="0" cellpadding="1" width="500">
	<tr><th width ="60"> Tick</th><th width ="100"0>Car Brand</th><th width ="60">Model</th><th width ="60">Fuel</th><th width ="60">Colour</th><th width="80">Car Type</th><th width ="80">pickUpCity</th><th width="30">carRegistration</th><th width="100">Rate [Bht/Day]</th><th></th></tr>

<?

?>

<?php foreach ($CarStock as $key => $value): ?> 
	
	<?
	$id=$value['id'];
	$data = array(
	    'name'        => 'to_reserv[]',
	    'id'          => 'car',
	    'value'       => $id,
	    'checked'     => FALSE,
	    'style'       => 'margin:10px',
	    );
	// $data["temp"]["car"]["detail"][$id]["rate"]=$value['rate'];
	// $this->session->set_userdata($data);
	?>
	<tr><td><center><?  	echo form_checkbox($data);  ?></center></td><td><center><?=$detail[$id]['brand']?></center></td><td><center><?=$value['model']?></center></td><td><center><?=$detail[$id]['fuel']?></center></td><td><center><?=$detail[$id]['colour']?></center></td><td><center><?=$detail[$id]['type']?></center></td><td><center><?=$detail[$id]['city']?></center></td><td><center><?=$value['carRegistration']?></center></td><td><center><?=$value['rate']?><? echo form_hidden('rate[]', $value['rate']);?></center></td></tr>
 
 
<?php endforeach;?>

</table>
 
<? echo form_submit(null, 'Submit Post!'); ?>
<? echo form_close(); ?>


</body>
</html>
<?



?>

