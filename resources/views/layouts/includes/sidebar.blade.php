<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      @if (Auth::user()->roles == 'ADMIN')
      <!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link " href="">
          <i class="bi bi-house-fill"></i>
          <span>Home</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link " href="{{ route('dashboard.category.index') }}">
          <i class="bi bi-handbag-fill"></i>
          <span>Category</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.product.index') }}">
          <i class="bi bi-newspaper"></i>
          <span>Product</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.transaction.index') }}">
          <i class="bi bi-currency-dollar"></i>
          <span>Transaction</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.user.index') }}">
          <i class="bi bi-people"></i>
          <span>User</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.my-transaction.index') }}">
          <i class="bi bi-cart"></i>
          <span>MyTransaction</span>
        </a>
      </li>

      @else
      <li class="nav-item">
        <a class="nav-link " href="">
          <i class="bi bi-house-fill"></i>
          <span>Home</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.my-transaction.index') }}">
          <i class="bi bi-cart"></i>
          <span>MyTransaction</span>
        </a>
      </li>
      @endif

     <!-- End Dashboard Nav -->

      <!-- End Components Nav -->
      
          
        

      <!-- End Blank Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->