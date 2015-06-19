<?php 
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!-- Html Page Specific -->        
	<meta charset="utf-8">
	<title><?php echo (isset($page_title) == 0) ? "Website Title" : $page_title; ?></title>
	
	<meta name="description" content='<?php echo (isset($page_description) == 0) ? "Generic Description" : $page_description; ?>' />
	<meta name="keywords" content='<?php echo (isset($page_keywords) == 0) ? "Generic Keywords" : $page_keywords; ?>' />
	<meta name="author" content="Company Name">
	
	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	
	<!-- CSS -->
	<link rel="stylesheet" href="/theme/css/style.css" type="text/css"/>
	
	<?php if (isset($page_css)) :?>
		<link rel="stylesheet" href="<?php echo $page_css ?>" type="text/css"/>
	<?php endif ?>
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<!-- JavaScript -->
	
	<!-- Favicons -->
	<link rel="shortcut icon" href="/theme/images/fav-icon.png">
	<link rel="apple-touch-icon" href="/theme/images/fav-icon.png">
</head>
<body>
	<div id="bodyWrapper">
		<header class="header">
			<div class="container headerBar">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="logoPanel">
							<a href="/" class="logo" title="Your Site - Your Logo"></a>
							<div class="slogan">
								<div class="title">Swift Framework</div>
								Framework for Geeks
							</div>
						</div>
						
					</div>
					<div class="col-xs-12 col-sm-6">
						
					</div>
				</div>
				<div class="clear">
				</div>
			</div>
		</header>
		