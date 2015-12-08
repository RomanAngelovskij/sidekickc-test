jQuery(document).ready(function($){
    if (comparedText.length > 0){
        $("#compared-wrap").html(comparedText);
    }

    $("span.changed").on('mouseenter', function(){
        var original = $(this).data("original");
        $(this).html(original);
    })

    $("span.changed").on('mouseout', function(){
        var changed = $(this).data("changed");
        $(this).html(changed);
    })

    $('[data-toggle="tooltip"]').tooltip()
})
