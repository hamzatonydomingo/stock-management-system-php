<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <!-- drop down icon -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!--  -->
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item <?=($page_title == 'dashboard')?'active':''?>">
                <a class="nav-link" href="./index.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=($page_title == 'items')?'active':''?>" href="./item-read.php">Items</a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?=($page_title == 'categories')?'active':''?>" href="./category-read.php">Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=($page_title == 'suppliers')?'active':''?>" href="./supplier-read.php">Suppiers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=($page_title == 'about')?'active':''?>" href="./about.php">About</a>
            </li>
            <!-- drop down button -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?=($page_title == 'edit account')?'active':''?>" 
                    href="javascript:void(0);" id="navbarDropdownMenuLink" 
                    data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Account
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item fa fa-pencil text-success" href="./user-edit.php"> edit account</a>
                    <a class="dropdown-item fa fa-lock text-warning" href="./user-edit-password.php"> change password</a>
                    <a class="dropdown-item text-danger fa fa-trash" href="./user-delete.php"> delete account</a>
                </div>
            </li>
            <!-- end of drop down button -->
        </ul>
    </div>
</nav>