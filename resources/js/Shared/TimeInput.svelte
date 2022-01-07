<script>
  import { nanoid } from 'nanoid'
  import Label from '@/Shared/Label.svelte'
  import { onMount } from 'svelte'
  import { TimepickerUI } from 'timepicker-ui'

  export let id = `time-input-${nanoid(5)}`
  export let divId = `time-input-${nanoid(5)}-div`
  export let value
  export let label
  export let type = 'text'
  export let error
  export let help

  let input

  export const focus = () => input.focus()
  export const select = () => input.select()

  $: props = {
    ...$$restProps,
    class: 'form-input',
  }

  function update(event) {
    value = `${event.detail.hour}:${event.detail.minutes}`
  }

  onMount(() => {
    const version24h = document.getElementById(divId)
    const version24hPicker = new TimepickerUI(version24h, {
      clockType: '24h',
      enableScrollbar: true,
      mobile: true,
      focusInputAfterCloseModal: true,
    })
    version24hPicker.create()
  })
</script>

<div class={$$restProps.class} id={divId} on:accept={update}>
  <Label {label} {id} />

  <input {...props} bind:this={input} class:error {id} {type} {value} />

  {#if error}
    <div class="form-error">{error}</div>
  {/if}

  {#if help}
    <div class="form-help">{help}</div>
  {/if}
</div>
