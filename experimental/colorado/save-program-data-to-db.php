<?php
	include("../../helpers/db-config.php");

	function getProviderPrograms($cip_code) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://www.cotrainingproviders.org/api/provider/getProviderPrograms/".$cip_code."/"); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_HEADER, 0);
		$curl_result = curl_exec($curl); 
		curl_close($curl);
		
		$json = json_decode($curl_result, true);
	
		foreach($json as $all_data => $program_data) {
			saveToDatabase($program_data);
			
			// print_r($program_data);
		}
	}
		
	function saveToDatabase($data) {
		global $db;
	 	if(!$db) { die("Connection failed: " . mysqli_connect_error()); }
		
		$query = "INSERT INTO programs (
			state,
			program_id,
			program_name,
			provider_name,
			cip_name,
			provider_address,
			provider_website,
			provider_contact_info,
			provider_licensing_agency,
			provider_non_govt_accrediting_org,
			attributes_in_demand,
			attributes_green,
			description_short,
			description_unique_features,
			credentials_degree,
			credentials_credential,
			credentials_license,
			features_wia_eligible,
			features_child_care_offered_on_site,
			features_assistance_in_obtaining_child_care,
			features_wheelchair_accessible,
			features_career_counseling_available,
			features_customized_training_services_provider,
			features_distance_learning_services_provider,
			features_spanish_spoken_by_staff,
			features_other_languages_spoken_by_staff,
			cost_total,
			cost_tuition,
			cost_books_and_materials,
			cost_supplies_and_tools,
			cost_other,
			info_admission_pre_requisites,
			info_clock_hours,
			info_credit_hours,
			info_calendar_length,
			info_licensing_agency,
			info_provider_type,
			contact_name,
			contact_phone_number,
			contact_phone_number_ext,
			contact_email,
			rating_value,
			rating_count,
			outcomes_data_6_months_this_program_employment_rate_value,
			outcomes_data_6_months_this_program_employment_rate_num,
			outcomes_data_6_months_this_program_wage_quarterly_value,
			outcomes_data_6_months_this_program_wage_quarterly_num,
			outcomes_data_6_months_this_program_wage_yearly_value,
			outcomes_data_6_months_this_program_wage_yearly_num,
			outcomes_data_6_months_this_provider_employment_rate_value,
			outcomes_data_6_months_this_provider_employment_rate_num,
			outcomes_data_6_months_this_provider_wage_quarterly_value,
			outcomes_data_6_months_this_provider_wage_quarterly_num,
			outcomes_data_6_months_this_provider_wage_yearly_value,
			outcomes_data_6_months_this_provider_wage_yearly_num,
			outcomes_data_6_months_related_programs_employment_rate_value,
			outcomes_data_6_months_related_programs_employment_rate_num,
			outcomes_data_6_months_related_programs_wage_quarterly_value,
			outcomes_data_6_months_related_programs_wage_quarterly_num,
			outcomes_data_6_months_related_programs_wage_yearly_value,
			outcomes_data_6_months_related_programs_wage_yearly_num,
			outcomes_data_1_year_this_program_employment_rate_value,
			outcomes_data_1_year_this_program_employment_rate_num,
			outcomes_data_1_year_this_program_retention_rate_value,
			outcomes_data_1_year_this_program_retention_rate_num,
			outcomes_data_1_year_this_program_wage_quarterly_value,
			outcomes_data_1_year_this_program_wage_quarterly_num,
			outcomes_data_1_year_this_program_wage_yearly_value,
			outcomes_data_1_year_this_program_wage_yearly_num,
			outcomes_data_1_year_this_provider_employment_rate_value,
			outcomes_data_1_year_this_provider_employment_rate_num,
			outcomes_data_1_year_this_provider_retention_rate_value,
			outcomes_data_1_year_this_provider_retention_rate_num,
			outcomes_data_1_year_this_provider_wage_quarterly_value,
			outcomes_data_1_year_this_provider_wage_quarterly_num,
			outcomes_data_1_year_this_provider_wage_yearly_value,
			outcomes_data_1_year_this_provider_wage_yearly_num,
			outcomes_data_1_year_related_programs_employment_rate_value,
			outcomes_data_1_year_related_programs_employment_rate_num,
			outcomes_data_1_year_related_programs_retention_rate_value,
			outcomes_data_1_year_related_programs_retention_rate_num,
			outcomes_data_1_year_related_programs_wage_quarterly_value,
			outcomes_data_1_year_related_programs_wage_quarterly_num,
			outcomes_data_1_year_related_programs_wage_yearly_value,
			outcomes_data_1_year_related_programs_wage_yearly_num,
			outcomes_data_2_years_this_program_employment_rate_value,
			outcomes_data_2_years_this_program_employment_rate_num,
			outcomes_data_2_years_this_program_retention_rate_value,
			outcomes_data_2_years_this_program_retention_rate_num,
			outcomes_data_2_years_this_program_wage_quarterly_value,
			outcomes_data_2_years_this_program_wage_quarterly_num,
			outcomes_data_2_years_this_program_wage_yearly_value,
			outcomes_data_2_years_this_program_wage_yearly_num,
			outcomes_data_2_years_this_provider_employment_rate_value,
			outcomes_data_2_years_this_provider_employment_rate_num,
			outcomes_data_2_years_this_provider_retention_rate_value,
			outcomes_data_2_years_this_provider_retention_rate_num,
			outcomes_data_2_years_this_provider_wage_quarterly_value,
			outcomes_data_2_years_this_provider_wage_quarterly_num,
			outcomes_data_2_years_this_provider_wage_yearly_value,
			outcomes_data_2_years_this_provider_wage_yearly_num,
			outcomes_data_2_years_related_programs_employment_rate_value,
			outcomes_data_2_years_related_programs_employment_rate_num,
			outcomes_data_2_years_related_programs_retention_rate_value,
			outcomes_data_2_years_related_programs_retention_rate_num,
			outcomes_data_2_years_related_programs_wage_quarterly_value,
			outcomes_data_2_years_related_programs_wage_quarterly_num,
			outcomes_data_2_years_related_programs_wage_yearly_value,
			outcomes_data_2_years_related_programs_wage_yearly_num
		) VALUES (
			'CO',
			'".$data["id"]."',
			'".mysqli_real_escape_string($db, $data["programName"])."',
			'".mysqli_real_escape_string($db, $data["providerName"])."',
			'".mysqli_real_escape_string($db, $data["cipCodeTitle"])."',
			'".mysqli_real_escape_string($db, $data["providerAddresses"][0]["address1"]." ".$data["providerAddresses"][0]["city"].", ".$data["providerAddresses"][0]["stateCode"]." ".$data["providerAddresses"][0]["zipCode"])."',
			'".mysqli_real_escape_string($db, $data["providerAddresses"][0]["admissionsWebsite"])."',
			'".mysqli_real_escape_string($db, $data["contact"])."',
			'',
			'',
			'".mysqli_real_escape_string($db, $data["employmentDemand"])."',
			'',
			'".mysqli_real_escape_string($db, $data["description"])."',
			'".mysqli_real_escape_string($db, $data["specialCharacteristics"])."',
			'',
			'".mysqli_real_escape_string($db, $data["credentialLevelName"])."',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'".mysqli_real_escape_string($db, $data["totalCost"])."',
			'".mysqli_real_escape_string($db, $data["tuitionCost"])."',
			'".mysqli_real_escape_string($db, $data["booksCost"])."',
			'".mysqli_real_escape_string($db, $data["suppliesCost"])."',
			'".mysqli_real_escape_string($db, $data["feesCost"])."',
			'".mysqli_real_escape_string($db, $data["prerequisiteSkills"])."',
			'".mysqli_real_escape_string($db, $data["totalContactHours"])."',
			'".mysqli_real_escape_string($db, $data["totalCreditHours"])."',
			'".mysqli_real_escape_string($db, $data["durationId"]." ".$data["durationTypeName"])."',
			'".mysqli_real_escape_string($db, $data["currentApprovalStatus"])."',
			'".mysqli_real_escape_string($db, $data["providerTypeName"])."',
			'".mysqli_real_escape_string($db, $data["providerAddresses"][0]["contact"])."',
			'".mysqli_real_escape_string($db, $data["providerAddresses"][0]["phone"])."',
			'',
			'".mysqli_real_escape_string($db, $data["providerAddresses"][0]["email"])."',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'".mysqli_real_escape_string($db, $data["percentCompleting"])."',
			'',
			'',
			'',
			'".mysqli_real_escape_string($db, $data["programWages"])."',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			''
		)";
		
		// echo $query;

		if(mysqli_query($db, $query)) {
			echo $data["program_id"]." added to database";
		} else {
			echo "Error: ".$query."<br>" . mysqli_error($db);
		}
	}
?>
<pre>
	<?php
		getProviderPrograms($_GET["cip_code"]);
	?>
</pre>