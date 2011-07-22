<?php
session_start();
header("Cache-control: private");

$resultType = $_POST["resultType"];

// these have to be outputted FIRST to send the headers properly
if ($resultType == "Excel")
{
  header("Content-Type: application/vnd.ms-excel"); 
  header("Content-Disposition: attachment; filename=randomdata.xls");
  header("Cache-Control: public");
}

if ($resultType == "CSV")
{
  header("Content-Type: application/csv"); 
  header("Content-Disposition: attachment; filename=randomdata.csv");
  header("Cache-Control: public");
}

if ($resultType == "XML")
{
  header("Content-Type: application/xml; charset=ISO-8859-1");
  header("Last-Modified: ". gmdate("D, d M Y H:i:s") ." GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
}


require("global/library.php");

// if there's no incoming FORM values, just exit.
if (empty($_POST))
  exit;

  
// get our basic data
$link = db_connect();
$g_male_names   = get_firstnames("male");
$g_female_names = get_firstnames("female");
$g_names        = get_firstnames();
$g_words        = get_lipsum();
$g_surnames     = get_surnames();
$g_cities       = get_cities();
$g_states       = get_states();
$g_provinces    = get_provinces();
$g_provinces_nl = get_provinces_nl();
$g_countries    = get_countries();
$g_counties     = get_counties();
db_disconnect($link);

$g_resultType = $_POST['resultType'];
$g_numCols    = $_POST['numCols'];
$g_numResults = $_POST['numResults'];

$template = get_random_data_template($_POST, $g_numCols);

switch ($g_resultType)
{
  case "HTML":
    require("html.php");
    break;
  case "Excel":
    require("excel.php");
    break;
  case "XML":
    require("xml.php");
    break;
  case "CSV":
    require("csv.php");
    break;
  case "SQL":
    require("sql.php");
    break;    
}


// ------------------------------------------------------------------------------------------------
function get_random_data_template($hash, $numCols)
{
  // find out what the user wants to generate
  $info = array();
  for ($i=1; $i<=$numCols; $i++)
  {
    $title = $hash["title_$i"];
    $type  = $hash["type_$i"];

    if (empty($type)) continue;

    $options = array();
    switch ($type)
    {
      case "Name":
      case "Phone":
      case "Email":
      case "Alphanumeric":
        $options[] = $hash["option_$i"];
        break;

      case "List":
        $options[] = $hash["listType_$i"]; // Exactly or AtMost
        $options[] = ($hash["listType_$i"] == "Exactly") ? $hash["exactly_$i"] : $hash["atMost_$i"];
        $options[] = $hash["option_$i"];
        break;
  
      case "Postal-Zip":
        if (empty($hash["includeZipCanada_$i"]) && empty($hash['includeZipNetherlands_$i']) && 
				    empty($hash["includeZipUK_$i"]) && empty($hash['includeZipUS_$i']))
          continue;
				
        $options[] = (isset($hash["includeZipCanada_$i"]) && in_array("canada", $hash["countryChoice"])) ? true : false;
				$options[] = (isset($hash["includeZipNetherlands_$i"]) && in_array("netherlands", $hash["countryChoice"])) ? true : false;
        $options[] = (isset($hash["includeZipUK_$i"]) && in_array("uk", $hash["countryChoice"])) ? true : false;
        $options[] = (isset($hash["includeZipUS_$i"]) && in_array("us", $hash["countryChoice"])) ? true : false;
        break;

      case "State-Province":

        // CANADA PROVINCE
        $includes_canada = (isset($hash["includeRegionCanada_$i"]) && in_array("canada", $hash["countryChoice"])) ? true : false;
        $province_full = "";
        $province_short = "";      
        if ($includes_canada)
        {
          $province_full  = (isset($hash["includeRegionCanadaFull_$i"])) ? true : false;        
          $province_short = (isset($hash["includeRegionCanadaShort_$i"])) ? true : false;        
        }
        $options[] = array($includes_canada, $province_full, $province_short);

        // NETHERLANDS PROVINCE 
        $includes_nl = (isset($hash["includeRegionNetherlands_$i"]) && in_array("netherlands", $hash["countryChoice"])) ? true : false;
        $province_nl_full = "";
        $province_nl_short = "";      
        if ($includes_nl)
        {
          $province_nl_full  = (isset($hash["includeRegionNetherlandsFull_$i"])) ? true : false;        
          $province_nl_short = (isset($hash["includeRegionNetherlandsShort_$i"])) ? true : false;        
        }
        $options[] = array($includes_nl, $province_nl_full, $province_nl_short);

        // UK COUNTY
        $includes_uk = (isset($hash["includeRegionUK_$i"]) && in_array("uk", $hash["countryChoice"])) ? true : false;
        $county_full = "";
        $county_short = "";
        if ($includes_uk)
        {
          $county_full   = (isset($hash["includeRegionUKFull_$i"])) ? true : false;        
          $county_abbrev = (isset($hash["includeRegionUKShort_$i"])) ? true : false;        
        }
        $options[] = array($includes_uk, $county_full, $county_abbrev);
	
        // US STATE
        $includes_us = (isset($hash["includeRegionUS_$i"]) && in_array("us", $hash["countryChoice"])) ? true : false;
        $state_full = "";
        $state_short = "";
        if ($includes_us)
        {
          $state_full  = (isset($hash["includeRegionUSFull_$i"])) ? true : false;        
          $state_short = (isset($hash["includeRegionUSShort_$i"])) ? true : false;        
        }
        $options[] = array($includes_us, $state_full, $state_short);

        if ($options[0][0] == false && $options[1][0] == false && $options[2][0] == false) continue;
        break;

      case "Text-Fixed":
        if (empty($hash["numWords_$i"])) continue;
        $options[] = $hash["numWords_$i"]; 
        break;
  
      case "Text-Random":
        if (empty($hash["numWordsMin_$i"]) || empty($hash["numWordsMin_$i"])) continue;
        $options[] = $hash["numWordsMin_$i"]; 
        $options[] = $hash["numWordsMax_$i"]; 
        $options[] = (isset($hash["startsWithLipsum_$i"])) ? true : false;
        break;
  
      case "Number-Range":
        if ((empty($hash["numRangeMin_$i"]) && $hash["numRangeMin_$i"] !== "0") || (empty($hash["numRangeMax_$i"]) && $hash["numRangeMax_$i"] !== "0")) 
          continue;
        if (!is_numeric($hash["numRangeMin_$i"]) || !is_numeric($hash["numRangeMax_$i"])) continue;
        $options[] = $hash["numRangeMin_$i"]; 
        $options[] = $hash["numRangeMax_$i"]; 
        break;    
  
      case "Date":
        if (empty($hash["fromDate_$i"]) || empty($hash["toDate_$i"]) || empty($hash["option_$i"])) continue;
        $options[] = $hash["option_$i"]; 
        $options[] = $hash["fromDate_$i"]; 
        $options[] = $hash["toDate_$i"]; 
        break;

      case "Auto-Increment":
        if (!isset($hash["autoIncrementStart_$i"]) || empty($hash["autoIncrementValue_$i"])) continue;
        $options[] = $hash["autoIncrementStart_$i"]; 
        $options[] = $hash["autoIncrementValue_$i"]; 
        break;
    }

    $info[] = array($title, $type, $options);
  }
  
  return $info;
}

?>
