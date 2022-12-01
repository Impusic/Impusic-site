function locationVerification() {
    var e = document.body.scrollTop || document.documentElement.scrollTop,
    o = e / (document.documentElement.scrollHeight - document.documentElement.clientHeight) * 100;
    if(e > 0){
        $('#header').addClass("sticky");
    }
    else{
        $('#header').removeClass("sticky");
    }
}

window.onload = function() {
    locationVerification()
}

window.onscroll = function() {
    locationVerification()
}


function openForm(id){
    $('#'+id).css("display", "grid");
}
function closeForm(id){
    $('#'+id).css("display", "none");
}

$.urlParam = function(name){
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
}

$(document).ready(function(){
    var form = $.urlParam('form');
    if(form == 'login'){
        openForm('loginForm');
    }else if(form == 'register'){
        openForm('registerForm');
    }
});

function verifyLoginButton(){
    var email = $('#emailLogin').val();
    var password = $('#passwordLogin').val();
    console.log(email);
    console.log(password);

    if(email !== '' && password !== ''){
        $('#publishLoginButton').removeClass('publishButtonDisabled');
    }else{
        $('#publishLoginButton').addClass('publishButtonDisabled');
    }
}
function verifyRegisterButton(){
    var name = $('nameRegister').val();
    var email = $('#emailRegister').val();
    var password = $('#passwordRegister').val();
    var user = $('#userRegister').val();

    if(name !== '' && email !== '' && password !== '' && user !== ''){
        $('#publishRegisterButton').removeClass('publishButtonDisabled');
    }else{
        $('#publishRegisterButton').addClass('publishButtonDisabled');
    }
}