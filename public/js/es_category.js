

function showContentCategory(field) {
    html(field, '');
    var numCat = getVal(category);
    if (numCat != '') {
        var url = '/ajax/getcontentofcategory/' + numCat;

        axios.get(url).then(function (response) {
            html(field, response.data);
        }).catch(function (error) {
            console.log(error);
        });
    }
}

/*##################################
####################################
####        CONSIGNE        ########
####################################
##################################*/
let categoryContent = $('#contentofdescription');

if (document.getElementById("backpack_new_category")) {
    var category = $('#backpack_new_category');
} else {
    var category = $('#backpack_category');
    showContentCategory(categoryContent);
}


// Passe le select en required
required(category);

category.change(function () {
    showContentCategory(categoryContent);
});