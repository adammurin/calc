<!DOCTYPE html>
<html lang="sk" ng-app="calc">
	<head>
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-2.1.4.min.js" ></script>
		<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
						
		<!-- jQuery UI theme -->
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.css">
		
		<!-- Bootstrap CSS, Theme and JS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		
		<!-- Angular JS -->
		<script type="text/javascript" src="js/angular.min.js" ></script>
		
		<!-- Slider JS and CSS -->
		<script type="text/javascript" src="js/nouislider.min.js" ></script>
		<script type="text/javascript" src="js/wNumb.min.js" ></script>
		<link rel="stylesheet" href="css/nouislider.min.css">
		<link rel="stylesheet" href="css/nouislider.pips.css">
		
		<script type="text/javascript" src="js/jquery-ui-slider-pips.js" ></script>
		<link rel="stylesheet" href="css/jquery-ui-slider-pips.css">
		
		<!-- Charts JS and CSS -->
		<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
		<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
		
		<!-- Main JS file -->
		<script type="text/javascript" src="js/app.js" ></script>
		
		<!-- Main CSS file -->
		<link href="css/main.css" rel="stylesheet">
		
	</head>
	
	<body>
		<div id="wrapper" ng-controller="CalcController as calc">
			
			<div class="calcForm">
				<form method="post" action="index.php">
					<div class="formRow totalAmount">
						<div class="label">Výška úveru</div>
						<input type="" class="" id="totalAmount" name="totalAmount" value="<?php echo isset($_POST['totalAmount']) ? $_POST['totalAmount'] : '' ?>"/>
						<div class="unit">€</div>
						<div id="totalAmountSlider" class="slider">
						</div>
					</div>
					<div class="formRow totalDuration">
						<div class="label">Doba splácania</div>
						<input type="text" class="" id="totalDuration" name="totalDuration" value="<?php echo isset($_POST['totalDuration']) ? $_POST['totalDuration'] : '' ?>" size="2"/>
						<div class="unit">rokov</div>
						<div id="totalDurationSlider" class="slider">
						</div>
					</div>
					<div class="formRow yearlyRate">
						<div class="label">Úrok</div>
						<input type="text" class="" name="yearlyRate" value="2.5" size="5"/>
						<div class="unit">%</div>
						<div id="pips-range" class="slider">
						</div>
						<div id="pips-range-rtl" class="slider">
						</div>
						<div id="pips-range-vertical" class="slider">
						</div>
						<div id="pips-range-vertical-rtl" class="slider">
						</div>
						<div id="pips-steps" class="slider">
						</div>
					</div>
					<div class="formRow">
						<input type="submit" name="submit" class="btn button btn-success" value="Vypočítať">
					</div>
					<!--<div class="ct-chart ct-perfect-fourth"></div>
					-->
				</form>
			</div>
			
						
			<?php //if(isset($_POST["submit"])){ echo isset($_POST['yearlyRate']) ? $_POST['yearlyRate'] : ''} else {echo "2.50%"} ?>
			
			<?php
				$displayResultsTable = "hidden";
				if(isset($_POST["submit"])){
					$displayResultsTable = "visible";
					
					$totalAmount = $_POST["totalAmount"];
					$totalDuration = $_POST["totalDuration"];
					$yearlyRate = $_POST["yearlyRate"];
					
					$montlyRate = $yearlyRate / 12 / 100;
					$numberOfPayments = $totalDuration * 12;
					
					$montlyPaymentBottomPart = 1 / (1 + $montlyRate);
					$montlyPaymentBottomPartToPower = pow($montlyPaymentBottomPart, $numberOfPayments);
					
					$monthlyPayment = $totalAmount * $montlyRate / (1 - $montlyPaymentBottomPartToPower);
					
					$totalPaidAmount = $monthlyPayment * $numberOfPayments;
					
					$totalCostOfLoan = $totalPaidAmount - $totalAmount;
					$relativeCostOfLoan = $totalCostOfLoan / $totalAmount * 100;
				}
			?>

			<!--
			<div class="calcResults <?php echo $displayResultsTable; ?>">
				<form>
					<div class="formRow monthlyPayment">
						<div class="label">Mesačná splátka</div>
						<div class="value"><?php echo number_format((float)$monthlyPayment, 2, '.', ''); ?></div>
						<div class="unit">€</div>
					</div>
					<div class="formRow monthlyPayment">
						<div class="label">Celková zaplatená suma</div>
						<div class="value"><?php echo number_format((float)$totalPaidAmount, 2, '.', ''); ?></div>
						<div class="unit">€</div>
					</div>
					<div class="formRow monthlyPayment">
						<div class="label">Navýšenie</div>
						<div class="value"><?php echo number_format((float)$totalCostOfLoan, 2, '.', ''); ?><div class="unit">€</div> ( <?php echo number_format((float)$relativeCostOfLoan, 2, '.', ''); ?>% )</div>
					</div>
				</form>
			</div>
			-->
			
			<div class="calcResults table-responsive tableHolder <?php echo $displayResultsTable; ?>">
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="bankName" colspan="2">
								Inštitúcia
							</th>
							<th>
								Výška úveru
							</th>
							<th>
								Úrok p.a.
							</th>
							<th>
								Splatnosť
							</th>
							<th>
								Mesačná splátka
							</th>
							<th>
								Celková zaplatená suma
							</th>
							<th>
								Navýšenie
							</th>
						</tr>
					</thead>
					<tbody class="accordionWrapper" ng-repeat="bank in calc.offer">
						
						<!-- Load summary -->
						<tr class="loanSummaryTable main accordionTrigger {{bank.alias}}">
							<td class="bankLogo">
								<span></span>
							</td>
							<td class="bankName">
							{{bank.name}}
							</td>
							<td class="value">
								<?php echo number_format($totalAmount, 0, ",", " "); ?><div class="unit"> €</div>
							</td>
							<td class="value">
							{{bank.interestRate | number:2}}<div class="unit">%</div>
							</td>
							<td class="value">
								<?php echo $totalDuration; ?><div class="unit"> rokov</div>
							</td>
							<td class="value">
								<?php echo number_format((float)$monthlyPayment, 2, '.', ' '); ?><div class="unit"> €</div>
							</td>
							<td class="value">
								<?php echo number_format((float)$totalPaidAmount, 2, '.', ' '); ?><div class="unit"> €</div>
							</td>
							<td class="value">
							<?php echo number_format((float)$totalCostOfLoan, 2, '.', ' '); ?><div class="unit"> €</div> (<?php echo number_format((float)$relativeCostOfLoan, 2, '.', ' '); ?>%)
							</td>
						</tr>
						<div class="ct-chart ct-perfect-fourth"></div>
						<!-- Load details -->
						<tr class="loanDetailsTable head accordionContent">
							<td class="value" colspan="4">
								<h5>Podmienky úveru</h5>
							</td>
							<td class="value" colspan="4">
								<h5>Graf úroku a úmoru</h5>
							</td>
						</tr>
						<tr class="loanDetailsTable content accordionContent">
							<td class="value loanConditions" colspan="4">
								<ul>
									<li data-ng-repeat="condition in bank.conditions">
										{{condition}}
									</li>
								</ul>
							</td>
							<td class="value graph loanCourse" colspan="4">
								<div style="width: 100%;height:100px;background:#aaa;color:#fff;font-size:40px;padding:20px;text-align:center;">
									Graf
								</div>
							</td>
						</tr>
						
						<!--
						<tr>
							<td class="bankName">
							{{calc.bank.name}}
							</td>
							<td class="value">
								<?php echo number_format($totalAmount, 0, ",", " "); ?><div class="unit"> €</div>
							</td>
							<td class="value">
								<?php echo number_format((float)$yearlyRate, 2, '.', ' '); ?><div class="unit">%</div>
							</td>
							<td class="value">
								<?php echo $totalDuration; ?><div class="unit"> rokov</div>
							</td>
							<td class="value">
								<?php echo number_format((float)$monthlyPayment, 2, '.', ' '); ?><div class="unit"> €</div>
							</td>
							<td class="value">
								<?php echo number_format((float)$totalPaidAmount, 2, '.', ' '); ?><div class="unit"> €</div>
							</td>
							<td class="value">
							<?php echo number_format((float)$totalCostOfLoan, 2, '.', ' '); ?><div class="unit"> €</div> (<?php echo number_format((float)$relativeCostOfLoan, 2, '.', ' '); ?>%)
							</td>
						</tr>
						
						<tr class="loanDetailsTable head">
							<td class="value" colspan="4">
								<h5>Podmienky úveru</h5>
							</td>
							<td class="value" colspan="3">
								<h5>Graf úroku a úmoru</h5>
							</td>
						</tr>
						<tr class="loanDetailsTable content">
							<td class="value loanConditions" colspan="4">
								<ul>
									<li>
										účet v banke
									</li>
									<li>
										sporiaci účet
									</li>
								</ul>
							</td>
							<td class="value graph loanCourse" colspan="3">
								<div style="width: 100%;height:100px;background:#aaa;color:#fff;font-size:40px;padding:20px;text-align:center;">
									Graf
								</div>
							</td>
						</tr>
						
						<tr>
							<td class="bankName">
								Tatra banka
							</td>
							<td class="value">
								<?php echo number_format($totalAmount, 0, ",", " "); ?><div class="unit"> €</div>
							</td>
							<td class="value">
								<?php echo number_format((float)$yearlyRate, 2, '.', ' '); ?><div class="unit">%</div>
							</td>
							<td class="value">
								<?php echo $totalDuration; ?><div class="unit"> rokov</div>
							</td>
							<td class="value">
								<?php echo number_format((float)$monthlyPayment, 2, '.', ' '); ?><div class="unit"> €</div>
							</td>
							<td class="value">
								<?php echo number_format((float)$totalPaidAmount, 2, '.', ' '); ?><div class="unit"> €</div>
							</td>
							<td class="value">
							<?php echo number_format((float)$totalCostOfLoan, 2, '.', ' '); ?><div class="unit"> €</div> (<?php echo number_format((float)$relativeCostOfLoan, 2, '.', ' '); ?>%)
							</td>
						</tr>
						
						<tr class="loanDetailsTable head">
							<td class="value" colspan="4">
								<h5>Podmienky úveru</h5>
							</td>
							<td class="value" colspan="3">
								<h5>Graf úroku a úmoru</h5>
							</td>
						</tr>
						<tr class="loanDetailsTable content">
							<td class="value loanConditions" colspan="4">
								<ul>
									<li>
										účet v banke
									</li>
									<li>
										sporiaci účet
									</li>
								</ul>
							</td>
							<td class="value graph loanCourse" colspan="3">
								<div style="width: 100%;height:100px;background:#aaa;color:#fff;font-size:40px;padding:20px;text-align:center;">
									Graf
								</div>
							</td>
						</tr>
						-->
						
					</tbody>
				</table>
			</div>
		</div>

	</body>
</html>