<template>
    <div class="login">
        <h1>This is an login page</h1>

        <FormKit type="form"
            @submit="handleLogin"
        >
            <FormKit type="email"
                name="email"
                label="Email"
                :value="testEmail"
                required
            />

            <FormKit type="password"
                label="Password"
                name="password"
                :value="testPassword"
                required
            />
        </FormKit>

        <RouterLink :to="{path: 'forgotpassword'}">
            Forgot password?
        </RouterLink>
    </div>
</template>


<style>

</style>


<script setup>

    import { Api } from '@/http.js'

    const testEmail = 'bob@law.blah'
    const testPassword = 'testing123'


    async function handleLogin(e) {
        console.log(e)

        const { email, password } = e

        let res

        try {
            res = await Api.post('auth/login', {
                email, password
            })
        } catch(err) {
            console.error(err)
        }

        console.log(res)



    }

</script>
