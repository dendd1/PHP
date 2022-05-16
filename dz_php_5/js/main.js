<<<<<<< HEAD
$( document ).ready(function() {
    let addr = $('#addressInput'),
        addrLbl = $('#addressLabel'),
        addrForm = $('#addressForm'),
        fullAddress = $('#fullAddress'),
        coordinates = $('#coordinates'),
        metro = $('#metro');

    addrForm.submit( function (e) {
        e.preventDefault();

        let addrVal = addr.val().trim();

        let formData = new FormData();
        formData.append('address', addrVal);
        $.ajax({
            url: 'src/yandex.php',
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            success(data) {
                fullAddress.text("Структурированный адрес: " + data['address']);
                coordinates.text("Координаты: " + data['coordinates']);
                metro.text("Ближайшее метро: " + data['metro'])
            }
        });
    });
=======
$( document ).ready(function() {
    let addr = $('#addressInput'),
        addrLbl = $('#addressLabel'),
        addrForm = $('#addressForm'),
        fullAddress = $('#fullAddress'),
        coordinates = $('#coordinates'),
        metro = $('#metro');

    addrForm.submit( function (e) {
        e.preventDefault();

        let addrVal = addr.val().trim();

        let formData = new FormData();
        formData.append('address', addrVal);
        $.ajax({
            url: 'src/yandex.php',
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            success(data) {
                fullAddress.text("Структурированный адрес: " + data['address']);
                coordinates.text("Координаты: " + data['coordinates']);
                metro.text("Ближайшее метро: " + data['metro'])
            }
        });
    });
>>>>>>> 8764aae0624cd60e8f0156d03f3a25ae7e48a593
});