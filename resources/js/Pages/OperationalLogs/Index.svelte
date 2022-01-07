<script context="module">
    import Layout, { title } from '@/Shared/Layout.svelte'
    export const layout = Layout
</script>

<script>
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/utils'
    import Icon from '@/Shared/Icon.svelte'
    import SearchFilter from '@/Shared/SearchFilter.svelte'
    import Pagination from '@/Shared/Pagination.svelte'
    import TextInput from '@/Shared/TextInput.svelte'

    $title = 'Operational Logs'

    export let oplogs = []

    let filters = {
      shift: $page.props.filters.shift,
      periodStart: $page.props.filters.periodStart,
      periodEnd: $page.props.filters.periodEnd,
    }
</script>

<h1 class="mb-8 font-bold text-3xl">Operational Logs</h1>
<div class="mb-6 flex justify-between items-center">
  <SearchFilter class="w-full max-w-md mr-4" bind:filters>
    <label for="role" class="block text-gray-700">Shift:</label>
    <select id="role" class="mt-1 mb-5 w-full form-select" bind:value={filters.shift}>
      <option value={null}></option>
      <option value="PAGI">Pagi</option>
      <option value="SIANG">Siang</option>
    </select>

    <label for="role" class="block text-gray-700">Period Start:</label>
    <TextInput
      bind:value={filters.periodStart}
      class="mt-1 mb-5 w-full"
      type="date"
    />

    <label for="role" class="block text-gray-700">Period End:</label>
    <TextInput
      bind:value={filters.periodEnd}
      class="mt-1 mb-5 w-full"
      type="date"
    />
  </SearchFilter>
  <a use:inertia href={route('operational-logs.create')} class="btn-indigo">
    <span>Create</span>
    <span class="hidden md:inline">Log</span>
  </a>
</div>
<div class="bg-white rounded-md shadow overflow-x-auto">
  <table class="w-full whitespace-nowrap">
    <tr class="text-left font-bold">
      <th class="px-6 pt-6 pb-4">Date</th>
      <th class="px-6 pt-6 pb-4">Shift</th>
      <th class="px-6 pt-6 pb-4">ATC on Duty</th>
      <th class="px-6 pt-6 pb-4">Created At</th>
      <th class="px-6 pt-6 pb-4">Action</th>
      <th class="px-6 pt-6 pb-4"></th>
    </tr>
    {#each oplogs.data as oplog (oplog.id)}
      <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
        <td class="border-t">
          <a
            use:inertia
            href={route('operational-logs.edit', oplog.id)}
            class="px-6 py-4 flex items-center"
            tabindex="-1">
            {oplog.date || ''}
          </a>
        </td>
        <td class="border-t">
          <a
            use:inertia
            href={route('operational-logs.edit', oplog.id)}
            class="px-6 py-4 flex items-center"
            tabindex="-1">
            <span class={oplog.shift === 'PAGI' ? 'inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full' : 'inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full'}>
              {oplog.shift || ''}
            </span>
          </a>
        </td>
        <td class="border-t">
          <a
            use:inertia
            href={route('operational-logs.edit', oplog.id)}
            class="px-6 py-4 flex items-center"
            tabindex="-1">
            {oplog.atc_on_duty || ''}
          </a>
        </td>
        <td class="border-t">
          <a
            use:inertia
            href={route('operational-logs.edit', oplog.id)}
            class="px-6 py-4 flex items-center"
            tabindex="-1">
            {oplog.created_at || ''}
          </a>
        </td>
        <td class="border-t">
            <span class="flex items-center px-6 py-4" tabindex="-1">
              <a
                class="btn-indigo mr-2"
                tabindex="-1"
                title="Download"
                target="_blank"
                rel="noopener"
                href={route('operational-logs.export', oplog.id)}
              >
                Download
              </a>
            </span>
        </td>
        <td class="border-t w-px">
          <a
            use:inertia
            href={route('operational-logs.edit', oplog.id)}
            class="px-4 flex items-center"
            tabindex="-1">
            <Icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
          </a>
        </td>
      </tr>
    {/each}

    {#if oplogs.data.length === 0}
      <tr>
        <td class="border-t px-6 py-4 font-semibold" colspan="6">No log found.</td>
      </tr>
    {/if}
  </table>
</div>
{#if oplogs.data.length > 0}
  <Pagination class="mt-6 justify-end" links={oplogs.meta.links} />
{/if}
