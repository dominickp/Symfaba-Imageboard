$( document ).ready(function() {

    // Quick reply to post
    $(".threadId > a").click(function(){
        $replyId = $(this).attr("data-id");
        $(".postBox textarea").append('>>'+$replyId+'\n');
    })

});