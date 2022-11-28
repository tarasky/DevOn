<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="bootstrap5/bootstrap.min.css" rel="stylesheet">
        <link href="ionRangeSlider/ion.rangeSlider.min.css" rel="stylesheet">
        <style>
            body {
                margin-top: 30px;
            }
        </style>
        <title>LeaseWeb Server List</title>
    </head>
    <body>

    <div class="container">
        
        <div class="row">
            <h1 class="page-header text-center">SERVER LIST</h1>
        </div>
        
        <div class="row">
            <div class="col-md-3">
              <select id="categoryFilter" class="form-select">
                <option value="">Show All HDD Types</option>
                <option value="SAS">SAS</option>
                <option value="SATA2">SATA2</option>
                <option value="SSD">SSD</option>
              </select>
            </div>
        
            <div class="col-md-3">
              <select id="categoryFilter2" class="form-select">
                <option value="">Show All Locations</option>
                <option value="AmsterdamAMS-01">AmsterdamAMS-01</option>
                <option value="DallasDAL-10">DallasDAL-10</option>
                <option value="FrankfurtFRA-10">FrankfurtFRA-10</option>
                <option value="Hong KongHKG-10">Hong KongHKG-10</option>
                <option value="San FranciscoSFO-12">San FranciscoSFO-12</option>
                <option value="SingaporeSIN-11">SingaporeSIN-11</option>
                <option value="Washington D.C.WDC-01">Washington D.C.WDC-01</option>
              </select>
            </div>
        </div>
        
        <br>
        
        <div class="row">
            <div class="col-md-2">Select RAM (GB):</div> 
            <div class="col-md-5">
                <input type="checkbox" class="filter" value="2"/> 2</label>
                <input type="checkbox" class="filter" value="4"/> 4</label>
                <input type="checkbox" class="filter" value="8"/> 8</label>
                <input type="checkbox" class="filter" value="12"/> 12</label>
                <input type="checkbox" class="filter" value="16"/> 16</label>
                <input type="checkbox" class="filter" value="24"/> 24</label>
                <input type="checkbox" class="filter" value="32"/> 32</label>
                <input type="checkbox" class="filter" value="48"/> 48</label>
                <input type="checkbox" class="filter" value="64"/> 64</label>
                <input type="checkbox" class="filter" value="96"/> 96</label>
            </div>
         </div>
        
        <div class="row">
            <input type="text" class="js-range-slider" name="my_range" value="" />
        </div>
        
        <div class="row">
            <table id="filterTable" class="display table">
                <thead>
                  <tr>
                    <th>Model</th>
                    <th>RAM</th>
                    <th>HDD</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>RAM(GB)</th>
                    <th>HDD Type</th>
                    <th>HDD capacity</th>
                    <th>HDD capacity(GB)</th>
                  </tr>
                </thead>
          </table>
      </div>
      
    </div>

        <script src="bootstrap5/bootstrap.bundle.min.js"></script>
        <script src="bootstrap5/jquery-1.12.4.min.js"></script>
        <script src="ionRangeSlider/ion.rangeSlider.min.js"></script>
        <script src="DataTables/datatables.min.js"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $('#filterTable').DataTable({
                ajax: "{{url('getallservers')}}",
                "paging": false,
                "sDom":"ltipr",
                "searching": true,
                columns: [{ data: 0 },{ data: 1 },{ data: 2 },{ data: 3 },{ data: 4 },{ data: 5 },{ data: 6 },{ data: 7 },{ data: 8 }],
            });
            
            //HDD types dropdown filter start
            var table = $('#filterTable').DataTable();
            $("#filterTable_filter.dataTables_filter").append($("#categoryFilter"));
            var categoryIndex = 0;
              $("#filterTable th").each(function (i) {
                if ($($(this)).html() == "HDD Type") {
                  categoryIndex = i; return false;
                }
              });
             
              $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                  var selectedItem = $('#categoryFilter').val()
                  var category = data[categoryIndex];
                  if (selectedItem === "" || category.includes(selectedItem)) {
                    return true;
                  }
                  return false;
                }
              );
      
              $("#categoryFilter").change(function (e) {
                table.draw();
              });
              table.draw();
              //HDD types dropdown filter end
              
              //Location dropdown filter start
              var table2 = $('#filterTable').DataTable();
                $("#filterTable_filter.dataTables_filter").append($("#categoryFilter2"));
                var categoryIndex2 = 0;
                  $("#filterTable th").each(function (i) {
                    if ($($(this)).html() == "Location") {
                      categoryIndex2 = i; return false;
                    }
                  });
              
              $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                  var selectedItem2 = $('#categoryFilter2').val()
                  var category2 = data[categoryIndex2];
                  if (selectedItem2 === "" || category2.includes(selectedItem2)) {
                    return true;
                  }
                  return false;
                }
              );
      
              $("#categoryFilter2").change(function (e) {
                table2.draw();
              });
              table2.draw();
              //Location dropdown filter end
              
              //RAM checkbox filter start
              var table3 = $('#filterTable').DataTable();
              
              $.fn.dataTable.ext.search.push(
                function(settings, searchData, index, rowData, counter) {
                  if ($('.filter:checked').length === 0) {
                    return true;
                  }

                  var found = false;
                  $('.filter').each(function(index, elem) {
                    if (elem.checked && rowData[5] === elem.value) {
                      found = true;
                    }
                  });

                  return found;
                }
              );
              
              $('input.filter').on('change', function() {
                table3.draw();
              });
              table3.draw();
              //RAM checkbox filter end
              
              //Storage slider filter start
              var custom_values = [0, 250, 500, 1000, 2000, 3000, 4000, 8000, 12000, 24000, 48000, 72000];
              var values_refined = ['0', '250GB', '500GB','ITB','2TB','3TB','4TB','8TB','12TB','24TB','48TB','72TB'];
              var my_from = custom_values.indexOf(0);
              var my_to = custom_values.indexOf(72000);
              var $d3 = $(".js-range-slider");
              $d3.ionRangeSlider({
                type: "double",
                grid: true,
                prettify: function (n) {
                    var ind = custom_values.indexOf(n);
                    return values_refined[ind];
                },
                from: my_from,
                to: my_to,
                values: custom_values
            });
            
            var table4 = $('#filterTable').DataTable();
            
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var arr = $(".js-range-slider").prop("value");
                var splits = arr.split(";");
                var min = parseInt(splits[0], 10);
                var max = parseInt(splits[1], 10);
                var capacity = parseFloat(data[8]) || 0;
                if (
                    (isNaN(min) && isNaN(max)) ||
                    (isNaN(min) && capacity <= max) ||
                    (min <= capacity && isNaN(max)) ||
                    (min <= capacity && capacity <= max)
                ) {
                    return true;
                }
                return false;
            });
            
            $d3.on("change", function () {
                table4.draw();
            });
            table4.draw();
            //Storage slider filter end   
            
         </script>

    </body>
</html>