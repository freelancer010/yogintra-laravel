<!DOCTYPE html>
<html lang="en">

<head>
	<title>card </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<style>
		.card {
			margin: 10px auto;
			box-shadow: 0px 2px 4px 0 rgba(0, 0, 0, 0.2);
			border-radius: 12px;
			height: 100%;
			transition: 0.3s;
			cursor: pointer;
		}
		.modal.fade .modal-dialog{
			display:flex !important;
		}
		.card:hover {
			box-shadow: 1px 10px 16px 0 rgba(0, 0, 0, 0.3);
		}

		.card-title {
			text-align: center;
			color: #666666;
			font-weight: bold;
		}

		.card-text {
			font-weight: 500;
			color: #66686b;
		}

		.card-image {
			text-align: center;
			margin: 5vh 0;
		}

		.card-text {
			text-align: center;
		}

		.title-heading {
			font-size: 3em;
			text-align: center;
			margin: 4%;
			font-weight: bold;

		}

		.card-header {
			color: #213661;
			border-top-left-radius: 12px !important;
			border-top-right-radius: 12px !important;
			padding: 0;
			text-align: center;
			background-repeat: no-repeat;
			background-size: cover;
			background-position: center;
		}
		span.location {
			background: #00000050;
			padding: 4px 12px;
			border-radius: 20px;
			display: block;
			width: fit-content;
			position: relative;
			left: 3%;
			top: 80%;
			color: #ffffff;
			backdrop-filter: blur(2px);
			box-shadow: 0px 1px 2px #00000090;
			font-size: 14px;
		}
	</style>
</head>

<body style="background-color: #f9fcff;background-image: url('https://yogintra.com/wp-content/uploads/2016/10/feature_classes_bg.jpg');background-size: contain;">
	

<!-- search bar -->
<div class="container">
	<div class="row">

		<div class="col-sm-12 mb-3 mt-5">
			<p class="text-center text-600" style="font-weight: 600; font-size: 22px;">Search Trainer</p>
			<div class="d-flex justify-content-center row">
				<input type="text" id="myFilter" class="col-lg-3 form-control mr-3" onkeyup="myFunction()" placeholder="Enter city name">
				<input type="text" id="nameInput" class="col-lg-3 form-control" onkeyup="searchName()" placeholder="Enter Trainer name">
			</div>
		</div>
	</div>
	
</div>

<div class="container" style="margin-top:30px " id="resultsContainer">
		<div class="row" id="myItems">
			<?php
			function ageCalculator($dob)
			{
				if (!empty($dob)) {
					$birthdate = new DateTime($dob);
					$today   = new DateTime('today');
					$age = $birthdate->diff($today)->y;
					return $age;
				} else {
					return 0;
				}
			}

			if($data != 'No data found!'){
				foreach ($data as $trainer) { ?>
					<div class="col-lg-6 col-sm-6 col-md-3 col-xl-3 col-xs-12 my-3">
						<div class="card" data-toggle="modal" data-target="#<?php echo "id".$trainer['id'] ?>">
							<div class="card-header" style="background-image: url('<?= $trainer['profile_image']?$trainer['profile_image']: base_url('assets/').'dist/img/default-profile.png' ?>');">
								<div style="height: 180px;">
									<span class="location"><b><?= $trainer['city'] ?></b></span>
								</div>
							</div>
							<div class="card-body" style="padding:1.25rem 1.25rem 0 1.25rem">
								<h6 class="card-title" style="margin-bottom:9px; font-size:16px"><?= $trainer['name'] ?></h6>
								<p class="card-text" style="margin-bottom:9px; font-size:13px"><b>Age</b> - &nbsp;&nbsp;<?= ageCalculator($trainer['dob']); ?></p>
								<p class="card-text text-left" style="margin-bottom:9px; font-size:13px"><b>Education</b> -&nbsp;&nbsp;
									<?php 
										if(strlen($trainer['Education'])>48){ 
											echo substr($trainer['Education'],0,48)."...";
										}else{
											echo $trainer['Education'];
										} 
									?>
								</p>
								<p class="card-text text-left" style="font-size:13px"><b>Experience</b> -&nbsp;&nbsp;
									<?php 
										if(strlen($trainer['experience'])>40){ 
											echo substr($trainer['experience'],0,40)."...";
										}else{
											echo $trainer['experience'];
										} 
									?>
								</p>
								<p class="card-text text-left" style="font-size:13px"><b>Package</b> -&nbsp;&nbsp;
									<?php echo $trainer['package'];?>
								</p>
							</div>
							<div class="card-footer">
								<p class="card-text">Know more..</b></p>
							</div>
						</div>
					</div>
					<!-- Modal -->
					<div class="modal fade" id="<?php echo "id".$trainer['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="card">
								<div class="card-header" style="background-image: url('<?= $trainer['profile_image']?$trainer['profile_image']: base_url('assets/').'dist/img/default-profile.png' ?>');">
									<div style="height: 50vh;"></div>
								</div>
								<div class="card-body">
									<h6 class="card-title"><?= $trainer['name'] ?></h6>
									<p class="card-text" style="margin-bottom:9px; font-size:13px"><b>Age - </b>&nbsp;&nbsp;<?= ageCalculator($trainer['dob']); ?></p>
									<p class="card-text" style="margin-bottom:9px; font-size:13px"><b>Education -</b>&nbsp;&nbsp;<?= $trainer['Education'] ?></p>
									<p class="card-text" style="margin-bottom:9px; font-size:13px"><b>Experience -</b>&nbsp;&nbsp;<?=  $trainer['experience']?></p>
									<p class="card-text" style="margin-bottom:9px; font-size:13px"><b>Package -</b>&nbsp;&nbsp;<?=  $trainer['package']?></p>
								</div>
								<div class="card-footer">
									<p class="card-text ">Location:&nbsp;&nbsp;<b><?= $trainer['state'] ?>,&nbsp;<?= $trainer['city'] ?></b></p>
								</div>
							</div>
						</div>
					</div>
				<?php }; 
			}else{
				echo "<h2 class='m-auto'>Sorry !, there are no trainers yet.</h2>";
			};?>
		</div>
	</div>


 <script>
		// search results //
		function myFunction() {
			var input, filter, cards, cardContainer, h5, title, i;
			input = document.getElementById("myFilter");
			filter = input.value.toUpperCase();
			cardContainer = document.getElementById("myItems");
			cards = cardContainer.getElementsByClassName("card");
			for (i = 0; i < cards.length; i++) {
				title = cards[i].querySelector("b");
				if (title.innerText.toUpperCase().indexOf(filter) > -1) {
					cards[i].parentElement.style.display = "flex"
				} else {
					cards[i].parentElement.style.display = "none"
				}
			}
		}


		function searchName() {
			var input, filter, cards, cardContainer, h5, title, i;
			input = document.getElementById("nameInput");
			filter = input.value.toUpperCase();
			cardContainer = document.getElementById("myItems");
			cards = cardContainer.getElementsByClassName("card");
			for (i = 0; i < cards.length; i++) {
				title = cards[i].querySelector("h6.card-title");
				if (title.innerText.toUpperCase().indexOf(filter) > -1) {
					cards[i].parentElement.style.display = "flex"
				} else {
					cards[i].parentElement.style.display = "none"
				}
			}
		}
	</script>
</body>

</html>
