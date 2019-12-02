function updateActive(e) {
    if (!location.hash) {
        location.hash = '#songs';
    }
    var $q = $('a[href="' + location.hash + '"]');
    if ($q.length) {
        $q.click();
    } else {
        $('a[href="#songs"]').click();
    }
}

function updateUrl(event) {
    window.location.href = '#' + event;
}

$(document).ready(function () {
    const h = $('nav').height() + 0; // add 20 to create some space if needed
    $('body').animate({paddingTop: h});
    
    window.onload = updateActive;
});
