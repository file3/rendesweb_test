<?php
require('common.inc.php');
require('soccer.inc.php');

/**
 * \brief Generate and simulate 2020 UEFA European Football Championship results from Group stage through the Knockout phase until the winner
 */
class Soccer extends ShowFrontend
{
    public static function compare_team($team_rank1, $team_rank2)
    {
        $team1 = $GLOBALS["INPUT_GROUP_RANK"][$GLOBALS["GROUP_NAME_FOR_COMPARE"]][$team_rank1];
        $team2 = $GLOBALS["INPUT_GROUP_RANK"][$GLOBALS["GROUP_NAME_FOR_COMPARE"]][$team_rank2];

        if ($team1["points"] > $team2["points"]) {
            return -1;
        } elseif ($team1["points"] < $team2["points"]) {
            return 1;
        } else {
            $found = false;
            foreach($team1["versus"] as $team_rank_versus1=>$team_value_versus1) {
                if ($team_rank2 == $team_rank_versus1) {
                    $goals_for = $team_value_versus1["goals_for"];
                    $goals_against = $team_value_versus1["goals_against"];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                foreach($team2["versus"] as $team_rank_versus2=>$team_value_versus2) {
                    if ($team_rank1 == $team_rank_versus2) {
                        $goals_for = $team_value_versus2["goals_against"];
                        $goals_against = $team_value_versus2["goals_for"];
                        $found = true;
                        break;
                    }
                }
            }
            if ($found) {
                $points_between1 = ($goals_for > $goals_against) ? 3 : (($goals_for == $goals_against) ? 1 : 0);
                $points_between2 = ($goals_for < $goals_against) ? 3 : (($goals_for == $goals_against) ? 1 : 0);

                if ($points_between1 > $points_between2) {
                    return -1;
                } elseif ($points_between1 < $points_between2) {
                    return 1;
                } else {
                    $goal_difference_between1 = $goals_for - $goals_against;
                    $goal_difference_between2 = $goals_against - $goals_for;

                    if ($goal_difference_between1 > $goal_difference_between2) {
                        return -1;
                    } elseif ($goal_difference_between1 < $goal_difference_between1) {
                        return 1;
                    } else {
                        if ($goals_for > $goals_against) {
                            return -1;
                        } elseif ($goals_for < $goals_against) {
                            return 1;
                        } else {
                            if ($team1["goal_difference"] > $team2["goal_difference"]) {
                                return -1;
                            } elseif ($team1["goal_difference"] < $team2["goal_difference"]) {
                                return 1;
                            } else {
                                if ($team1["goals_for"] > $team2["goals_for"]) {
                                    return -1;
                                } elseif ($team1["goals_for"] < $team2["goals_for"]) {
                                    return 1;
                                } else {
                                    if ($team1["won"] > $team2["won"]) {
                                        return -1;
                                    } elseif ($team1["won"] < $team2["won"]) {
                                        return 1;
                                    } else {
                                        $penalty = rand(0, 2);

                                        if ($penalty == 0) {
                                            return -1;
                                        } elseif ($penalty == 1) {
                                            return 1;
                                        } else {
                                            $fair_play = rand(0, 2);

                                            if ($fair_play == 1) {
                                                return -1;
                                            } elseif ($fair_play == 0) {
                                                return 1;
                                            } else {
                                                $qualifying_rank1 = $GLOBALS["INPUT_QUALIFYING_RANK"][$team_rank1];
                                                $qualifying_rank2 = $GLOBALS["INPUT_QUALIFYING_RANK"][$team_rank2];

                                                if ($qualifying_rank1 < $qualifying_rank2) {
                                                    return -1;
                                                } elseif ($qualifying_rank1 > $qualifying_rank2) {
                                                    return 1;
                                                } else {
                                                    echo "LOGICAL ERROR".PHP_EOL;
                                                    return 0;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                echo "LOGICAL ERROR".PHP_EOL;
                return 0;
            }
        }
    }

    public static function compare_third_team($team_rank1, $team_rank2)
    {
        $team1 = $GLOBALS["THIRD_RESULT"][$team_rank1];
        $team2 = $GLOBALS["THIRD_RESULT"][$team_rank2];

        if ($team1["points"] > $team2["points"]) {
            return -1;
        } elseif ($team1["points"] < $team2["points"]) {
            return 1;
        } else {
            if ($team1["goal_difference"] > $team2["goal_difference"]) {
                return -1;
            } elseif ($team1["goal_difference"] < $team2["goal_difference"]) {
                return 1;
            } else {
                if ($team1["goals_for"] > $team2["goals_for"]) {
                    return -1;
                } elseif ($team1["goals_for"] < $team2["goals_for"]) {
                    return 1;
                } else {
                    if ($team1["won"] > $team2["won"]) {
                        return -1;
                    } elseif ($team1["won"] < $team2["won"]) {
                        return 1;
                    } else {
                        $fair_play = rand(0, 2);

                        if ($fair_play == 1) {
                            return -1;
                        } elseif ($fair_play == 0) {
                            return 1;
                        } else {
                            $qualifying_rank1 = $GLOBALS["INPUT_QUALIFYING_RANK"][$team_rank1];
                            $qualifying_rank2 = $GLOBALS["INPUT_QUALIFYING_RANK"][$team_rank2];

                            if ($qualifying_rank1 < $qualifying_rank2) {
                                return -1;
                            } elseif ($qualifying_rank1 > $qualifying_rank2) {
                                return 1;
                            } else {
                                echo "LOGICAL ERROR".PHP_EOL;
                                return 0;
                            }
                        }
                    }
                }
            }
        }
    }

    private $save_file = NULL;
    private $save_file_pointer = NULL;

    public function __construct($save_file)
    {
        parent::__construct("Soccer");
        if (!is_cli()) {
            echo "<pre>".PHP_EOL;
        }

        try {
            if(substr($save_file, 0, 1) == DIRECTORY_SEPARATOR) {
                $this->save_file = realpath(dirname($save_file)).DIRECTORY_SEPARATOR.basename($save_file);
            } else {
                $this->save_file = realpath(getcwd()).DIRECTORY_SEPARATOR.$save_file;
            }
            echo "SAVE_FILE: ".$this->save_file.PHP_EOL;
            $this->save_file_pointer = @fopen($this->save_file, 'w');
            if (!$this->save_file_pointer) {
                throw new Exception("Cannot open file [".$this->save_file."]");
            }
        } catch (Exception $exception) {
            echo "Caught exception: ".$exception->getMessage().PHP_EOL;
            exit(2);
        }
    }

    private function get_team_value($team_position)
    {
        foreach($GLOBALS["INPUT_GROUP_RANK"] as $group_name=>$group_value) {
            foreach($group_value as $team_rank=>$team_value) {
                if ($team_position == $team_value["position"]) {
                    return array($team_rank => $team_value);
                }
            }
        }
    }

    public function generate_group_tips()
    {
        if ($this->save_file_pointer) fputcsv($this->save_file_pointer, array("team_name1", "team_name2", "goals_for1", "goals_for2", "penalty"));
        if (DEBUG) {
            print_r($GLOBALS["INPUT_GROUP_RANK"]);
            if (!is_cli()) echo "<hr>";
            echo PHP_EOL;
        }
        foreach($GLOBALS["INPUT_GROUP_RANK"] as $group_name=>$group_value) {
            $position = 0;
            foreach($group_value as $team_rank=>$team_value) {
                $played = 0;
                $won = $drawn = $lost = 0;
                $goals_for = $goals_against = 0;
                $versus = array();
                $index = 0;
                foreach($group_value as $team_rank_versus=>$team_value_versus) {
                    if ($index < $position) {
                        $goals_for_versus     = rand(0, GOAL_MAXIMUM_PER_TEAM_MATCH);
                        $goals_against_versus = rand(0, GOAL_MAXIMUM_PER_TEAM_MATCH);
                        if ($goals_for_versus < $goals_against_versus) {
                            exchange_var($goals_for_versus, $goals_against_versus);
                        }
                        if ($team_rank > $team_rank_versus) {
                            if (!rand(0, 1)) {
                                exchange_var($goals_for_versus, $goals_against_versus);
                            }
                        }
                        $versus[$team_rank_versus] = array("team_name"     => $team_value_versus
                                                         , "goals_for"     => $goals_for_versus
                                                         , "goals_against" => $goals_against_versus
                                                          );
                        $played++;
                        if ($goals_for_versus > $goals_against_versus) {
                            $won++;
                        } elseif ($goals_for_versus < $goals_against_versus) {
                            $lost++;
                        } else {
                            $drawn++;
                        }
                        $goals_for     += $goals_for_versus;
                        $goals_against += $goals_against_versus;

                        $team_versus = $GLOBALS["INPUT_GROUP_RANK"][$group_name][$team_rank_versus];
                        $team_versus["played"] = $team_versus["played"] + 1;
                        if ($goals_for_versus < $goals_against_versus) {
                            $team_versus["won"] = $team_versus["won"] + 1;
                        } elseif ($goals_for_versus > $goals_against_versus) {
                            $team_versus["lost"] = $team_versus["lost"] + 1;
                        } else {
                            $team_versus["drawn"] = $team_versus["drawn"] = 1;
                        }
                        $team_versus["goals_for"] = $team_versus["goals_for"] + $goals_against_versus;
                        $team_versus["goals_against"] = $team_versus["goals_against"] + $goals_for_versus;
                        $team_versus["goal_difference"] = $team_versus["goals_for"] - $team_versus["goals_against"];
                        $team_versus["points"] = 3*$team_versus["won"]+$team_versus["drawn"];
                        $GLOBALS["INPUT_GROUP_RANK"][$group_name][$team_rank_versus] = $team_versus;

                        if ($this->save_file_pointer) fputcsv($this->save_file_pointer, array($team_value, $team_value_versus, $goals_for_versus, $goals_against_versus, "no"));
                    }
                    $index++;
                }
                $position++;
                $GLOBALS["INPUT_GROUP_RANK"][$group_name][$team_rank] = array("position"        => $group_name.$position
                                                                            , "team_name"       => $team_value
                                                                            , "played"          => $played
                                                                            , "won"             => $won
                                                                            , "drawn"           => $drawn
                                                                            , "lost"            => $lost
                                                                            , "goals_for"       => $goals_for
                                                                            , "goals_against"   => $goals_against
                                                                            , "goal_difference" => $goals_for-$goals_against
                                                                            , "points"          => 3*$won+$drawn
                                                                            , "qualification"   => NULL
                                                                            , "versus"          => $versus
                                                                             );
            }
            $GLOBALS["GROUP_NAME_FOR_COMPARE"] = $group_name;
            uksort($GLOBALS["INPUT_GROUP_RANK"][$group_name], array(get_class($this), "compare_team"));
            if ($this->save_file_pointer) fputcsv($this->save_file_pointer, array("", "", "", "", ""));
        }
        if ($this->save_file_pointer) fputcsv($this->save_file_pointer, array("", "", "", "", ""));
        if (DEBUG) {
            print_r($GLOBALS["INPUT_GROUP_RANK"]);
            if (!is_cli()) echo "<hr>";
            echo PHP_EOL;
        }
    }

    public function generate_third_orders()
    {
        $GLOBALS["THIRD_RESULT"] = array();

        foreach($GLOBALS["INPUT_GROUP_RANK"] as $group_name=>$group_value) {
            $position = 0;
            foreach($group_value as $team_rank=>$team_value) {
                $position++;
                $GLOBALS["INPUT_GROUP_RANK"][$group_name][$team_rank]["position"] = $group_name.$position;
                if (($position == 1) || ($position == 2)) {
                    $GLOBALS["INPUT_GROUP_RANK"][$group_name][$team_rank]["qualification"] = 1;
                } elseif ($position == 3) {
                    $GLOBALS["THIRD_RESULT"][$team_rank] = $GLOBALS["INPUT_GROUP_RANK"][$group_name][$team_rank];
                    break;
                }
            }
        }

        uksort($GLOBALS["THIRD_RESULT"], array(get_class($this), "compare_third_team"));

        $index = 0;
        $group_third = "";
        foreach($GLOBALS["THIRD_RESULT"] as $team_rank=>$team_value) {
            if ($index < THIRD_COUNT) {
                $GLOBALS["THIRD_RESULT"][$team_rank]["qualification"] = 1;
                $group_third = $group_third.$team_value["position"][0];
                $GLOBALS["INPUT_GROUP_RANK"][$team_value["position"][0]][$team_rank]["qualification"] = 1;
            }
            $index++;
        }

        $group_third_split = str_split($group_third);
        sort($group_third_split);
        $group_third = implode($group_third_split);

        foreach($GLOBALS["INPUT_KNOCKOUT"] as $team_position1=>$team_position2) {
            if ($team_position2 == NULL) {
                $third_versus_index = array_search($team_position1, $GLOBALS["THIRD_VERSUS"]);
                $GLOBALS["INPUT_KNOCKOUT"][$team_position1] = $GLOBALS["THIRD_MATCH_MATRIX"][$group_third][2*$third_versus_index].$GLOBALS["THIRD_MATCH_MATRIX"][$group_third][2*$third_versus_index+1];
            }
        }

        if (DEBUG) {
            print_r($GLOBALS["THIRD_RESULT"]);
            if (!is_cli()) echo "<hr>";
            echo PHP_EOL;
        }
    }

    function generate_knockout_tips_recursive($input_knockout=NULL)
    {
        if ($input_knockout == NULL) {
            $input_knockout = $GLOBALS["INPUT_KNOCKOUT"];
        }

        $input_knockout_next = array();

        $index = 0;
        foreach($input_knockout as $position1=>$position2) {
            $value1 = $this->get_team_value($position1);
            $value2 = $this->get_team_value($position2);
            $rank1 = array_keys($value1);
            $rank2 = array_keys($value2);
            $rank1 = $rank1[0];
            $rank2 = $rank2[0];

            $goals_for     = rand(0, GOAL_MAXIMUM_PER_TEAM_MATCH);
            $goals_against = rand(0, GOAL_MAXIMUM_PER_TEAM_MATCH);

            $penalty = "no";
            if ($goals_for == $goals_against) {
                $penalty = "yes";
                (rand(0, 1)) ? $goals_for++ : $goals_against++;
            }

            if ($goals_for < $goals_against) {
                exchange_var($goals_for, $goals_against);
            }
            if ($rank1 > $rank2) {
                if (!rand(0, 1)) {
                    exchange_var($goals_for, $goals_against);
                }
            }

            $match = array("rank1"         => $rank1
                         , "position1"     => $position1
                         , "team_name1"    => $value1[$rank1]["team_name"]
                         , "rank2"         => $rank2
                         , "position2"     => $position2
                         , "team_name2"    => $value2[$rank2]["team_name"]
                         , "goals_for"     => $goals_for
                         , "goals_against" => $goals_against
                         , "penalty"       => $penalty
                          );

            if (DEBUG) print_r($match);

            if (($index % 2) == 0) {
                if ($goals_for > $goals_against) {
                    $store_position = $position1;
                } else {
                    $store_position = $position2;
                }
            } else {
                if ($goals_for > $goals_against) {
                    $input_knockout_next[$store_position] = $position1;
                } else {
                    $input_knockout_next[$store_position] = $position2;
                }
            }

            if ($this->save_file_pointer) fputcsv($this->save_file_pointer, array($value1[$rank1]["team_name"], $value2[$rank2]["team_name"], $goals_for, $goals_against, $penalty));

            $index++;
        }

        if (count($input_knockout_next) == 0) {
            return;
        } else {
            if ($this->save_file_pointer) fputcsv($this->save_file_pointer, array("", "", "", "", ""));
            if (DEBUG) {
                print_r($input_knockout_next);
                if (!is_cli()) echo "<hr>";
                echo PHP_EOL;
            }
        }
        $this->generate_knockout_tips_recursive($input_knockout_next);

    }

    public function __destruct()
    {
        if ($this->save_file_pointer) fclose($this->save_file_pointer);
        @readfile($this->save_file);

        if (!is_cli()) {
            echo "</pre>".PHP_EOL;
        }
        parent::__destruct();
    }
}


/**
 * \brief Parse arguments
 */
if (is_cli()) {
    $options = getopt("f:h");
    if (isset($options["h"])) {
        $command = $argv["0"];
        $save_file = DEFAULT_SAVE_FILE;
        echo <<<EOD
Usage: $command [-f SAVE_FILE]
    -f SAVE_FILE    Save to this CSV file (default: $save_file)
    -h              Print this help

EOD;
        exit(1);
    }
    $save_file = ostr($options["f"], DEFAULT_SAVE_FILE);
} else {
    $save_file = pgstr("save_file", DEFAULT_SAVE_FILE);
}

/**
 * \brief Invoke algorithm
 */
$soccer = new Soccer($save_file);

$soccer->generate_group_tips();
$soccer->generate_third_orders();
$soccer->generate_knockout_tips_recursive();
?>
