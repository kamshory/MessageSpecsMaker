<?php
$input = '';
$output = '';
$input2 = '';
$output2 = '';
function ksortRecursive(&$array, $sort_flags = SORT_REGULAR) 
{
    if (!is_array($array)) return false;
    ksort($array, $sort_flags);
    foreach ($array as &$arr) 
	{
        ksortRecursive($arr, $sort_flags);
    }
    return true;
}
function makeRow($no, $prefix, $key, $value)
{
	$type = ucwords(gettype($value));
	if(is_scalar($value))
	{
		$length = strlen($value."");
		if($length == 0)
		{
			$length = '';
		}
	}
	else
	{
		$length = '';
	}
	$prop = ltrim($prefix.".".$key, ".");
	$desc = ucwords(str_replace("_", " ", $key));
	return "| $no | $prop | $type | $length | $desc |";
}
if(isset($_POST['input']))
{
	$input = @$_POST['input'];
	if(strlen($input) > 2)
	{
		$json = json_decode($input, true);
		ksortRecursive($json);
	}
	$input2 = json_encode($json, JSON_PRETTY_PRINT);
	$no = 1;
	$tr = array();
	$tr[] = '| No | Parameter | Type | Length | Description |';
	$tr[] = '| -- | -- | -- | -- | -- |';
	foreach($json as $key1=>$value1)
	{
		if(is_array($value1))
		{
			foreach($value1 as $key2=>$value2)
			{
				$tr[] = makeRow($no, $key1, $key2, $value2);
				$no++;
			}
		}
		else
		{
			$tr[] = makeRow($no, '', $key1, $value1);
			$no++;
		}
	}
	$output2 = implode($tr, "\r\n");
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Specs Maker</title>
<style type="text/css">
body{
	margin:0;
	padding:0;
}
.wrapper{
	width:100%;
	padding:20px;
	box-sizing:border-box;
	position:relative;
}
.wrapper table{
	border-collapse:collapse;
}
.wrapper table td{
	padding:4px 0;
	position:relative;
}
.wrapper table td textarea{
	padding:10px 10px;
	width:100%;
	box-sizing:border-box;
	height:400px;
	border:1px solid #DDDDDD;
}
</style>
</head>
<body>
<div class="wrapper">
<form name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><textarea name="input" id="input" placeholder="Paste your JSON message here..."><?php echo htmlspecialchars($input2);?></textarea></td>
    </tr>
    <tr>
      <td>Length : <?php echo strlen($input2);?></td>
    </tr>
    <tr>
      <td><input type="submit" name="submit" id="submit" value="Convert To Table"></td>
    </tr>
    <tr>
      <td><textarea name="output" id="output"><?php echo htmlspecialchars($output2);?></textarea></td>
    </tr>
  </table>
</form>
</div>
</body>
</html>
