<?php

$csv_delimiter = $_POST['csv_delimiter'];
$csv_delimiter =  ($csv_delimiter == '\t') ? "\t" : $csv_delimiter;
$newline   = "\n";   // need this be system dependent?

// output the column headers
$columns = array();
foreach ($template as $col)
	$columns[] = $col[0];
echo join("$csv_delimiter", $columns) . $newline;


for ($row=1; $row<=$g_numResults; $row++)
{ 
 	$row_vals = array();

  foreach ($template as $col)
  {
    // display the appropriate thing, based on the data type
    switch ($col[1])
    {
      case "Name":
        $row_vals[] = generate_name($col[2][0], $g_male_names, $g_female_names, $g_names, $g_surnames);
        break;

      case "Phone":
        $row_vals[] = generate_random_num_str($col[2][0]);
        break;

      case "Email":
        $row_vals[] = generate_email_address($g_words);
        break;
        
      case "Street-Address":
        $row_vals[] = generate_street_address($g_words);
        break;

      case "City":
        $row_vals[] = $g_cities[rand(0, count($g_cities)-1)];
        break;
				
      case "Postal-Zip":
				$wants_canada_postal = $col[2][0];
				$wants_nl_postcode   = $col[2][1];
				$wants_uk_postcode   = $col[2][2];
				$wants_us_zip        = $col[2][3];
				
        $options_to_generate = array();

				if ($wants_canada_postal)	$options_to_generate[] = "LXL XLx";
				if ($wants_nl_postcode)	  $options_to_generate[] = "xxxxLL";
				if ($wants_uk_postcode) {
				  $valid_uk_postcode_format = array("Lx xLL", "Lxx xLL", "LxL xLL", "LLx xLL", "LLxx xLL", "LLxL xLL"); 
				  $options_to_generate[] = $valid_uk_postcode_format[rand(0, count($valid_uk_postcode_format)-1)];
				}
				if ($wants_us_zip)	      $options_to_generate[] = "xxxxx";

				if (!empty($options_to_generate))
				{
				  $random_zip_format = $options_to_generate[rand(0, count($options_to_generate)-1)];
				  $row_vals[] = generate_random_alphanumeric_str($random_zip_format);
				}
        break;

      case "State-Province":
				$wants_canada_province = $col[2][0][0];
				$wants_nl_province     = $col[2][1][0];
				$wants_uk_county       = $col[2][2][0];
				$wants_us_state        = $col[2][3][0];

        $places = array();

        if ($wants_canada_province)
        {
          if ($col[2][0][1] && $col[2][0][2])
            $index = rand()&1;
          else if ($col[2][0][1])
            $index = 0; 
          else if ($col[2][0][2])
            $index = 1; 

          $places[] = $g_provinces[rand(0, count($g_provinces)-1)][$index];
        }

        if ($wants_nl_province)
        {
          if ($col[2][1][1] && $col[2][1][2])
            $index = rand()&1;
          else if ($col[2][1][1])
            $index = 0; 
          else if ($col[2][1][2])
            $index = 1; 

          $places[] = $g_provinces_nl[rand(0, count($g_provinces_nl)-1)][$index];
        }

        if ($wants_uk_county)
        {
          if ($col[2][2][1] && $col[2][2][2])
            $index = rand()&1;
          else if ($col[2][2][1])
            $index = 0;
          else if ($col[2][2][2])
            $index = 1;

          $places[] = $g_counties[rand(0, count($g_counties)-1)][$index];
        }

        if ($wants_us_state)
        {
          if ($col[2][3][1] && $col[2][3][2])
            $index = rand()&1;
          else if ($col[2][3][1])
            $index = 0; 
          else if ($col[2][3][2])
            $index = 1; 

          $places[] = $g_states[rand(0, count($g_states)-1)][$index];
        }

        $row_vals[] = $places[rand(0, count($places)-1)];
        break;
        
      case "Country":
        $row_vals[] = $g_countries[rand(0, count($g_countries)-1)];
        break;

      case "Date":
        // convert the From and To dates to datetimes
        list($month, $day, $year) = split("/", $col[2][1]);
        $from_date = mktime(0, 0, 0, $month, $day, $year);
        list($month, $day, $year) = split("/", $col[2][2]);
        $to_date = mktime(0, 0, 0, $month, $day, $year);

        // randomly pick a date between those dates
        $rand_date = mt_rand($from_date, $to_date);
        
        // display the new date in the value specified
        $row_vals[] = date($col[2][0], $rand_date);
        break;
        
      case "Text-Fixed":
        $row_vals[] = generate_random_text_str($g_words, false, "fixed", $col[2][0]);
        break;
        
      case "Text-Random":
        $row_vals[] = generate_random_text_str($g_words, $col[2][2], "range", $col[2][0], $col[2][1]);
        break;
        
      case "Auto-Increment":
        $start = $col[2][0];
        $increment = $col[2][1];
        $row_vals[] = ((($row-1) * $increment) + $start);
        break;
        
      case "List":
        $elements = explode("|", $col[2][2]);
        if ($col[2][0] == "Exactly")
          $row_vals[] = join(", ", return_random_subset($elements, $col[2][1]));
        else
        {
          // At MOST. So randomly calculate a number up to the num specified:
          $num_items = rand(0, $col[2][1]);
          $row_vals[] = join(", ", return_random_subset($elements, $num_items));
        }
        break;

      case "Number-Range":
        $row_vals[] = rand($col[2][0], $col[2][1]);
        break;

      case "Alphanumeric":
        $row_vals[] = generate_random_alphanumeric_str($col[2][0]);
        break;
    }
  }
  
  echo join("$csv_delimiter", $row_vals) . $newline;
}

?>

