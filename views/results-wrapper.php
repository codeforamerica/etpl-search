<!DOCTYPE HTML>
<html>

  <head>
    <title>Find the Best Training</title>
    <link rel="stylesheet/less" href="../resources/css/style.less?<?php echo rand(0, 99999); ?>">
    <script src="../resources/js/lib/less.js"></script>
    <script src="../resources/js/lib/jquery.js"></script>
    <script src="../resources/js/common.js?<?php echo rand(0, 99999); ?>"></script>
    <script src="../resources/js/results.js?<?php echo rand(0, 99999); ?>"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body ontouchstart="" class="results">

    <div id="wrapper" class="results">
      <div class="header-wrapper">
        <form>
          <a href="search.php" class="back"></a>
          <input type="search" class="search" placeholder="Type a skill area..." name="query" autocomplete="off" value="<?php echo $_GET['query']; ?>">
          <div id="checkboxes-wrapper">
            <div id="checkboxes-scroll-wrapper">
              <div id="checkboxes-container">
                <?php include("shared/filters.php"); ?>
              </div>
            </div>
          </div>
        </form>
      </div>
      
      <div id="results-wrapper">
        <?php include("results.php"); ?>
      </div>
    </div>
    
    <?php include("shared/analytics.php"); ?>
  </body>
</html>

