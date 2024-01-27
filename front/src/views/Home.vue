<script setup>
import NavBar from "@/components/NavBar.vue";
</script>

<template>
  <NavBar></NavBar>
  <div class="dropdown">
    <button
      class="btn btn-secondary dropdown-toggle"
      type="button"
      id="dropdownMenuButton"
      data-bs-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
      Check Bootstrap
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <li><a class="dropdown-item" href="#">Action</a></li>
      <li><a class="dropdown-item" href="#">Another action</a></li>
      <li>
        <a class="dropdown-item" href="#">Something else here</a>
      </li>
    </ul>
  </div>
</template>

<style scoped></style>
