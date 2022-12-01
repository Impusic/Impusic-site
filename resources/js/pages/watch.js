$(document).ready(function() {
    var id = $('#musicId').text();
    const settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://deezerdevs-deezer.p.rapidapi.com/track/"+id,
        "method": "GET",
        "headers": {
            "X-RapidAPI-Key": "bec52cf648msh1ee493ff3e8a89ep1f42ddjsn4cfc0ac57e5f",
            "X-RapidAPI-Host": "deezerdevs-deezer.p.rapidapi.com"
        }
    };
    
    $.ajax(settings).done(function (response) {
        // SETA O TITLE E TÍTULO DAS MÚSICA
        $('#musicTitle').text(response.title);
        $('#musicTitle').attr('title', response.title);

        // SETA O TITLE E O NOME DO ARTISTA
        $('#musicArtist').text(response.artist.name);
        $('#musicArtist').attr('title', response.artist.name);

        // SETA O TITLE E O NOME DO ALBUM
        $('#musicCover').attr('src', response.album.cover);
        $('#musicCover').attr('title', response.album.title);

        // SETA O TITLE E O LINK DA MÚSICA
        $('#musicLink').attr('href', response.link);

        $('.musicDeezer').css('display', 'grid');
    });

    var videoDescriptionLink = $(".videoDescriptionLink");
    if(videoDescriptionLink.length > 0){
        for(var i = 0; i < videoDescriptionLink.length; i++) {
            var textEdited = videoDescriptionLink.eq(i).text().substr(0, 40)+'...';
            videoDescriptionLink.eq(i).text(textEdited);
        }
    }    
});

function openMoreOptions(id){
    var moreOptions = $('#moreOptions-'+id);
    if(moreOptions.hasClass('hidden')){
        moreOptions.removeClass('hidden');
    } else {
        moreOptions.addClass('hidden');
    }
}

function verifyEditComment(id){
    var text = $('#textEdit-'+id).val();
    var button = $('#buttonEdit-'+id);

    if(text !== ''){
        button.addClass('buttonEditConfirm');
    }else{
        button.removeClass('buttonEditConfirm');
    }
}

function editCommentForm(id, action){
    var textComment = $('#text-comment-'+id);
    var dateComment = $('#date-comment-'+id);
    var form = $('#form-edit-comment-'+id);

    if(action == 'open'){
        textComment.css('display', 'none');
        dateComment.css('display', 'none');
        form.removeClass('hidden');
    }else if(action == 'close'){
        textComment.css('display', 'block');
        dateComment.css('display', 'block');
        form.addClass('hidden');
    }
}