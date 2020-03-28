<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daydevelops - Metrobus Tracker</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        body {
            background-color: #333;
            color: #FFF;
        }

        p>small>a {
            color: #FFF;
        }
    </style>
</head>

<body>

    <h2>MetroTrack</h2>
        <div id="map" style="height:500px; width:100%"></div>
        <p><small>This application is not affiliated, associated, endorsed by, or in anyway officially connected with Metrobus. 
            The official Metrobus website can be found at <a href="https://metrobus.com">https://metrobus.com</a></small></p>
        <p><small>Built by <a href="https://daydevelops.com">Daydevelops</a></small></p>
        <script>
            var map;
            var marks = [];
            var busses;

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: 47.542048,
                        lng: -52.761061
                    },
                    zoom: 12
                });
                updateBusses();
            }

            window.setInterval(updateBusses, 10000);

            function setMarkers() {
                for (var i = 0; i < marks.length; i++) {
                    marks[i].setMap(null);
                }
                marks = [];

                for (var key in busses) {
                    latlon = {
                        lat: parseFloat(busses[key][0]),
                        lng: parseFloat(busses[key][1])
                    }
                    marker = new google.maps.Marker({
                        position: latlon,
                        map: map,
                        label: key,
                        visible: true
                    });
                    marks.push(marker);
                }
            }

            function updateBusses() {
                jQuery.ajax({
                    type: "GET",
                    url: 'busses',
                    dataType: 'json',
                    success: function(obj, textstatus) {
                        busses = obj;
                        setMarkers();
                    }
                });
            }
        </script>
        <?php echo '<script src="https://maps.googleapis.com/maps/api/js?key='.$gmap_key.'&callback=initMap" async defer></script>'; ?>
</body>

</html>