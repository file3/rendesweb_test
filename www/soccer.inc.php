<?php

$INPUT_GROUP_RANK = array("A" => array(29 => "Turkey"
                                     ,  7 => "Italy"
                                     , 17 => "Wales"
                                     , 13 => "Switzerland"
                                      )
                        , "B" => array(10 => "Denmark"
                                     , 54 => "Finland"
                                     ,  1 => "Belgium"
                                     , 38 => "Russia"
                                      )
                        , "C" => array(16 => "Netherlands"
                                     , 24 => "Ukraine"
                                     , 23 => "Austria"
                                     , 62 => "North Macedonia"
                                      )
                        , "D" => array( 4 => "England"
                                     , 14 => "Croatia"
                                     , 44 => "Scotland"
                                     , 40 => "Czech Republic"
                                      )
                        , "E" => array( 6 => "Spain"
                                     , 18 => "Sweden"
                                     , 21 => "Poland"
                                     , 36 => "Slovakia"
                                      )
                        , "F" => array(37 => "Hungary"
                                     ,  5 => "Portugal"
                                     ,  2 => "France"
                                     , 12 => "Germany"
                                      )
                         );

$INPUT_QUALIFYING_RANK = array(29 => 14
                             ,  7 =>  2
                             , 17 => 19
                             , 13 =>  9
                             , 10 => 15
                             , 54 => 20
                             ,  1 =>  1
                             , 38 => 12
                             , 16 => 11
                             , 24 =>  6
                             , 23 => 16
                             , 62 => 30
                             ,  4 =>  3
                             , 14 => 10
                             , 44 => 29
                             , 40 => 18
                             ,  6 =>  5
                             , 18 => 17
                             , 21 =>  8
                             , 36 => 22
                             , 37 => 31
                             ,  5 => 13
                             ,  2 =>  7
                             , 12 =>  4
                              );

define("DEFAULT_SAVE_FILE", "soccer.csv");
define("GOAL_MAXIMUM_PER_TEAM_MATCH", 4);
define("THIRD_COUNT", 4);

$THIRD_MATCH_MATRIX = array("ABCD" => "A3D3B3C3"
                          , "ABCE" => "A3E3B3C3"
                          , "ABCF" => "A3F3B3C3"
                          , "ABDE" => "D3E3A3B3"
                          , "ABDF" => "D3F3A3B3"
                          , "ABEF" => "E3F3B3A3"
                          , "ACDE" => "E3D3C3A3"
                          , "ACDF" => "F3D3C3A3"
                          , "ACEF" => "E3F3C3A3"
                          , "ADEF" => "E3F3D3A3"
                          , "BCDE" => "E3D3B3C3"
                          , "BCDF" => "F3D3C3B3"
                          , "BCEF" => "F3E3C3B3"
                          , "BDEF" => "F3E3D3B3"
                          , "CDEF" => "F3E3D3C3"
                           );

$THIRD_VERSUS = array("B1", "C1", "E1", "F1");

$INPUT_KNOCKOUT = array("B1" => NULL
                      , "A1" => "C2"
                      , "F1" => NULL
                      , "D2" => "E2"
                      , "E1" => NULL
                      , "D1" => "F2"
                      , "C1" => NULL
                      , "A2" => "B2"
                       );

function exchange_var(&$var1, &$var2)
{
    $t = $var1;
    $var1 = $var2;
    $var2 = $t;
}

/**
 * \brief Pretty printing of JSON, source: http://stackoverflow.com/questions/6054033/pretty-printing-json-with-php
 * \param json - Input JSON
 * \return Formatted JSON
 */
function json_pretty_print( $json )
{
	$result = '';
	$level = 0;
	$in_quotes = false;
	$in_escape = false;
	$ends_line_level = NULL;
	$json_length = strlen( $json );

	for( $i = 0; $i < $json_length; $i++ ) {
		$char = $json[$i];
		$new_line_level = NULL;
		$post = "";
		if( $ends_line_level !== NULL ) {
			$new_line_level = $ends_line_level;
			$ends_line_level = NULL;
		}
		if ( $in_escape ) {
			$in_escape = false;
		} else if( $char === '"' ) {
			$in_quotes = !$in_quotes;
		} else if( ! $in_quotes ) {
			switch( $char ) {
				case '}': case ']':
					$level--;
					$ends_line_level = NULL;
					$new_line_level = $level;
					break;

				case '{': case '[':
					$level++;
				case ',':
					$ends_line_level = $level;
					break;

				case ':':
					$post = " ";
					break;

				case " ": case "\t": case "\n": case "\r":
					$char = "";
					$ends_line_level = $new_line_level;
					$new_line_level = NULL;
					break;
			}
		} else if ( $char === '\\' ) {
			$in_escape = true;
		}
		if( $new_line_level !== NULL ) {
			$result .= "\n".str_repeat( "\t", $new_line_level );
		}
		$result .= $char.$post;
	}

	return $result;
}

?>
