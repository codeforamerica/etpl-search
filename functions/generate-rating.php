<?php
	function int($string) {		
		$int = str_replace("$", "", $string);
		$int = str_replace(",", "", $int);
		return intval($int);
	}

	function convert_range($input, $input_max, $input_min, $output_max, $output_min) {
		return (($input - $input_min) / ($input_max - $input_min)) * ($output_max - $output_min) + $output_min;
	}
	
	// normalizing value
	function convert_to_0_1($input, $input_max, $input_min) {
		return (($input - $input_min) / ($input_max - $input_min)) * (1 - 0) + 0;
	}

	function generate_rating($program) {
	
		// FEATUTRES
		$rating_components["features"] = 0;
	
		$rating_components["features"] =
			$program["features_wia_eligible"] + 
	    	($program["features_child_care_offered_on_site"] * 4) + 
	    	($program["features_assistance_in_obtaining_child_care"] * 3) + 
	    	$program["features_wheelchair_accessible"] + 
	    	$program["features_career_counseling_available"] + 
	    	$program["features_customized_training_services_provider"] + 
	    	$program["features_distance_learning_services_provider"] + 
	    	($program["features_spanish_spoken_by_staff"] * 2) + 
	    	($program["features_other_languages_spoken_by_staff"] * 2);
	
		$rating_components["features"] = convert_to_0_1($rating_components["features"], 16, 0);
	
		// COST
		if($program["outcomes_data_6_months_this_program_wage_yearly_value"] != "N/A") {
			$cost = int($cost);
			
			if($cost == 0) {
				$rating_components["cost"] = 1;
			} else {
				$rating_components["cost"] = 1 - (($cost * 10) / int($program["outcomes_data_6_months_this_program_wage_yearly_value"]));
				$rating_components["cost"] = convert_to_0_1($rating_components["cost"], 1, 0);
			}
		}
		
		// IN DEMAND
		$rating_components["in_demand"] = $program["attributes_in_demand"];
		
		// GREEN
		$rating_components["green"] = $program["attributes_green"];
	
		// QUALITY OF 6 MONTH EMPLOYMENT RATE
		if($program["outcomes_data_6_months_this_program_employment_rate_value"] != "N/A") {
			$rating_components["quality_of_6_month_employment_rate_value"] = int(str_replace("%", "", $program["outcomes_data_6_months_this_program_employment_rate_value"]));
			$rating_components["quality_of_6_month_employment_rate_value"] = convert_to_0_1($rating_components["quality_of_6_month_employment_rate_value"], 100, 0);
		}
	
		// QUALITY OF 1 + 2 YEAR EMPLOYMENT RATE
		if($program["outcomes_data_1_year_this_program_employment_rate_value"] != "N/A" && $program["outcomes_data_2_years_this_program_employment_rate_value"] != "N/A") {	
			$rating_components["quality_of_1_plus_years_employment_rate_value"] = (int(str_replace("%", "", $program["outcomes_data_1_year_this_program_employment_rate_value"])) + int(str_replace("%", "", $program["outcomes_data_2_years_this_program_employment_rate_value"]))) / 2;
			$rating_components["quality_of_1_plus_years_employment_rate_value"] = convert_to_0_1($rating_components["quality_of_1_plus_years_employment_rate_value"], 100, 0);
		}
		
		// QUALITY OF GROWTH FROM 6 MONTH WAGES AND 2 YEAR WAGES
		if($program["outcomes_data_6_months_this_program_wage_yearly_value"] != "N/A" && $program["outcomes_data_2_years_this_program_wage_yearly_value"] != "N/A") {
			$rating_components["wage_growth"] = 1;
			$rating_components["wage_growth"] = (int($program["outcomes_data_2_years_this_program_wage_yearly_value"]) / int($program["outcomes_data_6_months_this_program_wage_yearly_value"]));
		}
	
		// Calculate total
		$rating_components_total = 0;
		foreach($rating_components as $rating_components_key => $rating_components_value) {
			$rating_components_total += $rating_components_value;
		}
		$rating_components_total = convert_range($rating_components_total, count($rating_components), 0, 10, 1);
		if($rating_components_total > 10) { $rating_components_total = 10; }
	
		// Calculate color
		if($rating_components_total > 0 && $rating_components_total <= 2) { $rating_color = "#E53300"; }
		if($rating_components_total > 2 && $rating_components_total <= 4) { $rating_color = "#EBA51E"; }
		if($rating_components_total > 4 && $rating_components_total <= 6) { $rating_color = "#E6D215"; }
		if($rating_components_total > 6 && $rating_components_total <= 8) { $rating_color = "#AFD531"; }
		if($rating_components_total > 8 && $rating_components_total <= 10) { $rating_color = "#71CE0B"; }
		
		// Build return array
		$rating["total"] = number_format($rating_components_total, 1);
		$rating["components"] = $rating_components;
		$rating["color"] = $rating_color;
		
		return $rating;
	}
?>