<!DOCTYPE html>
<html>
<head>
	<title> </title>
	<style type="text/css">
		body {
			font-family: arial;
			margin: 0;
		}
		.menu-details {
			list-style: none;
			padding: 0;
		}
		.menu-details__serving, .menu-details__sauce {
			font-weight: bold;
			margin-bottom: 5px;
			line-height: 1.3;
		}
		.menu-details__serving li, .menu-details__sauce li {
			list-style: square;
			font-weight: normal;
		}
		.action-bar-separator {
			box-shadow: 0px 2px 5px #8C8181;
			height: 2px;
			position: fixed;
			top: 0; left: 0;
    		width: 100%;
    		background-color: #fff;
		}
		.menu-content {
			padding: 0 10px;
		}
		#qty {
			color : red;
		}
	</style>

</head>
<body>
		<div class="action-bar-separator"></div>
		
		<div class="menu-content">
			<h2>Traditional Wings</h2>
			<p>Traditional upstate New York-style chicken wings deep fried and tossed in your choice of sauce.</p>

			<ul class="menu-details">
				<li class="menu-details__serving">Servings:
					<ul>
						<li>Half pound <strong>Php 325.00</strong></li>
						<li>One pound <strong>Php 575.00</strong></li>
					</ul>
				</li>
				<li class="menu-details__sauce">Sauce:
					<ul>
						<li>Original Buffalo</li>
						<li>Honey Barbecue</li>
						<li>Cajun Spice</li>
					</ul>
				</li>
			</ul>
		</div>
		
</body>
</html>