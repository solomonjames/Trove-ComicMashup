var App = {};

App.getRandom = function() {
    $.post("/random.php")
        .success(function(data) {
            $("#comic").html(data);
        });
    
    return false;
}