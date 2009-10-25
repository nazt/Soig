<html>
<head>
<title>Test</title>

 
 
</head>
<body>
<?
// print_r($this->detail);



	// print_r($CarStock);
	// print_r($detail);
	$attributes = array('class' => 'email', 'id' => 'myform');

	echo form_open('CarReservation/addCarDetail', $attributes);
?>
<table border="1" cellspacing="5" cellpadding="5">
	<tr><th>Tick</th><th>Car Brand</th><th>Model</th><th>Fuel</th><th>Colour</th><th>Car Type</th><th>pickUpCity</th><th>carRegistration</th><th>Rate [Bht/Day]</th></tr>

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
	<tr><td><?  	echo form_checkbox($data);  ?></td><td><?=$detail[$id]['brand']?></td><td><?=$value['model']?></td><td><?=$detail[$id]['fuel']?></td><td><?=$detail[$id]['colour']?></td><td><?=$detail[$id]['type']?></td><td><?=$detail[$id]['city']?></td><td><?=$value['carRegistration']?></td><td><?=$value['rate']?><? echo form_hidden('rate[]', $value['rate']);?></td></tr>
 
 
<?php endforeach;?>

</table>
 
<? echo form_submit(null, 'Submit Post!'); ?>
<? echo form_close(); ?>


</body>
</html>
<?



?>

