<script context="module">
  import Layout, { title } from '@/Shared/Layout.svelte'
  export const layout = Layout
</script>

<script>
  import { inertia } from '@inertiajs/inertia-svelte'
  import Icon from '@/Shared/Icon.svelte'

  $title = 'Dashboard'

  export let oplogCount
</script>

<h1 class="mb-4 font-bold text-3xl">Dashboard</h1>
<h3 class="font-semibold mb-8 text-gray-400 leading-normal">
  ATS Operational Log Book - Perum LPPNPI Kantor Cabang Pembantu Lampung
</h3>

<div class="mt-1">
  <div class="flex flex-wrap -mx-6">
    <div class="w-full px-6 sm:w-1/2 xl:w-1/3">
      <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-md">
        <div class="p-3 bg-indigo-800 bg-opacity-75 rounded-full">
          <Icon class="w-7 h-7 text-white" name="clipboard-check" />
        </div>
        <div class="mx-5">
          <h4 class="text-2xl font-semibold text-gray-700">{ oplogCount }</h4>
          <div class="text-gray-500">Operational Logs</div>
        </div>
      </div>
    </div>
  </div>
</div>
