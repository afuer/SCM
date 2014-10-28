<?php

class Ibiconvert {

    function __construct() {
        
    }

    function val($values) {
        $this->assign();

        $value_length = strlen($values); // Get value length

        $for_places = $value_length; // For assign place array


        for ($i = 0; $i < $value_length; $i++) {
            $for_value = substr($values, $i, 1);


            if ($for_places == 5) { // Check for Ten thousand
                ($for_value == 0) ? $add_value == "" : $add_value = $for_value;
            } elseif ($for_places == 4) {  // Check for thousand
                if ($for_value != 0 || $add_value != "") {
                    if ($add_value != "") {
                        if ($add_value > 1) {
                            $add_value = $add_value . "0";
                            $convert .= $this->words["$add_value"] . $this->words["$for_value"] . $this->places["$for_places"];
                        } else {
                            $add_value = $add_value . $for_value;
                            $convert .= $this->words["$add_value"] . $this->places["$for_places"];
                        }
                    } else {
                        $convert .= $this->words["$for_value"] . $this->places["$for_places"];
                    }
                }
            } elseif ($for_places == 2) {  // Check for after hundred
                if ($for_value != 0 || $add_value != "") {
                    //$convert .= " and" ;
                    ($for_value == 0) ? $add_value == "" : $add_value = $for_value;
                }
            } elseif ($for_places == 1) {

                if ($add_value != "") {
                    if ($add_value > 1) {
                        $add_value = $add_value . "0";
                        $convert .= $this->words["$add_value"] . $this->words["$for_value"];
                    } else {
                        $add_value = $add_value . $for_value;
                        $convert .= $this->words["$add_value"];
                    }
                } else {
                    $convert .= $this->words["$for_value"] . $this->places["$for_places"];
                }
            } elseif ($for_value != 0) {
                $convert .= $this->words["$for_value"] . $this->places["$for_places"];
            }
            $for_places--;
        }

        return $convert;
    }

    function assign() {
        $this->words["1"] = " One";
        $this->words["2"] = " Two";
        $this->words["3"] = " Three";
        $this->words["4"] = " Four";
        $this->words["5"] = " Five";
        $this->words["6"] = " Six";
        $this->words["7"] = " Seven";
        $this->words["8"] = " Eight";
        $this->words["9"] = " Nine";

        $this->words["10"] = " Ten";
        $this->words["11"] = " Eleven";
        $this->words["12"] = " Twelve";
        $this->words["13"] = " Thirteen";
        $this->words["14"] = " Fourteen";
        $this->words["15"] = " Fiften";
        $this->words["16"] = " Sixteen";
        $this->words["17"] = " Seventeen";
        $this->words["18"] = " Eighteen";
        $this->words["19"] = " Nineteen";

        $this->words["20"] = " Twenty";
        $this->words["30"] = " Thirty";
        $this->words["40"] = " Forty";
        $this->words["50"] = " Fifty";
        $this->words["60"] = " Sixty";
        $this->words["70"] = " Seventy";
        $this->words["80"] = " Eighty";
        $this->words["90"] = " Ninety";

        $this->places["3"] = " Hundred";
        $this->places["4"] = " Thousand";
        $this->places["6"] = " Lac";
        $this->places["7"] = " Million";
        $this->places["8"] = " Crore";
        $this->places["9"] = " Billion";
    }

}
