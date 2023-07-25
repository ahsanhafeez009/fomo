<?php
$servername = "localhost";
$username   = "fomocrm5_fomo";
$password   = "cDPH_OZ=1fT(";
$db         = "fomocrm5_fomo";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql="SELECT company_type FROM `companies` GROUP BY company_type";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
  $results[] = $row['company_type'];
}

foreach ($results as $key => $text) {
  @$company_type[$key] = getKeywordSuggestionsFromGoogle(@$text);
}

foreach ($results as $key => $outerwrap) {
  if (!empty($outerwrap)) {
    @$text_match_to[$key] = clean(@$outerwrap);
    if (!empty($company_type[$key])) {
      $text_match_with[$key] = get_words(@$company_type[$key], str_word_count(@$text_match_to[$key]));
    }
  }
}

foreach (@$text_match_to as $key => $outerwrap) {
  foreach (@$text_match_with[$key] as $key1 => $innerwrap) {
    if (@$outerwrap == @$innerwrap) {
      @$record_result[$key] = @$outerwrap;
    }
    if (!array_key_exists($key,(array)@$record_result)){
      @$record_result[$key] = @$innerwrap;
    }
  }
}
echo'<pre/>';
print_r($record_result);
exit;
update_company_type($record_result);

function update_company_type($record_result){
  $servername = "localhost";
  $username   = "fomocrm5_newfomo";
  $password   = "o5*VLl?}=B.!";
  $db         = "fomocrm5_newfomo";

  $conn = new mysqli($servername, $username, $password, $db);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  foreach ($record_result as $key => $value) {

    $sql1="SELECT COUNT(1) FROM company_type WHERE name = value";
    $row = $conn->query($sql1);
    while($row = $row->fetch_assoc()) {
      $results[] = $row['name'];
    }
  }

  echo'<pre/>';
  print_r(@$results);
  print_r(@$record_result);
}

function clean($outerwrap) {
  $string = preg_replace("/[^a-zA-Z]+/", " ", $outerwrap);
  return strtolower($string);
}

function getKeywordSuggestionsFromGoogle($keyword) {
  $keywords = array();
  $data = file_get_contents('https://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q='.urlencode(@$keyword));
  if (($data = json_decode($data, true)) !== null) {
    $keywords = $data[1];
  }

  return $keywords;
}

function get_words($sentence, $count) {
  foreach ($sentence as $key => $value) {
    preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $value, $matches);
    $data[]  = $matches[0];
  }
  return $data;
}
?>