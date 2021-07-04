<?php
require('common.inc.php');

define("DEFAULT_START_DATE", "2000-01-01");

/**
 * \brief Find dates strting from start date that's first day of its month is Sunday
 */
class Sunday extends ShowFrontend
{
    private $start_date;
    private $end_date;
    private $count=0;

    public function __construct($start_date)
    {
        parent::__construct("Sunday");
        if (!is_cli()) {
            echo "<pre>".PHP_EOL;
        }

        $this->start_date = strtotime($start_date);
        $this->end_date = getdate();
        $this->start_date = getdate($this->start_date);

        $this->end_date = strtotime($this->end_date["year"]."-".$this->end_date["mon"]."-1");

        $st = mktime(0, 0, 0, $this->start_date["mon"], 1, $this->start_date["year"]);

        if ($this->start_date["mday"] > 1) {
            $this->start_date = strtotime('+1 month', $st);
        } else {
            $this->start_date = $st;
        }
    }

    public function do_dates()
    {
        echo "RANGE: ".date("Y-m-d", $this->start_date)." - ".date("Y-m-d", $this->end_date).PHP_EOL;

        do {
            if (DEBUG) echo date("Y-m-d", $this->start_date).PHP_EOL;
            $gdate = getdate($this->start_date);
            if ($gdate["wday"] == 0) {
                echo "FOUND: ".date("Y-m-d", $this->start_date).": Sunday".PHP_EOL;
                $this->count++;
            }
            $this->start_date = strtotime('+1 month', $this->start_date);
        } while($this->start_date <= $this->end_date);
    }

    public function show_count()
    {
        echo "TOTAL: ".$this->count.PHP_EOL;
    }

    public function get_count()
    {
        return $this->count;
    }

    public function __destruct()
    {
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
    $options = getopt("d:h");
    if (isset($options["h"])) {
        $command = $argv["0"];
        $start_date = DEFAULT_START_DATE;
        echo <<<EOD
Usage: $command [-d START_DATE]
    -d START_DATE   Start from this date (default: $start_date)

EOD;
        exit(1);
    }
    $start_date = ostr($options["d"], DEFAULT_START_DATE);
} else {
    $start_date = pgstr("start_date", DEFAULT_START_DATE);
}

/**
 * \brief Invoke algorithm
 */
$sunday = new Sunday($start_date);

$sunday->do_dates();
$sunday->show_count();
?>
