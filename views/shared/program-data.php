<?php
	if($program["outcomes_data_6_months_this_program_employment_rate_value"] != "N/A") {
		$employment_rate = int($program["outcomes_data_6_months_this_program_employment_rate_value"]);
		if($employment_rate > 60) { $employment_rate_rating = "good"; }
		if($employment_rate < 60 && $employment_rate >= 40) { $employment_rate_rating = "ok"; }
		if($employment_rate < 40) { $employment_rate_rating = "bad"; }
	?>
	
	<h3 class="employment-rate <?php echo $employment_rate_rating; ?>"><div class="icon"></div><div class="number"><?php echo $program["outcomes_data_6_months_this_program_employment_rate_value"]; ?></div> employed after 6 months</h3>
<?php } else { ?>
	<h3 class="no-data"><div class="icon"></div>No employment data</h3>
<?php } ?>

<?php if(
		$program["outcomes_data_2_years_this_program_wage_yearly_value"] != "N/A" &&
		$program["outcomes_data_6_months_this_program_wage_yearly_value"] != "N/A"
) { ?>

	<?php if($program["outcomes_data_6_months_this_program_wage_yearly_value"] != "N/A") { ?>
		<h3 class="wages"><div class="icon"></div><div class="number">$<?php echo number_format(round(int(str_replace("$", "", $program["outcomes_data_6_months_this_program_wage_yearly_value"])), 1)); ?></div> salary after 6 months</h3>
	<?php } ?>

	<?php if($program["outcomes_data_2_years_this_program_wage_yearly_value"] != "N/A") { ?>
		<h3 class="wages"><div class="icon"></div><div class="number">$<?php echo number_format(round(int(str_replace("$", "", $program["outcomes_data_2_years_this_program_wage_yearly_value"])), 1)); ?></div> salary after 2 years</h3>
	<?php } ?>
<?php } elseif($program["outcomes_data_6_months_this_program_wage_yearly_value"] == "N/A" && $program["outcomes_data_2_years_this_program_wage_yearly_value"] == "N/A") { ?>

	<h3 class="no-data"><div class="icon"></div>No salary data</h3>

<?php } else { ?>

	<?php if($program["outcomes_data_6_months_this_program_wage_yearly_value"] != "N/A") { ?>
		<h3 class="wages"><div class="icon"></div><div class="number">$<?php echo number_format(round(int(str_replace("$", "", $program["outcomes_data_6_months_this_program_wage_yearly_value"])), 1)); ?></div> salary after 6 months</h3>
	<?php } else { ?>
		<h3 class="no-data"><div class="icon"></div>No data for salary after 6 months</h3>
	<?php } ?>

	<?php if($program["outcomes_data_2_years_this_program_wage_yearly_value"] != "N/A") { ?>
		<h3 class="wages"><div class="icon"></div><div class="number">$<?php echo number_format(round(int(str_replace("$", "", $program["outcomes_data_2_years_this_program_wage_yearly_value"])), 1)); ?></div> salary after 2 years</h3>
	<?php } else { ?>
		<h3 class="no-data"><div class="icon"></div>No data for salary after 2 years</h3>
	<?php } ?>

<?php } ?>