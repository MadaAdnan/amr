GMaps.prototype.addStyle = function (options) {
    var styledMapType = new google.maps.StyledMapType(options.styles, options.styledMapName);
    this.map.mapTypes.set(options.mapTypeId, styledMapType);
};

GMaps.prototype.setStyle = function (mapTypeId) {
    this.map.setMapTypeId(mapTypeId);
};

map = new GMaps({
    div: '#map-canvas',
    lat: 40.712784,
    lng: -74.005941,
    enableNewStyle: true,
    zoom: 11,
    scrollwheel: false


}); 

var styles = [
    {
        stylers: [
            {
                hue: "#0075BF"
            },
            {
                saturation: -20
            }
]
}, {
        featureType: "road",
        elementType: "geometry",
        stylers: [
            {
                lightness: 100
            },
            {
                visibility: "simplified"
            }
]
}, {
        featureType: "road",
        elementType: "labels",
        stylers: [
            {
                visibility: "off"
            }
]
}
];

map.addStyle({
    styledMapName: "Styled Map",
    styles: styles,
    mapTypeId: "map_style"
});

map.setStyle("map_style");

map.addMarker({
    lat: 40.796138,
    lng: -74.060898,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});
map.addMarker({
    lat: 40.692093,
    lng: -74.279251,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});

map.addMarker({
    lat: 40.688969,
    lng: -74.382248,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point-blue.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});

map.addMarker({
    lat: 40.737893,
    lng: -74.206467,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});
map.addMarker({
    lat: 40.716038,
    lng: -73.979874,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point-blue.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
}); 
map.addMarker({
    lat: 40.808119,
    lng: -73.88237,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point-blue.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});

map.addMarker({
    lat: 40.665015,
    lng: -73.816452,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});

map.addMarker({
    lat: 40.725015,
    lng: -73.881452,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});

map.addMarker({
    lat: 40.70015,
    lng: -73.81452,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});
map.addMarker({
    lat: 40.519019,
    lng: -74.20372,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point-blue.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});
map.addMarker({
    lat: 40.660327,
    lng: -74.120979,
    title: 'Marker',
    icon: 'assets/img/placeholder-filled-point-blue.svg',
    infoWindow: {
        content: '<div class="store"><div class="title">Mikes Garage</div><div class="address"><a href="info@mikesgaragare.com">info@mikesgaragare.com</a><br>Green St San Francisco, Kaliforniya, Amerika Birleşik Devletleri</div><ul class="features"><li>Dealer</li></ul></div>'
    }
});