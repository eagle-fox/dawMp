<script>
export default {
    name: 'NavBar',
}
</script>

<template>
    <!-- Button trigger modal -->

    <button
        type="button"
        class="btn btn-primary btn-lg"
        data-toggle="modal"
        data-target="#myModal"
    >
        Launch demo modal
    </button>

    <div class="dropdown">
        <button
            class="btn btn-secondary dropdown-toggle"
            type="button"
            id="dropdownMenuButton1"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        >
            Check Bootstrap
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another dd action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
        </ul>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand me-2" href="https://mdbgo.com/">
                <img
                    src="../assets/logo_circle.svg"
                    height="48"
                    alt="Eagle-fox logo"
                    loading="lazy"
                />
            </a>

            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fs-5" href="#">EagleFox</a>
                    </li>
                </ul>

                <div class="dropdown">
                    <button
                        class="btn btn-secondary dropdown-toggle"
                        type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        Dropdown button
                    </button>
                    <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuButton"
                    >
                        <a class="dropdown-item" href="#">THE Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#"
                            >Something else here</a
                        >
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <button class="btn btn-primary px-3 me-2">Login</button>
                    <button type="button" class="btn btn-primary me-3">
                        Sign up for free
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped></style>
