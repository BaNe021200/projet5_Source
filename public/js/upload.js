function unhideForm()
{
    var button = document.getElementById('uploadButton');
    var hiddenForm = document.getElementById('uploadFormHidden')
    button.addEventListener('click', function (e) {
        e.preventDefault();
        hiddenForm.style.visibility = "visible";

    })
}



/*function showModal() {


  var modal = document.getElementById("Succes");
 modal.addEventListener("load",function () {
    // showModal("succes");
 });
        //$('#Succes').modal('show');
        //$('#Succes').modal({ show: false});
}*/

/*$(function () {

    var $button;
    $button= $('#submitButton');

    $button.on('click', function () {
        $('#Succes').modal('show');
    });


});*/




unhideForm();

