<?php $this->View('header');  ?>

<div class="container">
	<div class="helloBox">
		<h1>Hello <?php echo $person ?></h1>
	</div>
</div>

<div class="container easyWrapper" >
	<div class="easyTitle">
		It's as easy as
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4 easyCountBox">
			<?php echo $easyCounter[0] ?>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 easyCountBox easyCountBox2">
			<?php echo $easyCounter[1] ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 easyCountBox easyCountBox3">
			<?php echo $easyCounter[2] ?>
		</div>
	</div>
	<div class="clear"></div>
</div>



<?php $this->View('footer');  ?>