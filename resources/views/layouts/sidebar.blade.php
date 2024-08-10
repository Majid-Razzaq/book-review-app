<ul class="nav flex-column">
    @if(Auth::user()->role == 'admin')
        <li class="nav-item">
            <a href="{{ route('books.index') }}">Books</a>                               
        </li>
        <li class="nav-item">
            <a href="{{ route('books.reviews') }}">Reviews</a>                               
        </li>
    @endif
    <li class="nav-item">
        <a href="{{ route('account.profile') }}">Profile</a>                               
    </li>
    <li class="nav-item">
        <a href="{{ route('reviews.myReviews') }}">My Reviews</a>
    </li>
    <li class="nav-item">
        <a href="change-password.html">Change Password</a>
    </li> 
    <li class="nav-item">
        <a href="{{ route('account.logout') }}">Logout</a>
    </li>                           
</ul>