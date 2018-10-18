require('bootstrap');

require('../css/app.scss');

const $ = require('jquery');

$(document).ready(function () {
    $('.custom-file-input').on('change',function(){
        var fileName = $(this).val();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});

