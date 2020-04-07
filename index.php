<?php
include 'vendors/simple_html_dom.php';
include 'vendors/Curl.php';
include 'src/Client.php';

//Login User and Get Token
$Client = new Client();
$html = $Client->login();

//Get HTML and Table
$Client->getDataHtml($html);



?>
<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <title>Bbo - Web Crawler</title>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/crawler.css"/>
    </head>
    <body>
        <!--TODO: Implement your code here-->

        <script type="text/javascript" src="assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
       
        
    </body>
    <script type="text/javascript">
        $(document).ready(function(){
            
            

            // Group table rows by first cell value. Assume first row is header row
            function groupByFirst(table) {

              // Add expand/collapse button
              function addButton(cell) {
                var button = cell.appendChild(document.createElement('button'));
                button.className = 'toggleButton';
                button.textContent = '+';
                button.addEventListener('click', toggleHidden, false);
                return button;
              }

              // Expand/collapse all rows below this one until next header reached
              function toggleHidden(evt) {
                var row = this.parentNode.parentNode.nextSibling;
                
                while (row && !row.classList.contains('groupHeader')) {
                  row.classList.toggle('hiddenRow');
                  row = row.nextSibling;
                }
              }
              
              // Use tBody to avoid Safari bug (appends rows to table outside tbody)
              var tbody = table.tBodies[0];

              // Get rows as an array, exclude first row
              var rows = Array.from(tbody.rows).slice(1);
              
              // Group rows in object using first cell value
              var groups = rows.reduce(function(groups, row) {
                var val = row.cells[5].textContent;
                
                if (!groups[val]) groups[val] = [];
                
                groups[val].push(row);
                return groups;
              }, Object.create(null));
              
              // Put rows in table with extra header row for each group
              Object.keys(groups).forEach(function(value, i) {

                // Add header row
                var row = tbody.insertRow();
                row.className = 'groupHeader';
                var cell = row.appendChild(document.createElement('td'));
                cell.colSpan = groups[value][0].cells.length;
                cell.appendChild(
                  document.createTextNode(
                    'Grouped by ' + table.rows[0].cells[5].textContent +
                    ' (' + value + ') ' + groups[value].length + ' hits'
                  )
                );
                var button = addButton(cell);

                // Put the group's rows in tbody after header
                groups[value].forEach(function(row){tbody.appendChild(row)});
                
                // Call listener to collapse group
                button.click();
              });
            }

            window.onload = function(){
              groupByFirst(document.getElementById('mainTable'));
            }

        });
        
    </script>


</html>
