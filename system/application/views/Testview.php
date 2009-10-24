<html>
<head>
<title>Test</title>
</head>
<body>
	<ul>
	<?php foreach ($gear as $key => $value):
		?>

	<li><?php echo $key ;?></li>

	<?php endforeach;?>
	</ul>
<?

$attributes = array('class' => 'email', 'id' => 'myform');

echo form_open('CarReservation/index', $attributes);

?>
	<? echo form_dropdown('gear',$gear); ?>
	<? echo form_dropdown('engine',$engine); ?>	
	<? echo form_dropdown('colour',$colour); ?>	
	<? echo form_dropdown('brand',$brand); ?>		
 	<? echo form_submit('mysubmit', 'Submit Post!'); ?>
	<? echo form_close(); 
	
	?>
</body>
</html>
