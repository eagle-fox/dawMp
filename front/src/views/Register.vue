<script setup>

</script>

<template>
  <div class="vh-100 d-flex justify-content-center align-items-center">
    <div class="shadow p-4 rounded">
      <h3 class="text-center">{{ $t('login.login_message1') }}</h3>
      <form>
        <div class="mb-3">
          <label for="username" class="form-label">{{ $t('login.login_username') }}</label>
          <input type="text" class="form-control" id="username"/>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">{{ $t('login.login_email') }}</label>
          <input type="email" class="form-control" id="email" aria-describedby="emailHelp"/>
          <div id="emailhelp" class="form-text">{{ $t('login.login_message') }}</div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">{{ $t('login.login_password') }}</label>
          <input type="password" class="form-control" id="password"/>
        </div>
        <div class="mb-3">
          <label for="repeatPassword" class="form-label">{{ $t('login.login_rpassword') }}</label>
          <input type="password" class="form-control" id="repeatPassword"/>
        </div>
        <button type="submit" class="btn btn-primary">{{ $t('login.login_message1') }}</button>
      </form>
    </div>
  </div>
</template>

<style scoped>

</style>
