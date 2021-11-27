function initialize() {   
    initAutocomplete();
}
var marker;
var map;
function initAutocomplete() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: user_lat, lng: user_long}, // update with user's current lat long
        zoom: 13,
        mapTypeId: 'roadmap'
    });

    marker = new google.maps.Marker({
        position: {lat: user_lat, lng: user_long},
        map: map,
      });
    marker.setMap(map);

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.

    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
        return;
        }
        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
        if (!place.geometry) {
            console.log("Returned place contains no geometry");
            return;
        }
        var icon = {
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
        };

        // Create a marker for each place.
        if ( marker ) {
            marker.setPosition(place.geometry.location);
        } else {
            marker = new google.maps.Marker({
            position:  place.geometry.location,
            map: map
            });
        }
        updatePlace(place.geometry.location);
        // ajax request


        if (place.geometry.viewport) {
            // Only geocodes have viewport.
            bounds.union(place.geometry.viewport);
        } else {
            bounds.extend(place.geometry.location);
        }
        });
        map.fitBounds(bounds);
    });

    google.maps.event.addListener(map, 'click', function(event) {
        if ( marker ) {
        marker.setPosition(event.latLng);
        } else {
        marker = new google.maps.Marker({
            position: event.latLng,
            map: map
        });
        }
        updatePlace(event.latLng);
    });
}
function updatePlace(location){
    console.log(location.lat());
    console.log(location.lng());
    $.ajax({
        url:BASE_URL+"user/updateLocation",
        data:{lat:location.lat(),lon:location.lng()},
        type:'post',
        success:function(response){
            console.log(response);
        }
    });

}