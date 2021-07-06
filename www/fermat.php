<?php
require('common.inc.php');

define("DEFAULT_LIMIT", 1000000);

/**
 * \brief Generate Fermat Equation, explanation: https://projecteuler.net/problem=753
 */
class Fermat extends ShowFrontend
{
    private $count=0;

    public function __construct()
    {
        parent::__construct("Fermat");
        if (!is_cli()) {
            echo "<pre>".PHP_EOL;
        }
    }

    private function check_prime($limit, $n)
    {
        static $a_prev = 1;

        $count = 0;

        if ($n < 3)
            return 0;

        $n1 = 1.0 / $n;
        for($a = $a_prev; $a <= $limit; $a++) {
            for($b = 1; $b <= $limit; $b++) {
                $pow_sum = pow($a, $n) + pow($b, $n);
                $c = (int)(pow($pow_sum, $n1));
                $c_pow = pow($c, $n);

                $pow_sum = $pow_sum % $limit;
                $c_pow = $c_pow % $limit;

                if (DEBUG) echo PHP_EOL."a=$a, b=$b, c=$c: pow_sum=$pow_sum, c_pow=$c_pow".PHP_EOL;

                if ($c_pow == $pow_sum) {
                    if (DEBUG) echo PHP_EOL."FOUND: a=$a, b=$b, c=$c: pow_sum=$pow_sum, c_pow=$c_pow".PHP_EOL;
                    $count++;
                }
            }
        }

        $a_prev = $a;

        if ($count ==  0) {
            if (DEBUG) echo PHP_EOL."NOT FOUND: limit=$limit, n=$n".PHP_EOL;
        }
        return $count;
    }

    public function do_primes($limit)
    {
        echo "LIMIT: limit=".$limit.PHP_EOL;
        $num = 1;
        do {
            $div_count = 0;
            for ($i = 1; $i <= $num; $i++) {
                if (($num % $i) == 0) {
                    $div_count++;
                }
            }
            if ($div_count < 3) {
                usleep(10);
                if (DEBUG) echo "PRIME: num=".$num."\r";
                $this->count += $this->check_prime($num, 3);
            }
            $num = $num + 1;
        } while($num <= $limit);
    }

    public function show_count()
    {
        echo PHP_EOL."TOTAL: ".$this->count.PHP_EOL;
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
    $options = getopt("l:h");
    if (isset($options["h"])) {
        $command = $argv["0"];
        $limit = DEFAULT_LIMIT;
        echo <<<EOD
Usage: $command [-l LIMIT]
    -l LIMIT    Maximum generated primes (default: $limit)
    -h          Print this help

EOD;
        exit(1);
    }
    $limit = oint($options["l"], DEFAULT_LIMIT);
} else {
    $limit = pgint("limit", DEFAULT_LIMIT);
}

/**
 * \brief Invoke algorithm
 */
$fermat = new Fermat();

$fermat->do_primes($limit);
$fermat->show_count();
?>
