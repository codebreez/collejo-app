var Collejo = Collejo || {
    settings: {
        alertInClass: 'bounceInDown',
        alertOutClass: 'fadeOutUp'
    },
    templates: {},
    form: {},
    link: {},
    modal: {},
    dynamics: {},
    browser: {},
    components: {},
    ready: []
};

$(function() {
    $.each(Collejo.ready, function(i, f) {
        f($(document));
    });
});