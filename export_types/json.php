<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <style type="text/css">
  body { margin: 10px; }
  table, th, td { font-family: arial; font-size: 8pt; }
  table { background-color: #efefef; border: 2px solid #dddddd; width: 100%; }
  th { background-color: #efefef; }
  td { background-color: #ffffff; }
  </style>
</head>
<body>

<?php
ksort($g_template, SORT_NUMERIC);

$output = array();

for ($row=1; $row<=$g_numResults; $row++)
{
    //echo 'title: ', [$i]['title'], '<BR>';
  $row_data = array();
  while (list($order, $data_types) = each($g_template))
  {
    foreach ($data_types as $data_type)
    {
      $order = $data_type["column_num"];
      $data_type_folder = $data_type["data_type_folder"];
      $data_type_func = "{$data_type_folder}_generate_item";
      $data_type["random_data"] = $data_type_func($row, $data_type["options"], $row_data);
      $row_data[$order] = $data_type;
    }
  }
  reset($g_template);
  ksort($row_data, SORT_NUMERIC);

    $tmpData = array();
  foreach ($row_data as $data)
  {
//      pr($data);
     $tmpData[$data['title']] = (is_array($data["random_data"])) ? $data["random_data"]["display"] : $data["random_data"];

  }
    $output[] = $tmpData;

}

echo json_encode($output);
?>

</body>
</html>
