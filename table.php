<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Column Toggle</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <style>
        .toggle-btns {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>DataTable Column Toggle</h2>

        <!-- Toggle Button -->
        

        <!-- DataTable -->
        <table id="myTable" class="display">
            <thead>
                <tr>
                    <th>1</th> <!-- Index 0 -->
                    <th>2</th> <!-- Index 1 -->
                    <th>3</th> <!-- Index 2 -->
                    <th>4</th> <!-- Index 3 -->
                    <th>5</th> <!-- Index 4 -->
                    <th>6</th> <!-- Index 5 -->
                    <th>7</th> <!-- Index 6 -->
                    <th>view</th>
                    <th>8</th> <!-- Index 7 -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td><button id="toggleColumns" class="btn btn-primary">Show column 1-3</button></td>
                    <td>8</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td><button id="toggleColumns" class="btn btn-primary">Show column 1-3</button></td>
                    <td>8</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                columnDefs: [{
                        target: 0,
                        visible: false, // Hide the first column
                    },
                    {
                        target: 1,
                        visible: false, // Hide the second column
                    },
                    {
                        target: 2,
                        visible: false, // Hide the third column
                    }
                ]
            });

            // Toggle button functionality
            $('#toggleColumns').on('click', function() {
                var column1 = table.column(0); // Column index 0 (Name)
                var column2 = table.column(1); // Column index 1 (Position)
                var column3 = table.column(2); // Column index 2 (Office)

                // Toggle visibility
                column1.visible(!column1.visible());
                column2.visible(!column2.visible());
                column3.visible(!column3.visible());

                // Change button text based on visibility
                if (column1.visible()) {
                    $('#toggleColumns').text('Hide Columns 1-3');
                } else {
                    $('#toggleColumns').text('Show Columns 1-3');
                }
            });
        });
    </script>

</body>

</html>