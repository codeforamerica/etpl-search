<?php
	function clean_html($html) {
		$html = str_replace("\n", "", $html);
		$html = str_replace("\r", "", $html);
		$html = str_replace("\t", "", $html);
		$html = str_replace("> <", "><", $html);
		
		return $html;
	}
	
	function get_html($type, $program_id) {
		$base_url = "http://www.njtrainingsystems.org/Program/";

		$html_data = curl_init();

		if($type == "program_info") {
			$url = $base_url."Default.aspx";
			curl_setopt($html_data, CURLOPT_URL, $url."?ProgramID=".$program_id);
		} elseif($type == "outcomes_data_6_months" || $type == "outcomes_data_1_year" || $type == "outcomes_data_2_years") {
			$url = $base_url."Results/ProgramResults.aspx";

			curl_setopt($html_data, CURLOPT_URL, $url."?ProgramID=".$program_id);
			curl_setopt($html_data, CURLOPT_POST, 1);
			if($type == "outcomes_data_6_months") {
				curl_setopt($html_data, CURLOPT_POSTFIELDS, "__EVENTTARGET=cboQuarter&ShowSampleSize=1&cboQuarter=2");
			} elseif($type == "outcomes_data_1_year") {
				curl_setopt($html_data, CURLOPT_POSTFIELDS, "__EVENTTARGET=cboQuarter&ShowSampleSize=1&cboQuarter=4");
			} elseif($type == "outcomes_data_2_years") {
				curl_setopt($html_data, CURLOPT_POSTFIELDS, "__EVENTTARGET=cboQuarter&ShowSampleSize=1&cboQuarter=8");
			}
		} elseif($type == "provider_info") {
			$url = $base_url."Provider.aspx";
			curl_setopt($html_data, CURLOPT_URL, $url."?ProgramID=".$program_id);
		}

		curl_setopt($html_data, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($html_data, CURLOPT_HEADER, 0);
		
		return curl_exec($html_data);
		curl_close($html_data);
	}

	function parse_rate_and_number($stat) {
		$stat = str_replace(")", "", $stat);
		$exploded_stats = explode("(", $stat);
		return $exploded_stats;
	}
	
	function process_data($program_id) {		
		$html["program_info"] = get_html("program_info", $program_id);
		$html["program_info"] = clean_html($html["program_info"]);
		
		$html["provider_info"] = get_html("provider_info", $program_id);
		$html["provider_info"] = clean_html($html["provider_info"]);
	
		$html["outcomes_data_6_months"] = get_html("outcomes_data_6_months", $program_id);
		$html["outcomes_data_6_months"] = clean_html($html["outcomes_data_6_months"]);
	
		$html["outcomes_data_1_year"] = get_html("outcomes_data_1_year", $program_id);
		$html["outcomes_data_1_year"] = clean_html($html["outcomes_data_1_year"]);
	
		$html["outcomes_data_2_years"] = get_html("outcomes_data_2_years", $program_id);
		$html["outcomes_data_2_years"] = clean_html($html["outcomes_data_2_years"]);
	
		preg_match('/<b>Program Name:<\/b><\/font><\/td><td><font face="arial, verdana" size="2" color="#ffffff"><b>(.*)<\/b><\/font><\/td><\/tr><tr><td><font face="arial, verdana" size="2" color="#ffcf00"><b>Provider Name:<\/b><\/font><\/td><td><font face="arial, verdana" size="2" color="#ffffff"><b>(.*)<\/b><\/font><\/td><\/tr><tr><td><font face="arial, verdana" size="2" color="#ffcf00"><b>CIP Name:<\/b><\/font><\/td><td><font face="arial, verdana" size="2" color="#ffffff"><b>(.*)<\/b><\/font>/', $html["program_info"], $name);
	
		preg_match('/<table cellpadding="4" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#7d8a9b"><font face="arial, verdana" size="2"><b>Description Of The Program:<\/b><\/font><\/td><\/tr><tr><td valign="top" bgcolor="#ffffff"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr><td valign="top" bgcolor="#ffffff">&nbsp;<\/td><\/tr><\/table><\/td><\/tr><\/table><p><table cellpadding="3" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#000000"><table cellpadding="4" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#7d8a9b"><font face="arial, verdana" size="2"><b>Description of Unique Features Of The Program:<\/b><\/font><\/td><\/tr><tr><td valign="top" bgcolor="#ffffff"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><\/table><\/td><\/tr><\/table><p><table cellpadding="3" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#000000"><table cellpadding="4" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#7d8a9b"><font face="arial, verdana" size="2">/', $html["program_info"], $description);
	
		preg_match('/<b>Degree, License, or Credential Offered by the Program:<\/b><\/font><\/td><\/tr><tr><td valign="top" bgcolor="#ffffff"><font face="arial, verdana" size="2"><b>Degree:<\/b>&nbsp;(.*)<\/font><\/td><\/tr><tr><td valign="top" bgcolor="#ffffff"><font face="arial, verdana" size="2"><b>Credential:<\/b>(.*)<\/font><\/td><\/tr><tr><td valign="top" bgcolor="#ffffff"><font face="arial, verdana" size="2"><b>License:<\/b>(.*)<\/font><\/td><\/tr><\/table><\/td><\/tr><\/table><p><table cellpadding="3" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#000000"><table cellpadding="4" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#7d8a9b" colspan="2"><font face="arial, verdana" size="2">/', $html["program_info"], $credentials);
	
		preg_match('/<b>Special Features<\/b><\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2"><b>WIA Eligible:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#d1d1d1"><td valign="top"><font face="arial, verdana" size="2"><b>Child Care Offered On Site:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2"><b>Assistance In Obtaining Child Care:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#d1d1d1"><td valign="top"><font face="arial, verdana" size="2"><b>Wheelchair Accessible:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2"><b>Career Counseling Available:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#d1d1d1"><td valign="top"><font face="arial, verdana" size="2"><b>Customized Training Services Provider:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2"><b>Distance Learning Services Provider:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#d1d1d1"><td valign="top"><font face="arial, verdana" size="2"><b>Spanish Spoken By Staff:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2"><b>Other Languages Spoken By Staff:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#d1d1d1"><td valign="top"><font face="arial, verdana" size="2"><b>Evening Courses:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2"><b>Financial Aid Assistance Available:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#d1d1d1"><td valign="top"><font face="arial, verdana" size="2"><b>Linked to 1-stop system:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2"><b>Personal on-site job placement assistance:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#d1d1d1"><td valign="top"><font face="arial, verdana" size="2"><b>Access to America\'s Job Bank:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><\/table><\/td><\/tr><\/table><p><table cellpadding="3" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#000000"><table cellpadding="3" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#7d8a9b" colspan="2"><font face="arial, verdana" size="2"><b>Program Cost &amp; Basic Information:<\/b>/', $html["program_info"], $features);
	
		preg_match('/<b>Total Cost Of Program:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2" color="#960018">(.*)<\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2">Tuition<\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#d1d1d1"><td valign="top"><font face="arial, verdana" size="2">Fees<\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2">Books &amp; Materials<\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#d1d1d1"><td valign="top"><font face="arial, verdana" size="2">Supplies &amp; Tools<\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2">Other<\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" colspan="2">&nbsp;<\/td><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Pre-requisites For&nbsp;Admission:<\/b>/', $html["program_info"], $cost);
	
		preg_match('/<b>Pre-requisites For&nbsp;Admission:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Total Clock Hours:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Total Credit Hours:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Calendar length of program:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" colspan="2">&nbsp;<\/td><\/tr><tr bgcolor="#e6e6e6"><td valign="top"><font face="arial, verdana" size="2"><b>Licensing Agency:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Type Of Training Provider:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><\/table><\/td><\/tr><\/table><p><table cellpadding="3" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#000000"><table cellpadding="3" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#7d8a9b" colspan="2"><font face="arial, verdana" size="2"><b>Individual To Contact For More Program Information:<\/b>/', $html["program_info"], $info);
	
		preg_match('/<b>Individual To Contact For More Program Information:<\/b><\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Contact:<\/b><\/font><\/td><td valign="top" width="100%"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Phone:<\/b><\/font><\/td><td valign="top" width="100%"><font face="arial, verdana" size="2">(.*)<b>Ext:<\/b>(.*)<\/font><\/td><\/tr><!--<tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Email:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td width="100%">&nbsp;<\/td><\/tr>--><\/table><\/td><\/tr><\/table><!-- End Content --><\/font><\/p><\/TD><\/TR><\/TBODY><\/TABLE><!-- Begin Bottom Tabs -->/', $html["program_info"], $contact);
			
		preg_match('/<table cellpadding=\'2\' cellspacing=\'0\' border=\'0\'><tr><td><font face=\'arial, verdana\' size=\'2\' color=\'#ffcf00\'><b>Average Program Rating:<\/b><\/font><\/td><\/tr><tr><td><font face=\'arial, verdana\' size=\'2\' color=\'#ffcf00\'><b>(.*) \((.*)\)<\/b><\/font><\/td><\/tr><tr><td><font face=\'arial, verdana\' size=\'2\' color=\'#ffcf00\'>/', $html["program_info"], $rating);
		
		preg_match('/<font face="arial, verdana" size="2"><b>Location Of Training Program:<\/b><\/font><\/td><\/tr><tr bgcolor="#ffffff"><td><font face="arial, verdana" size="2">(.*)<\/font><\/td><td align="right"><font face="arial, verdana" size="2"><a href="javascript:OpenDirectionsURL\(\)"><img src="\/images\/link_directions.gif" border="0" WIDTH="155" HEIGHT="20"><\/a><\/font><\/td><\/tr><tr bgcolor="#ffffff"><td><font face="arial, verdana" size="2">(.*)<\/font><\/td><td align="right"><font face="arial, verdana" size="2"><a href="javascript:OpenNJTransitURL\(\)"><img src="\/images\/link_transit.gif" border="0" WIDTH="155" HEIGHT="20"><\/a><\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Individual To Contact For School Information:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" colspan="2">&nbsp;<\/td><\/tr><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Licensing Agency:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top"><font face="arial, verdana" size="2"><b>Non-Governmental Accrediting Organization:<\/b><\/font><\/td><td valign="top"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><\/table><\/td><\/tr><\/table><p><table cellpadding="3" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#000000"><table cellpadding="4" cellspacing="0" border="0" width="100%"><tr><td bgcolor="#7d8a9b" colspan="2"><font face="arial, verdana" size="2"><b>Special Features:<\/b>/', $html["provider_info"], $provider_info);
		
		preg_match('/<a href="(.*)" target="_blank">(.*)<\/a>/', $provider_info[2], $provider_info_website);
		
		if(strpos($html["outcomes_data_6_months"], "Achieved Personal Goal") !== false) {
			preg_match('/<b>Employment Rate<\/b><\/font><a href="javascript: Open_Window1\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Average Quarterly Wage<\/b><\/font><a href="javascript: Open_Window2\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Estimated Yearly Wage<\/b><\/font><a href="javascript: Open_Window3\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td bgcolor="#e6e6e6" colspan="4"><br><br><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Achieved Personal Goal<\/b><\/font><a href="javascript: Open_Window4\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Increased Literacy Level<\/b><\/font><a href="javascript: Open_Window5\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr><td bgcolor="#e6e6e6" colspan="4"><font face="arial, verdana" size="2"><a href="javascript:OnShowSampleSize/', $html["outcomes_data_6_months"], $outcomes_data["6_months"]);
		} else {
			preg_match('/<b>Employment Rate<\/b><\/font><a href="javascript: Open_Window1\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Average Quarterly Wage<\/b><\/font><a href="javascript: Open_Window2\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Estimated Yearly Wage<\/b><\/font><a href="javascript: Open_Window3\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr><td bgcolor="#e6e6e6" colspan="4"><font face="arial, verdana" size="2"><a href="javascript:OnShowSampleSize/', $html["outcomes_data_6_months"], $outcomes_data["6_months"]);
		}

		if(strpos($html["outcomes_data_1_year"], "Achieved Personal Goal") !== false) {
			preg_match('/<b>Employment Rate<\/b><\/font><a href="javascript: Open_Window1\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Retention Rate<\/b><\/font><a href="javascript: Open_Window6\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Average Quarterly Wage<\/b><\/font><a href="javascript: Open_Window2\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Estimated Yearly Wage<\/b><\/font><a href="javascript: Open_Window3\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td bgcolor="#e6e6e6" colspan="4"><br><br><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Achieved Personal Goal<\/b><\/font><a href="javascript: Open_Window4\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Increased Literacy Level<\/b><\/font><a href="javascript: Open_Window5\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr><td bgcolor="#e6e6e6" colspan="4"><font face="arial, verdana" size="2"><a href="javascript:OnShowSampleSize/', $html["outcomes_data_1_year"], $outcomes_data["1_year"]);
		} else {
			preg_match('/<b>Employment Rate<\/b><\/font><a href="javascript: Open_Window1\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Retention Rate<\/b><\/font><a href="javascript: Open_Window6\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Average Quarterly Wage<\/b><\/font><a href="javascript: Open_Window2\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Estimated Yearly Wage<\/b><\/font><a href="javascript: Open_Window3\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr><td bgcolor="#e6e6e6" colspan="4"><font face="arial, verdana" size="2"><a href="javascript:OnShowSampleSize/', $html["outcomes_data_1_year"], $outcomes_data["1_year"]);
		}
	
		if(strpos($html["outcomes_data_2_years"], "Achieved Personal Goal") !== false) {
			preg_match('/<b>Employment Rate<\/b><\/font><a href="javascript: Open_Window1\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Retention Rate<\/b><\/font><a href="javascript: Open_Window6\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Average Quarterly Wage<\/b><\/font><a href="javascript: Open_Window2\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Estimated Yearly Wage<\/b><\/font><a href="javascript: Open_Window3\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td bgcolor="#e6e6e6" colspan="4"><br><br><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Achieved Personal Goal<\/b><\/font><a href="javascript: Open_Window4\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Increased Literacy Level<\/b><\/font><a href="javascript: Open_Window5\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr><td bgcolor="#e6e6e6" colspan="4"><font face="arial, verdana" size="2"><a href="javascript:OnShowSampleSize/', $html["outcomes_data_2_years"], $outcomes_data["2_years"]);
		} else {
			preg_match('/<b>Employment Rate<\/b><\/font><a href="javascript: Open_Window1\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Retention Rate<\/b><\/font><a href="javascript: Open_Window6\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Average Quarterly Wage<\/b><\/font><a href="javascript: Open_Window2\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr bgcolor="#ffffff"><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2"><b>Estimated Yearly Wage<\/b><\/font><a href="javascript: Open_Window3\(\)"><img src="\/images\/help.gif" alt="Definition" width="12" height="12" border="0"><\/a><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><td valign="top" bgcolor="#e6e6e6"><font face="arial, verdana" size="2">(.*)<\/font><\/td><\/tr><tr><td bgcolor="#e6e6e6" colspan="4"><font face="arial, verdana" size="2"><a href="javascript:OnShowSampleSize/', $html["outcomes_data_2_years"], $outcomes_data["2_years"]);
		}
		
		$data = Array();
	
		$data["program_id"] = $program_id;
		$data["program_name"] = $name[1];
		$data["provider_name"] = $name[2];
		$data["cip_name"] = $name[2];
		
		$data["provider"]["address"] = str_replace("&nbsp;&nbsp;", ", ", $provider_info[1]);
		$data["provider"]["website"] = $provider_info_website[1];
		$data["provider"]["contact_info"] = str_replace("<br>", ", ", str_replace("&nbsp;&nbsp;", ", ", $provider_info[3]));
		$data["provider"]["licensing_agency"] = $provider_info[4];
		$data["provider"]["non_govt_accrediting_org"] = $provider_info[5];

		$data["attributes"]["in_demand"] = (strpos($html["program_info"], "../search/IndemandDetail.aspx") === false) ? 0 : 1;
		$data["attributes"]["green"] = (strpos($html["program_info"], "green_program") === false) ? 0 : 1;
		
		$data["description"]["short"] = $description[1];
		$data["description"]["unique_features"] = $description[2];
	
		$data["credentials"]["degree"] = ($credentials[1] == '&nbsp;' || $credentials[1] == '') ? 0 : $credentials[1];
		$data["credentials"]["credential"] = ($credentials[2] == '&nbsp;' || $credentials[2] == '') ? 0 : $credentials[2];
		$data["credentials"]["license"] = ($credentials[3] == '&nbsp;' || $credentials[3] == '') ? 0 : $credentials[3];
	
		$data["features"]["wia_eligible"] = ($features[1] == 'Yes') ? 1 : 0;
		$data["features"]["child_care_offered_on_site"] = ($features[2] == 'Yes') ? 1 : 0;
		$data["features"]["assistance_in_obtaining_child_care"] = ($features[3] == 'Yes') ? 1 : 0;
		$data["features"]["wheelchair_accessible"] = ($features[4] == 'Yes') ? 1 : 0;
		$data["features"]["career_counseling_available"] = ($features[5] == 'Yes') ? 1 : 0;
		$data["features"]["customized_training_services_provider"] = ($features[6] == 'Yes') ? 1 : 0;
		$data["features"]["distance_learning_services_provider"] = ($features[7] == 'Yes') ? 1 : 0;
		$data["features"]["spanish_spoken_by_staff"] = ($features[8] == 'Yes') ? 1 : 0;
		$data["features"]["other_languages_spoken_by_staff"] = ($features[9] == 'Yes') ? 1 : 0;
		/*
		$data["features"]["evening_courses"] = ($features[10] == 'Yes') ? 1 : 0;
		$data["features"]["financial_aid_assistance_available"] = ($features[11] == 'Yes') ? 1 : 0;
		$data["features"]["linked_to_1stop_system"] = ($features[12] == 'Yes') ? 1 : 0;
		$data["features"]["personal_on_site_job_placement_assistance"] = ($features[13] == 'Yes') ? 1 : 0;
		$data["features"]["access_to_americas_job_bank"] = ($features[14] == 'Yes') ? 1 : 0;
		*/
	
		$data["cost"]["total"] = $cost[1];
		$data["cost"]["tuition"] = $cost[2];
		$data["cost"]["books_and_materials"] = $cost[3];
		$data["cost"]["supplies_and_tools"] = $cost[4];
		$data["cost"]["other"] = $cost[5];
	
		$data["info"]["admission_pre_requisites"] = $info[1];
		$data["info"]["clock_hours"] = $info[2];
		$data["info"]["credit_hours"] = $info[3];
		$data["info"]["calendar_length"] = $info[4];
		$data["info"]["licensing_agency"] = $info[5];
		$data["info"]["provider_type"] = $info[6];
	
		$data["contact"]["name"] = $contact[1];
		$data["contact"]["phone_number"] = $contact[2];
		$data["contact"]["phone_number_ext"] = $contact[3];
		$data["contact"]["email"] = $contact[4];

		$data["rating"]["value"] = substr_count($rating[1], "starfull");
		$data["rating"]["count"] = $rating[2];
	
		// OUTCOME DATA
		
		// 6 MONTHS
	
		$data["outcomes_data"]["6_months"]["this_program"]["employment_rate"]["value"] = parse_rate_and_number($outcomes_data["6_months"][1])[0];
		$data["outcomes_data"]["6_months"]["this_program"]["employment_rate"]["num_students"] = parse_rate_and_number($outcomes_data["6_months"][1])[1];
		$data["outcomes_data"]["6_months"]["this_program"]["wage"]["quarterly"]["value"] = parse_rate_and_number($outcomes_data["6_months"][4])[0];
		$data["outcomes_data"]["6_months"]["this_program"]["wage"]["quarterly"]["num_students"] = parse_rate_and_number($outcomes_data["6_months"][4])[1];
		$data["outcomes_data"]["6_months"]["this_program"]["wage"]["yearly"]["value"] = parse_rate_and_number($outcomes_data["6_months"][7])[0];
		$data["outcomes_data"]["6_months"]["this_program"]["wage"]["yearly"]["num_students"] = parse_rate_and_number($outcomes_data["6_months"][7])[1];
		
		$data["outcomes_data"]["6_months"]["this_provider"]["employment_rate"]["value"] = parse_rate_and_number($outcomes_data["6_months"][3])[0];
		$data["outcomes_data"]["6_months"]["this_provider"]["employment_rate"]["num_students"] = parse_rate_and_number($outcomes_data["6_months"][3])[1];
		$data["outcomes_data"]["6_months"]["this_provider"]["wage"]["quarterly"]["value"] = parse_rate_and_number($outcomes_data["6_months"][6])[0];
		$data["outcomes_data"]["6_months"]["this_provider"]["wage"]["quarterly"]["num_students"] = parse_rate_and_number($outcomes_data["6_months"][6])[1];
		$data["outcomes_data"]["6_months"]["this_provider"]["wage"]["yearly"]["value"] = parse_rate_and_number($outcomes_data["6_months"][9])[0];
		$data["outcomes_data"]["6_months"]["this_provider"]["wage"]["yearly"]["num_students"] = parse_rate_and_number($outcomes_data["6_months"][9])[1];
		
		$data["outcomes_data"]["6_months"]["related_programs"]["employment_rate"]["value"] = parse_rate_and_number($outcomes_data["6_months"][2])[0];
		$data["outcomes_data"]["6_months"]["related_programs"]["employment_rate"]["num_students"] = parse_rate_and_number($outcomes_data["6_months"][2])[1];
		$data["outcomes_data"]["6_months"]["related_programs"]["wage"]["quarterly"]["value"] = parse_rate_and_number($outcomes_data["6_months"][5])[0];
		$data["outcomes_data"]["6_months"]["related_programs"]["wage"]["quarterly"]["num_students"] = parse_rate_and_number($outcomes_data["6_months"][5])[1];
		$data["outcomes_data"]["6_months"]["related_programs"]["wage"]["yearly"]["value"] = parse_rate_and_number($outcomes_data["6_months"][8])[0];
		$data["outcomes_data"]["6_months"]["related_programs"]["wage"]["yearly"]["num_students"] = parse_rate_and_number($outcomes_data["6_months"][8])[1];
		
		// 1 YEAR
		
		$data["outcomes_data"]["1_year"]["this_program"]["employment_rate"]["value"] = parse_rate_and_number($outcomes_data["1_year"][1])[0];
		$data["outcomes_data"]["1_year"]["this_program"]["employment_rate"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][1])[1];
		$data["outcomes_data"]["1_year"]["this_program"]["retention_rate"]["value"] = parse_rate_and_number($outcomes_data["1_year"][4])[0];
		$data["outcomes_data"]["1_year"]["this_program"]["retention_rate"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][4])[1];	
		$data["outcomes_data"]["1_year"]["this_program"]["wage"]["quarterly"]["value"] = parse_rate_and_number($outcomes_data["1_year"][7])[0];
		$data["outcomes_data"]["1_year"]["this_program"]["wage"]["quarterly"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][7])[1];
		$data["outcomes_data"]["1_year"]["this_program"]["wage"]["yearly"]["value"] = parse_rate_and_number($outcomes_data["1_year"][10])[0];
		$data["outcomes_data"]["1_year"]["this_program"]["wage"]["yearly"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][10])[1];
		
		$data["outcomes_data"]["1_year"]["this_provider"]["employment_rate"]["value"] = parse_rate_and_number($outcomes_data["1_year"][3])[0];
		$data["outcomes_data"]["1_year"]["this_provider"]["employment_rate"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][3])[1];
		$data["outcomes_data"]["1_year"]["this_provider"]["retention_rate"]["value"] = parse_rate_and_number($outcomes_data["1_year"][6])[0];
		$data["outcomes_data"]["1_year"]["this_provider"]["retention_rate"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][6])[1];
		$data["outcomes_data"]["1_year"]["this_provider"]["wage"]["quarterly"]["value"] = parse_rate_and_number($outcomes_data["1_year"][9])[0];
		$data["outcomes_data"]["1_year"]["this_provider"]["wage"]["quarterly"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][9])[1];
		$data["outcomes_data"]["1_year"]["this_provider"]["wage"]["yearly"]["value"] = parse_rate_and_number($outcomes_data["1_year"][12])[0];
		$data["outcomes_data"]["1_year"]["this_provider"]["wage"]["yearly"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][12])[1];
		
		$data["outcomes_data"]["1_year"]["related_programs"]["employment_rate"]["value"] = parse_rate_and_number($outcomes_data["1_year"][2])[0];
		$data["outcomes_data"]["1_year"]["related_programs"]["employment_rate"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][2])[1];
		$data["outcomes_data"]["1_year"]["related_programs"]["retention_rate"]["value"] = parse_rate_and_number($outcomes_data["1_year"][5])[0];
		$data["outcomes_data"]["1_year"]["related_programs"]["retention_rate"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][5])[1];
		$data["outcomes_data"]["1_year"]["related_programs"]["wage"]["quarterly"]["value"] = parse_rate_and_number($outcomes_data["1_year"][8])[0];
		$data["outcomes_data"]["1_year"]["related_programs"]["wage"]["quarterly"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][8])[1];
		$data["outcomes_data"]["1_year"]["related_programs"]["wage"]["yearly"]["value"] = parse_rate_and_number($outcomes_data["1_year"][11])[0];
		$data["outcomes_data"]["1_year"]["related_programs"]["wage"]["yearly"]["num_students"] = parse_rate_and_number($outcomes_data["1_year"][11])[1];
	
		// 2 YEARS
		
		$data["outcomes_data"]["2_years"]["this_program"]["employment_rate"]["value"] = parse_rate_and_number($outcomes_data["2_years"][1])[0];
		$data["outcomes_data"]["2_years"]["this_program"]["employment_rate"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][1])[1];
		$data["outcomes_data"]["2_years"]["this_program"]["retention_rate"]["value"] = parse_rate_and_number($outcomes_data["2_years"][4])[0];
		$data["outcomes_data"]["2_years"]["this_program"]["retention_rate"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][4])[1];	
		$data["outcomes_data"]["2_years"]["this_program"]["wage"]["quarterly"]["value"] = parse_rate_and_number($outcomes_data["2_years"][7])[0];
		$data["outcomes_data"]["2_years"]["this_program"]["wage"]["quarterly"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][7])[1];
		$data["outcomes_data"]["2_years"]["this_program"]["wage"]["yearly"]["value"] = parse_rate_and_number($outcomes_data["2_years"][10])[0];
		$data["outcomes_data"]["2_years"]["this_program"]["wage"]["yearly"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][10])[1];
		
		$data["outcomes_data"]["2_years"]["this_provider"]["employment_rate"]["value"] = parse_rate_and_number($outcomes_data["2_years"][3])[0];
		$data["outcomes_data"]["2_years"]["this_provider"]["employment_rate"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][3])[1];
		$data["outcomes_data"]["2_years"]["this_provider"]["retention_rate"]["value"] = parse_rate_and_number($outcomes_data["2_years"][6])[0];
		$data["outcomes_data"]["2_years"]["this_provider"]["retention_rate"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][6])[1];
		$data["outcomes_data"]["2_years"]["this_provider"]["wage"]["quarterly"]["value"] = parse_rate_and_number($outcomes_data["2_years"][9])[0];
		$data["outcomes_data"]["2_years"]["this_provider"]["wage"]["quarterly"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][9])[1];
		$data["outcomes_data"]["2_years"]["this_provider"]["wage"]["yearly"]["value"] = parse_rate_and_number($outcomes_data["2_years"][12])[0];
		$data["outcomes_data"]["2_years"]["this_provider"]["wage"]["yearly"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][12])[1];
		
		$data["outcomes_data"]["2_years"]["related_programs"]["employment_rate"]["value"] = parse_rate_and_number($outcomes_data["2_years"][2])[0];
		$data["outcomes_data"]["2_years"]["related_programs"]["employment_rate"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][2])[1];
		$data["outcomes_data"]["2_years"]["related_programs"]["retention_rate"]["value"] = parse_rate_and_number($outcomes_data["2_years"][5])[0];
		$data["outcomes_data"]["2_years"]["related_programs"]["retention_rate"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][5])[1];
		$data["outcomes_data"]["2_years"]["related_programs"]["wage"]["quarterly"]["value"] = parse_rate_and_number($outcomes_data["2_years"][8])[0];
		$data["outcomes_data"]["2_years"]["related_programs"]["wage"]["quarterly"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][8])[1];
		$data["outcomes_data"]["2_years"]["related_programs"]["wage"]["yearly"]["value"] = parse_rate_and_number($outcomes_data["2_years"][11])[0];
		$data["outcomes_data"]["2_years"]["related_programs"]["wage"]["yearly"]["num_students"] = parse_rate_and_number($outcomes_data["2_years"][11])[1];

	
		foreach($data as $data_item_key => $data_item_value) {
			$data[$data_item_key] = str_replace("&nbsp;", " ", $data_item_value);
			if(is_string($data_item_value)) {
				$data[$data_item_key] = trim($data_item_value);
			} else {
				foreach($data[$data_item_key] as $data_nested_key => $data_nested_value) {
					if(is_string($data_nested_value)) {
						$data[$data_item_key][$data_nested_key] = trim($data_nested_value);
					}
				}
			}
		}
	
		return $data;
	}
?>