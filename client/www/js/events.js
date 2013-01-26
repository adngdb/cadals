define(['./config'], function (config) {
    var events = $('.events').get(0);

    function open(){
        events.open();
        $.get(config.host + 'lists', function (data) {
            console.log(data);

        });
    }

    return {
        'open': open
    };
});
