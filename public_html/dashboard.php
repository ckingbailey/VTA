<?php
$baseDir = $_SERVER['DOCUMENT_ROOT'] . '/..';
require "$baseDir/inc/sql_functions/sqlFunctions.php";
require "$baseDir/inc/session.php";

$title = PROJECT_NAME . " - Home";
//$table = pages;

$System = "SELECT S.System, COUNT(D.GroupToResolve) FROM deficiency D LEFT JOIN System S ON D.GroupToResolve=S.SystemID GROUP BY System ORDER BY S.System"; //Count Actions by System
$Sev = "SELECT S.SeverityName, COUNT(D.Severity) FROM deficiency D LEFT JOIN Severity S ON D.Severity=S.SeverityID WHERE D.Status=1 GROUP BY Severity ORDER BY S.SeverityName";
$Status = "SELECT S.Status, COUNT(D.Status) FROM deficiency D LEFT JOIN Status S ON D.Status=S.StatusID GROUP BY Status ORDER BY StatusID";
$location = "SELECT L.LocationName, COUNT(D.Location) FROM deficiency D LEFT JOIN Location L ON L.LocationID=D.Location GROUP BY Location  ORDER BY L.LocationName";
$Comp = "SELECT CompName FROM Comp ORDER BY CompName";
$sqlSys = "SELECT COUNT(*) FROM System"; //Systems Count
$sqlStat = "SELECT COUNT(*) FROM Status"; //Status Counts
$sqlSev = "SELECT COUNT(*) FROM Severity"; //Severity Counts
$sqlLoc = "SELECT COUNT(*) FROM Location"; //Location Counts
$sqlET = "SELECT COUNT(*) FROM deficiency WHERE Status=2"; //Status Closed Counts

// vars to pass to JS scripts
$statusOpen = 0;
$statusClosed = 0;
$blockSev = 0;
$critSev = 0;
$majSev = 0;
$minSev = 0;

//if(!f_tableExists($link, $table, DB_Name)) {
//    die('<br>Destination table does not exist: '.$table);
//}

// array of tables to render as cards
// each item MUST include TableName, PluralString, ItemString, SqlString
// if any extra content is needed, add it to index after required items
$cards = [
  ['status', 'Statuses', 'Items',
    "<div class='data-display'><div id='open-closed-graph' class='chart-container'></div><p id='open-closed-legend' class='flex-column'></p></div>"],
  ['severity', 'Severities', 'Open Items', '<div class="data-display"><div id="severity-graph" class="chart-container"></div><p id="open-closed-legend" class="legend"></p></div>'],
  ['system', 'Systems', 'Actions'],
  ['location', 'Locations', 'Open Items']
];
$queries = [
  'status' => 'SELECT S.StatusName, COUNT(D.Status) FROM deficiency D LEFT JOIN status S ON D.Status=S.StatusID GROUP BY StatusName ORDER BY StatusID',
  'severity' => 'SELECT S.SeverityName, COUNT(D.Severity) FROM deficiency D LEFT JOIN severity S ON D.Severity=S.SeverityID WHERE D.Status=1 GROUP BY SeverityName ORDER BY S.SeverityName',
  'system' => 'SELECT S.SystemName, COUNT(D.Status) FROM deficiency D LEFT JOIN system S ON D.GroupToResolve=S.SystemID WHERE D.Status=1 GROUP BY SystemName ORDER BY S.SystemName',
  'location' => 'SELECT L.LocationName, COUNT(D.Status) FROM deficiency D LEFT JOIN location L ON L.LocationID=D.Location WHERE Status = 1 GROUP BY LocationName ORDER BY L.LocationName'
];

function writeDashCard($count, array $res, $card) {
  global $statusOpen, $statusClosed, $blockSev, $critSev, $majSev, $minSev;
  echo "
    <div class='card dash-card'>
      <header class='card-header'>
        <h4>{$card[1]}</h4>
      </header>
      <div class='card-body grey-bg'>
        <ul class='dash-list'>
          <li class='bg-secondary text-white dash-list-heading'>
            <span class='dash-list-left dash-list-name'>{$card[0]}</span>
            <span class='dash-list-right dash-list-count'>{$card[2]}</span>
          </li>";
  if ($count > 0 ) {
    foreach ($res as $row) {
        if ($row[0] == 'Open') $statusOpen = $row[1];
        elseif ($row[0] == 'Closed') $statusClosed = $row[1];
        elseif ($row[0] == 'Blocker') $blockSev = $row[1];
        elseif ($row[0] == 'Critical') $critSev = $row[1];
        elseif ($row[0] == 'Major') $majSev = $row[1];
        elseif ($row[0] == 'Minor') $minSev = $row[1];
        echo "
            <li class='dash-list-item'>
              <span class='dash-list-left'>{$row[0]}</span>
              <span class='dash-list-right'>{$row[1]}</span>
            </li>
        ";
    }
    echo "</ul>";
    if (count($card) > 3) {
      echo "{$card[3]}</div>";
    } else echo "</div>";
    echo "
        <footer class='card-footer'>
          <a href='Display{$card[1]}.php' class='btn btn-lg btn-outline btn-a'>Number of {$card[1]} {$count}</a>
        </footer>
    ";
  } else echo "</ul><p class='empty-qry-msg'>0 items returned from database</p>";
  echo "</div></div>";
}

include('filestart.php'); //Provides all HTML starting code
$link = connect();
?>
<header class="container page-header">
  <h1 class="page-title">Database Information</h1>
</header>
<main role="main" class="container main-content dashboard">
  <?php
  foreach($cards as $card) {
    $res = $link->query($queries[$card[0]]);
    $count = $link->count;
    writeDashCard($count, $res, $card);
  }
?>
</main>
<?php
$link->disconnect();
echo "
<script src='https://d3js.org/d3.v5.js'></script>
<script src='js/pie_chart.js'></script>
<script>
window.drawOpenCloseChart(window.d3, '$statusOpen', '$statusClosed')
window.drawSeverityChart(window.d3, '$blockSev', '$critSev', '$majSev', '$minSev')
</script>
<!--DO NOT TYPE BELOW THIS LINE-->";
include('fileend.php');
?>
