function displayUniqueChannels() {
    const articles = JSON.parse(localStorage.getItem('savedArticles'));
    const uniqueIds = [];
    const filtered = articles.filter(article => {
        const isDuplicate = uniqueIds.includes(article.channel_id);

        if (!isDuplicate) {
            uniqueIds.push(article.channel_id);
            return true;
        }
    });

    let list = document.getElementById("filters")
    uniqueIds.forEach(function (i) {
        let li = document.createElement("li");
        var a = document.createElement("a");
        a.textContent = (i);
        a.setAttribute('href', '/bookmarks/' + i);
        li.appendChild(a);
        list.appendChild(li);
    })
}

window.onload = function() {
    displayUniqueChannels();
};
