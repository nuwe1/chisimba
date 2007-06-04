<?php

$enable_debug_logging = TRUE;

if ($enable_debug_logging == TRUE)
{
    require_once 'pear/Log.php';

    $conf = array('mode' => 0644, 'timeFormat' => '%Y-%m-%d %H:%M:%S');
    $sqlconf = array('mode' => 0644, 'error_prepend' => '[SQLDATA]',
              'error_append'  => '[/SQLDATA]');
    $log = &Log::singleton('file', 'error_log/system_errors.log', 'framework', $conf);
    $sqllog = &Log::singleton('file', 'error_log/sqllog.log', 'sql', $sqlconf);
    $composite = &Log::singleton('composite');
	$composite->addChild($log);
	$composite->addChild($sqllog);
	
    $GLOBALS['DEBUG_LOG_OBJ'] = $log;
	$GLOBALS['SQL_LOG_OBJ'] = $sqllog;
    function log_debug($str)
    {
        ob_start();
        print_r($str);
        $logstr = ob_get_contents();
        ob_end_clean();

        $logger = $GLOBALS['DEBUG_LOG_OBJ'];
        $logger->log($logstr, PEAR_LOG_DEBUG);
    }
    
    function sql_log($str)
    {
    	//$composite->removeChild($log);
    	ob_start();
        print_r(stripcslashes(stripslashes($str)));
        $logstr = ob_get_contents();
        ob_end_clean();

        $logger = $GLOBALS['SQL_LOG_OBJ'];
        $logger->log($logstr, PEAR_LOG_DEBUG);
    }
}
else
{
    function log_debug($str)
    {
    }
}

?>