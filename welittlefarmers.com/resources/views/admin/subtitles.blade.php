<!DOCTYPE html>
<html>

<head>
    <title>Subtitle Management - Little Farmers Academy</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-completed {
            background-color: #28a745;
            color: white;
        }

        .status-processing {
            background-color: #ffc107;
            color: black;
        }

        .status-pending {
            background-color: #6c757d;
            color: white;
        }

        .status-failed {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">📝 Subtitle Management</h1>

        <!-- Statistics Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h4>Course Statistics</h4>
                <div class="row" id="statsContainer">
                    <div class="col-md-12 text-center">
                        <p class="text-muted">Select a course to see statistics</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Generation -->
        <div class="card mb-4">
            <div class="card-body">
                <h3>🚀 Generate All Subtitles</h3>
                <p class="text-muted">Generate subtitles for all lessons in a course at once</p>
                <form id="bulkGenerateForm">
                    <div class="mb-3">
                        <label class="form-label">Select Course:</label>
                        <select name="course_id" class="form-control" required id="courseSelect">
                            <option value="">-- Select Course --</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span class="spinner-border spinner-border-sm d-none" id="bulkSpinner"></span>
                        Generate All Subtitles
                    </button>
                </form>
                <div id="bulkResult" class="mt-3"></div>
            </div>
        </div>

        <!-- Retry Failed -->
        <div class="card mb-4">
            <div class="card-body">
                <h3>🔄 Retry Failed Subtitles</h3>
                <p class="text-muted">Retry subtitle generation for failed lessons</p>
                <form id="retryFailedForm">
                    <div class="mb-3">
                        <label class="form-label">Select Course:</label>
                        <select name="course_id" class="form-control" required>
                            <option value="">-- Select Course --</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <span class="spinner-border spinner-border-sm d-none" id="retrySpinner"></span>
                        Retry Failed
                    </button>
                </form>
                <div id="retryResult" class="mt-3"></div>
            </div>
        </div>

        <!-- Lesson List -->
        <div class="card">
            <div class="card-body">
                <h3>📋 Subtitle Status by Lesson</h3>
                <div id="lessonList" class="mt-3">
                    <p class="text-muted text-center">Select a course to view lessons</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Load stats and lessons when course is selected
        $('#courseSelect').change(function() {
            const courseId = $(this).val();
            if (courseId) {
                loadCourseStats(courseId);
                loadCourseLessons(courseId);
            }
        });

        // Load course statistics
        function loadCourseStats(courseId) {
            $.get(`/admin/subtitles/stats/${courseId}`)
                .done(function(stats) {
                    $('#statsContainer').html(`
                        <div class="col-md-2">
                            <div class="text-center">
                                <h2>${stats.total}</h2>
                                <p class="text-muted">Total Lessons</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h2 class="text-success">${stats.completed}</h2>
                                <p class="text-muted">Completed</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h2 class="text-warning">${stats.processing}</h2>
                                <p class="text-muted">Processing</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h2 class="text-secondary">${stats.pending}</h2>
                                <p class="text-muted">Pending</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h2 class="text-danger">${stats.failed}</h2>
                                <p class="text-muted">Failed</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h2>${Math.round((stats.completed / stats.total) * 100)}%</h2>
                                <p class="text-muted">Progress</p>
                            </div>
                        </div>
                    `);
                });
        }

        // Load course lessons
        function loadCourseLessons(courseId) {
            // You'll need to create this endpoint
            $('#lessonList').html('<p class="text-center"><div class="spinner-border"></div></p>');

            // For now, show placeholder
            setTimeout(() => {
                $('#lessonList').html(
                    '<p class="text-muted">Lesson details will appear here after implementation</p>');
            }, 500);
        }

        // Bulk generate form
        $('#bulkGenerateForm').submit(function(e) {
            e.preventDefault();
            const courseId = $(this).find('select[name="course_id"]').val();
            const spinner = $('#bulkSpinner');

            spinner.removeClass('d-none');

            $.post('/admin/subtitles/generate-bulk', {
                    course_id: courseId
                })
                .done(function(response) {
                    $('#bulkResult').html(`
                        <div class="alert alert-success">
                            ✅ ${response.message}<br>
                            <small>Queued: ${response.total_queued} | Skipped: ${response.skipped}</small>
                        </div>
                    `);
                    loadCourseStats(courseId);
                })
                .fail(function(error) {
                    $('#bulkResult').html(`
                        <div class="alert alert-danger">
                            ❌ Error: ${error.responseJSON?.message || 'Unknown error'}
                        </div>
                    `);
                })
                .always(function() {
                    spinner.addClass('d-none');
                });
        });

        // Retry failed form
        $('#retryFailedForm').submit(function(e) {
            e.preventDefault();
            const courseId = $(this).find('select[name="course_id"]').val();
            const spinner = $('#retrySpinner');

            spinner.removeClass('d-none');

            $.post('/admin/subtitles/retry-failed', {
                    course_id: courseId
                })
                .done(function(response) {
                    $('#retryResult').html(`
                        <div class="alert alert-success">
                            ✅ ${response.message}
                        </div>
                    `);
                    loadCourseStats(courseId);
                })
                .fail(function(error) {
                    $('#retryResult').html(`
                        <div class="alert alert-danger">
                            ❌ Error: ${error.responseJSON?.message || 'Unknown error'}
                        </div>
                    `);
                })
                .always(function() {
                    spinner.addClass('d-none');
                });
        });
    </script>
</body>

</html>
