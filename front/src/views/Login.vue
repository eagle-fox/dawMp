<script>
import Cookies from 'js-cookie';
import NavBar from  '@/components/NavBar.vue'

export default {
    name: 'Login',
    components: {
        NavBar
    }
}

</script>

<template>
    <NavBar></NavBar>
    <div class="main-container">
        <div class="d-inline-block d-flex justify-content-center align-items-center all">
            <div class="shadow p-4 rounded">
                <h3 class="text-center mb-2">{{ $t('login.login_t1') }}</h3>

                <div class="d-flex justify-content-center align-items-center flex-column gap-2">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">{{$t('login.login_username')}}</label>
                            <input type="text" class="form-control" id="name" aria-describedby="name">
                            <div id="name" class="form-text">{{$t('login.login_message')}}</div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">{{$t('login.login_password')}}</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a type="submit" class="btn btn-primary" >{{$t('login.login_t1')}}</a>
                            <router-link to="/register" type="submit" class="btn btn-primary">{{$t('login.login_message1')}}</router-link>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>
.main-container {
    height: calc(100vh - 200px);
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>