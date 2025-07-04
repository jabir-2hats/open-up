<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#posts-table').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('posts.data') }}",
                "type": "GET"
            },
            "pageLength": 12,
            "lengthMenu": [
                [12, 25, 50],
                [12, 25, 50]
            ],
            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'author.name',
                    name: 'author.name'
                },
                {
                    data: 'published_at',
                    name: 'published_at'
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'comments_count',
                    name: 'comments_count',
                },
                {
                    data: 'tags',
                    name: 'tags',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            "order": [
                [3, "desc"]
            ]
        });

        $(document).on('click', '.delete-btn', function() {
            let deleteModal = $('#delete_modal');
            deleteModal[0].showModal();
            let form = deleteModal.find('form');
            let postId = $(this).data('post-id');
            form.attr('action', `/posts/${postId}`);
        });

        $(document).on('click', '.comments-btn', function() {

            var postId = $(this).data('post-id');
            var modal = $('#comments_modal');
            var content = $('#comments_content');

            content.html('<span class="text-gray-400">Loading...</span>');

            modal[0].showModal();
            $.ajax({
                url: '/comments',
                type: 'GET',
                data: {
                    post_id: postId
                },
                dataType: 'json',
                success: function(response) {

                    let comments = response.comments || [];

                    if (comments.length === 0) {
                        content.html(
                            '<span class="text-gray-400">No comments yet.</span>');
                    } else {
                        content.html(comments.map(function(comment) {
                            return `
                        <div class='border rounded p-3 bg-gray-50'>
                            <div class='flex items-center mb-1'>
                                <span class='font-semibold mr-2'>${comment.user ? comment.user.name : 'Anonymous'}</span>
                                <span class='text-xs text-gray-400'>${comment.created_at}</span>
                            </div>
                            <div class='text-gray-800'>${comment.content}</div>
                        </div>
                    `;
                        }).join(''));
                    }
                },
                error: function() {
                    content.html(
                        '<span class="text-error">Error loading comments.</span>');
                }
            });
        });

    });
</script>
