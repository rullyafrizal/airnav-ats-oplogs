<script context="module">
  import Layout, { title } from '@/Shared/Layout.svelte'
  export const layout = Layout
</script>

<script>
  import { inertia, useForm } from '@inertiajs/inertia-svelte'
  import { route } from '@/utils'
  import LoadingButton from '@/Shared/LoadingButton.svelte'
  import SelectInput from '@/Shared/SelectInput.svelte'
  import TextInput from '@/Shared/TextInput.svelte'
  import Icon from '@/Shared/Icon.svelte'
  import {Inertia} from '@inertiajs/inertia'
  import TimeInput from '@/Shared/TimeInput.svelte'
  import SignatureModal from '@/Shared/SignatureModal.svelte'

  $title = 'Update Log'

  export let oplog

  let form = useForm('Editlog', {
    date: oplog.data.date_2,
    session: oplog.data.session,
    cadet_on_duty: oplog.data.cadet_on_duty,
    cadet_on_duty_signature: oplog.data.cadet_on_duty_signature,
    cadet_names: oplog.data.cadets.data.map(i => {
      return i.name
    }).join(','),
    lecturer_names: oplog.data.lecturers.data.map(i => {
      return i.name
    }).join(','),
    time: oplog.data.time,
    operational_specifications: oplog.data.operational_specifications.data,
    facilities: {
      tx_ht_twr: oplog.data.tx_ht_twr,
      rx_ht_twr: oplog.data.rx_ht_twr,
      tx_ht_pilot: oplog.data.tx_ht_pilot,
      rx_ht_pilot: oplog.data.rx_ht_pilot,
      weather_monitor: oplog.data.weather_monitor,
      signal_lamp: oplog.data.signal_lamp,
      papi: oplog.data.papi,
      phone: oplog.data.phone,
    },
  })

  const addSpec = () => {
    $form.operational_specifications = [...$form.operational_specifications, {
      key: $form.operational_specifications.length + 1,
      time: null,
      specification: null,
    }]
  }

  const checkAll = () => {
    const keys = Object.keys($form.facilities)

    keys.forEach(key => {
      $form.facilities[key] = true
    })
  }

  const uncheckAll = () => {
    const keys = Object.keys($form.facilities)

    keys.forEach(key => {
      $form.facilities[key] = false
    })
  }

  const update = () => {
    $form.put(route('operational-logs.update', { operational_log: oplog.data.id }).url())
  }

  const destroy = () => {
    if (confirm('Are you sure you want to delete this log?')) {
      Inertia.delete(route('operational-logs.destroy', { operational_log: oplog.data.id }).url())
    }
  }

  const removeSpec = () => {
    $form.operational_specifications.pop()
    $form.operational_specifications = [...$form.operational_specifications]
  }

  let checkedAll

  let show

  const showModal = () => {
    show = true
  }

  const closeModal = () => {
    show = false
  }

  const saveCanvas = (event) => {
    $form.cadet_on_duty_signature.image = event.detail.image
    $form.cadet_on_duty_signature.data = event.detail.data
    closeModal()
  }

  $: (Object.keys($form.facilities).every(key => $form.facilities[key])) ?
    checkedAll = true :
    checkedAll = false
</script>

{#if !oplog.data.is_updatable}
  <div class="mb-8 flex items-center justify-between bg-yellow-500 rounded max-w-xl">
    <div class="flex items-center">
      <Icon name="warning" class="text-white mx-4" />
      <div class="py-4 text-white text-sm font-medium">This log can't be updated due to more than 48 hours no edit policy</div>
    </div>
  </div>
{/if}

<SignatureModal {show} signature={$form.cadet_on_duty_signature} on:close={closeModal} on:saveCanvas={saveCanvas} />

<h1 class="mb-8 font-bold text-3xl">
  <a use:inertia href={route('operational-logs.index')} class="text-indigo-400 hover:text-indigo-600">
    Operational Logs
  </a>
  <span class="text-indigo-400 font-medium">/</span> Update
</h1>

<div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
  <form on:submit|preventDefault={update}>
    <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
      <SelectInput
        bind:value={$form.session}
        error={$form.errors.session}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Session :">
        <option value="PAGI">Pagi</option>
        <option value="SIANG">Siang</option>
      </SelectInput>

      <TextInput
        bind:value={$form.date}
        error={$form.errors.date}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Date :"
        type="date"
      />
    </div>

    <div class="font-bold pl-8 mt-5">Personel on Duty</div>
    <div class="px-8 pb-8 pt-4 -mr-6 -mb-8 flex flex-wrap">
      <TimeInput
        bind:value={$form.time}
        error={$form.errors.time}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Time : "
        placeholder="00:00"
      />

      <TextInput
        bind:value={$form.cadet_on_duty}
        error={$form.errors.cadet_on_duty}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Cadet on Duty :"
      />

      <TextInput
        bind:value={$form.cadet_names}
        error={$form.errors.cadet_names}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Cadet Names :"
        help="Pisahkan dengan koma jika lebih dari 1, tanpa spasi"
      />

      <TextInput
        bind:value={$form.lecturer_names}
        error={$form.errors.lecturer_names}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Lecturer Names :"
        help="Pisahkan dengan koma jika lebih dari 1, tanpa spasi"
      />

      <div class="pr-6 pb-6 w-full lg:w-1/2">
        <label for="signature">Cadet on duty Signature : </label><br>
        <button on:click|preventDefault={showModal} class="btn-indigo mt-2" id="signature">
          Signature
        </button>
      </div>

    </div>

    <div class="font-bold pl-8 mt-5">
      Operational Specifications &nbsp;&nbsp;

      {#if $form.operational_specifications.length > 1}
        <button on:click|preventDefault={removeSpec} class="h-5 px-2 text-xs hover:bg-gray-200 font-bold border-dashed border-2 border-red-700 text-red-700 bg-gray-100 rounded focus:shadow-outline">
          &#8212;
        </button>
      {/if}
    </div>
    <div class="px-8 pb-8 pt-4 -mr-6 -mb-8 flex flex-wrap">
      {#each $form.operational_specifications as specification}
        <TimeInput
          bind:value={specification.time}
          error={$form.errors.operational_specifications}
          class="pr-6 pb-6 w-full lg:w-1/2"
          label="Time : "
          placeholder="00:00"
        />

        <TextInput
          bind:value={specification.specification}
          error={$form.errors.operational_specifications}
          class="pr-6 pb-6 w-full lg:w-1/2"
          label="Specification :"
        />
      {/each}

      <button on:click|preventDefault={addSpec} class="flex items-center px-6 py-3 mb-6 rounded bg-gray-100 border-dashed border-2 border-indigo-600 text-black text-sm leading-4 font-bold whitespace-nowrap hover:bg-gray-200 focus:bg-orange-400">
        + &nbsp; Add more specifications
      </button>
    </div>

    <div class="font-bold pl-8 mt-5 mb-1">Facilities</div>
    <div class="grid lg:grid-cols-4 sm:grid-cols-3 gap-6 mt-3 mb-2 mx-8">
      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.tx_ht_twr}
            type="checkbox"
            class="rounded"
          >
          Tx HT TWR
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.rx_ht_twr}
            type="checkbox"
            class="rounded"
          >
          Rx HT TWR
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.tx_ht_pilot}
            type="checkbox"
            class="rounded"
          >
          Tx HT Pilot
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.rx_ht_pilot}
            type="checkbox"
            class="rounded"
          >
          Rx HT Pilot
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.weather_monitor}
            type="checkbox"
            class="rounded"
          >
          Weather Monitor
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.signal_lamp}
            type="checkbox"
            class="rounded"
          >
          Signal Lamp
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.papi}
            type="checkbox"
            class="rounded"
          >
          PAPI
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.phone}
            type="checkbox"
            class="rounded"
          >
          Phone
        </label>
      </div>

    </div>
    {#if checkedAll}
      <div on:click|preventDefault={uncheckAll} class="border-dashed border-2 focus:bg-orange-400 rounded hover:text-indigo-600 bg-gray-100 text-gray-700 border-indigo-600 cursor-pointer text-xs font-bold py-1 px-1 ml-8 mt-2 mb-8 inline-block">
        &cross; Uncheck All
      </div>
    {:else}
      <div on:click|preventDefault={checkAll} class="border-dashed border-2 focus:bg-orange-400 rounded hover:text-indigo-600 bg-gray-100 text-gray-700 border-indigo-600 cursor-pointer text-xs font-bold py-1 px-1 ml-8 mt-2 mb-8 inline-block">
        &check; Check All
      </div>
    {/if}
    <div class="flex items-center justify-between w-full px-8 py-4 mt-8 bg-gray-100 border-t border-gray-200">
      <button class="text-red-600 hover:underline" tabindex="-1" type="button" on:click={destroy}>
        <Icon name="trash" class="w-5 h-5 inline-block pb-1" /> Delete Log
      </button>

      {#if oplog.data.is_updatable}
        <LoadingButton loading={$form.processing} class="btn-indigo" type="submit">
          Update Log
        </LoadingButton>
      {:else}
        <button class="disabled-btn-indigo bg-opacity-50 cursor-default" tabindex="-1" type="button" disabled>
          Update Log
        </button>
      {/if}
    </div>
  </form>
</div>
