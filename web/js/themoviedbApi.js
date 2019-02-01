function loadFilms(query,select)
{
    url = "https://api.themoviedb.org/3/search/movie?api_key=2e5aee0d6e6ad27e13cf48eb3cd9b5dc&language=en-US&query="+query+"&page=1&include_adult=true";
    if(query == "")
        return;
    $.getJSON(url,function(movies)
    {
        $(select+" option").remove();
        //loop through countries..
        $.each(movies['results'],function(key,movie)
        {
            $("<option></option>")
                .attr("value",movie.title)
                .append(movie.title)
                .appendTo($(select));
        });
        // trigger "change" to fire the #state section update process


    });
}
function loadSeries(query,select)
{
    if(query == "")
        return;
    url = "https://api.themoviedb.org/3/search/tv?api_key=2e5aee0d6e6ad27e13cf48eb3cd9b5dc&language=en-US&query="+query+"&page=1&include_adult=true";
    $.getJSON(url,function(movies)
    {
        $(select+" option").remove();
        //loop through countries..
        $.each(movies['results'],function(key,movie)
        {
            $("<option></option>")
                .attr("value",movie.title)
                .append(movie.original_name)
                .appendTo($(select));
        });
        // trigger "change" to fire the #state section update process


    });
}