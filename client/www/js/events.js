define(['./config'], function (config) {
    var events = $('.events').get(0);

    function compare(a,b){
        if (a.date < b.date)
            return 1;
        if (a.date > b.date)
            return -1;
        return 0;
    }

    function open(){
        events.open();
        $.get(config.host + 'lists', function (data) {
            var listsToShow = [];

            for (var i=0; i < data.owned_lists.length; i++){
                listsToShow.push({
                    'id': data.owned_lists[i].id,
                    'title': data.owned_lists[i].name,
                    'date': data.owned_lists[i].date,
                    'desc': data.owned_lists[i].description
                });
            };

            for (var i=0; i < data.accessible_lists.length; i++){
                listsToShow.push({
                    'id': data.accessible_lists[i].id,
                    'title': data.accessible_lists[i].name,
                    'date': data.accessible_lists[i].date,
                    'desc': data.accessible_lists[i].description
                });
            };

            // short list by title
            listsToShow.sort(compare);
            //console.log(listsToShow);
            for (var i=0; i < listsToShow.length; i++){
                events.add(listsToShow[i]);
            };
        });
    }

    return {
        'open': open
    };
});
