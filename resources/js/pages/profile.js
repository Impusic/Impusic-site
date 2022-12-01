new ClipboardJS('#profile-share-button');
new ClipboardJS('#video-share-button');

function shareToClipboard(){
    var copyText = $('#profile-share-link');
    copyText.val(window.location.href);
}


function openMoreOptions(id){
    var moreOptions = $('#'+id);
    if(moreOptions.hasClass('hidden')){
        moreOptions.removeClass('hidden');
    } else {
        moreOptions.addClass('hidden');
    }
}

let moreDescription = 1;
function showAllAccountDescription(){
    var allAccountDescription = nl2br($('#allAccountDescription').text());
    var minAccountDescription = nl2br($('#minAccountDescription').text());

    var accountDescription = $('#accountDescription span');
    var seeMoreDescription = $('#seeMoreDescription');

    if(moreDescription === 1){
        accountDescription.html(allAccountDescription);
        seeMoreDescription.text('Ver Menos.');
        moreDescription = 0;
    }else{
        accountDescription.html(minAccountDescription);
        seeMoreDescription.text('Ver Mais.');
        moreDescription = 1;
    }
}

function nl2br(str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}