define(['./config'], function (config) {
    var events = $('.events').get(0);

    function compare(a,b){
        if (a.title < b.title)
            return -1;
        if (a.title > b.title)
            return 1;
        return 0;
    }

    function open(){
        events.open();
        $.get(config.host + 'lists', function (data) {
            var listsToShow = [];
            console.log(data);

            for (var i=0; i < data.owned_lists.length; i++){
                listsToShow.push({
                    'title': data.owned_lists[i].name,
                    'desc': data.owned_lists[i].description
                });
            };

            for (var i=0; i < data.accessible_lists.length; i++){
                listsToShow.push({
                    'title': data.accessible_lists[i].name,
                    'desc': data.accessible_lists[i].description
                });
            };

            // short list by title
            listsToShow.sort(compare);

            for (var i=0; i < listsToShow.length; i++){
                events.add({
                    'title': listsToShow[i].title,
                    'desc': listsToShow[i].desc
                });
            };


        });
    }

    return {
        'open': open
    };
});
