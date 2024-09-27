<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book List</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 60px;
    }
    .card {
      margin-bottom: 20px;
    }
    .star-rating {
      color: #ffc107;
    }
    .comment-section {
      display: none;
      margin-top: 10px;
    }
    .user-icon {
      cursor: pointer;
    }
    .search-container {
      margin-bottom: 20px;
    }
    .card img {
      height: 200px;
      object-fit: cover;
    }
  </style>
</head>
<body>
  <!-- Navbar with User Icon -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Books Hub</a>
      <div class="d-flex ms-auto">
        <!-- User Section -->
        <div class="user-icon dropdown">
          <img src="https://via.placeholder.com/40" alt="User Icon" class="rounded-circle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
              <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="dropdown-item" style="border: none; background: none; cursor: pointer;">
                  Logout
                </button>
              </form>
            </li>
          </ul>
        </div>      </div>
    </div>
  </nav>

  <!-- Main Content: Book List in Cards -->
  <div class="container">
    <!-- Search Field -->
    <div class="row search-container">
      <div class="col-md-12">
        <form class="d-flex" action="search.php" method="GET">
          <input class="form-control me-2" type="search" name="query" placeholder="Search books by title or author" aria-label="Search">
          <button class="btn btn-outline-primary" type="submit" onclick="event.preventDefault(); document.location.href = '{{ route('searchbook') }}?query=' + document.querySelector('input[name=query]').value;">Search</button>
        </form>
      </div>
    </div>

    <div class="row">
      <h3>Available Books</h3>

      @foreach($books as $book)
      <div class="col-md-3">
        <div class="card">
          <img src="{{ $book->image_url }}" class="card-img-top" alt="{{ $book->title }} Image">
          <div class="card-body">
            <h5 class="card-title">{{ $book->book_name }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{ $book->author_name }}</h6>
            
            <!-- Star Rating Section -->
            <div class="star-rating" data-book-id="{{ $book->id }}">
              @for ($i = 1; $i <= 5; $i++)
                <span class="star" data-rating="{{ $i }}" style="cursor:pointer;">
                  @if ($i <= $book->avg_rating)
                    &#9733; <!-- Filled star -->
                  @else
                    &#9734; <!-- Empty star -->
                  @endif
                </span>
              @endfor
            </div>
            <form id="rating-form-{{ $book->id }}" action="{{ route('comment.rating') }}" method="POST" style="display: none;">
              @csrf
              <input type="hidden" name="rating" id="rating-input-{{ $book->id }}" value="">
              <input type="hidden" name="book_id" value="{{ $book->id }}">
            </form>

            <!-- Comment Section -->
            <div class="mt-2">
              <a href="#" class="comment-icon text-muted" data-id="{{ $book->id }}">
                <i class="bi bi-chat-dots"></i> Comment ({{ $book->comment ?? 0 }})
              </a>
              <div class="comment-section" id="comment-section-{{ $book->id }}">
                <form action="{{ route('comments.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="book_id" value="{{ $book->id }}">
                  <input type="text" class="form-control mt-2" name="comment" placeholder="Leave a comment" required>
                  <button type="submit" class="btn btn-primary btn-sm mt-2">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="row">
      <div class="col-12 mt-4">
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">
            {{ $books->links() }} <!-- Laravel pagination links -->
          </ul>
        </nav>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Icon library -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.js"></script>
  <script>
    // JavaScript to handle star rating
    document.querySelectorAll('.star-rating .star').forEach(star => {
      star.addEventListener('click', function() {
        const rating = this.getAttribute('data-rating');
        const bookId = this.closest('.star-rating').getAttribute('data-book-id');
        
        // Set the rating value in the hidden input
        document.getElementById('rating-input-' + bookId).value = rating;
        
        // Submit the rating form
        document.getElementById('rating-form-' + bookId).submit();
      });
    });

    // JavaScript to toggle comment section visibility
    document.querySelectorAll('.comment-icon').forEach(function(icon) {
      icon.addEventListener('click', function(event) {
        event.preventDefault();
        const id = event.target.closest('.comment-icon').getAttribute('data-id');
        const commentSection = document.getElementById(`comment-section-${id}`);
        commentSection.style.display = commentSection.style.display === 'none' ? 'block' : 'none';
      });
    });
  </script>
</body>
</html>
