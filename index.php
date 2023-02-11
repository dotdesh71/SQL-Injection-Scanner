<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <section class="section">
      <div class="container">
        <h1 class="title">SQL Injection Scanner</h1>

        <div class="box">
        <div class="field">
          <label class="label">Target URL:</label>
          <div class="control">
            <input id="target_url" class="input" type="text" placeholder="http://example.com">
          </div>
        </div>
        <div class="field">
          <div class="control">
            <button id="scan_button" class="button is-link">Scan</button>
          </div>
          <div id="loading" style="display:none;">
            <p>Scanning...</p>
          </div>
        </div>
        <div id="results"></div>
        </div>
        
      </div>
    </section>
    <script>
      $(document).ready(function() {
        $("#scan_button").click(function() {
          var target_url = $("#target_url").val();
          $("#loading").show();
          $.ajax({
            type: "POST",
            url: "scan.php",
            data: { target_url: target_url },
            success: function(result) {
              $("#loading").hide();
              $("#results").html(result);
            }
          });
        });
      });

    </script>
  </body>
</html>
