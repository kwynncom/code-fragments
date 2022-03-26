function geoFindMe() {

    function success(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;

        KWG_MAPU.setll('set', lat, lon);
    }

    function error(err) { inht('latlone', 'error');  }

    if(!navigator.geolocation) {    } 
    else {
      const opts = { enableHighAccuracy: true, maximumAge: 30000, timeout: 10000, };
      navigator.geolocation.getCurrentPosition(success, error, opts);
    }
}
