<?php
require_once 'sql_functions/sqlFunctions.php';
require_once '../inc/lookupQryFcns.php';

$sql = $sqlStrings['listAll'];

try {
    $link = connect();
    
    if (!$data = $link->query($sql)) throw new mysqli_sql_exception($link->getLastError());
    
    foreach ($data as &$row) {
        $row['href'] = "/manage.php/list/{$row['name']}";
        $row['name'] = ucwords(
            isset($displayNames[$row['name']])
                ? $displayNames[$row['name']]
                : $row['name']
        );
    }
    
    $context['count'] = $link->count;
    $context['data'] = $data;
} catch (mysqli_sql_exception $e) {
    $context['errorMsg'] = 'There was a problem fetching from the database';
} catch (Exception $e) {
    $context['errorMsg'] = 'There was a problem fetching from the database';
} finally {
    $link->disconnect();
}
