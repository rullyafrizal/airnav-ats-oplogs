<script context="module">
  import { writable } from 'svelte/store'
  export const title = writable(null)
</script>

<script>
  import { inertia, page } from '@inertiajs/inertia-svelte'
  import { route } from '@/utils'
  import Dropdown from '@/Shared/Dropdown.svelte'
  import FlashMessages from '@/Shared/FlashMessages.svelte'
  import Icon from '@/Shared/Icon.svelte'
  import LogoLayout from '@/Shared/LogoLayout.svelte'
  import MainMenu from '@/Shared/MainMenu.svelte'
</script>

<svelte:head>
  <title>{$title ? `${$title} - SIAPELO` : 'SIAPELO'}</title>
</svelte:head>

<div class="md:flex md:flex-col">
  <div class="md:h-screen md:flex md:flex-col">
    <div class="md:flex md:flex-shrink-0">
      <div class="bg-indigo-900 md:flex-shrink-0 md:w-56 px-6 py-4 flex items-center justify-between md:justify-center">
        <a use:inertia href="/">
          <LogoLayout class="m-0 w-36 h-20"/>
        </a>
        <Dropdown class="md:hidden" placement="bottom-end">
          <svg class="fill-white w-6 h-6" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
          </svg>
          <div slot="dropdown" class="mt-2 px-8 py-4 shadow-lg bg-indigo-800 rounded">
            <MainMenu />
          </div>
        </Dropdown>
      </div>
      <div class="bg-white border-b w-full p-4 md:py-0 md:px-12 text-sm md:text-md flex justify-between items-center">
        <div class="mt-1 mr-4">
          <h2 class="font-semibold text-xl">ATS Operations</h2>
        </div>
        <Dropdown class="mt-1" placement="bottom-end">
          <div class="flex items-center cursor-pointer select-none group">
            <div class="text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 mr-1 whitespace-nowrap">
              <span>{$page.props.auth.user.name}</span>
            </div>
            <Icon
              name="cheveron-down"
              class="w-5 h-5 group-hover:fill-indigo-600 fill-gray-700 focus:fill-indigo-600" />
          </div>
          <div slot="dropdown" class="mt-2 py-2 shadow-xl bg-white rounded text-sm">
            <button
              use:inertia={{ href: route('logout'), method: 'get' }}
              class="block px-6 py-2 hover:bg-indigo-500 hover:text-white w-full text-left">
              Logout
          </button>
          </div>
        </Dropdown>
      </div>
    </div>
    <div class="md:flex md:flex-grow md:overflow-hidden">
      <MainMenu class="bg-indigo-800 flex-shrink-0 w-56 p-10 hidden md:block overflow-y-auto" />
      <div class="flex-1 px-4 py-8 md:p-12 md:overflow-y-auto" scroll-region>
        <FlashMessages />
        <slot />
      </div>
    </div>
  </div>
</div>
