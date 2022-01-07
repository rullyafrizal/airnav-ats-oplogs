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
    'shift': oplog.data['shift'],
    atc_on_duty: oplog.data.atc_on_duty,
    atc_on_duty_signature: oplog.data.atc_on_duty_signature,
    controller_initial_names: oplog.data.controller_initials.data.map(i => {
      return i.name
    }).join(','),
    time: oplog.data.time,
    sign: oplog.data.sign,
    operational_specifications: oplog.data.operational_specifications.data,
    facilities: {
      tx_122_4: oplog.data.tx_122_4,
      rx_122_4: oplog.data.rx_122_4,
      tx_120_55: oplog.data.tx_120_55,
      rx_120_55: oplog.data.rx_120_55,
      awos: oplog.data.awos,
      signal_lamp: oplog.data.signal_lamp,
      crash_bell: oplog.data.crash_bell,
      sirine: oplog.data.sirine,
      binocular: oplog.data.binocular,
      vscs: oplog.data.vscs,
      navaid_monitor: oplog.data.navaid_monitor,
      fids: oplog.data.fids,
      afls: oplog.data.afls,
      aftn: oplog.data.aftn,
      iais: oplog.data.iais,
      ht_1: oplog.data.ht_1,
      ht_2: oplog.data.ht_2,
      ht_3: oplog.data.ht_3,
      phone_coord: oplog.data.phone_coord,
      phone_tele: oplog.data.phone_tele,
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
    $form.atc_on_duty_signature.image = event.detail.image
    $form.atc_on_duty_signature.data = event.detail.data
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

<SignatureModal {show} signature={$form.atc_on_duty_signature} on:close={closeModal} on:saveCanvas={saveCanvas} />

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
        bind:value={$form['shift']}
        error={$form.errors['shift']}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Shift :">
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

    <div class="font-bold pl-8 mt-5">Personal on Duty</div>
    <div class="px-8 pb-8 pt-4 -mr-6 -mb-8 flex flex-wrap">
      <TimeInput
        bind:value={$form.time}
        error={$form.errors.time}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Time : "
        placeholder="00:00"
      />

      <TextInput
        bind:value={$form.atc_on_duty}
        error={$form.errors.atc_on_duty}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Atc on Duty :"
      />

      <TextInput
        bind:value={$form.controller_initial_names}
        error={$form.errors.controller_initial_names}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Controller Initials :"
        help="Pisahkan dengan koma jika lebih dari 1, tanpa spasi"
      />

      <div class="pr-6 pb-6 w-full lg:w-1/2">
        <label for="signature">Atc on duty Signature : </label><br>
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
            bind:checked={$form.facilities.tx_122_4}
            type="checkbox"
            class="rounded"
          >
          Tx 122,4
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.rx_122_4}
            type="checkbox"
            class="rounded"
          >
          Rx 122,4
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.tx_120_55}
            type="checkbox"
            class="rounded"
          >
          Tx 120,55
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.rx_120_55}
            type="checkbox"
            class="rounded"
          >
          Rx 120,55
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.awos}
            type="checkbox"
            class="rounded"
          >
          AWOS
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
            bind:checked={$form.facilities.crash_bell}
            type="checkbox"
            class="rounded"
          >
          Crash Bell
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.sirine}
            type="checkbox"
            class="rounded"
          >
          Sirine
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.binocular}
            type="checkbox"
            class="rounded"
          >
          Binocular
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.vscs}
            type="checkbox"
            class="rounded"
          >
          VSCS
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.navaid_monitor}
            type="checkbox"
            class="rounded"
          >
          Navaid Monitor
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.fids}
            type="checkbox"
            class="rounded"
          >
          FIDS
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.afls}
            type="checkbox"
            class="rounded"
          >
          AFLS
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.aftn}
            type="checkbox"
            class="rounded"
          >
          AFTN
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.iais}
            type="checkbox"
            class="rounded"
          >
          IAIS
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.ht_1}
            type="checkbox"
            class="rounded"
          >
          HT 1
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.ht_2}
            type="checkbox"
            class="rounded"
          >
          HT 2
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.ht_3}
            type="checkbox"
            class="rounded"
          >
          HT 3
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.phone_coord}
            type="checkbox"
            class="rounded"
          >
          Phone Coord
        </label>
      </div>

      <div class="col-span-1">
        <label>
          <input
            bind:checked={$form.facilities.phone_tele}
            type="checkbox"
            class="rounded"
          >
          Phone Tele
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
