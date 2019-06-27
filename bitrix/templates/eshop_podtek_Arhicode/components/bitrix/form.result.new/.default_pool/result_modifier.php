<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $arResult
 * @var CBitrixComponentTemplate $arParams
 */

$arName = ['arQuestions','arAnswers','QUESTIONS',];

foreach ($arName as $name)
{
    $arResultMod = [];

    foreach ($arResult[$name] as $key=>$item)
    {
        $arr = explode('_', $key);

        if(count($arr) == 4)
        {
            if(!is_array($arResultMod[$arr[0].'_'.$arr[1].'_'.$arr[2]]['Q_DATA']))
                $arResultMod[$arr[0].'_'.$arr[1].'_'.$arr[2]]['Q_DATA'] = [];

            $arResultMod[$arr[0].'_'.$arr[1].'_'.$arr[2]]['Q_DATA'][$arr[3]] = $item;
        }
        elseif (count($arr) == 3)
        {
            $arResultMod[$key] = $item;
        }
    }

    $arResult[$name] = $arResultMod;
    unset($arResultMod, $arr);
}






/*
$arQuestions = [];

foreach ($arResult['arQuestions'] as $key=>$item)
{
    $question = explode('_', $key);

    if(count($question) == 4)
    {
        if(!is_array($arQuestions[$question[0].'_'.$question[1].'_'.$question[2]]['Q_DATA']))
            $arQuestions[$question[0].'_'.$question[1].'_'.$question[2]]['Q_DATA'] = [];

        $arQuestions[$question[0].'_'.$question[1].'_'.$question[2]]['Q_DATA'][$question[3]] = $item;
    }
    elseif (count($question) == 3)
    {
        $arQuestions[$key] = $item;
    }
}

$arResult['arQuestions'] = $arQuestions;
unset($arQuestions, $question);


$arAnswers = [];
foreach ($arResult['arAnswers'] as $key=>$item)
{
    $answer = explode('_', $key);
    if(count($answer) == 4)
    {
        if(!is_array($arAnswers[$answer[0].'_'.$answer[1].'_'.$answer[2]]['Q_DATA']))
            $arAnswers[$answer[0].'_'.$answer[1].'_'.$answer[2]]['Q_DATA'] = [];

        $arAnswers[$answer[0].'_'.$answer[1].'_'.$answer[2]]['Q_DATA'][$answer[3]] = $item;
    }
    elseif (count($answer) == 3)
    {
        $arAnswers[$key] = $item;
    }
}
$arResult['arAnswers'] = $arAnswers;
unset($arAnswers, $answer);


$QUESTIONS = [];
foreach ($arResult['QUESTIONS'] as $key=>$item)
{
    $question = explode('_', $key);
    if(count($question) == 4)
    {
        if(!is_array($QUESTIONS[$question[0].'_'.$question[1].'_'.$question[2]]['Q_DATA']))
            $QUESTIONS[$question[0].'_'.$question[1].'_'.$question[2]]['Q_DATA'] = [];

        $QUESTIONS[$question[0].'_'.$question[1].'_'.$question[2]]['Q_DATA'][$question[3]] = $item;
    }
    elseif (count($question) == 3)
    {
        $QUESTIONS[$key] = $item;
    }
}
$arResult['QUESTIONS'] = $QUESTIONS;
unset($QUESTIONS, $question);
*/
?>