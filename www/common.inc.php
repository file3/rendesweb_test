<?php
require('config.inc.php');

/**
 * \brief Detect whether called from CLI
 * \return Is called from CLI
 */
function is_cli()
{
    if (defined('STDIN'))
        return true;
    elseif (php_sapi_name() === 'cli')
        return true;
/*  elseif (array_key_exists('SHELL', $_ENV))
        return true;
    elseif (empty($_SERVER['REMOTE_ADDR']) && (!isset($_SERVER['HTTP_USER_AGENT'])) && (count($_SERVER['argv']) > 0))
        return true;
    elseif (array_key_exists('REQUEST_METHOD', $_SERVER))
        return true;
*/  else
        return false;
}

/**
 * \brief Translation function e.g. with database backend - currently dummy
 * \param word - Word to translate
 * \param lang - Language, default none
 * \return Translated word
 */
function __($word, $lang=false)
{
    return $word;
}


function ostr(&$var, $def='')
{
    return trim(isset($var) ? $var : $def);
}
function oint(&$var, $def=0)
{
    return (int)(isset($var) ? $var : $def);
}

function pgstr($key, $def='')
{
    return trim(isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $def));
}
function pgint($key, $def=0)
{
    return (int)(isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $def));
}


setlocale(LC_ALL, LOCALE.'.'.CHARSET);


/**
 * \brief Print beginning and ending of web-page if not called from CLI
 */
class ShowFrontend
{
    /**
     * \brief Print beginning of web-page if not called from CLI
     */
    public function __construct($title)
    {
        if (!is_cli()):
            $t = htmlspecialchars($title);
            echo  <<<EOD
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>$t</title>
<style>
body {
  opacity: 0;
  transition: opacity 2s;
  -webkit-transition: opacity 2s;
}
</style>
</head>
<body onload="document.body.style.opacity='1'">

EOD;
        endif;
    }

    /**
     * \brief Print ending of web-page if not called from CLI
     */
    public function __destruct()
    {
        if (!is_cli()):
            echo  <<<EOD
</body>
</html>

EOD;
        endif;
    }
}
?>
