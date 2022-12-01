function openInputThumbnail(){
    $("#selectThumbnailInput").click();
}

function openVideoThumbnail(){
    $("#selectVideoInput").click();
}

$('#videoTitle').keypress(function(e){
    e.stopPropagation();
});

function setPreviewThumbnail(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(thumbnailPreview).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        $('.showThumbnail').css('display', 'block');
    }
}

$("#selectThumbnailInput").change(function(){
    setPreviewThumbnail(this);
});

$(document).on("change", "#selectVideoInput", function(evt) {
    var $source = $('#videoPreview');
    $source[0].src = URL.createObjectURL(this.files[0]);

    $source.parent()[0].load();
    $('.showThumbnail').css('display', 'block');
    $('.playerWarning').css('display', 'block');
});

// PROCURA A MÚSICA NA API DO DEEZER VIA RAPIDAPI E RETORNA OS RESULTADOS
function search(){
    var search = $('#musicName').val();

    const settings = {
        "async": true,
        "crossDomain": true,
        "url": `https://deezerdevs-deezer.p.rapidapi.com/search?q=${search}`,
        "method": "GET",
        "headers": {
            "X-RapidAPI-Key": "bec52cf648msh1ee493ff3e8a89ep1f42ddjsn4cfc0ac57e5f",
            "X-RapidAPI-Host": "deezerdevs-deezer.p.rapidapi.com"
        }
    };
    
    $.ajax(settings).done(function (response) {
        $('#musics').empty();

        let maxCount = 0;
        if(response.total > 6){
            maxCount = 6;
        }else{
            maxCount = response.total;
        }

        if(response.data == undefined || response.data.length <= 0){
            $('#musics').empty();
            return false;
        }
        for(let count = 0; count < maxCount; count++){
            var musicDiv = document.createElement("div");
            musicDiv.setAttribute("id", "music"+count);
            document.getElementById("musics").appendChild(musicDiv);

            // Adiciona o select box da música
            var musicSelectBox = document.createElement("input");
            musicSelectBox.type = "radio";
            musicSelectBox.name = "musicId";
            musicSelectBox.classList.add('musicSelectBox');
            musicSelectBox.setAttribute('value', response.data[count].id);
            musicDiv.appendChild(musicSelectBox);

            $("#music"+count).click(function (e) {    
                if (!$(e.target).is('input:radio')) {
                    var $checkbox = $(this).find('input:radio');
                    $checkbox.prop('checked', !$checkbox.prop('checked'));
                    $('.musicSelected').removeClass('musicSelected');
                    $checkbox.prop('checked', $('#music'+count).addClass("musicSelected"));
                }
            });

            // Adiciona a DIV 'musicThumbnail'
            var musicThumbnail = document.createElement("div");
            musicDiv.appendChild(musicThumbnail);
            musicThumbnail.classList.add('musicThumbnail');

            // Adiciona a Thumbnail da música na DIV 'musicThumbnail'
            var musicThumbnailImg = document.createElement("img");
            musicThumbnail.appendChild(musicThumbnailImg);
            musicThumbnailImg.src = response.data[count].album.cover;

            // Adiciona a DIV 'musicInfo'
            var musicInfo = document.createElement("div");
            musicDiv.appendChild(musicInfo);
            musicInfo.classList.add('musicInfo');

            // Adiciona o título da música na DIV 'musicInfo'
            var musicTitle = document.createElement("p");
            musicInfo.appendChild(musicTitle);
            musicTitle.classList.add('musicTitle');
            musicTitle.textContent = response.data[count].title;

            // Adiciona o artista da música na DIV 'musicInfo'
            var musicArtist = document.createElement("p");
            musicInfo.appendChild(musicArtist);
            musicArtist.classList.add('musicArtist');
            musicArtist.textContent = response.data[count].artist.name;

            // Adiciona o código para o link da música
            var musicLinkText = document.createElement("p");
            musicInfo.appendChild(musicLinkText);
            musicLinkText.classList.add('musicLink');

            //Adiciona a logo do deezer na texto do link
            var musicLogo = document.createElement("img");
            musicLogo.classList.add('deezerLogo');
            musicLogo.src = '../tcc/resources/svg/deezerSet.svg';
            musicLogo.alt = 'Deezer';
            musicLogo.title = 'Deezer';
            musicLinkText.appendChild(musicLogo);

            // Adiciona o link da música na DIV 'musicInfo'
            var musicLink = document.createElement("a");
            musicLinkText.appendChild(musicLink);
            musicLink.href = response.data[count].link;
            musicLink.target = "_blank";
            musicLink.title = `Ouvir '${response.data[count].title}' no Deezer!`;
            musicLink.textContent = "Ouvir Música";
        }
    });
}

$('#musicName').keydown(delayEvent(function(e) {
    search();
}, 200));

function delayEvent( fn, delay ) {
    var timer = null;
    // this is the actual function that gets run.
    return function(e) {
        var self = this;
        // if the timeout exists clear it
        timer && clearTimeout(timer);
        // set a new timout
        timer = setTimeout(function() {
        return fn.call(self, e);
        }, delay || 200);
    }
}

function verifyUploadInputs(){
    var video = $('#selectVideoInput').val();
    var title = $('#videoTitle').val();
    var thumbnail = $('#selectThumbnailInput').val();

    if(isNaN(video) === true && isNaN(title) === true && isNaN(thumbnail)){
        $('#publishButtonUpload').removeClass('publishButtonDisabledUpload');
    }else{
        $('#publishButtonUpload').addClass('publishButtonDisabledUpload');
    }
}