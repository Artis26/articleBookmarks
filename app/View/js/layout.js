$(window).scroll(function(){
    if($(document).scrollTop() > 100){
        $('nav').addClass('animate');
    }else{
        $('nav').removeClass('animate');
    }
})

function goToBookmarks() {
    var savedArticles = JSON.parse(localStorage.getItem("savedArticles"));

    $.post('bookmarks', {all: savedArticles});
    console.log('s', savedArticles);
}
