function openInputIcon(id){
    $('#'+id).click();
}

$('#inputIcon').change(function(){
    if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#iconProfile').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    }
});

$('#inputBanner').change(function(){
    if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#bannerProfile').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    }
});