<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.delete-btn').on('click', function() {
            let deleteModal = $('#delete_modal');
            deleteModal[0].showModal()
            let form = deleteModal.find('form');
            let commentId = $(this).data('comment-id');
            form.attr('action', `/comments/${commentId}`);
        });

        $('.edit-btn').on('click', function() {
            let editModal = $('#edit_modal');
            editModal[0].showModal();
            let form = $('#edit_form');
            let commentId = $(this).data('comment-id');
            let commentContent = $(this).data('comment-content');
            form.attr('action', `/comments/${commentId}`);
            $('#edit_content').val(commentContent);
        });

        $('#edit-submit-btn').on('click', function() {
            let form = $('#edit_form');
            if (form[0].checkValidity()) {
                form.submit();
            } else {
                form[0].reportValidity();
            }
        });

    });
</script>
