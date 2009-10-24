<html>
<head>
<title>View Stock</title>
</head>
<body>
 	

<?
	
	$attributes = array('class' => 'email', 'id' => 'myform');

	echo form_open('CarReservation/index', $attributes);
?>
<table border="1" cellspacing="5" cellpadding="5">
	<tr><th>Tick</th><th>Car Brand</th><th>Model</th><th>Colour</th><th>carRegistration</th><th>Rate [Bht/Day]</th></tr>

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
	?>
	<tr><td><?  	echo form_checkbox($data);  ?></td><td><?=$detail[$id]['brand']?></td><td><?=$value['model']?></td><td><?=$detail[$id]['colour']?></td><td><?=$value['carRegistration']?></td><td><?=$value['rate']?></td></tr>
 
 
<?php endforeach;?>

</table>
 
<? echo form_submit('mysubmit', 'Submit Post!'); ?>
<? echo form_close(); ?>

<?



?>

 
</html>
