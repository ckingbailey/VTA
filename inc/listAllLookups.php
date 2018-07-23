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
    echo "<pre id='list_sql_exc' style='color: deepPink'>There was a problem fetching from the database: $e</pre>";
} catch (Exception $e) {
    echo "<pre id='list_Exc' style='color: chocolate'>There was a problem fetching from the database: $e</pre>";
} finally {
    $link->disconnect();
}
