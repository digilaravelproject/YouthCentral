<style>
    /* Styling to make CKEditor blend cleanly with Soft UI */
    .ck-editor__editable_inline {
        min-height: 300px;
        background-color: #fff !important;
        font-family: inherit;
        font-size: 14px;
        color: #495057;
    }
    .ck-toolbar {
        background: #f8f9fa !important;
        border-color: #d2d6da !important;
        border-radius: 4px 4px 0 0 !important;
    }
    .ck-editor__main .ck-editor__editable {
        border-color: #d2d6da !important;
        border-top: none !important;
        border-radius: 0 0 4px 4px !important;
    }
</style>

<!-- CKEditor 5 Classic Build (Includes PasteFromOffice for MS Word tables) -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.querySelector('#long_description');
        if (textarea) {
            // Initialize CKEditor on the existing textarea
            ClassicEditor
                .create(textarea, {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                        'insertTable', '|',
                        'undo', 'redo'
                    ],
                    table: {
                        contentToolbar: [
                            'tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties'
                        ]
                    }
                })
                .then(editor => {
                    // Update textarea value immediately before form submission
                    const form = textarea.closest('form');
                    if(form){
                        form.addEventListener('submit', () => {
                            textarea.value = editor.getData();
                        });
                    }
                })
                .catch(error => {
                    console.error('CKEditor Initialization Error:', error);
                });
        }
    });
</script>
