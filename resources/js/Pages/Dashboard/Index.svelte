<script context="module">
  import Layout, { title } from '@/Shared/Layout.svelte'
  export const layout = Layout
</script>

<script>
  import Chart from 'chart.js/auto'
  import { onMount } from 'svelte'

  $title = 'Dashboard'

  export let oplogCount
  export let years

  let currentYear = new Date().getFullYear()
  let params = new URLSearchParams(window.location.search)
  if (params.has('year')) currentYear = params.get('year')

  const buildChart = (el, type, data, options) => {
    const ctx = document.getElementById(el)

    new Chart(ctx, {
      type,
      data,
      options,
    })
  }

  const oplogsChartData = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [{
      label: 'Quantity',
      data: oplogCount,
      backgroundColor: [
        'rgb(47, 54, 95)',
      ],
    }],
    options: {
      responsive: true,
      plugins: {
        title: {
          display: true,
          text: 'Jumlah Operational Logs per Bulan',
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
          },
        },
      },
    },
  }

  const changeYear = (year) => {
    window.location.href = `?year=${year}`
  }

  onMount(() => {
    buildChart('oplogsChart', 'bar', oplogsChartData, oplogsChartData.options)
  })
</script>

<h1 class="mb-4 font-bold text-3xl">Dashboard</h1>
<h3 class="font-semibold mb-8 text-gray-400 leading-normal">
  Log Book Tower Politeknik Penerbangan Surabaya
</h3>

<div class="mb-6 flex justify-start items-center overflow-x-auto">
  {#each years as year}
    <button on:click|preventDefault={changeYear(year)}
            class="{currentYear == year ?
            'py-2 px-4 border border-dashed border-2 border-indigo-600 shadow-md no-underline rounded-lg bg-white-200 text-indigo-600 font-sans hover:bg-gray-200 font-semibold text-sm border-blue btn-primary focus:outline-none active:shadow-none mr-2' :
            'py-2 px-4 border border-dashed border-2 border-black shadow-md no-underline rounded-lg bg-white-100 text-black font-sans hover:bg-gray-200 font-semibold text-sm border-blue btn-primary focus:outline-none active:shadow-none mr-2'}">
      {year}
    </button>
  {/each}
</div>

<div class="mt-1">
  <div class="flex flex-wrap -mx-6">
    <div class="w-full px-6 sm:w-1/2 xl:w-full">
      <div class="chart-container p-5 rounded-lg bg-white h-full shadow-lg w-full p-4">
        <canvas id="oplogsChart"></canvas>
      </div>
    </div>
  </div>
</div>
