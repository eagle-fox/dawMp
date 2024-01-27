<script>
export default {
  name: 'NavBar'
}
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand me-2" href="https://mdbgo.com/">
        <img src="../assets/logo_circle.svg" height="48" alt="Eagle-fox logo" loading="lazy" />
      </a>

      <div class="collapse navbar-collapse" id="navbarButtonsExample">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link fs-5" href="#">EagleFox</a>
          </li>
        </ul>


        <div class="d-flex align-items-center">
          <button class="btn btn-primary px-3 me-2">
            Login
          </button>
          <button type="button" class="btn btn-primary me-3">
            Sign up for free
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>

</style>