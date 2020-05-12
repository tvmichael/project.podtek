<?
function GetFormId($WEB_FORM_ID)
{
	$myFormId = $WEB_FORM_ID;
	return $myFormId;
}

function GetResultId($RESULT_ID)
{
	$myResultId = $RESULT_ID;	
	return $myResultId;
}

function GetAreaMirrorsOfWater($myLong, $myWidth)
{
	$myAreaMirrorsOfWater = $myLong * $myWidth;
	return $myAreaMirrorsOfWater;
}


function GetAreaOfTheBasin($myLong, $myWidth, $myDepth)
{
	$myAreaOfTheBasin =($myLong + $myWidth)*2*$myDepth + $myLong * $myWidth;
	return $myAreaOfTheBasin;
}


function GetBasinAreaForFilms($myLong, $myWidth, $myDepth)
{
	$BasinAreaForFilms = (($myLong + $myWidth)*2*$myDepth + $myLong * $myWidth)*1.2;
	$myBasinAreaForFilms = ceil($BasinAreaForFilms);
	return $myBasinAreaForFilms;
}

function GetBasinAreaForTile($myLong, $myWidth, $myDepth)
{
	$BasinAreaForTile = (($myLong + $myWidth)*2*$myDepth + $myLong * $myWidth)*1.1;
	$myBasinAreaForTile = ceil($BasinAreaForTile);
	return $myBasinAreaForTile;
}


function GetPerimeterOfTheBasin($myLong, $myWidth, $myDepth)
{
	
	$myPerimeterOfTheBasin = ($myLong + $myWidth)*2;
	return $myPerimeterOfTheBasin;
}

?>
