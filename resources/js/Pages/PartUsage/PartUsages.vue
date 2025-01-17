<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PartUsageTable from './PartUsageTable.vue';
import SearchInput from '@/Components/SearchInput.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import BackWindowIcon from '@/Components/Icon/BackWindowIcon.vue';

const props = defineProps({
  partUsages: Array,
  serviceDetails: Array,
  statusServices: Array,
  spareParts: Array,
});

const previousPage = () => {
  window.history.back();
}

const searchQuery = ref('');

const filteredPartUsages = computed(() => {
  if (!searchQuery.value) {
    return props.partUsages;
  }
  return props.partUsages.filter(partUsage =>
    partUsage.service_detail.service_detail_code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    partUsage.spare_part.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
</script>

<template>

  <Head title="Part Usages" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center gap-2 px-2">
        <div class="flex items-center">
          <SecondaryButton @click="previousPage" style="padding-inline: 8px; border: none; box-shadow: none;">
            <BackWindowIcon />
          </SecondaryButton>
          <h2 class="font-bold text-green-700 text-lg leading-4 flex-none px-4 py-4">Part Usages</h2>
        </div>
        <div class="flex w-full items-center">
          <SearchInput v-model:searchQuery="searchQuery" placeholder="Search for service detail codes or part names" />
        </div>
      </div>
    </template>
    <div class="flex overflow-x-auto">
      <!-- Main Content -->
      <div class="py-8 flex-1 transition-all duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow sm:rounded p-4 sm:p-8">
            <!-- Your main content here -->
            <PartUsageTable :partUsages="filteredPartUsages" :serviceDetails="serviceDetails"
              :statusServices="statusServices" :spareParts="spareParts" />
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
