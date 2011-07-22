<?php
session_start();
header("Cache-control: private");

require_once("global/library.php");

$g_default_save_form_empty_str = "Enter form name here.";
$_SESSION["account_id"] = 1;
$forms = get_forms($_SESSION["account_id"]);

$next_year = mktime(0, 0, 0, date("m"),  date("d"),  date("Y")+1);
$next_year = date("m/d/Y", $next_year);

$last_year = mktime(0, 0, 0, date("m"),  date("d"),  date("Y")-1);
$last_year = date("m/d/Y", $last_year);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <title><?php echo $g_title?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="description" content="GenerateData.com: free, GNU-licensed, random custom data generator for testing software" />
  <meta name="keywords" content="Random Data, Test Data, Sample Data, data generator, generate, data, Ben Keen, Benjamin Keen" />

  <script type="text/javascript">
  var g_logged_in = true;
  var g_default_save_form_empty_str = "<?=$g_default_save_form_empty_str;?>";
  </script>

  <link rel="stylesheet" type="text/css" href="global/css/main.css">
  <script type="text/javascript" src="global/scripts/library-compressed.js"></script>
  <script type="text/javascript" src="global/scriptaculous/lib/prototype-compressed.js"></script>
  <script type="text/javascript" src="global/scriptaculous/src/scriptaculous.js"></script>

	<script type="text/javascript">
	var cal = new CalendarPopup();
	</script>

</head>
<body onload="onPageLoad()">

<div id="site_header"> </div>

<table width="930" cellpadding="0" cellspacing="0" align="center" border="0" summary="Main content table">
<tr>
  <td id="canvas" height="200" colspan="12">

    <div id="main_area">

      <div id="wrapper">

        <div id="content">

<div id='page_loading_icon' style='position:absolute;display:none;right:0px; top:-20px;'><img src='images/loading2.gif' width='16' height='16' /></div>

  <div style="margin-top: 10px;">

	<div id="control_panel_box" style="float: right; width: 320px;" class="box"><span><span><span><span><span><span><span><span>
		<div id="control_panel">
  		<table cellpadding="1" style="height:40px;">
  		<tr>
  			<td><input type="text" name="save_form_name" id="save_form_name" value="<?=$g_default_save_form_empty_str;?>" onfocus="save_form_field_onfocus(this)" onblur="save_form_field_onblur(this)" style="width: 200px; color: #cccccc;" maxlength="35" /></td>
  			<td colspan="2"><input type="image" onclick="save_form()" src="images/save.jpg" style="border: 1px solid #cccccc;" /></td>
  		</tr>
  		<tr>
  			<td>
  				<select name="form_list" id="form_list" style="width:200px;">
  					<option value="">Please Select</option>
            <?php
            for ($i=0; $i<count($forms); $i++)
            {
              $form_id   = $forms[$i][0];
              $form_name = $forms[$i][1];
              echo "<option value=\"$form_id\">$form_name</option>\n";
            }
            ?>
  				</select>
  			</td>
  			<td><input type="image" onclick="load_form()" src="images/load.jpg" style="border: 1px solid #cccccc;" /></td>
  			<td><input type="image" onclick="delete_form()" src="images/delete.jpg" style="border: 1px solid #cccccc;" /></td>
  		</tr>
  		</table>
		</div>

		<div id="control_panel_message" style="display: none; height: 38px; padding: 5px;"></div>

		</span></span></span></span></span></span></span></span>
	</div>

	<form action="process.php" method="post" name="data" target="hiddenIframe" onsubmit="return submitForm()">
		<input type="hidden" name="numCols" id="numCols" value="" />

		<table cellspacing="2" cellpadding="0">
		<tr>
			<td class="setting_label" width="140">Result type:</td>
			<td rowspan="3" style="border-left: 2px dotted #cccccc;">&nbsp;&nbsp;</td>
			<td>
				<input type="radio" name="resultType" value="HTML" id="HTML" onclick="changeResultType(this.value)" checked /> <label for="HTML">HTML</label>&nbsp;
				<input type="radio" name="resultType" value="Excel" id="Excel" onclick="changeResultType(this.value)" /> <label for="Excel">Excel</label>&nbsp;
				<input type="radio" name="resultType" value="XML" id="XML" onclick="changeResultType(this.value)" /> <label for="XML">XML</label>&nbsp;
				<input type="radio" name="resultType" value="CSV" id="CSV" onclick="changeResultType(this.value)" /> <label for="CSV">CSV</label>&nbsp;
				<input type="radio" name="resultType" value="SQL" id="SQL" onclick="changeResultType(this.value)" /> <label for="SQL">SQL</label>
			</td>
		</tr>
		<tr>
			<td class="setting_label">Country-specific data:</td>
			<td>
				<input type="checkbox" name="countryChoice[]" value="canada" id="canada" onclick="updateCountryChoice()" checked /> <label for="canada">Canada</label>&nbsp;
				<input type="checkbox" name="countryChoice[]" value="netherlands" id="netherlands" onclick="updateCountryChoice()" /> <label for="netherlands">Netherlands</label>&nbsp;
				<input type="checkbox" name="countryChoice[]" value="uk" id="uk" onclick="updateCountryChoice()" /> <label for="uk">UK</label>&nbsp;
				<input type="checkbox" name="countryChoice[]" value="us" id="us" onclick="updateCountryChoice()" checked /> <label for="us">US</label>
			</td>
		</tr>
		<tr>
			<td class="setting_label">Number of results:</td>
			<td><input type="text" style="width:45px;" name="numResults" id="numResults" value="100" /></td>
		</tr>
		</table>

		<br />

		<div id="fullTable">
		<table class="dataTable" id="dataTable" cellpadding="0" cellspacing="1" width="870">
			<tbody>
				<tr height="20">
					<th width="30">&nbsp;Order&nbsp;</th>
					<th width="120"><span id="custom_col_name">Column Title</span></th>
					<th width="180">Data Type</th>
					<th width="230">Examples</th>
					<th width="270">Options</th>
					<th width="30">&nbsp;Help&nbsp;</th>
				</tr>
			</tbody>
		</table></div>

		<p>
      <div style="float: right"><input type="button" value="Empty Form" onclick="empty_form(true, '5'); return false;" /></div>
			Add <input type="text" name="numRows" value="1" size="2" />
			<input type="button" value="Row(s)" onclick="addRows(document.data.numRows.value)" />
		</p>

		<div id="sql_options" style="width:300px">
			<div class="heading_1">SQL Options</div>
			<br />

			<table cellpadding="0" cellspacing="1">
			<tr>
				<td width="130">Database</td>
				<td>
					<select name="sql_database" id="sql_database" onchange="selectDatabaseType(this.value)">
						<option value="MySQL">MySQL</option>
						<option value="Oracle">Oracle</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Database table name</td>
				<td><input type="text" size="10" name="sql_table_name" value="myTable" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="checkbox" name="sql_create_table" id="sql_create_table" checked />
					<label for="sql_create_table">Include CREATE TABLE query</label>
				</td>
			</tr>
      <tr>
				<td colspan="2">
					<input type="checkbox" name="enclose_with_backquotes" id="enclose_with_backquotes" checked />
					<label for="enclose_with_backquotes">Enclose table and field names with backquotes</label>
				</td>
			</tr>
			</table>
		</div>

		<div id="xml_options">
			<div class="heading_1">XML Options</div>
			<br />

			<table cellpadding="0" cellspacing="1">
			<tr>
				<td width="110">Root node name</td>
				<td><input type="text" size="10" name="xml_root_node_name" value="records" /></td>
			</tr>
			<tr>
				<td>Record node name</td>
				<td><input type="text" size="10" name="xml_record_node_name" value="record" /></td>
			</tr>
			</table>
		</div>

		<div id="csv_options">
			<div class="heading_1">CSV Options</div>
			<br />

			<table cellpadding="0" cellspacing="1">
			<tr>
				<td width="140">Delimiter Character(s)</td>
				<td><input type="text" size="2" name="csv_delimiter" value="|" /></td>
			</tr>
			</table>

		</div>

		<p>
			<input type="image" name="" src="images/generate.jpg" />
		</p>

	</form>

<!-- ------------------------------ HTML Code Blocks ------------------------------------------- -->

<div class="HTML_Block" id="HTML_Block_Name">
	<select name="datatype_$ROW$" id="datatype_$ROW$" onchange="document.data.option_$ROW$.value = this.value">
		<option value="">Please Select</option>
		<option value="MaleName">John (Male Name)</option>
		<option value="FemaleName">Jane (Female Name)</option>
		<option value="Name">First name - any gender</option>
		<option value="MaleName Surname">John Roberts</option>
		<option value="FemaleName Surname">Jill Jenkins</option>
		<option value="Name Initial. Surname">Bob H. Thomas</option>
		<option value="Surname, Name Initial.">Smith, John P.</option>
		<option value="Name, Name, Name, Name">Jenny, Toby, Ben, Peter</option>
	</select>
</div>

<div class="HTML_Block" id="HTML_Block_Phone">
	<select name="datatype_$ROW$" id="datatype_$ROW$" onchange="document.data.option_$ROW$.value = this.value">
		<option value="">Please Select</option>
		<option value="1-Xxx-Xxx-xxxx">Canada (1)</option>
		<option value="(Xxx) Xxx-xxxx">Canada (2)</option>
		<option value="1 Xx Xxx Xxxx-xxxx">UK</option>
	</select>
</div>

<div class="HTML_Block" id="HTML_Block_Alphanumeric">
	<select name="datatype_$ROW$" id="datatype_$ROW$" onchange="document.data.option_$ROW$.value = this.value">
		<option value="">Please Select</option>
		<option value="LxL xLx">V6M 4C1 (Can. Postal code)</option>
		<option value="xxxxx">90210 (US Zip code)</option>
		<option value="LLLxxLLLxLL">eZg29gdF5K1 (Password)</option>
	</select>
</div>

<div class="HTML_Block" id="HTML_Block_Text-Fixed">
&nbsp;Generate #<input type="text" name="numWords_$ROW$" id="numWords_$ROW$" style="width: 30px" value="10" /> words
</div>

<div class="HTML_Block" id="HTML_Block_CustomListExamples">
	<select name="datatype_$ROW$" id="datatype_$ROW$" onchange="document.data.option_$ROW$.value = this.value">
		<option value="">Please Select</option>
		<option value="1|3|5|7|9|11|13|15|17|19|21|23|25|27|29|31|33|35|37|39|41|43|45|47|49">Odd Nums under 50</option>
		<option value="2|4|6|8|10|12|14|16|18|20|22|24|26|28|30|32|34|36|38|40|42|44|46|48|50">Even Nums under 50</option>
		<option value="1|2|3|4|5|6|7|8|9|10">1-10</option>
		<option value="one|two|three|four|five|six|seven|eight|nine|ten">One-Ten</option>
		<option value="1|2|3|5|7|11|13|17|19|23|29|31|37|41|43|47|53|59|61|67|71|73|79|83|89|97">Prime Nums under 100</option>
		<option value="red|orange|yellow|green|blue|indigo|violet">Colours</option>
		<option value="Single|Married|Divorced|Common-Law">Marital Status</option>
		<option value="Dr.|Mr.|Mrs.|Ms.|">Title</option>
		<option value="Accounting|Advertising|Asset Management|Customer Relations|Customer Service|Finances|Human Resources|Legal Department|Media Relations|Payroll|Public Relations|Quality Assurance|Sales and Marketing|Research and Development|Tech Support">Department Names</option>
		<option value="Microsoft|Macromedia|Google|Yahoo|Lycos|Altavista|Chami|Adobe|Borland|Lavasoft|Cakewalk|Sibelius|Finale|Apple Systems">Company Names</option>
	</select>
<br />
&nbsp;Enter values separated by |
</div>

<div class="HTML_Block" id="HTML_Block_Postal">
	<div class="country_canada">
		<input type="checkbox" name="includeZipCanada_$ROW$" id="includeZipCanada_$ROW$" checked />
		<label for="includeZipCanada_$ROW$">Postal codes (Canada)</label>
	</div>
	<div class="country_netherlands">
		<input type="checkbox" name="includeZipNetherlands_$ROW$" id="includeZipNetherlands_$ROW$" checked />
		<label for="includeZipNetherlands_$ROW$">Postcodes (Netherlands)</label>
	</div>
	<div class="country_uk">
		<input type="checkbox" name="includeZipUK_$ROW$" id="includeZipUK_$ROW$" checked />
		<label for="includeZipUK_$ROW$">Postcodes (UK)</label>
	</div>
	<div class="country_us">
		<input type="checkbox" name="includeZipUS_$ROW$" id="includeZipUS_$ROW$" checked />
		<label for="includeZipUS_$ROW$">Zip codes (US)</label>
	</div>
</div>

<div class="HTML_Block" id="HTML_Block_State">

	<div class="country_canada" style="position: relative;">
		<input type="checkbox" name="includeRegionCanada_$ROW$" id="includeRegionCanada_$ROW$"
			onclick="hideShowStateProvCounty($ROW$, this.checked, 'Canada')" checked /><label for="includeRegionCanada_$ROW$">Canadian Prov.</label>
		<span style="position: absolute; left: 125px; border-left: 1px solid #666666;"><input type="checkbox"
			name="includeRegionCanadaFull_$ROW$" id="includeRegionCanadaFull_$ROW$" checked /><label for="includeRegionCanadaFull_$ROW$"
			id="includeRegionCanadaFullLabel_$ROW$" class="suboptionActive">Full</label></span>
		<span style="position: absolute; left: 175px;"><input type="checkbox" name="includeRegionCanadaShort_$ROW$"
			id="includeRegionCanadaShort_$ROW$" checked /><label for="includeRegionCanadaShort_$ROW$" id="includeRegionCanadaShortLabel_$ROW$"
			class="suboptionActive">Short</label></span>
	</div>

	<div class="country_netherlands" style="position: relative;">
		<input type="checkbox" name="includeRegionNetherlands_$ROW$" id="includeRegionNetherlands_$ROW$"
			onclick="hideShowStateProvCounty($ROW$, this.checked, 'Netherlands')" checked /><label for="includeRegionNetherlands_$ROW$">Netherlands Prov.</label>
		<span style="position: absolute; left: 125px; border-left: 1px solid #666666;"><input type="checkbox"
			name="includeRegionNetherlandsFull_$ROW$" id="includeRegionNetherlandsFull_$ROW$" checked /><label for="includeRegionNetherlandsFull_$ROW$"
			id="includeRegionNetherlandsFullLabel_$ROW$" class="suboptionActive">Full</label></span>
		<span style="position: absolute; left: 175px;"><input type="checkbox" name="includeRegionNetherlandsShort_$ROW$"
			id="includeRegionNetherlandsShort_$ROW$" checked /><label for="includeRegionNetherlandsShort_$ROW$" id="includeRegionNetherlandsShortLabel_$ROW$"
			class="suboptionActive">Short</label></span>
	</div>

	<div class="country_uk" style="position: relative;">
		<input type="checkbox" name="includeRegionUK_$ROW$" id="includeRegionUK_$ROW$"
			onclick="hideShowStateProvCounty($ROW$, this.checked, 'UK')" checked /><label for="includeRegionUK_$ROW$">UK Counties</label>
		<span style="position: absolute; left: 125px; border-left: 1px solid #666666;"><input type="checkbox"
			name="includeRegionUKFull_$ROW$" id="includeRegionUKFull_$ROW$" checked /><label for="includeRegionUKFull_$ROW$"
			id="includeRegionUKFullLabel_$ROW$" class="suboptionActive">Full</label></span>
		<span style='position: absolute; left: 175px;'><input type="checkbox" name="includeRegionUKShort_$ROW$"
			id="includeRegionUKShort_$ROW$" checked /><label for="includeRegionUKShort_$ROW$" id="includeRegionUKShortLabel_$ROW$"
			class="suboptionActive">Short</label></span>
	</div>

	<div class="country_us" style="position: relative;">
		<input type="checkbox" name="includeRegionUS_$ROW$" id="includeRegionUS_$ROW$"
			onclick="hideShowStateProvCounty($ROW$, this.checked, 'US')" checked /><label for="includeRegionUS_$ROW$">US States</label>
		<span style="position: absolute; left: 125px; border-left: 1px solid #666666;"><input type="checkbox"
			name="includeRegionUSFull_$ROW$" id="includeRegionUSFull_$ROW$" checked /><label for="includeRegionUSFull_$ROW$"
			id="includeRegionUSFullLabel_$ROW$" class="suboptionActive">Full</label></span>
		<span style='position: absolute; left: 175px;'><input type="checkbox" name="includeRegionUSShort_$ROW$"
			id="includeRegionUSShort_$ROW$" checked /><label for="includeRegionUSShort_$ROW$" id="includeRegionUSShortLabel_$ROW$"
			class="suboptionActive">Short</label></span>
	</div>

</div>

<div class="HTML_Block" id="HTML_Block_Text-Random">
<table cellpadding="0" cellspacing="1">
<tr>
	<td>
		<input type="checkbox" name="startsWithLipsum_$ROW$" id="startsWithLipsum_$ROW$" />
		<label for="startsWithLipsum_$ROW$">Start with "Lorem Ipsum..."</label>
	</td>
</tr>
<tr>
	<td>
		Generate #<input type="text" name="numWordsMin_$ROW$" id="numWordsMin_$ROW$" style="width: 40px" value="1" />
		to #<input type="text" name="numWordsMax_$ROW$" id="numWordsMax_$ROW$" style="width: 40px" value="10" /> words
	</td>
</tr>
</table>
</div>

<div class="HTML_Block" id="HTML_Block_List">
<table cellpadding="0" cellspacing="1">
<tr>
	<td>
		<input type="radio" name="listType_$ROW$" id="listType1_$ROW$" value="Exactly" checked />
		<label for="listType1_$ROW$">Exactly</label>
		<input type="text" size="2" name="exactly_$ROW$" id="exactly_$ROW$" value="1" />&nbsp;&nbsp;
		<input type="radio" name="listType_$ROW$" id="listType2_$ROW$" value="AtMost" />
		<label for="listType2_$ROW$">At Most</label>
		<input type="text" size="2" name="atMost_$ROW$" id="atMost_$ROW$" value="1" />
	</td>
</tr>
<tr>
	<td>
		<input type="text" name="option_$ROW$" id="option_$ROW$" style="width: 230px;" />
	</td>
</tr>
</table>
</div>

<div class="HTML_Block" id="HTML_Block_Number-Range">
&nbsp;Between <input type="text" name="numRangeMin_$ROW$" id="numRangeMin_$ROW$" style="width: 30px" value="1" />
and <input type="text" name="numRangeMax_$ROW$" id="numRangeMax_$ROW$" style="width: 30px" value="10" />
</div>

<div class="HTML_Block" id="HTML_Block_Date">
	<select onchange="document.data.option_$ROW$.value = this.value">
		<option value="">Please Select</option>
		<option value="M j, Y">Jan 1, 2006</option>
		<option value="F jS, Y">January 1st, 2006</option>
		<option value="D, M d">Mon, Jan 01</option>
		<option value="D, jS, Y">Mon, Jan 1st, 2006</option>
		<option value="m.d.y">03.25.06</option>
		<option value="m-d-y">03-25-06</option>
		<option value="m/d/y">03/25/06</option>
		<option value="m/d/Y">03/25/2006</option>
		<option value="d.m.y">25.03.06</option>
		<option value="d-m-y">25-03-06</option>
		<option value="d/m/y">25/03/06</option>
		<option value="d/m/Y">25/03/2006</option>
		<option value="Y-m-d H:i:s">MySQL datetime</option>
		<option value="U">UNIX timestamp</option>
		<option value="c">ISO 8601 date</option>
		<option value="r">RFC 2822 formatted date</option>
		<option value="T">A timezone</option>
	</select>
</div>

<div class="HTML_Block" id="HTML_Block_DateOptions">
	<table cellpadding="0" cellspacing="0">
	<tr>
		<td width="35">&nbsp;From:</td>
		<td width="62"><input type="text" name="fromDate_$ROW$" id="fromDate_$ROW$" size="10" value="<?=$last_year?>" /></td>
		<td width="17" align="left"><a href="#" onclick="cal.select(document.data.fromDate_$ROW$,'anchorFrom_$ROW$','MM/dd/yyyy'); return false;" name="anchorFrom_$ROW$" id="anchorFrom_$ROW$"><img src="images/calendar_icon.gif" border="0" /></a></td>
		<td width="25" align="center">To:</td>
		<td width="62"><input type="text" name="toDate_$ROW$" id="toDate_$ROW$" size="10" value="<?=$next_year?>" /></td>
		<td width="17"><a href="#" onclick="cal.select(document.data.toDate_$ROW$,'anchorTo_$ROW$','MM/dd/yyyy'); return false;" name="anchorTo_$ROW$" id="anchorTo_$ROW$"><img src="images/calendar_icon.gif" border="0" /></a></td>
	</tr>
	<tr>
		<td colspan="6" nowrap>&nbsp;Format code:&nbsp;<input type="text" name="option_$ROW$" id="option_$ROW$" style="width: 160px;" /></td>
	</tr>
	</table>
</div>

<div class="HTML_Block" id="HTML_Block_Auto-Increment-Example">
  <select name="datatype_$ROW$" id="datatype_$ROW$" onchange="var parts = this.value.split(','); document.data.autoIncrementStart_$ROW$.value = parts[0]; document.data.autoIncrementValue_$ROW$.value = parts[1];">
    <option value="1,1">1, 2, 3, 4, 5, 6...</option>
    <option value="100,1">100, 101, 102, 103, 104...</option>
    <option value="0,2">0, 2, 4, 6, 8, 10...</option>
    <option value="0,5">0, 5, 10, 15, 20, 25...</option>
    <option value="1000,-1">1000, 999, 998, 997...</option>
    <option value="0,-1">0, -1, -2, -3, -4...</option>
    <option value="0,0.5">0, 0.5, 1, 1.5, 2...</option>
  </select>
</div>

<div class="HTML_Block" id="HTML_Block_Auto-Increment">
&nbsp;Start at: <input type="text" name="autoIncrementStart_$ROW$" id="autoIncrementStart_$ROW$" style="width: 40px" value="1" />&nbsp;
&nbsp;Increment: <input type="text" name="autoIncrementValue_$ROW$" id="autoIncrementValue_$ROW$" style="width: 40px" value="1" />
</div>


<!-- ------------------------------ help post its ---------------------------------------------- -->

<div id="postit1" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Name</div>

	<p>
		This data type randomly generates human names (mostly Western) according to
		the format you specify. The following strings will be converted to their
		random name equivalent:
	</p>
	<table cellpadding="0" cellspacing="1">
	<tr>
		<td class="heading_1" width="100">Name</td>
		<td>A first name, male or female.</td>
	</tr>
	<tr>
		<td class="heading_1">MaleName</td>
		<td>A male first name.</td>
	</tr>
	<tr>
		<td class="heading_1">FemaleName</td>
		<td>A female first name.</td>
	</tr>
	<tr>
		<td class="heading_1">Initial</td>
		<td>An upper-case letter, A-Z.</td>
	</tr>
	<tr>
		<td class="heading_1">Surname</td>
		<td>A random surname.</td>
	</tr>
	</table>
</div>

<div id="postit2" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Phone/Fax Numbers</div>

	<p>
		Whatever text you enter into the options text field will be used to generate telephone numbers.
		Capital <b>X</b>'s will be converted to a random number between 1 and 9; lower-case <b>x</b>'s
		will be converted to a random number between 0 and 9.
	</p>

	<p>
		Select one of the values in the example dropdown for some ideas. Remember: anything other than
		the <b>X</b> and <b>x</b> character are left unconverted.
	</p>
</div>

<div id="postit3" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Text: Fixed Number of Words</div>

	<p>
		This option generates a fixed number of random words, pulled the standard lorem ipsum
		latin text. The Starts with "Lorem Lipsum..." checkbox makes the text begin with lorem ipsum.
		If it is checked, every result will contain the same words.
	</p>
</div>

<div id="postit4" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Text: Random Number of Words</div>
	<p>
		This option generates a number of random words - the total number with the
		range that you specified (inclusive). As with the Fixed number option, the words
		are pulled the standard lorem ipsum	latin text.
	</p>
</div>


<div id="postit6" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Custom List</div>
	<p>
		Enter a list of items, separated by a pipe | character. Then select whether you want <b>Exactly</b>
		X number of items, or <b>At most</b> X items from the list. Multiple items are returned
		in a comma-delimited list in the results.
	</p>
</div>

<div id="postit7" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Number Range</div>
	<p>
		This randomly generates a number between the values you specify. Both fields
		allow you to enter negative numbers.
	</p>
</div>

<div id="postit8" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Alphanumeric</div>

	<p>
		This data type lets you generate random alpha-numeric strings. The following
		table contains the character legend for this field.
	</p>

	<table cellpadding="0" cellspacing="1" width="100%">
	<tr>
		<td class="heading_1" width="20">L</td>
		<td width="180">An uppercase <b>L</b>etter.</td>
		<td class="heading_1">V</td>
		<td>An uppercase <b>V</b>owel.</td>
	</tr>
	<tr>
		<td class="heading_1">l</td>
		<td>A lowercase <b>l</b>etter.</td>
		<td class="heading_1">v</td>
		<td>A lowercase <b>v</b>owel.</td>
	</tr>
	<tr>
		<td class="heading_1">D</td>
		<td>A letter (upper or lower).</td>
		<td class="heading_1">F</td>
		<td>A vowel (upper or lower).</td>
	</tr>
	<tr>
		<td class="heading_1">C</td>
		<td>An uppercase <b>C</b>onsonant.</td>
		<td class="heading_1">x</td>
		<td>Any number, 0-9.</td>
	</tr>
	<tr>
		<td class="heading_1">c</td>
		<td>A lowercase <b>c</b>onsonant.</td>
		<td class="heading_1">X</td>
		<td>Any number, 1-9.</td>
	</tr>
	<tr>
		<td class="heading_1">E</td>
		<td rowspan="3">A consonant (upper or lower).</td>
	</tr>
	</table>

</div>

<div id="postit9" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Postal / Zip Code</div>
	<p>
		Generates a random America or Canadian zip code. For greater control, use
		the alpha-numeric data type option.
	</p>
</div>

<div id="postit10" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">State / Province / County</div>
	<p>
		Generates random Canadian provinces, US states and UK counties, based on the options you
		select. The <b>Full Name</b> and <b>Abbreviation</b> sub-options determine whether the output will
		contain the full string (e.g. "British Columbia") or its abbreviation (e.g. "BC"). For
		UK counties, the abbreviation is the standard 3-character Chapman code.
	</p>
</div>

<div id="postit11" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Date</div>
	<p>
		This data type randomly generates a date between the dates specified,
		and allows for unique formatting of the result. See the table below for a
		list of viable formatting rules (these are cribbed from the standard PHP
		date() formatting options). Take a look at the example dropdown to select
		from existing formatting options.
	</p>

	<table cellpadding="0" cellspacing="1">
	<tr>
		<td class="heading_1" width="50">Char</td>
		<td class="heading_1">Description</td>
		<td class="heading_1">Example</td>
	</tr>
	<tr>
		<td class="heading_3" colspan="3">Day</td>
	</tr>
	<tr>
		<td class="heading_1">d</td>
		<td>Day of the month, 2 digits with leading zeros</td>
		<td>01 to 31</td>
	</tr>
	<tr>
		<td class="heading_1">D</td>
		<td>A textual representation of a day, three letters</td>
		<td>Mon through Sun</td>
	</tr>
	<tr>
		<td class="heading_1">j</td>
		<td>Day of the month without leading zeros</td>
		<td>1 to 31</td>
	</tr>
	<tr>
		<td class="heading_1">l</td>
		<td>A full textual representation of the day of the week</td>
		<td>Sunday through Saturday</td>
	</tr>
	<tr>
		<td class="heading_1">S</td>
		<td>English ordinal suffix for the day of the month, 2 characters	st, nd, rd or th. Works well with j</td>
		<td>Sunday through Saturday</td>
	</tr>
	<tr>
		<td class="heading_1">w</td>
		<td>Numeric representation of the day of the week</td>
		<td>0 (for Sunday) through 6 (for Saturday)</td>
	</tr>
	<tr>
		<td class="heading_1">z</td>
		<td>The day of the year (starting from 0)</td>
		<td>0 through 365</td>
	</tr>
	<tr>
		<td class="heading_3" colspan="3">Week</td>
	</tr>
	<tr>
		<td class="heading_1">W</td>
		<td>ISO-8601 week number of year, weeks starting on Monday</td>
		<td>42 (the 42nd week in the year)</td>
	</tr>
	<tr>
		<td class="heading_3" colspan="3">Month</td>
  </tr>
	<tr>
		<td class="heading_1">F</td>
		<td>A full textual representation of a month, such as January or March</td>
		<td>January through December</td>
	</tr>
	<tr>
		<td class="heading_1">m</td>
		<td>Numeric representation of a month, with leading zeros</td>
		<td>01 through 12</td>
	</tr>
	<tr>
		<td class="heading_1">M</td>
		<td>A short textual representation of a month, three letters</td>
		<td>Jan through Dec</td>
	</tr>
	<tr>
		<td class="heading_1">n</td>
		<td>Numeric representation of a month, without leading zeros</td>
		<td>1 through 12</td>
	</tr>
	<tr>
		<td class="heading_1">t</td>
		<td>Number of days in the given month</td>
		<td>28 through 31</td>
	</tr>
	<tr>
		<td class="heading_3" colspan="3">Year</td>
		</tr>
	<tr>
		<td class="heading_1">L</td>
		<td>Whether it's a leap year</td>
		<td>1 if it is a leap year, 0 otherwise.</td>
	</tr>
	<tr>
		<td class="heading_1">Y</td>
		<td>A full numeric representation of a year, 4 digits</td>
		<td>1999 or 2003</td>
	</tr>
	<tr>
		<td class="heading_1">y</td>
		<td>A two digit representation of a year</td>
		<td>99 or 03</td>
	</tr>
	</table>

</div>

<div id="postit12" class="postit" onclick="return showPostit(this.id)">
	<div class="help_title">Auto-increment</div>
	<p>
		Generates a column that contains a unique number on each row, incrementing by whatever value you
    enter. This option is helpful for inserting the data into a database field with an auto-increment
    primary key.
	</p>
</div>


<!-- hidden iframe, where the form is submitted to -->
<iframe name="hiddenIframe" src="" frameborder="0" scrolling="no" style="height: 10px; width: 10px;"></iframe>

</div>

        <noscript>
          <div class="error"><span><span><span><span><span><span><span><span>
            <div class="red"><b>Error:</b> JavaScript is not enabled.</div>
            <br/>
            This site and the Data Generator script itself relies heavily on javascript. In order to view
            and navigate the site, you'll need to enable it in your browser then refresh this page.
          </span></span></span></span></span></span></span></span></div>
        </noscript>

        </div>

      </div>

      <div id="ivy_fixed_bottom" style="display:none"></div>

    </div>

  </td>
  <td id="main_table_right_col"><img src="images/main_table_right_top_corner_bg.jpg" border="0" /></td>
</tr>
<tr>
  <td colspan="12" id="bottom_row">

    <table cellpadding="0" cellspacing="0" width="100%" summary="Main Table Bottom Row">
    <tr>
      <td width="20"><img src="images/main_table_left_bottom_rounded_corner.jpg" border="0" /></td>
      <td align="center" valign="top">

        <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="100"> </td>
          <td align="center" id="footer">
            <a href="http://www.generatedata.com" id="black_sheep_link">GenerateData.com</a>, Version 2.1
          </td>
          <td width="100" align="right"><a href="http://www.mozilla.com/firefox/"><img src="images/firefox_small.gif" border="0"/></a></td>
        </tr>
        </table>

      </td>
      <td width="19"><img src="images/main_table_right_bottom_rounded_corner.jpg" border="0" /></td>
    </tr>
    </table>

  </td>
  <td><img src="images/main_table_right_bottom_corner_bg.jpg" border="0" /></td>
</tr>
</table>

</body>
</html>
