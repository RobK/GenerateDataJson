<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<style type="text/css">
body { margin: 10px; }
body, table, th, td { font-family: arial; font-size: 8pt; }
#resultTable { background-color: #efefef; border: 2px solid #dddddd; width: 100%; }
#resultTable th { background-color: #efefef; }
#resultTable td { background-color: #ffffff; }
</style>

</head>
<body>

<?php

$database_type = $_POST["sql_database"];
$backquote  = isset($_POST['enclose_with_backquotes']) ? "`" : "";

// get the column names
$column_names_arr = array();
foreach ($template as $col)
  $column_names_arr[] = "{$backquote}{$col[0]}{$backquote}";
$col_names = join(",", $column_names_arr);

$table_name = $_POST['sql_table_name'];

$insert_qry_start = "INSERT INTO {$backquote}$table_name{$backquote} ($col_names) VALUES (";

// ------------------------------------------------------------------------------------------------

// if required, output the CREATE TABLE query
if (isset($_POST['sql_create_table']))
{
	if ($database_type == "MySQL")
	{
    $map = array(
         "Name" => "varchar(255)",
         "Phone" => "varchar(100)",
         "Email" => "varchar(255)",
         "Street-Address" => "varchar(255)",
         "Alphanumeric" => "varchar(255)",
         "List" => "varchar(255)",
         "Postal-Zip" => "varchar(10)",
         "Country" => "varchar(50)",
         "State-Province" => "varchar(50)",
         "City" => "varchar(50)",
         "Date" => "varchar(50)",
         "Number-Range" => "varchar(50)",
         "Text-Fixed" => "TEXT",
         "Text-Random" => "TEXT",
         "Auto-Increment" => "MEDIUMINT"
             );

		echo "CREATE TABLE $table_name (<br />
&nbsp;&nbsp;{$backquote}id{$backquote} mediumint(8) unsigned NOT NULL auto_increment,\n<br/>";

		foreach ($template as $col)
		  echo "&nbsp;&nbsp;{$backquote}{$col[0]}{$backquote} " . $map[$col[1]] . " default NULL,<br />";

		echo "&nbsp;&nbsp;PRIMARY KEY  (`id`)<br />
) TYPE=MyISAM AUTO_INCREMENT=1;
";
	}

	if ($database_type == "Oracle")
	{
    $map = array(
         "Name" => "varchar2(255)",
         "Phone" => "varchar2(100)",
         "Email" => "varchar2(255)",
         "Street-Address" => "varchar2(255)",
         "Alphanumeric" => "varchar2(255)",
         "List" => "varchar2(255)",
         "Postal-Zip" => "varchar2(10)",
         "Country" => "varchar2(50)",
         "State-Province" => "varchar2(50)",
         "City" => "varchar2(50)",
         "Date" => "varchar2(50)",
         "Number-Range" => "varchar2(50)",
         "Text-Fixed" => "BLOB",
         "Text-Random" => "BLOB",
         "Auto-Increment" => "number"
             );

		echo "CREATE TABLE $table_name (<br />
&nbsp;&nbsp;{$backquote}id{$backquote} number primary key,\n<br/>";

		foreach ($template as $col)
		  echo "&nbsp;&nbsp;{$backquote}{$col[0]}{$backquote} " . $map[$col[1]] . " default NULL,<br />";

		echo ");";
	}

  echo "<br /><br /><hr size='1' /><br />";
}

for ($row=1; $row<=$g_numResults; $row++)
{
  $elements = array();

  foreach ($template as $col)
  {
    // display the appropriate thing, based on the data type
    switch ($col[1])
    {
      case "Name":
        $elements[] = generate_name($col[2][0], $g_male_names, $g_female_names, $g_names, $g_surnames);
        break;

      case "Phone":
        $elements[] = generate_random_num_str($col[2][0]);
        break;

      case "Email":
        $elements[] = generate_email_address($g_words);
        break;

      case "Street-Address":
        $elements[] = generate_street_address($g_words);
        break;

      case "City":
        $elements[] = $g_cities[rand(0, count($g_cities)-1)];
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
				  $elements[] = generate_random_alphanumeric_str($random_zip_format);
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

        $elements[] = $places[rand(0, count($places)-1)];
        break;

      case "Country":
        $elements[] = $g_countries[rand(0, count($g_countries)-1)];
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
        $elements[] = date($col[2][0], $rand_date);
        break;

      case "Text-Fixed":
        $elements[] = generate_random_text_str($g_words, false, "fixed", $col[2][0]);
        break;

      case "Text-Random":
        $elements[] = generate_random_text_str($g_words, $col[2][2], "range", $col[2][0], $col[2][1]);
        break;

      case "Auto-Increment":
        $start = $col[2][0];
        $increment = $col[2][1];
        $elements[] = ((($row-1) * $increment) + $start);
        break;

      case "List":
        $all_elements = explode("|", $col[2][2]);
        if ($col[2][0] == "Exactly")
          $elements[] = join(", ", return_random_subset($all_elements, $col[2][1]));
        else
        {
          // At MOST. So randomly calculate a number up to the num specified:
          $num_items = rand(0, $col[2][1]);
          $elements[] = join(", ", return_random_subset($all_elements, $num_items));
        }
        break;

      case "Number-Range":
        $elements[] = rand($col[2][0], $col[2][1]);
        break;

      case "Alphanumeric":
        $elements[] = generate_random_alphanumeric_str($col[2][0]);
        break;
    }
  }

  array_walk($elements, "enquote");

  echo $insert_qry_start;
  echo join(",", $elements);
  echo ");<br />";
}

// helper function to wrap each element of an array with ' chars.
function enquote(&$item1, $key)
{
   $item1 = "'$item1'";
}


?>

</body>
</html>
