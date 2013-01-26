define(['./config'], function (config) {
    var giftlist = $('.gifts').get(0);

    function open(){
        giftlist.open();
    }

    return {
        'open':open
    }
});
