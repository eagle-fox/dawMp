<script setup>

</script>

<template>
    <div class="vh-100 d-flex justify-content-center align-items-center all">
        <div class="shadow p-4 rounded">
            <h3 class="text-center mb-2">{{ $t('login.login_t1') }}</h3>

            <div class="d-flex justify-content-center align-items-center flex-column gap-2">
                <form>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">{{$t('login.login_username')}}</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">{{$t('login.login_message')}}</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">{{$t('login.login_password')}}</label>
                        <input type="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">{{$t('login.login_t1')}}</button>
                        <router-link to="/register" type="submit" class="btn btn-primary">{{$t('login.login_message1')}}</router-link>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>


<style scoped>

</style>