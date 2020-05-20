$(document).ready(function(){
    var jsonStr = $(".json-valido pre").text();
    var jsonObj = JSON.parse(jsonStr);
    var jsonPretty = JSON.stringify(jsonObj, null, '\t');
    $(".json-valido pre").text(jsonPretty);
    $(document).on("click", ".copiar", function(){
        var cadena_json = $(".json-valido pre").text();
        $("#json").text(cadena_json);
    });
    $(document).on("submit", "#calculadora", function(e){
        e.preventDefault();
        var value_json = $("#json").val();
        try {
            json = jQuery.parseJSON(value_json);
            $('.alert-json').removeClass('json-invalid');
        } catch (e) {
            $('.alert-json').addClass('json-invalid');
            return;
        }
        var data = $("#calculadora").serialize();
        $.ajax({
            type: 'POST',
            url: 'calcula.php',
            data: data,
            success: function(data) {
                data = $.parseJSON(data);
                var error = data.error, show_error = data.show_error, content = data.content;
                $(".show_response").remove();
                if(error != ""){
                    $("#show_error").prepend(show_error);
                    setTimeout(function() {
                        $(".show_error").remove();
                    }, 5000);
                }else{
                    $("#show_result").html(content);
                }
            }
        });
        return false;
    });
});