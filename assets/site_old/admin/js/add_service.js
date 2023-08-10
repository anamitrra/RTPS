$(document).ready(function() {

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


});