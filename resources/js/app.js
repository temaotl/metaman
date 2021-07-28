require('./bootstrap');

$(document).on('click', '#menu', function() {
    $('#navigation').toggleClass('hidden')
    $('#profile').toggleClass('hidden')
    $('#open-menu').toggleClass('hidden')
    $('#close-menu').toggleClass('hidden')
});

$(document).on('click', 'tbody.clickable tr', function() {
    var href = $(this).find('a.link').attr('href')
    if(href)
    {
        window.location = href
    }
});

$('.checkable tr').on('click', function(event) {
    if (event.target.type !== 'checkbox') {
        $(':checkbox', this).trigger('click')
    }
});

$('#checkAll').on('click', function() {
    $('input:checkbox').not(this).prop('checked', this.checked)
});

$('.open-modal').on('click', function(event) {
    event.preventDefault()
    var target = $(this).attr('data-target')
    $('#'+target+'-modal').toggleClass('hidden')
});

$('.close-modal').on('click', function() {
    $('.modal-overlay').each(function() {
        $(this).addClass('hidden')
    });
});