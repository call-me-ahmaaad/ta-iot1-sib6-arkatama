<a href={{route('dashboard')}} id="dashboardBtn">Dashboard</a>
<h2>{{$title}}</h2>
        <div class="dropdown">
            <button class="dropbtn">{{ Auth::user()->name }}</button>
            <div class="dropdown-content">
                <a href={{route('profile.edit')}}>Profile</a>
                <a href={{ route('logout') }} onclick="event.preventDefault(); document.getElementById('logout-form').submit();" id="logout">Log Out</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <script>
           document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.querySelector('.dropdown');
    const dropdownContent = document.querySelector('.dropdown-content');

    dropdown.addEventListener('mouseover', () => {
        dropdownContent.classList.remove('hide');
        dropdownContent.classList.add('show');
    });

    dropdown.addEventListener('mouseout', () => {
        dropdownContent.classList.remove('show');
        dropdownContent.classList.add('hide');
    });
});
        </script>
