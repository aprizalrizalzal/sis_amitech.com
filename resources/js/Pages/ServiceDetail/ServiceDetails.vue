<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ServiceDetailTable from './ServiceDetailTable.vue';
import SearchInput from '@/Components/SearchInput.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import BackWindowIcon from '@/Components/Icon/BackWindowIcon.vue';

const props = defineProps({
  serviceDetails: Array,
  statusServices: Array,
  services: Array,
});

const previousPage = () => {
  window.history.back();
}

const searchQuery = ref('');

const filteredServiceDetails = computed(() => {
  if (!searchQuery.value) {
    return props.serviceDetails;
  }
  return props.serviceDetails.filter(serviceDetail =>
    serviceDetail.service_detail_code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    serviceDetail.user.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    serviceDetail.service.service_code.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
</script>

<template>

  <Head title="Service Details" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center gap-2 px-2">
        <div class="flex items-center">
          <SecondaryButton @click="previousPage" style="padding-inline: 8px; border: none; box-shadow: none;">
            <BackWindowIcon />
          </SecondaryButton>
          <h2 class="font-bold text-green-700 text-lg leading-4 flex-none px-4 py-4">Service Details</h2>
        </div>
        <div class="flex w-full items-center">
          <SearchInput v-model:searchQuery="searchQuery"
            placeholder="Search for Service Code Details, Technician Email or Service Code" />
        </div>
      </div>
    </template>
    <div class="flex overflow-x-auto">
      <!-- Main Content -->
      <div class="py-8 flex-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow sm:rounded p-4 sm:p-8">
            <!-- Your main content here -->
            <ServiceDetailTable :serviceDetails="filteredServiceDetails" :statusServices="statusServices"
              :services="services" />
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
