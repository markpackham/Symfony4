/*
Code to delete articles
 */
let articles = document.getElementById('articles');

if (articles) {
    articles.addEventListener('click', (e) => {
        if (e.target.className === 'btn btn-danger delete-article') {
            if (confirm('Are you sure?')) {
                let id = e.target.getAttribute('data-id');

                fetch(`/articles/delete/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}