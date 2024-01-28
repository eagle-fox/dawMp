<script>
export default {
  name: 'NavBar',
}
</script>

<template>
  <!-- Button trigger modal -->

  <button
    class="btn btn-primary btn-lg"
    data-target="#myModal"
    data-toggle="modal"
    type="button"
  >
    Launch demo modal
  </button>

  <div class="dropdown">
    <button
      id="dropdownMenuButton1"
      aria-expanded="false"
      class="btn btn-secondary dropdown-toggle"
      data-bs-toggle="dropdown"
      type="button"
    >
      Check Bootstrap
    </button>
    <ul aria-labelledby="dropdownMenuButton1" class="dropdown-menu">
      <li><a class="dropdown-item" href="#">Action</a></li>
      <li><a class="dropdown-item" href="#">Another dd action</a></li>
      <li><a class="dropdown-item" href="#">Something else here</a></li>
    </ul>
  </div>

  <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand me-2" href="https://mdbgo.com/">
        <img
          alt="Eagle-fox logo"
          height="48"
          loading="lazy"
          src="../assets/logo_circle.svg"
        />
      </a>

      <div id="navbarButtonsExample" class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link fs-5" href="#">EagleFox</a>
          </li>
        </ul>

        <div class="dropdown">
          <button
            id="dropdownMenuButton"
            aria-expanded="false"
            aria-haspopup="true"
            class="btn btn-secondary dropdown-toggle"
            data-toggle="dropdown"
            type="button"
          >
            Dropdown button
          </button>
          <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
            <a class="dropdown-item" href="#">THE Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </div>

        <div class="d-flex align-items-center">
          <button class="btn btn-primary px-3 me-2">Login</button>
          <button class="btn btn-primary me-3" type="button">
            Sign up for free
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped></style>