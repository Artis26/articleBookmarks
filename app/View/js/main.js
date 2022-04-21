function saveArticle(key) {
    var article_id = $('#id'+key).val();

    const articles = JSON.parse(localStorage.getItem('savedArticles'));
    const filtered = articles.filter(article => article.id === article_id);
    if (filtered.length >= 1) return alert('Raksts jau ir pievienots Jūsu grāmatzīmēm!')

    if (localStorage.getItem("savedArticles") === null) {
        var savedArticles = [];
    } else {
        var savedArticles = JSON.parse(localStorage.getItem("savedArticles"));
    }
    var article_id = {'id' : article_id, 'channel_id': $('#channel_id'+key).val(),
        'title': $('#title'+key).val(), 'url': $('#url'+key).val(), 'picture': $('#picture'+key).val()};


    savedArticles.push(article_id);
    localStorage.setItem('savedArticles', JSON.stringify(savedArticles));
    console.log('s', savedArticles);
    var all = localStorage.getItem('savedArticles');
    $.post('bookmarks', {all: all});
}

function removeArticle(id) {
    var result = confirm('Vai tiešām vēlies idzēst rakstu ar id:' + id + ' ?');
    if(!result){
        return false;
    }
    const articles = JSON.parse(localStorage.getItem('savedArticles'));
    const filtered = articles.filter(article => article.id !== id);
    localStorage.setItem('savedArticles', JSON.stringify(filtered));
    var all = localStorage.getItem('savedArticles');
    $.post('bookmarks', {all: all}).done( function(){ window.location.reload(true); });
    displayUniqueChannels();
}
