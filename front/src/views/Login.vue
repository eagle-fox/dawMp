<script setup>

</script>

<template>
    <div class="h-100 d-flex justify-content-center align-items-center">
        <div class="shadow p-4 rounded">
            <h3 class="text-center">{{ $t('login.login_t1') }}</h3>

            <div class="d-flex justify-content-center align-items-center flex-column gap-2">
                <div class="input-group">
                    <span class="input-group-text">{{ $t('login.login_username') }} :</span>
                    <input type="text" aria-label="First name" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-text">{{ $t('login.login_email') }} :</span>
                    <input type="text" aria-label="First name" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-text">{{ $t('login.login_password') }} :</span>
                    <input type="text" aria-label="First name" class="form-control">
                </div>

                <div class="input-group">
                    <span class="input-group-text">{{ $t('login.login_rpassword') }} :</span>
                    <input type="text" aria-label="First name" class="form-control">
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>

</style>