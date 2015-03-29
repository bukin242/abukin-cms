$(function(){
    $(".colorbox").colorbox({maxWidth:'100%', maxHeight:'100%'});
    $(".ymaps").colorbox({width:"80%", height:"80%", iframe:true, scrolling:false});
    $(".colorbox").colorbox({
        onComplete: function(){

            if(this.id)
            {
                $("#cboxLoadedContent").append('<a href="'+this.id+'" onclick="this.style.width = \'700px\" style="display:block; position:absolute; top:0px; right:0px;" target="'+this.target+'" title="Открыть"><img src="/styles/js/jquery.colorbox/images/arrow_expand.png" alt="Открыть"/></a>');

            }

        }
    });
});
