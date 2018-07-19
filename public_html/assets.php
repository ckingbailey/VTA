<?php
require_once 'session.php';
require_once '../vendor/autoload.php';
require_once 'sql_functions/sqlFunctions.php';
require_once '../routes/assetRoutes.php';

// instantiate objects
$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader,
    [
        'debug' => true
    ]
);
$twig->addExtension(new Twig_Extension_Debug());

// base context
$context = [
    'navbarHeading' => !empty($_SESSION['firstname']) ? $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] : '',
    'title' => 'Asset List',
    'pageHeading' => 'Assets',
    'tableName' => 'asset',
];

/* parse url to establish which view to display
** [0] => route for view, e.g., 'list', 'add', 'update', 'view'
** [1] => name of table to query
*/
$pathinfo = !empty($_SERVER['PATH_INFO'])
    ? $_SERVER['PATH_INFO']
    : '';
    
$pathParams = explode("/", $pathinfo);
array_shift($pathParams);

// assign template name and sql string based on path params
// if path params invalid, use default 'table' template
if (!empty($pathParams) && array_search($pathParams[0], $routes, true)) {
    $route = $pathParams[0];
} else $route = 'table';

$template = $twig->load("$route.html");

// if it's not the list view, show a back button | list view gets no back button
$context['backto'] = $route !== 'table' ? 'assets.php' : '';
$context['meta'] = $pathinfo; // THIS IS FOR DEV'S INFO ONLY

// retrieve data from db
$context = array_merge(
    $context,
    getAssetData($route)
);

// then render the template with appropriate variables
// $template = $twig->load("table.html");
$template->display($context);