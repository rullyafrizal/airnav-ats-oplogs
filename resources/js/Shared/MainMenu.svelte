<script>
  import { inertia, page } from '@inertiajs/inertia-svelte'
  import { route } from '@/utils'
  import Icon from '@/Shared/Icon.svelte'

  $: isUrl = (...urls) => {
    let currentUrl = $page.url.substr(1)

    if (urls[0] === '') {
      return currentUrl === ''
    }

    return urls.filter(url => currentUrl.startsWith(url)).length
  }
</script>

<div {...$$restProps}>
  <div class="mb-4">
    <a use:inertia href={route('dashboard')} class="flex items-center group py-3">
      <div class={isUrl('') ? 'text-white font-bold' : 'text-indigo-300 group-hover:text-white'}>
        Dashboard
      </div>
    </a>
  </div>
  <div class="mb-4">
    <a use:inertia href={route('operational-logs.index')} class="flex items-center group py-3">
      <div class={isUrl('operational-logs') ? 'text-white font-bold' : 'text-indigo-300 group-hover:text-white'}>
        Operational Logs
      </div>
    </a>
  </div>
</div>
