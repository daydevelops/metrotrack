<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daydevelops - Metrobus Tracker</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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

    <p><small>
            This application is not affiliated, associated, endorsed by, or in anyway officially connected with
            Metrobus.
            The official Metrobus website can be found at <a href="https://metrobus.com">https://metrobus.com</a>
        </small></p>

    <p><small>
            Built by <a href="https://daydevelops.com">Daydevelops</a>
        </small></p>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title text-black" id='route-name'></h5>
                    </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td scope="row">Location:</td>
                                <td id='location'></td>
                            </tr>
                            <tr>
                                <td scope="row">Leaving Next Stop At:</td>
                                <td id='time'></td>
                            </tr>
                            <tr>
                                <td scope="row">Status:</td>
                                <td id='status'></td>
                            </tr>
                            <tr>
                                <td scope="row">Deviation:</td>
                                <td id='dev'></td>
                            </tr>
                            <tr>
                                <td scope="row">Speed (km/h):</td>
                                <td id='speed'></td>
                            </tr>
                            <tr>
                                <td scope="row">Heading:</td>
                                <td id='heading'></td>
                            </tr>
                            <tr>
                                <td scope="row">Vehicle #:</td>
                                <td id='vehicle'></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
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

        };

        function updateBusses() {
            jQuery.ajax({
                type: "GET",
                url: 'https://www.mbusnews.info/api/timetrack/json/',
                dataType: 'json',
                success: function (obj, textstatus) {
                    busses = obj;

                    for (var i = 0; i < marks.length; i++) {
                        marks[i].setMap(null);
                    }
                    marks = [];
                    busses.forEach((bus) => {

                        latlon = {
                            lat: parseFloat(bus['bus_lat']),
                            lng: parseFloat(bus['bus_lon'])
                        }
                        let marker = new google.maps.Marker({
                            position: latlon,
                            map: map,
                            label: bus['current_route'],
                            visible: true
                        });
                        marker.name = bus['current_route'];
                        marker.location = bus['current_location'];
                        marker.deviation = bus['deviation'] ?  bus['deviation'] : 'NA';
                        marker.vehicle = bus['vehicle'];
                        marker.speed = bus['speed'];
                        marker.heading = bus['heading'];
                        marker.status = bus['gtfs_stop_last_known_status'];
                        
                        if (bus['departure_time_at_closest_stop']) {
                            let depart = new Date((new Date()).toDateString() + ' 00:00:00');
                            depart.setSeconds(depart.getSeconds() + bus['departure_time_at_closest_stop']);
                            marker.departing_at = depart;
                        } else {
                            marker.departing_at = 'Unknown';
                        }

                        marker.addListener("click", () => {
                            document.getElementById('route-name').innerHTML = marker.name;
                            document.getElementById('location').innerHTML = marker.location;
                            document.getElementById('dev').innerHTML = marker.deviation;
                            document.getElementById('speed').innerHTML = marker.speed;
                            document.getElementById('heading').innerHTML = marker.heading;
                            document.getElementById('time').innerHTML = marker.departing_at;
                            document.getElementById('vehicle').innerHTML = marker.vehicle;
                            document.getElementById('status').innerHTML = marker.status;
                            $('#myModal').modal('show');
                        });

                        marks.push(marker);

                    })
                }
            });
        }

    </script>
    <?php echo '<script src="https://maps.googleapis.com/maps/api/js?key='.$gmap_key.'&callback=initMap" async defer></script>'; ?>
</body>

</html>
