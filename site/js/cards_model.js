window.addEventListener("DOMContentLoaded", function(){
    literalString();
});

function literalString(){
    const cards = [
        {
            'titulo': 'LDA',
            'texto': "."
        },
        {
            'titulo': 'LSA',
            'texto': "."
        },
        {
            'titulo': 'Word2Vec',
            'texto': "."
        }
    ];

    function cardsHTML(model){
        return `
        <div class="card">
            <div class="tit-card">
                <img src="img/hashtag.png" alt="${model.titulo}">
                <h2 card-title>${model.titulo}</p>
            </div>
            <p class="text-card">${model.texto}</p>
            <a class="btn-card" href="#" role="button">Read More</a>
        </div>
        `;
    }

    document.getElementById('card-models-body').innerHTML+=`${cards.map((card) => cardsHTML(card)).join('')}`;
}
