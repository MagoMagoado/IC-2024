function fetchData() {
    var url = 'http://localhost/IC-2024/site/php/list_files.php';
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var values = data.map(function(item) {
                return {
                    name: item.col,
                    date: item.id
                };
            });

            var listOptions = {
                valueNames: ['name', 'date', 'fulltext'],
                item: '<li><p class="date"></p> <p class="name"></p></li>',
                page: 9,
                pagination: true,
            };

            new List('all-files', listOptions, values);
        },
        error: function(error) {
            console.error('Erro ao buscar dados: ' + error);
        }
    });
}

fetchData();

$('.search').on('click', function(){
    $('#search-icon').addClass("search-icon-active");
});
$('.next').on('click', function(){
    $('.pagination .active').next().trigger('click');
});

$('.prev').on('click', function(){
    $('.pagination .active').prev().trigger('click');
});

// listObj.add({
//     name: "Gustaf Lindqvist",
//     date: '20/03/2003',
// });

$(document).on('click', '.list li', function() {

    $('.list li').css('background-color', '#d6d4d4');
    $(this).css('background-color', '#5279c04d');
    // Captura o conteúdo do atributo 'fulltext' do elemento clicado
    let fulltext = $(this).find('.name').text();

    
    $('.alert').fadeOut(200, function() {
        setTimeout(function () {
            $("#full-file").css("display", "flex");
            $("#full-file").addClass("is-open");
            $('#full-file p').text(fulltext);
        }, 350);
    });

    setTimeout(function () {
        $('#full-file p').text(fulltext);
    }, 100);
});