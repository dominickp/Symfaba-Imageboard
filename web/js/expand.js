$( document ).ready(function() {


    $(".thread").on( "click", "a.replyImage", function() {
        // Put the link target in a variable tio replace in the image
        var fullSize = $(this).attr("href");
        // Save the thumbnail in an attribute for later
        var thumbnail = $(this).children("img").attr("src");
        // Replace in the child image
        $(this).children("img").attr("src", fullSize);
        $(this).children("img").attr("data-thumbnail", thumbnail);

        // Swap styles
        $(this).removeClass("replyImage");
        $(this).addClass("largeInlineImage");

        // Disable default link functionality
        return false
    });

    $(".thread").on('click', ".largeInlineImage", function(){

        var thumbnail = $(this).children("img").attr("data-thumbnail");

        $(this).children("img").attr("src", thumbnail);

        // Swap styles
        $(this).removeClass("largeInlineImage");
        $(this).addClass("replyImage");

        // Disable default link functionality
        return false

    });

});

