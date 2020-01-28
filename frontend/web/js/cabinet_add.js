ymaps.ready(init_map);

var myMap;

function init_map() {  

    var center = [55.753215, 37.622504],
    myPlacemark,
    suggestView = new ymaps.SuggestView('suggest');

    myMap = new ymaps.Map('ya_map', {
        center: center,
        zoom: 9,
        controls: ['geolocationControl', 'zoomControl', 'fullscreenControl']
    }, {
        searchControlProvider: 'yandex#search'
    });

    

    $('#suggest').on('keyup', function (e) {
        var address = $(this).val();
        getCoords(address);
        myMap.setZoom(16);
    });

    suggestView.events.add('select', function (e) {
        var address = e.get('item').value;
        getCoords(address);
        myMap.setZoom(16);
    });     

    // Слушаем клик на карте.
    myMap.events.add('click', function (e) {
        var coords = e.get('coords');

        if (myPlacemark) {
            myPlacemark.geometry.setCoordinates(coords);

            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', function () {
                
                getAddress(myPlacemark.geometry.getCoordinates());
                setLatLongInput(myPlacemark.geometry.getCoordinates()[0], myPlacemark.geometry.getCoordinates()[1]);
            });

        } else {

            myPlacemark = createPlacemark(coords);
            myMap.geoObjects.add(myPlacemark);

            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', function () {
                
                getAddress(myPlacemark.geometry.getCoordinates());
                setLatLongInput(myPlacemark.geometry.getCoordinates()[0], myPlacemark.geometry.getCoordinates()[1]);
            });
        }

        setLatLongInput(coords[0], coords[1]);

        getAddress(coords);

    });

    //получить координаты
    function getCoords(address) {
        ymaps.geocode(address, {results: 1}).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);
            coords = firstGeoObject.geometry.getCoordinates();

            setLatLongInput(coords[0], coords[1]);
            
            if (myPlacemark) {
                myPlacemark.geometry.setCoordinates(coords);
                setPlacemark(address);
            } else {
                myPlacemark = createPlacemark(coords);
                myMap.geoObjects.add(myPlacemark);
                setPlacemark(address);
            }
            
            myMap.setCenter(coords);
        });
    }

    //поставить метку на карте
    function setPlacemark(address) {
        myPlacemark.properties.set({
            iconCaption: [address],
            balloonContent: address
        });
        myPlacemark.options.set({
            iconCaptionMaxWidth  : [250],
        });
    }

    // Создание метки.
    function createPlacemark(coords) {
        return new ymaps.Placemark(coords, {
            iconCaption: 'поиск...'
        }, {
            preset: 'islands#violetDotIconWithCaption',
            draggable: true
        });
    }
    
    // Определяем адрес по координатам (обратное геокодирование).
    function getAddress(coords) {
        myPlacemark.properties.set('iconCaption', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);

            var address = firstGeoObject.getAddressLine();

            setPlacemark(address);

            setAddressInput(address);
        });
    }


    //записать адрес в инпут
    function setAddressInput(address) {
        $('#suggest').val(address);
    }

    //записать долготу и широту в инпут
    function setLatLongInput(latitude, longitude) {
        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
    }

}

//поставим центр карты на город
function setCenter(city) {
    //найдем координаты города
    ymaps.geocode(city, {results: 1})
        .then(function (res) { 
            myMap.setCenter(res.geoObjects.get(0).geometry.getCoordinates(), 10);
        })
}
