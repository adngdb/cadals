define(['./config'], function (config) {
    var event = $('.detail').get(0);

    function open(){
        event.open();
    }

    function prepare() {
        event.render = function(item) {
            //console.log(item.get('id'));
            $('.title', this).html(item.get('title'));
            $('.desc', this).html(item.get('desc'));
            $('.date', this).html(item.get('date'));
        };
    }

    return {
        'open': open,
        'prepare' : prepare
    };
});
