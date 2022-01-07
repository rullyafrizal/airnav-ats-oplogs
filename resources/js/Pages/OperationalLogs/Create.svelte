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
  import { facilities } from '@/utils/facilities'
  import SignatureModal from '@/Shared/SignatureModal.svelte'
  import TimeInput from '@/Shared/TimeInput.svelte'

  $title = 'Create Log'

  let form = useForm('CreateLog', {
    date: null,
    'shift': 'PAGI',
    atc_on_duty: null,
    atc_on_duty_signature: {
      data: null,
      image: null,
    },
    controller_initial_names: null,
    time: null,
    sign: true,
    operational_specifications: [
      {
        key: 1,
        time: null,
        specification: null,
      },
    ],
    facilities,
  })

  function store() {
    $form.post(route('operational-logs.store').url())

    uncheckAll()
  }

  const addSpec = () => {
    $form.operational_specifications = [...$form.operational_specifications, {
      key: $form.operational_specifications.length + 1,
      time: null,
      specification: null,
    }]
  }

  const removeSpec = () => {
    $form.operational_specifications.pop()
    $form.operational_specifications = [...$form.operational_specifications]
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

<SignatureModal {show} signature={$form.atc_on_duty_signature} on:close={closeModal} on:saveCanvas={saveCanvas} />

<h1 class="mb-8 font-bold text-3xl">
  <a use:inertia href={route('operational-logs.index')} class="text-indigo-400 hover:text-indigo-600">
    Operational Logs
  </a>
  <span class="text-indigo-400 font-medium">/</span> Create
</h1>

<div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
  <form on:submit|preventDefault={store}>
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
        placeholder="Herman Agus Siswoyo"
      />

      <TextInput
        bind:value={$form.controller_initial_names}
        error={$form.errors.controller_initial_names}
        class="pr-6 pb-6 w-full lg:w-1/2"
        label="Controller Initials :"
        help="Pisahkan dengan koma jika lebih dari 1, tanpa spasi"
        placeholder="HA,RR,RD"
      />

      <div class="pr-6 pb-6 w-full lg:w-1/2">
        <label for="signature">Atc on duty Signature : </label><br>
        <button on:click|preventDefault={showModal} class="btn-indigo mt-2" id="signature">
          Signature
        </button>
      </div>
    </div>
    <div class="font-bold pl-8 mt-5">
      Operational Specifications &nbsp;

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
          placeholder="Open Duty"
        />
      {/each}

      <button on:click|preventDefault={addSpec} class="flex w-full lg:w-1/3 items-center px-6 py-3 mb-6 rounded bg-gray-100 border-dashed border-2 border-indigo-600 text-black text-sm leading-4 font-bold whitespace-nowrap hover:bg-gray-200 focus:bg-orange-400">
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

    <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
      <LoadingButton loading={$form.processing} class="btn-indigo" type="submit">Create Log</LoadingButton>
    </div>
  </form>
</div>
