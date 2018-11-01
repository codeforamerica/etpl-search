<!DOCTYPE HTML>
<html>

  <head>
    <title>Find the Best Training</title>
    <link rel="stylesheet/less" href="../resources/css/style.less?<?php echo rand(0, 99999); ?>">
    <script src="../resources/js/lib/less.js"></script>
    <script src="../resources/js/lib/jquery.js"></script>
    <script src="../resources/js/common.js?<?php echo rand(0, 99999); ?>"></script>
    <script src="../resources/js/search.js?<?php echo rand(0, 99999); ?>"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body ontouchstart="" class="search">

    <div id="wrapper" class="search">
      <div id="people-wrapper">
        <div id="people"></div>
      </div>
      <h1>Find the best training for your next job.</h1>
      <h2>This is a list of job training programs that are eligible to receive publically funded tuition assistance.</h2>
      <form method="GET" action="results-wrapper.php">
        <input type="text" class="location" value="New Jersey" disabled>
        <input type="search" class="search" placeholder="Type a skill area..." name="query" autocomplete="off">
        <input type="submit" value="" class="circle">
        <h3>Filters</h2>
        <div id="checkboxes">
          <?php include("shared/filters.php"); ?>
        </div>

        <input type="submit" value="Search" class="fake">
      </form>
    </div>

    <?php include("shared/analytics.php"); ?>
  </body>
</html>
