<script>
  import SignaturePad from 'signature_pad'

  export let show
  export let signature

  import { createEventDispatcher } from 'svelte'

  const dispatch = createEventDispatcher()

  const close = () => {
    dispatch('close')
  }

  let signaturePad = ''

  const initCanvas = () => {
    const canvas = document.querySelector('canvas')
    signaturePad = new SignaturePad(canvas, {
      minWidth: 0.5,
      maxWidth: 1.1,
      velocityFilterWeight: 0.1,
    })

    if (signature) {
      if (signature.data) {
        signaturePad.fromData(signature.data)
      }
    }
  }

  const clearCanvas = () => {
    if (signaturePad) {
      signaturePad.clear()
    }
  }

  const saveCanvas = () => {
    const image = signaturePad.toDataURL('image/svg+xml')
    const data = signaturePad.toData()

    dispatch('saveCanvas', {
      image,
      data,
    })
  }

  $: if(show) {
    setTimeout(initCanvas, 200)
  }

</script>

{#if show}
  <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" />
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white pb-4 pt-4">
          <div class="sm:flex sm:items-end justify-center">
            <div class="mt-3 text-center sm:mt-0 sm:text-left">
              <h3 id="modal-title" class="text-lg leading-6 font-medium text-gray-900">
                Signature Pad
              </h3>
              <div class="mt-3 w-48 h-36 border-dashed border-2 border-gray-500 bg-gray-50 rounded">
                <canvas></canvas>
              </div>
              <button type="button" on:click|preventDefault={clearCanvas}
                      class="lg:w-full sm:w-1/2 inline-flex justify-center rounded border border-transparent shadow-sm px-4 py-2 mt-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm"
              >
                Clear
              </button>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button type="button" on:click|preventDefault={saveCanvas}
                  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"

          >
            Submit
          </button>
          <button type="button"
                  on:click|preventDefault={close}
                  class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"

          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
{/if}
