define(['./config'], function (config) {
    var events = $('.events').get(0);

    function open(){
        events.open();
        $.get(config.host + 'lists', function (data) {
            console.log(data);
            for (var i=0; i < data.owned_lists.length; i++){
                events.add({
                    'title': data.owned_lists[i].name,
                    'desc': data.owned_lists[i].description
                });
            };
        });
    }

    return {
        'open': open
    };
});
