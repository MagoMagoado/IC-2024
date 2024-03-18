var values = [{
    name: 'Jonny Strömberg',
    date: '20/03/2003'
},
{
    name: 'Jonas Arnklint',
    date: '20/03/2003'
},
{
    name: 'Jonas Arnklint',
    date: '20/03/2003'
},
{
    name: 'Jonas Arnklint',
    date: '20/03/2003'
},

{
    name: 'Martina Elm',
    date: '20/03/2003'
}];
var paginationOptions = {
    name: "pagination",
    paginationClass: "pagination"
};
var listOptions = {
    valueNames: ['name', 'date'],
    item: '<li><p class="name"></p><p class="date"></p></li>',
    page: 9,
    pagination: true,
};

var listObj = new List('all-files', listOptions, values);

$('.search').on('click', function(){
    $('#search-icon').addClass("search-icon-active");
});
$('.next').on('click', function(){
    $('.pagination .active').next().trigger('click');
});

$('.prev').on('click', function(){
    $('.pagination .active').prev().trigger('click');
});

listObj.add({
    name: "Gustaf Lindqvist",
    date: '20/03/2003'
});