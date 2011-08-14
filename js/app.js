var App = {};

App.getRandom = function() {
    $("#ajaxLoader").show();
    
    $.post("/random.php")
        .success(function(data) {
            $("#ajaxLoader").hide();
            $("#comic").html(data);
        });
    
    return false;
}