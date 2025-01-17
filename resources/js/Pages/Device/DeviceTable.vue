<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import DeviceForm from '@/Pages/Device/DeviceForm.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ButtonImage from '@/Components/ButtonImage.vue';
import BackIcon from '@/Components/Icon/BackIcon.vue';
import NextIcon from '@/Components/Icon/NextIcon.vue';

const props = defineProps({
  devices: Array,
  deviceTypes: Array,
});

const showingModalDeviceUpdate = ref(false);
const selectedDevice = ref(null);
const selectedDeviceType = ref(null);

const showModalDeviceUpdate = (device) => {
  selectedDevice.value = device;
  selectedDeviceType.value = device.device_type;
  showingModalDeviceUpdate.value = true;
};

const confirmingDeviceDeletion = ref(false);

const form = useForm({
  id: null,
});

const confirmDeviceDeletion = (deviceId) => {
  confirmingDeviceDeletion.value = true;
  form.id = deviceId;
};

const deleteDevice = () => {
  form.delete(route('destroy.device'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: (errors) => {
      if (errors) {
        closeModal();
      } else {
        const errorMessages = Object.values(errors).flat();
        alert(`${errorMessages}`);
      }
    }
  });
};

const showingModalUpdateSuccessfully = ref(false);

const showModalUpdateSuccessfully = () => {
  showingModalDeviceUpdate.value = false;
  showingModalUpdateSuccessfully.value = true;
};

const closeModalUpdateSuccessfully = () => {
  showingModalUpdateSuccessfully.value = false;
};

const closeModal = () => {
  showingModalDeviceUpdate.value = false;
  confirmingDeviceDeletion.value = false;
};

const currentPage = ref(1);
const itemsPerPage = 15;

const paginatedDevices = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return props.devices.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(props.devices.length / itemsPerPage);
});

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};
</script>

<template>
  <div class=" overflow-x-auto">
    <table class="min-w-full bg-white border-collapse">
      <thead>
        <tr>
          <th class="py-4 px-4 border-b border-green-300 bg-green-300 truncate">No</th>
          <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Device Type</th>
          <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Model</th>
          <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Serial Number</th>
          <th class="py-4 px-4 border-b border-green-300 bg-green-300" colspan="2">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(device, index) in paginatedDevices" :key="device.id" class="hover:bg-green-100">
          <td class="py-2 px-4 border-b border-green-300 text-center">
            {{ (currentPage - 1) * itemsPerPage + index + 1 }}
          </td>
          <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
            {{ device.device_type.type_name }}</td>
          <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
            {{ device.model }}</td>
          <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
            {{ device.serial_number }}</td>
          <td class="py-2 px-4 border-b border-green-300 text-center">
            <SecondaryButton @click="showModalDeviceUpdate(device)">Update</SecondaryButton>
          </td>
          <td class="py-2 px-4 border-b border-green-300 text-center">
            <DangerButton @click="confirmDeviceDeletion(device.id)">Delete</DangerButton>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="flex justify-center gap-4 items-center p-6">
    <ButtonImage class="py-2" @click="previousPage" :disabled="currentPage === 1">
      <BackIcon />
    </ButtonImage>
    <span>{{ currentPage }} / {{ totalPages }}</span>
    <ButtonImage class="py-2" @click="nextPage" :disabled="currentPage === totalPages">
      <NextIcon />
    </ButtonImage>
  </div>

  <Modal v-model:show="showingModalDeviceUpdate">
    <div class="m-6">
      <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
        <span class="font-bold text-center w-full">Update Device</span>
        <DangerButton @click="closeModal">X</DangerButton>
      </div>
      <hr class="mt-4 mb-2">
      <DeviceForm :device="selectedDevice" :deviceType="selectedDeviceType" :deviceTypes="deviceTypes"
        @updateDevice="showModalUpdateSuccessfully" />
    </div>
  </Modal>

  <Modal maxWidth="xl" :show="showingModalUpdateSuccessfully">
    <div class="m-6">
      <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
        <span class="font-bold text-center w-full">Update Device</span>
        <DangerButton @click="closeModalUpdateSuccessfully">X</DangerButton>
      </div>
      <hr class="mt-4 mb-2">
      <p class="my-4 text-sm text-green-700">
        Device Update Successful!
      </p>
      <div class="mt-2 flex">
        <PrimaryButton @click="closeModalUpdateSuccessfully">Ok</PrimaryButton>
      </div>
    </div>
  </Modal>

  <Modal :show="confirmingDeviceDeletion">
    <div class="p-6">
      <h2 class="text-lg font-medium text-green-900">
        Are you sure you want to delete your Device?
      </h2>

      <p class="mt-1 text-sm text-green-700">
        Once your Device is deleted, all of its resources and data will be permanently deleted.
      </p>

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

        <DangerButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
          @click="deleteDevice">
          Delete Device
        </DangerButton>
      </div>
    </div>
  </Modal>
</template>

<style scoped>
/* Custom scrollbar style for overflow-x-auto */
.overflow-x-auto::-webkit-scrollbar {
  display: none;
}

.overflow-x-auto {
  -ms-overflow-style: none;
  scrollbar-width: thin
}
</style>