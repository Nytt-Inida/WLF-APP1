@extends('main')

@section('content')
    <div class="min-h-screen bg-gray-100">
        <!-- Page Content -->
        <main>
        </main>
    </div>

    @stack('modals')

    @livewireScripts

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html> 

@extends('main')

@section('content')


    

     <style>
    
      
        /* Ensure middle content spacing is minimal */
        .card {
            max-width: 80%; /* Adjust the width of the card */
            margin: 10px auto; /* Center the card horizontally */
            border-radius: 10px;
        }

        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 15px; /* Adjust padding */
        }

        .card-title {
            font-size: 1.2rem; /* Adjust font size */
        }
        #certificates-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center; /* Keeps the certificates centered */
    gap: 20px; /* Adds space between the certificates */
}


      
        .my-certificates-container {
    min-height: 100vh;
    margin-top:50px;
    justify-content: center;
    
}


        .modal-footer button:hover {
            background-color: #0056b3;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .card {
                max-width: 90%; /* Adjust the width for smaller screens */
            }
        }
    </style>
   <!--slider-area start-->
        <section class="slider-area pt-10 pt-xs-150 pt-80 pb-xs-35">
           
       </section>
     




<div class="container  my-certificates-container   ">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center mb-4">My Certificates</h3>
            <div class="row" id="certificates-container">
                <!-- The certificates will be dynamically inserted here -->
            </div>
            <div id="alert-message" class="alert alert-danger mt-4 text-center" style="display: none;"></div>
            <div id="empty-state" class="mt-4" style="display: none;">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <img src="{{ asset('assets/img/icon/certificate-line.svg') }}" alt="certificate" style="width:64px;height:64px;opacity:0.8;"/>
                        <h5 class="mt-3 mb-2">Complete your course to unlock your certificate</h5>
                        <p class="mb-3">Finish all lessons and quizzes in a course to download your certificate of completion.</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-warning" style="border-radius:8px;">Browse Courses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function() {
    const userId = {{ auth()->id() }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const alertMessage = document.getElementById('alert-message');
    const certificatesContainer = document.getElementById('certificates-container');

    // Fetch completed courses from the server
    function fetchCompletedCourses() {
        $.ajax({
            url: '{{ route("auth.generateCertificate") }}',
            type: 'GET',
            data: {
                _token: csrfToken,
                user_id: userId
            },
            success: function(response) {
                if (response.completed_courses && response.completed_courses.length > 0) {
                    response.completed_courses.forEach(course => {
                        createCertificateCard(course);
                    });
                } else {
                    document.getElementById('empty-state').style.display = 'block';
                }
            },
            error: function(error) {
                if (error.status === 404) {
                    document.getElementById('empty-state').style.display = 'block';
                } else {
                    alertMessage.textContent = 'Error fetching completed courses.';
                    alertMessage.style.display = 'block';
                    console.error('Error fetching completed courses:', error);
                }
            }
        });
    }

    // Function to create a certificate card dynamically
    function createCertificateCard(course) {
        const cardHTML = `
            <div class="col-md-12">
                <div class="card mb-3">
                    <img src="${course.image}" alt="Course Image" class="card-img-top">
                    <div class="card-body">
                        <h4 class="card-title">${course.title}</h4>
                        <button class="btn btn-warning w-100 download-btn" 
                            style="border-radius: 5px;" 
                            data-course-id="${course.course_id}">
                            Download certificate
                        </button>
                    </div>
                </div>
            </div>
        `;
        certificatesContainer.insertAdjacentHTML('beforeend', cardHTML);

        // Attach click event listener to the download button
        const downloadBtn = certificatesContainer.querySelector(`button[data-course-id="${course.course_id}"]`);
        downloadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            downloadCertificate(course.course_id);
        });
    }

    // Function to download certificate
    function downloadCertificate(courseId) {
        const downloadLink = `{{ route('certificate.download', '') }}/${courseId}`;
        window.open(downloadLink, '_blank');
    }

    // Fetch the completed courses when the page loads
    fetchCompletedCourses();
});
</script>




@endsection
