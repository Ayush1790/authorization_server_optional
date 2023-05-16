function updateData(id) {
    $.ajax({
        url: 'update',
        data: 'id=' + id,
        type: 'post',
        datatype: 'json'
    }).done(function () {
        window.location = 'update';
    })
}

function deleteData(id) {
    $.ajax({
        url: 'delete',
        data: 'id=' + id,
        type: 'post',
        datatype: 'json'
    }).done(function () {
        window.location = 'view';
    })
}
let count = 1;
function addMeta() {
    $(".data").append("<p class='d-flex' id='metaData_" + count + "'><select name='metaKey" + count + "' class='form-control text-center m-1'><option disabled>Choose any One</option><option>color</option><option>size</option><option>fabric</option></select><input type='text' name='metaValue" + count + "' class='form-control m-1' placeholder='value'><input type='text' name='metaValuePrice" + count + "' class='form-control m-1' placeholder='amount'><input type='button' value='+' id='add' onclick=addMeta() class='btn btn-primary m-1'><input type='button' value='-' id='sub' onclick=subMeta(this) class='btn btn-primary m-1'></p>");
    count++;
}

function subMeta(val) {
    $(val).parent().remove();
}
function addAdditional() {
    $('.additional').append("<p class='d-flex' ><input type='text' name='additionaldata" + count + "' placeholder='additional key' class='form-control m-1'><input type='text' name='additionaldatavalue" + count + "' placeholder='additional Value' class='form-control m-1'> <input type='button' value='+' id='add' onclick=addAdditional() class='btn btn-primary m-1'><input type='button' value='-' id='sub' onclick=subAdditional(this) class='btn btn-primary m-1'> </p>");
}
function subAdditional(val) {
    $(val).parent().remove();
}