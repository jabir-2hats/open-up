<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        let startDate = null;
        let endDate = null;
        const $postsTable = $('#posts-table');
        const $statusFilter = $('#status');
        const $commentsCountOperator = $('#comments_count_operator');
        const $commentsCount = $('#comments_count');
        const $tagsFilter = $('#tags');
        const $dateRangeInput = $('input[name="dates"]');
        const $deleteModal = $('#delete_modal');
        const $commentsModal = $('#comments_modal');
        const $commentsContent = $('#comments_content');


        function reloadPostsTable() {
            $postsTable.DataTable().ajax.reload();
        }

        function renderComment(comment) {
            return `
                <div class='border rounded p-3 bg-gray-50'>
                    <div class='flex items-center mb-1'>
                        <span class='font-semibold mr-2'>${comment.user ? comment.user.name : 'Anonymous'}</span>
                        <span class='text-xs text-gray-400'>${comment.created_at}</span>
                    </div>
                    <div class='text-gray-800'>${comment.content}</div>
                </div>
            `;
        }

        // --- Filters ---

        // Status, Comments Count
        $statusFilter.add($commentsCountOperator).add($commentsCount).on('change keyup', function() {
            if ($(this).is($commentsCount) && !$commentsCountOperator.val()) {
                return;
            }
            reloadPostsTable();
        });

        // Tags
       $.ajax({
            url: '{{ route('tags.data') }}',
            dataType: 'json',
            success: function(data) {
                const formattedData = Object.keys(data).map(key => ({
                    id: key,
                    text: data[key]
                }));

                $tagsFilter.select2({
                    placeholder: "Select Tags",
                    allowClear: true,
                    data: formattedData
                });
            },
            error: function() {
                console.error("Error fetching tags data.");
                $tagsFilter.select2({
                    placeholder: "Select Tags",
                    allowClear: true,
                });
            }
        });
        $tagsFilter.on('change', reloadPostsTable);

        // Published At
        $dateRangeInput.daterangepicker({
            maxDate: moment(),
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $dateRangeInput.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                'DD/MM/YYYY'));
            startDate = picker.startDate.format('YYYY-MM-DD');
            endDate = picker.endDate.format('YYYY-MM-DD');
            reloadPostsTable();
        });

        $dateRangeInput.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            startDate = null;
            endDate = null;
            reloadPostsTable();
        });


        // --- Datatable ---
        const postsDataTable = $postsTable.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "language": {
                "searchPlaceholder": 'Search Title or Author'
            },
            "ajax": {
                "url": "{{ route('posts.data') }}",
                "type": "GET",
                "data": function(d) {
                    Object.assign(d, {
                        start_date: startDate,
                        end_date: endDate,
                        tags: $tagsFilter.val(),
                        status: $statusFilter.val(),
                        comments_count_operator: $commentsCountOperator.val(),
                        comments_count: $commentsCount.val(),
                    });
                },
            },
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
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


        // --- Modals ---

        // Delete Modal
        $(document).on('click', '.delete-btn', function() {
            $deleteModal[0].showModal();
            const $form = $deleteModal.find('form');
            const postId = $(this).data('post-id');
            $form.attr('action', `/posts/${postId}`);
        });

        // Comments Modal
        $(document).on('click', '.comments-btn', function() {
            const postId = $(this).data('post-id');

            $commentsContent.html('<span class="text-gray-400">Loading...</span>');
            $commentsModal[0].showModal();

            $.ajax({
                url: '/comments',
                type: 'GET',
                data: {
                    post_id: postId
                },
                dataType: 'json',
                success: function(response) {
                    const comments = response.comments || [];
                    if (comments.length === 0) {
                        $commentsContent.html(
                            '<span class="text-gray-400">No comments yet.</span>');
                    } else {
                        $commentsContent.html(comments.map(renderComment).join(''));
                    }
                },
                error: function() {
                    $commentsContent.html(
                        '<span class="text-error">Error loading comments.</span>');
                }
            });
        });

    });
</script>
