function loadLivres(query,select)
{
    if(query == "")
    {
        $(select).empty();
        return;
    }
    $.ajax({
        url: "https://www.googleapis.com/books/v1/volumes?q="+query,
        dataType: "json",
        type: 'GET',
        success: function(data) {
            $(select).empty();
            for(i=0;i<data.items.length;i++){
                $("<option></option>")
                    .attr("value",data.items[i].volumeInfo.title)
                    .append(data.items[i].volumeInfo.title)
                    .appendTo($(select));
            }
        },
    });
}

function loadMusique(query,select)
{
    if(query == "")
    {
        $(select).empty();
        return;
    }
    $.ajax({
        url: "https://cors-anywhere.herokuapp.com/https://api.deezer.com/search/artist?q="+query,
        dataType: "json",
        type: 'GET',
        success: function(data) {
            $(select).empty();
            for(i=0;i<data.data.length;i++){
                $("<option></option>")
                    .attr("value",data.data[i].name)
                    .append(data.data[i].name)
                    .appendTo($(select));
            }
        },
    });
}

function loadOccupations(url,select)
{
    $.ajax({
        url: url,
        dataType: "json",
        type: 'GET',
        success: function(data) {
            for(i=0;i<data.length;i++){
                $("<option></option>")
                    .attr("value",data[i])
                    .append(data[i])
                    .appendTo($(select));
            }
        },
    });
}