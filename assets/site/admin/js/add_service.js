$(document).ready(function () {

    $('.summernote-guidelines').summernote({

        placeholder: 'Please add guidelines...',
        tabsize: 2,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']],
        ],
        height: 200,
    });

    $('.summernote-notice').summernote({

        placeholder: 'Please add service-related notice...',
        tabsize: 2,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']],
        ],
        height: 200,
    });

    $('.summernote-requirements').summernote({

        placeholder: 'Please add requirements...',
        tabsize: 2,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']],
        ],
        height: 200,
    });

    $('.summernote-about').summernote({

        placeholder: 'Update About Section',
        tabsize: 2,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']],
        ],
        height: 200,
    });
    $('.summernote-faq').summernote({

        placeholder: 'Update FAQ Section',
        tabsize: 2,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']],
        ],
        height: 200,
    });
    $('.summernote-contact').summernote({

        placeholder: 'Update Contact Section',
        tabsize: 2,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']],
        ],
        height: 200,
    });
    $('.summernote-access').summernote({

        placeholder: 'Update Accessibility Section',
        tabsize: 2,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']],
        ],
        height: 200,
    });


    // Delete service document
    $('a.delete').on('click', function (e) {
        e.preventDefault();

        $('[name="doc_path"]').val($(this).data('doc_path'));

        $('#deleteModel').modal('show');
    });


    // Get all sub-categories for a particular selected category
    // Valina JS Code

    const catList = document.querySelector('select#cat_id');
    const subCatList = document.querySelector('select#sub_cat');

    let selectedCat = catList.value;
    if (selectedCat.trim().length) {
        getSubCategories(selectedCat.trim(), subCatList.dataset.subcat);
    }


    catList.addEventListener('change', function (event) {
        selectedCat = event.target.value;
        if (selectedCat.trim().length) {
            getSubCategories(selectedCat.trim());
        }

    });


});
 
async function getSubCategories(catID, selectedSubCat = '') {

    // console.log(baseURL);

    const response = await fetch(`${baseURL}site/admin/online/sub_categories_api/${catID}`);
    if (!response.ok) {
        alert("Couldn't get sub-categories. Please retry!");
    }
    const jsonData = await response.json();

    // console.log(jsonData, selectedSubCat);

    // Populate sub-cat list
    const subCatList = document.querySelector('select#sub_cat');
    if (!selectedSubCat.length) {
        subCatList.innerHTML = '<option value="">---please select one---</option>';
    }
    else {
        subCatList.innerHTML = '';
    }


    subCatList.innerHTML += jsonData.sub_categories.reduce(function (acc, val) {

        let opElm = '';
        if (val.en.trim() == selectedSubCat) {
            opElm = `<option value="${val.en.trim()}" selected >${val.en.charAt(0).toUpperCase() + val.en.slice(1)}</option>`
        }
        else {
            opElm = `<option value="${val.en.trim()}" >${val.en.charAt(0).toUpperCase() + val.en.slice(1)}</option>`
        }

        return acc + opElm;

    }, '');
}
