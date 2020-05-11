<script>
    import { Router } from 'svelte-router-spa'
    import { routes } from './routes'

    export let name;
</script>


<h1>Hello {name}!</h1>

<Router {routes} />
