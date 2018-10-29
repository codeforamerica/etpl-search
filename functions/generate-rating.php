<?php
	function int($str) {
	    return preg_replace("/([^0-9\\.])/i", "", $str);
	}

	function convert_range($input, $input_max, $input_min, $output_max, $output_min) {
		return (($input - $input_min) / ($input_max - $input_min)) * ($output_max - $output_min) + $output_min;
	}

	function convert_to_0_1($input, $input_max, $input_min) {
		return (($input - $input_min) / ($input_max - $input_min)) * (1 - 0) + 0;
	}

	function generate_rating($program) {
	
		// FEATUTRES
		$rating_component["features"] = 0;
	
		$rating_component["features"] =
			$program["features_wia_eligible"] + 
	    	($program["features_child_care_offered_on_site"] * 4) + 
	    	($program["features_assistance_in_obtaining_child_care"] * 3) + 
	    	$program["features_wheelchair_accessible"] + 
	    	$program["features_career_counseling_available"] + 
	    	$program["features_customized_training_services_provider"] + 
	    	$program["features_distance_learning_services_provider"] + 
	    	($program["features_spanish_spoken_by_staff"] * 2) + 
	    	($program["features_other_languages_spoken_by_staff"] * 2);
	
		$rating_component["features"] = convert_to_0_1($rating_component["features"], 16, 0);
	
		// COST
		$rating_component["cost"] = 0;
	
		if($program["outcomes_data_6_months_this_program_wage_yearly_value"] != "N/A") {
			$rating_component["cost"] = (int($program["outcomes_data_6_months_this_program_wage_yearly_value"]) /  int($program["cost_total"])) / 10;
			$rating_component["cost"] = convert_to_0_1($rating_component["cost"], 10, 0);
		} else {
			$rating_component["cost"] = 0.2;
		}
		
		// IN DEMAND
		$rating_component["in_demand"] = $program["attributes_in_demand"];
		
		// GREEN
		$rating_component["in_demand"] = $program["attributes_green"];
	
		// CREDENTIAL
		$rating_component["credential"] = 0;
	
		if($program["credentials_degree"] != "0") { $rating_component["credential"] += 1; }
		if($program["credentials_credential"] != "0") { $rating_component["credential"] += 1; }
		if($program["credentials_license"] != "0") { $rating_component["credential"] += 1; }
	
		$rating_component["credential"] = convert_to_0_1($rating_component["credential"], 3, 0);
	
		// WAGE DATA AVAILABLE
		$rating_component["wage_data_available"] = 0;
	
		if($program["outcomes_data_6_months_this_program_wage_yearly_value"] != "N/A") { $rating_component["wage_data_available"] += 3; }
		if($program["outcomes_data_1_year_this_program_wage_yearly_value"] != "N/A") { $rating_component["wage_data_available"] += 2; }
		if($program["outcomes_data_2_years_this_program_wage_yearly_value"] != "N/A") { $rating_component["wage_data_available"] += 1; }
	
		$rating_component["wage_data_available"] = convert_to_0_1($rating_component["wage_data_available"], 6, 0);
	
		// EMPLOYMENT RATE AVAILABLE
		$rating_component["employment_rate_available"] = 0;
	
		if($program["outcomes_data_6_months_this_program_employment_rate_value"] != "N/A") { $rating_component["employment_rate_available"] += 3; }
		if($program["outcomes_data_1_year_this_program_employment_rate_value"] != "N/A") { $rating_component["employment_rate_available"] += 2; }
		if($program["outcomes_data_2_years_this_program_employment_rate_value"] != "N/A") { $rating_component["employment_rate_available"] += 1; }
	
		$rating_component["employment_rate_available"] = convert_to_0_1($rating_component["employment_rate_available"], 6, 0);
	
		// QUALITY OF 6 MONTH EMPLOYMENT RATE
		$rating_component["quality_of_6_month_employment_rate_value"] = 0;
	
		$rating_component["quality_of_6_month_employment_rate_value"] = int(str_replace("%", "", $program["outcomes_data_6_months_this_program_employment_rate_value"]));
	
		$rating_component["quality_of_6_month_employment_rate_value"] = convert_to_0_1($rating_component["quality_of_6_month_employment_rate_value"], 100, 0);
	
		// QUALITY OF 1 + 2 YEAR EMPLOYMENT RATE
		$rating_component["quality_of_1_plus_years_employment_rate_value"] = 0;
	
		$rating_component["quality_of_1_plus_years_employment_rate_value"] = (int(str_replace("%", "", $program["outcomes_data_1_year_this_program_employment_rate_value"])) + int(str_replace("%", "", $program["outcomes_data_2_years_this_program_employment_rate_value"]))) / 2;
	
		$rating_component["quality_of_1_plus_years_employment_rate_value"] = convert_to_0_1($rating_component["quality_of_1_plus_years_employment_rate_value"], 100, 0);
	
		// AMOUNT OF PRE-REQUISITES (BARRIER)
		$rating_component["pre_requisites"] = 0;
	
		if(strtolower($program["info_admission_pre_requisites"]) != "none") {
			$rating_component["pre_requisites"] = 0;
		}
	
		$rating_component["pre_requisites"] = convert_to_0_1($rating_component["pre_requisites"], 1, 0);
	
		// QUALITY OF GROWTH FROM 6 MONTH WAGES AND 2 YEAR WAGES
		$rating_component["wage_growth"] = 1;
	
		if($program["outcomes_data_6_months_this_program_wage_yearly_value"] != "N/A" && $program["outcomes_data_2_years_this_program_wage_yearly_value"] != "N/A") {
			$rating_component["wage_growth"] = (int(str_replace("$", "", $program["outcomes_data_2_years_this_program_wage_yearly_value"])) / int(str_replace("$", "", $program["outcomes_data_6_months_this_program_wage_yearly_value"])));
		
			$rating_component["wage_growth"] = $rating_component["wage_growth"];
		}
	
		// Calculate total
		$rating_component_total = 0;
		foreach($rating_component as $rating_component_key => $rating_component_value) {
			$rating_component_total += int($rating_component_value);
		}
		$rating_component_total = convert_range($rating_component_total, count($rating_component), 0, 10, 1);
	
		// Calculate color
		if($rating_component_total > 0 && $rating_component_total <= 2) { $rating_color = "#E53300"; }
		if($rating_component_total > 2 && $rating_component_total <= 4) { $rating_color = "#EBA51E"; }
		if($rating_component_total > 4 && $rating_component_total <= 6) { $rating_color = "#E6D215"; }
		if($rating_component_total > 6 && $rating_component_total <= 8) { $rating_color = "#AFD531"; }
		if($rating_component_total > 8 && $rating_component_total <= 10) { $rating_color = "#71CE0B"; }
		
		// Build return array
		$rating["total"] = number_format($rating_component_total, 1);
		$rating["components"] = $rating_component;
		$rating["color"] = $rating_color;
		
		return $rating;
	}
?>