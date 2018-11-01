<?php 
  function filter_checkbox($column_name, $label) {
    if($_GET[$column_name] == "on"){
      $checked = " checked";
    }
    echo '
      <div class="checkbox-wrapper'.$checked.'">
        <input type="checkbox" name="'.$column_name.'"'.$checked.'><div class="checkbox"></div><label for="'.$column_name.'">'.$label.'</label>
      </div>';
  }

  filter_checkbox('attributes_in_demand', 'In-demand');
  filter_checkbox('features_child_care_offered_on_site', 'On-Site Childcare Available');
  filter_checkbox('features_distance_learning_services_provider', 'Distance Learning Available');
  filter_checkbox('features_spanish_spoken_by_staff', 'Spanish-Speaking Staff');
  filter_checkbox('features_other_languages_spoken_by_staff', 'Other Languages Spoken By Staff');
  filter_checkbox('features_wia_eligible', 'WIA Eligible');
  filter_checkbox('features_wheelchair_accessible', 'Wheelchair Accessible');
  filter_checkbox('features_career_counseling_available', 'Career Counseling Available');
?>

