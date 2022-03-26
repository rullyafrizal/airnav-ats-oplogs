<script>
  import { useForm } from '@inertiajs/inertia-svelte'
  import { route } from '@/utils'
  import LoadingButton from '@/Shared/LoadingButton.svelte'
  import Logo from '@/Shared/Logo.svelte'
  import TextInput from '@/Shared/TextInput.svelte'

  let form = useForm({
    email: '',
    password: '',
    remember: false,
  })

  function login() {
    $form.post(route('login.store'))
  }
</script>

<svelte:head>
  <title>Login - SILOTO</title>
</svelte:head>

<div class="p-6 bg-cover min-h-screen flex justify-center items-center bg-opacity-50" style="background-image: url('/images/politeknik-surabaya.png')">
  <div class="w-full max-w-md">
    <Logo class="block mx-auto w-full max-w-xs fill-white h-36" height="75" />
    <h1 class="font-extrabold text-4xl text-white mt-5 text-center">S I L O T O</h1>
    <p class="text-center text-gray-300 font-semibold leading-loose text-sm">Aplikasi Log Book Tower</p>
    <form
      class="mt-8 bg-white rounded-lg shadow-2xl overflow-hidden"
      on:submit|preventDefault={login}>
      <div class="px-10 py-12">
        <h1 class="text-center font-bold text-2xl">ATS Operational Log Book</h1>
        <p class="text-center text-gray-400 leading-loose text-sm">Politeknik Penerbangan Surabaya</p>
        <div class="mx-auto mt-5 w-24 border-b-2" />
        <TextInput
          bind:value={$form.email}
          error={$form.errors.email}
          class="mt-10"
          label="Email:"
          type="email"
          autofocus
          autocapitalize="off" />
        <TextInput
          bind:value={$form.password}
          class="mt-6"
          label="Password:"
          type="password" />
      </div>
      <div class="px-10 py-4 bg-gray-50 border-t border-gray-100 flex">
        <LoadingButton bind:loading={$form.processing} class="btn-indigo ml-auto" type="submit">Login</LoadingButton>
      </div>
    </form>
  </div>
</div>
