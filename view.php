<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<?php
include "db.php";
#echo "haha yes";
$sql = "SELECT ID, Name, Url FROM chargerToAnalyze";
$chargers = $mysqli->query($sql);
#while($row = $result->fetch_assoc()){
#    echo $row["Time"].": ".$row["Status"]."<br>";
#}
?>
<!doctype html>
<html lang="de">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Charger Analysis</title>
</head>
<script>
    var layout = {
                width: 1000,
                height: 300,
                xaxis: {
                    autorange: true,
                    domain: [0, 1],
                    range: [],
                    title: '<- Time ->',
                },
            };

</script>
<body>
    <div class="container">
        <?php while ($charger = $chargers->fetch_assoc()) : ?>

            <H3>Chance of free charge using the CCS connector at "<a href="<?php echo $charger["Url"] ?>"><?php echo $charger["Name"] ?></a>":</H3>

            <br>
            <?php
            $cUrl = $charger["Url"];
            //get data and print
            $sql = "SELECT Hour(Date) as h,  ROUND((1-SUM(Status)/ COUNT(ID))*100) as availability FROM `analyse` WHERE Url ='" . $cUrl . "' GROUP BY Hour(Date)";
            $result = $mysqli->query($sql);
            $availability = "[";
            $time = "[";
            while ($row = $result->fetch_assoc()) {
                #echo (string)$row["h"] . ":00: " . $row["availability"] . "%<br>";
                $availability = $availability . $row["availability"] . ",";
                $time = $time . $row["h"] . ",";
            }
            $availability = $availability . "]";
            $time = $time . "]";
            ?>
            <script>
                var <?php echo "var" . $charger["ID"] ?> = {
                    x: <?php echo $time; ?>,
                    y: <?php echo $availability; ?>,
                    type: 'scatter',
                    name: "<?php echo $charger['Name'] ?>"
                };
                console.log(<?php echo "var" . $charger["ID"] ?>)
            </script>
            <div id='<?php echo "availability" . $charger["ID"] ?>'></div>

            <script>
                var <?php echo "data" . $charger["ID"] ?>=[<?php echo "var" . $charger["ID"] ?>]
                console.log(<?php echo "data" . $charger["ID"] ?>)
                Plotly.newPlot('<?php echo "availability" . $charger["ID"] ?>', <?php echo "data" . $charger["ID"] ?>, layout)
            </script>
        <?php endwhile ?>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>
