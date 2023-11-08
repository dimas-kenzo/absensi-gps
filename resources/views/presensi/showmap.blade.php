<style>
    #map {
        height: 180px;
    }
</style>
<div id="map"></div>
<script>
    var lokasi = "{{ $presensi->location_in }}"; // Perluas variabel dengan tanda kutip
    var lok = lokasi.split(",");
    var latitude = parseFloat(lok[0]); // Konversi ke float
    var longitude = parseFloat(lok[1]); // Konversi ke float

    var map = L.map('map').setView([latitude, longitude], 16);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Tambahkan marker ke peta
    L.marker([latitude, longitude]).addTo(map);
    var circle = L.circle([-7.626136866059694, 109.58462431534495], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 65
    }).addTo(map);

    var popup = L.popup()
    .setLatLng([latitude, longitude])
    .setContent("{{ $presensi->name }}")
    .openOn(map);
</script>
